<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $totalTransaksiHariIni = Transaction::where('branch_id', $branchId)
            ->whereDate('tanggal_transaksi', today())
            ->count();

        $pendapatanHariIni = Transaction::where('branch_id', $branchId)
            ->whereDate('tanggal_transaksi', today())
            ->where('status', 'success')
            ->sum('total_bayar');

        $produkMenipis = Stock::where('branch_id', $branchId)
            ->where('jumlah_stok', '<', 30)
            ->count();

        $totalProduk = Stock::where('branch_id', $branchId)
            ->count();

        $transaksiTerbaru = Transaction::with('cashier')
            ->where('branch_id', $branchId)
            ->latest('tanggal_transaksi')
            ->limit(5)
            ->get();

        $stokMenipis = Stock::with('product.category')
            ->where('branch_id', $branchId)
            ->where('jumlah_stok', '<', 30)
            ->orderBy('jumlah_stok')
            ->limit(5)
            ->get();

        $pendapatanChart = Transaction::where('branch_id', $branchId)
            ->where('status', 'success')
            ->whereDate('tanggal_transaksi', '>=', now()->subDays(6))
            ->selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_bayar) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = [];
        $dataPendapatan = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');

            $labels[] = now()->subDays($i)->format('d M');

            $dataPendapatan[] = $pendapatanChart
                ->firstWhere('tanggal', $date)
                ->total ?? 0;
        }

        return view('supervisor.dashboard', compact(
            'totalTransaksiHariIni',
            'pendapatanHariIni',
            'produkMenipis',
            'totalProduk',
            'transaksiTerbaru',
            'stokMenipis',
            'labels',
            'dataPendapatan'
        ));
    }
}
