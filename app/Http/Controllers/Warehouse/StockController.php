<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $warehouse = Auth::user();
        $branchId = $warehouse->branch_id;

        $query = Stock::with(['product.category', 'product.supplier'])
            ->where('branch_id', $branchId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            if ($request->status === 'aman') {
                $query->where('jumlah_stok', '>=', 30);
            } elseif ($request->status === 'menipis') {
                $query->where('jumlah_stok', '>', 0)
                    ->where('jumlah_stok', '<', 30);
            } elseif ($request->status === 'habis') {
                $query->where('jumlah_stok', 0);
            }
        }

        $stocks = $query->orderBy('jumlah_stok')
            ->paginate(10)
            ->withQueryString();

        $totalProduk = Stock::where('branch_id', $branchId)->count();

        $stokAman = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', '>=', 30)
            ->count();

        $stokMenipis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', '>', 0)
            ->where('jumlah_stok', '<', 30)
            ->count();

        $stokHabis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', 0)
            ->count();

        return view('warehouse.stocks.index', compact(
            'stocks',
            'totalProduk',
            'stokAman',
            'stokMenipis',
            'stokHabis'
        ));
    }
}
