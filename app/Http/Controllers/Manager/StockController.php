<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();
        $branchId = $manager->branch_id;

        $query = Stock::with(['branch', 'product.category'])
            ->where('branch_id', $branchId);

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'habis') {
                $query->where('jumlah_stok', 0);
            } elseif ($request->status_stok === 'menipis') {
                $query->whereBetween('jumlah_stok', [1, 10]);
            } elseif ($request->status_stok === 'normal') {
                $query->where('jumlah_stok', '>', 10);
            }
        }

        $stocks = (clone $query)
            ->orderBy('jumlah_stok', 'asc')
            ->paginate(10)
            ->withQueryString();

        $filteredStocks = (clone $query)->get();

        $totalProduk = $filteredStocks->count();
        $totalStok = $filteredStocks->sum('jumlah_stok');
        $stokMenipis = $filteredStocks->whereBetween('jumlah_stok', [1, 10])->count();
        $stokHabis = $filteredStocks->where('jumlah_stok', 0)->count();

        $chartStocks = (clone $query)
            ->orderBy('jumlah_stok', 'asc')
            ->limit(10)
            ->get();

        $chartLabels = $chartStocks->map(function ($stock) {
            return $stock->product->nama ?? '-';
        });

        $chartData = $chartStocks->pluck('jumlah_stok');

        $categories = Category::orderBy('nama', 'asc')->get();

        return view('manager.stocks.index', compact(
            'stocks',
            'categories',
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'stokHabis',
            'chartLabels',
            'chartData'
        ));
    }

    public function pdf(Request $request)
    {
        $manager = Auth::user();
        $branchId = $manager->branch_id;

        $query = Stock::with(['branch', 'product.category'])
            ->where('branch_id', $branchId);

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        if ($request->filled('status_stok')) {
            if ($request->status_stok === 'habis') {
                $query->where('jumlah_stok', 0);
            } elseif ($request->status_stok === 'menipis') {
                $query->whereBetween('jumlah_stok', [1, 10]);
            } elseif ($request->status_stok === 'normal') {
                $query->where('jumlah_stok', '>', 10);
            }
        }

        $stocks = $query
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        $pdf = Pdf::loadView('manager.stocks.pdf', compact('stocks', 'manager'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-stok-manager.pdf');
    }
}
