<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionMonitoringController extends Controller
{
    private function applyFilters($query, Request $request, $prefix = null)
    {
        $branchColumn = $prefix ? $prefix . '.branch_id' : 'branch_id';
        $dateColumn = $prefix ? $prefix . '.tanggal_transaksi' : 'tanggal_transaksi';

        if ($request->filled('branch_id')) {
            $query->where($branchColumn, $request->branch_id);
        }

        if ($request->filled('periode') && $request->periode !== 'semua') {
            if ($request->periode === 'harian') {
                $query->whereDate($dateColumn, now()->toDateString());
            } elseif ($request->periode === 'mingguan') {
                $query->whereBetween($dateColumn, [
                    now()->startOfWeek()->startOfDay(),
                    now()->endOfWeek()->endOfDay(),
                ]);
            } elseif ($request->periode === 'bulanan') {
                $query->whereMonth($dateColumn, now()->month)
                    ->whereYear($dateColumn, now()->year);
            } elseif ($request->periode === 'tahunan') {
                $query->whereYear($dateColumn, now()->year);
            }
        }

        if ($request->filled('start_date')) {
            $query->whereDate($dateColumn, '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate($dateColumn, '<=', $request->end_date);
        }

        return $query;
    }

    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();

        $baseQuery = Transaction::with(['branch', 'cashier'])
            ->where('status', 'success');

        $this->applyFilters($baseQuery, $request);

        if ($request->filled('search')) {
            $baseQuery->where(function ($q) use ($request) {
                $q->whereHas('cashier', function ($cashier) use ($request) {
                    $cashier->where('first_name', 'like', '%' . $request->search . '%')
                        ->orWhere('last_name', 'like', '%' . $request->search . '%');
                })->orWhereHas('branch', function ($branch) use ($request) {
                    $branch->where('nama', 'like', '%' . $request->search . '%')
                        ->orWhere('kota', 'like', '%' . $request->search . '%');
                });
            });
        }

        $totalTransaksi = (clone $baseQuery)->count();
        $totalPendapatan = (clone $baseQuery)->sum('total_bayar');

        $cabangTerbaikQuery = Transaction::join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->where('transactions.status', 'success');

        $this->applyFilters($cabangTerbaikQuery, $request, 'transactions');

        $cabangTerbaik = $cabangTerbaikQuery
            ->select(
                'branches.nama',
                'branches.kota',
                DB::raw('SUM(transactions.total_bayar) as total_pendapatan')
            )
            ->groupBy('branches.id', 'branches.nama', 'branches.kota')
            ->orderByDesc('total_pendapatan')
            ->first();

        $chartQuery = Transaction::join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->where('transactions.status', 'success');

        $this->applyFilters($chartQuery, $request, 'transactions');

        $chartPendapatanCabang = $chartQuery
            ->select(
                'branches.nama',
                DB::raw('SUM(transactions.total_bayar) as total_pendapatan')
            )
            ->groupBy('branches.id', 'branches.nama')
            ->orderByDesc('total_pendapatan')
            ->get();

        $labelsCabang = $chartPendapatanCabang->pluck('nama');
        $dataPendapatanCabang = $chartPendapatanCabang->pluck('total_pendapatan');

        $transactions = $baseQuery
            ->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        return view('owner.monitoring-transactions.index', compact(
            'branches',
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'cabangTerbaik',
            'labelsCabang',
            'dataPendapatanCabang'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['branch', 'cashier', 'details.product']);

        return view('owner.monitoring-transactions.show', compact('transaction'));
    }

    public function pdf(Request $request)
    {
        $query = Transaction::with(['branch', 'cashier'])
            ->where('status', 'success');

        $this->applyFilters($query, $request);

        $transactions = $query
            ->latest('tanggal_transaksi')
            ->get();

        $totalTransaksi = $transactions->count();
        $totalPendapatan = $transactions->sum('total_bayar');

        $branch = null;

        if ($request->filled('branch_id')) {
            $branch = Branch::find($request->branch_id);
        }

        $periode = 'Semua Periode';

        if ($request->periode == 'harian') {
            $periode = 'Per Hari';
        } elseif ($request->periode == 'mingguan') {
            $periode = 'Per Minggu';
        } elseif ($request->periode == 'bulanan') {
            $periode = 'Per Bulan';
        } elseif ($request->periode == 'tahunan') {
            $periode = 'Per Tahun';
        }

        if ($request->filled('start_date') || $request->filled('end_date')) {
            $tanggalMulai = $request->start_date
                ? \Carbon\Carbon::parse($request->start_date)->format('d M Y')
                : 'Awal';

            $tanggalAkhir = $request->end_date
                ? \Carbon\Carbon::parse($request->end_date)->format('d M Y')
                : 'Akhir';

            $periode = $tanggalMulai . ' - ' . $tanggalAkhir;
        }

        $cabangTerbaik = Transaction::join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->where('transactions.status', 'success');

        $this->applyFilters($cabangTerbaik, $request, 'transactions');

        $cabangTerbaik = $cabangTerbaik
            ->select(
                'branches.nama',
                'branches.kota',
                DB::raw('SUM(transactions.total_bayar) as total_pendapatan')
            )
            ->groupBy('branches.id', 'branches.nama', 'branches.kota')
            ->orderByDesc('total_pendapatan')
            ->first();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
            'owner.monitoring-transactions.pdf',
            compact(
                'transactions',
                'totalTransaksi',
                'totalPendapatan',
                'cabangTerbaik',
                'branch',
                'periode'
            )
        )->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-transaksi-owner.pdf');
    }
}
