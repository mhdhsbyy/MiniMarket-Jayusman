<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $query = Stock::with(['product.category', 'product.supplier'])
            ->where('branch_id', $branchId)
            ->whereHas('product');

        if ($request->filled('search')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where(function ($subQuery) use ($request) {
                    $subQuery->where('nama', 'like', '%'.$request->search.'%')
                        ->orWhere('kode', 'like', '%'.$request->search.'%');
                });
            });
        }

        if ($request->filled('category_id')) {
            $query->whereRelation('product', 'category_id', $request->category_id);
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'aman') {
                $query->where('jumlah_stok', '>', 30);
            } elseif ($request->status_stok === 'menipis') {
                $query->where('jumlah_stok', '>', 0)
                    ->where('jumlah_stok', '<', 30);
            } elseif ($request->status_stok === 'habis') {
                $query->where('jumlah_stok', '=', 0);
            }
        }

        $stocks = $query
            ->join('products', 'stocks.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select('stocks.*')
            ->orderBy('categories.nama', 'asc')
            ->orderBy('products.nama', 'asc')
            ->orderBy('stocks.jumlah_stok', 'asc')
            ->paginate(10)
            ->withQueryString();

        $categories = Category::whereHas('products.stocks', function ($q) use ($branchId) {
            $q->where('branch_id', $branchId);
        })
            ->orderBy('nama')
            ->get();

        $summaryQuery = Stock::where('branch_id', $branchId);

        $totalProduk = (clone $summaryQuery)->count();

        $totalStok = (clone $summaryQuery)->sum('jumlah_stok');

        $stokMenipis = (clone $summaryQuery)
            ->where('jumlah_stok', '>', 0)
            ->where('jumlah_stok', '<', 30)
            ->count();

        $stokHabis = (clone $summaryQuery)
            ->where('jumlah_stok', '=', 0)
            ->count();

        return view('supervisor.stocks.index', compact(
            'stocks',
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'stokHabis',
            'categories'
        ));
    }
}
