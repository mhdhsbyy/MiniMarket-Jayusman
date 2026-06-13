<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Stock;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();
        $categories = Category::orderBy('nama')->get();

        $stocksQuery = Stock::with(['branch', 'product.category'])
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('product', function ($product) use ($request) {
                    $product->where('category_id', $request->category_id);
                });
            });

        $totalProduk = (clone $stocksQuery)->count();
        $totalStok = (clone $stocksQuery)->sum('jumlah_stok');
        $stokMenipis = (clone $stocksQuery)->where('jumlah_stok', '<=', 30)->count();
        $stokHabis = (clone $stocksQuery)->where('jumlah_stok', '<=', 0)->count();

        $stocks = $stocksQuery
            ->orderBy('jumlah_stok')
            ->paginate(15)
            ->withQueryString();

        return view('owner.reports.stocks.index', compact(
            'branches',
            'categories',
            'stocks',
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'stokHabis'
        ));
    }

    public function pdf(Request $request)
    {
        $branch = null;
        $category = null;

        if ($request->branch_id) {
            $branch = Branch::find($request->branch_id);
        }

        if ($request->category_id) {
            $category = Category::find($request->category_id);
        }

        $stocks = Stock::with(['branch', 'product.category'])
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->category_id, function ($query) use ($request) {
                $query->whereHas('product', function ($product) use ($request) {
                    $product->where('category_id', $request->category_id);
                });
            })
            ->orderBy('jumlah_stok')
            ->get();

        $totalProduk = $stocks->count();
        $totalStok = $stocks->sum('jumlah_stok');
        $stokMenipis = $stocks->where('jumlah_stok', '<=', 30)->count();
        $stokHabis = $stocks->where('jumlah_stok', '<=', 0)->count();

        $pdf = Pdf::loadView('owner.reports.stocks.pdf', compact(
            'stocks',
            'branch',
            'category',
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'stokHabis'
        ));

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-stok.pdf');
    }
}
