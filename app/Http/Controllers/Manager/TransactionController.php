<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
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

        if ($request->filled('periode') && $request->periode !== 'semua') {
            if ($request->periode === 'harian') {
                $filteredQuery->whereDate('tanggal_transaksi', now()->toDateString());
            } elseif ($request->periode === 'mingguan') {
                $filteredQuery->whereBetween('tanggal_transaksi', [
                    now()->startOfWeek()->startOfDay(),
                    now()->endOfWeek()->endOfDay(),
                ]);
            } elseif ($request->periode === 'bulanan') {
                $filteredQuery->whereMonth('tanggal_transaksi', now()->month)
                    ->whereYear('tanggal_transaksi', now()->year);
            } elseif ($request->periode === 'tahunan') {
                $filteredQuery->whereYear('tanggal_transaksi', now()->year);
            }
        }

        if ($request->filled('start_date')) {
            $filteredQuery->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $filteredQuery->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $filteredQuery->where(function ($q) use ($request) {
                $q->whereHas('cashier', function ($cashier) use ($request) {
                    $cashier->where('first_name', 'like', '%'.$request->search.'%')
                        ->orWhere('last_name', 'like', '%'.$request->search.'%');
                });
            });
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

        $chartLabels = $chartTransactions->map(function ($item) {
            return Carbon::parse($item->tanggal)->format('d M');
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

    public function show(Transaction $transaction)
    {
        $manager = Auth::user();

        if ($transaction->branch_id !== $manager->branch_id) {
            abort(403);
        }

        $transaction->load(['cashier', 'details.product']);

        return view('manager.transactions.show', compact('transaction'));
    }

    public function pdf(Request $request)
    {
        $manager = Auth::user();
        $branchId = $manager->branch_id;

        $query = Transaction::with(['branch', 'cashier', 'details.product'])
            ->where('branch_id', $branchId);

        if ($request->filled('periode') && $request->periode !== 'semua') {
            if ($request->periode === 'harian') {
                $query->whereDate('tanggal_transaksi', now()->toDateString());
            } elseif ($request->periode === 'mingguan') {
                $query->whereBetween('tanggal_transaksi', [
                    now()->startOfWeek()->startOfDay(),
                    now()->endOfWeek()->endOfDay(),
                ]);
            } elseif ($request->periode === 'bulanan') {
                $query->whereMonth('tanggal_transaksi', now()->month)
                    ->whereYear('tanggal_transaksi', now()->year);
            } elseif ($request->periode === 'tahunan') {
                $query->whereYear('tanggal_transaksi', now()->year);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('cashier', function ($cashier) use ($request) {
                    $cashier->where('first_name', 'like', '%'.$request->search.'%')
                        ->orWhere('last_name', 'like', '%'.$request->search.'%');
                });
            });
        }

        $transactions = $query
            ->orderByDesc('tanggal_transaksi')
            ->get();

        $pdf = Pdf::loadView('manager.transactions.pdf', compact('transactions', 'manager'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-transaksi-manager.pdf');
    }
}
