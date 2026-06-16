<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $manager = Auth::user();
        $branchId = $manager->branch_id;

        $filteredQuery = Transaction::with(['branch', 'cashier', 'details.product'])
            ->where('branch_id', $branchId);

        if ($request->filled('tanggal_awal')) {
            $filteredQuery->whereDate('tanggal_transaksi', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $filteredQuery->whereDate('tanggal_transaksi', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $filteredQuery->where('status', $request->status);
        }

        $transactions = (clone $filteredQuery)
            ->orderByDesc('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        $totalTransaksi = (clone $filteredQuery)->count();

        $totalPendapatan = (clone $filteredQuery)
            ->where('status', 'success')
            ->sum('total_bayar');

        $transaksiSelesai = (clone $filteredQuery)
            ->where('status', 'success')
            ->count();

        $transaksiBatal = (clone $filteredQuery)
            ->where('status', 'cancelled')
            ->count();

        $chartTransactions = (clone $filteredQuery)
            ->where('status', 'success')
            ->select(
                DB::raw('DATE(tanggal_transaksi) as tanggal'),
                DB::raw('SUM(total_bayar) as total')
            )
            ->groupBy(DB::raw('DATE(tanggal_transaksi)'))
            ->orderBy('tanggal')
            ->get();

        $chartLabels = $chartTransactions->pluck('tanggal')->map(function ($tanggal) {
            return \Carbon\Carbon::parse($tanggal)->format('d M');
        });

        $chartData = $chartTransactions->pluck('total');

        return view('manager.transactions.index', compact(
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'transaksiSelesai',
            'transaksiBatal',
            'chartLabels',
            'chartData'
        ));
    }

    public function pdf(Request $request)
    {
        $manager = Auth::user();
        $branchId = $manager->branch_id;

        $query = Transaction::with(['branch', 'cashier', 'details.product'])
            ->where('branch_id', $branchId);

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_akhir);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $pdf = Pdf::loadView('manager.transactions.pdf', compact('transactions', 'manager'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-transaksi-manager.pdf');
    }
}
