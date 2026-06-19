<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $query = Transaction::with(['cashier', 'details.product'])
            ->where('branch_id', $branchId);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = (clone $query)
            ->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        $summaryQuery = Transaction::where('branch_id', $branchId);

        if ($request->filled('tanggal_mulai')) {
            $summaryQuery->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $summaryQuery->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($request->filled('status')) {
            $summaryQuery->where('status', $request->status);
        }

        $totalTransaksi = (clone $summaryQuery)->count();

        $totalPendapatan = (clone $summaryQuery)
            ->where('status', 'success')
            ->sum('total_bayar');

        $rataRataTransaksi = (clone $summaryQuery)
            ->where('status', 'success')
            ->avg('total_bayar') ?? 0;

        $transaksiSelesai = (clone $summaryQuery)
            ->where('status', 'success')
            ->count();

        $periode = $request->get('periode', 'harian');

        $chartQuery = Transaction::where('branch_id', $branchId)
            ->where('status', 'success');

        if ($request->filled('tanggal_mulai')) {
            $chartQuery->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $chartQuery->whereDate('tanggal_transaksi', '<=', $request->tanggal_selesai);
        }

        if ($periode == 'mingguan') {
            $chartData = $chartQuery
                ->selectRaw('YEAR(tanggal_transaksi) as tahun, WEEK(tanggal_transaksi, 1) as minggu, SUM(total_bayar) as total')
                ->groupBy('tahun', 'minggu')
                ->orderBy('tahun')
                ->orderBy('minggu')
                ->get();

            $labels = $chartData->map(function ($item) {
                return 'Minggu ' . $item->minggu . ' - ' . $item->tahun;
            });
        } elseif ($periode == 'bulanan') {
            $chartData = $chartQuery
                ->selectRaw('YEAR(tanggal_transaksi) as tahun, MONTH(tanggal_transaksi) as bulan, SUM(total_bayar) as total')
                ->groupBy('tahun', 'bulan')
                ->orderBy('tahun')
                ->orderBy('bulan')
                ->get();

            $labels = $chartData->map(function ($item) {
                return Carbon::create($item->tahun, $item->bulan, 1)->format('M Y');
            });
        } elseif ($periode == 'tahunan') {
            $chartData = $chartQuery
                ->selectRaw('YEAR(tanggal_transaksi) as tahun, SUM(total_bayar) as total')
                ->groupBy('tahun')
                ->orderBy('tahun')
                ->get();

            $labels = $chartData->map(function ($item) {
                return $item->tahun;
            });
        } else {
            $chartData = $chartQuery
                ->selectRaw('DATE(tanggal_transaksi) as tanggal, SUM(total_bayar) as total')
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();

            $labels = $chartData->map(function ($item) {
                return Carbon::parse($item->tanggal)->format('d M');
            });
        }

        $dataPendapatan = $chartData->pluck('total');

        return view('supervisor.transactions.index', compact(
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'rataRataTransaksi',
            'transaksiSelesai',
            'labels',
            'dataPendapatan'
        ));
    }
}
