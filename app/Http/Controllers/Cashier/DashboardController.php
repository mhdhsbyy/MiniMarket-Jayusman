<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cashier = Auth::user();
        $branchId = $cashier->branch_id;

        $totalTransaksiHariIni = Transaction::where('cashier_id', $cashier->id)
            ->whereDate('tanggal_transaksi', now()->toDateString())
            ->count();

        $totalPendapatanHariIni = Transaction::where('cashier_id', $cashier->id)
            ->where('status', 'success')
            ->whereDate('tanggal_transaksi', now()->toDateString())
            ->sum('total_bayar');

        $totalProdukCabang = Stock::where('branch_id', $branchId)->count();

        $stokMenipis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', '>', 0)
            ->where('jumlah_stok', '<', 30)
            ->count();

        $transaksiTerbaru = Transaction::with(['details.product'])
            ->where('cashier_id', $cashier->id)
            ->latest('tanggal_transaksi')
            ->limit(5)
            ->get();

        return view('cashier.dashboard', compact(
            'totalTransaksiHariIni',
            'totalPendapatanHariIni',
            'totalProdukCabang',
            'stokMenipis',
            'transaksiTerbaru'
        ));
    }
}
