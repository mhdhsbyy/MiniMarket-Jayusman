<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();

        $baseQuery = Transaction::with(['branch', 'cashier'])
            ->where('status', 'success')
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
            })
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('cashier', function ($cashier) use ($request) {
                        $cashier->where('first_name', 'like', '%' . $request->search . '%')
                            ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    })->orWhereHas('branch', function ($branch) use ($request) {
                        $branch->where('nama', 'like', '%' . $request->search . '%')
                            ->orWhere('kota', 'like', '%' . $request->search . '%');
                    });
                });
            });

        $totalTransaksi = (clone $baseQuery)->count();
        $totalPendapatan = (clone $baseQuery)->sum('total_bayar');

        $cabangTerbaik = Transaction::join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->where('transactions.status', 'success')
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('transactions.branch_id', $request->branch_id);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('transactions.tanggal_transaksi', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('transactions.tanggal_transaksi', '<=', $request->end_date);
            })
            ->select(
                'branches.nama',
                'branches.kota',
                DB::raw('SUM(transactions.total_bayar) as total_pendapatan')
            )
            ->groupBy('branches.id', 'branches.nama', 'branches.kota')
            ->orderByDesc('total_pendapatan')
            ->first();

        $chartPendapatanCabang = Transaction::join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->where('transactions.status', 'success')
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('transactions.branch_id', $request->branch_id);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('transactions.tanggal_transaksi', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('transactions.tanggal_transaksi', '<=', $request->end_date);
            })
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
}
