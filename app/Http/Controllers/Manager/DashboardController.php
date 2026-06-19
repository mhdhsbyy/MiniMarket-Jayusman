<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $totalProduk = Stock::where('branch_id', $branchId)->count();

        $totalStok = Stock::where('branch_id', $branchId)->sum('jumlah_stok');

        $transaksiHariIni = Transaction::where('branch_id', $branchId)
            ->whereDate('tanggal_transaksi', today())
            ->count();

        $pendapatanHariIni = Transaction::where('branch_id', $branchId)
            ->whereDate('tanggal_transaksi', today())
            ->sum('total_bayar');

        $totalSupervisor = User::role('supervisor')
            ->where('branch_id', $branchId)
            ->count();

        $totalKasir = User::role('cashier')
            ->where('branch_id', $branchId)
            ->count();

        $totalGudang = User::role('warehouse')
            ->where('branch_id', $branchId)
            ->count();

        $stokMenipis = Stock::with(['product.category'])
            ->where('branch_id', $branchId)
            ->where('jumlah_stok', '<=', 30)
            ->orderBy('jumlah_stok', 'asc')
            ->limit(5)
            ->get();

        $transaksiTerbaru = Transaction::with(['cashier'])
            ->where('branch_id', $branchId)
            ->latest('tanggal_transaksi')
            ->limit(5)
            ->get();

        $labels = [];
        $dataPendapatan = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i);

            $labels[] = $tanggal->format('d M');

            $dataPendapatan[] = Transaction::where('branch_id', $branchId)
                ->whereDate('tanggal_transaksi', $tanggal)
                ->sum('total_bayar');
        }

        return view('manager.dashboard', compact(
            'totalProduk',
            'totalStok',
            'transaksiHariIni',
            'pendapatanHariIni',
            'totalSupervisor',
            'totalKasir',
            'totalGudang',
            'stokMenipis',
            'transaksiTerbaru',
            'labels',
            'dataPendapatan'
        ));
    }
}
