<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCabang = Branch::count();
        $totalProduk = Product::count();
        $totalSupplier = Supplier::count();

        $totalKaryawan = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['manager', 'supervisor', 'cashier', 'warehouse']);
        })->count();

        $pendapatanHarian = Transaction::where('status', 'success')
            ->whereDate('tanggal_transaksi', '>=', now()->subDays(6))
            ->select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $labels = collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->format('d M');
        });

        $dataPendapatan = collect(range(0, 6))->map(function ($i) use ($pendapatanHarian) {
            $date = now()->subDays(6 - $i)->format('Y-m-d');

            return $pendapatanHarian->firstWhere('tanggal', $date)->total ?? 0;
        });

        $transaksiTerbaru = Transaction::with(['branch', 'cashier'])
            ->where('status', 'success')
            ->latest('tanggal_transaksi')
            ->take(5)
            ->get();

        $stokMenipis = Stock::with(['branch', 'product'])
            ->where('jumlah_stok', '<=', 30)
            ->orderBy('jumlah_stok', 'asc')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalCabang',
            'totalProduk',
            'totalSupplier',
            'totalKaryawan',
            'labels',
            'dataPendapatan',
            'transaksiTerbaru',
            'stokMenipis'
        ));
    }
}
