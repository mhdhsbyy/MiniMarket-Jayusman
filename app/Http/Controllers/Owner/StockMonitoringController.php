<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();
        $categories = Category::orderBy('nama')->get();

        $baseQuery = Stock::with(['branch', 'product.category'])
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('product', function ($product) use ($request) {
                    $product->where('category_id', $request->category_id);
                });
            });

        $totalProdukStok = (clone $baseQuery)->count();
        $totalStokBarang = (clone $baseQuery)->sum('jumlah_stok');
        $produkMenipis = (clone $baseQuery)->where('jumlah_stok', '<=', 30)->count();
        $produkHabis = (clone $baseQuery)->where('jumlah_stok', '<=', 0)->count();

        $chartStokCabang = Stock::join('branches', 'stocks.branch_id', '=', 'branches.id')
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('stocks.branch_id', $request->branch_id);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->join('products', 'stocks.product_id', '=', 'products.id')
                    ->where('products.category_id', $request->category_id);
            })
            ->select(
                'branches.nama',
                DB::raw('SUM(stocks.jumlah_stok) as total_stok')
            )
            ->groupBy('branches.id', 'branches.nama')
            ->orderByDesc('total_stok')
            ->get();

        $labelsCabang = $chartStokCabang->pluck('nama');
        $dataStokCabang = $chartStokCabang->pluck('total_stok');

        $stokMenipisList = (clone $baseQuery)
            ->orderBy('jumlah_stok')
            ->take(5)
            ->get();

        $stocks = $baseQuery
            ->orderBy('jumlah_stok')
            ->paginate(10)
            ->withQueryString();

        return view('owner.monitoring-stocks.index', compact(
            'branches',
            'categories',
            'stocks',
            'totalProdukStok',
            'totalStokBarang',
            'produkMenipis',
            'produkHabis',
            'labelsCabang',
            'dataStokCabang',
            'stokMenipisList'
        ));
    }

    public function pdf(Request $request)
    {
        $query = Stock::with(['branch', 'product.category']);

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('category_id')) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $stocks = $query
            ->orderBy('jumlah_stok', 'asc')
            ->get();

        $branch = null;
        $category = null;

        if ($request->filled('branch_id')) {
            $branch = Branch::find($request->branch_id);
        }

        if ($request->filled('category_id')) {
            $category = Category::find($request->category_id);
        }

        $totalProduk = $stocks->count();
        $totalStok = $stocks->sum('jumlah_stok');
        $stokMenipis = $stocks->whereBetween('jumlah_stok', [1, 30])->count();
        $stokHabis = $stocks->where('jumlah_stok', '<=', 0)->count();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'owner.monitoring-stocks.pdf',
            compact(
                'stocks',
                'branch',
                'category',
                'totalProduk',
                'totalStok',
                'stokMenipis',
                'stokHabis'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-stok-owner.pdf');
    }
}
