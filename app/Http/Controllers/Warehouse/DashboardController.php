<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $warehouse = Auth::user();
        $branchId = $warehouse->branch_id;

        $totalProduk = Stock::where('branch_id', $branchId)->count();

        $totalStok = Stock::where('branch_id', $branchId)->sum('jumlah_stok');

        $stokMenipis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', '>', 0)
            ->where('jumlah_stok', '<', 30)
            ->count();

        $stokHabis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', 0)
            ->count();

        $recentIncomingGoods = IncomingGood::with(['product.supplier'])
            ->where('branch_id', $branchId)
            ->latest('tanggal_masuk')
            ->take(5)
            ->get();

        $lowStocks = Stock::with('product')
            ->where('branch_id', $branchId)
            ->where('jumlah_stok', '<', 30)
            ->orderBy('jumlah_stok')
            ->take(5)
            ->get();

        return view('warehouse.dashboard', compact(
            'warehouse',
            'totalProduk',
            'totalStok',
            'stokMenipis',
            'stokHabis',
            'recentIncomingGoods',
            'lowStocks'
        ));
    }
}
