<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();

        $transactionsQuery = Transaction::with(['branch', 'cashier'])
            ->where('status', 'success')
            ->when($request->branch_id, function ($query) use ($request) {
                $query->where('branch_id', $request->branch_id);
            })
            ->when($request->start_date, function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '<=', $request->end_date);
            });

        $totalTransaksi = (clone $transactionsQuery)->count();
        $totalPendapatan = (clone $transactionsQuery)->sum('total_bayar');

        $transactions = $transactionsQuery
            ->latest('tanggal_transaksi')
            ->paginate(15)
            ->withQueryString();

        return view('owner.reports.transactions.index', compact(
            'branches',
            'transactions',
            'totalTransaksi',
            'totalPendapatan'
        ));
    }

    public function print(Request $request)
    {
        $branch = null;

        if ($request->branch_id) {
            $branch = Branch::find($request->branch_id);
        }

        $transactions = Transaction::with(['branch', 'cashier'])
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
            ->latest('tanggal_transaksi')
            ->get();

        $totalTransaksi = $transactions->count();
        $totalPendapatan = $transactions->sum('total_bayar');

        $periode = $this->getPeriodeText($request);

        return view('owner.reports.transactions.print', compact(
            'transactions',
            'totalTransaksi',
            'totalPendapatan',
            'branch',
            'periode'
        ));
    }

    private function getPeriodeText(Request $request)
    {
        if ($request->start_date && $request->end_date) {
            return Carbon::parse($request->start_date)->format('d M Y') .
                ' - ' .
                Carbon::parse($request->end_date)->format('d M Y');
        }

        if ($request->start_date) {
            return 'Mulai ' . Carbon::parse($request->start_date)->format('d M Y');
        }

        if ($request->end_date) {
            return 'Sampai ' . Carbon::parse($request->end_date)->format('d M Y');
        }

        return 'Semua Periode';
    }

    public function pdf(Request $request)
    {
        $branch = null;

        if ($request->branch_id) {
            $branch = Branch::find($request->branch_id);
        }

        $transactions = Transaction::with(['branch', 'cashier'])
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
            ->latest('tanggal_transaksi')
            ->get();

        $totalTransaksi = $transactions->count();
        $totalPendapatan = $transactions->sum('total_bayar');

        $periode = $this->getPeriodeText($request);

        $pdf = Pdf::loadView(
            'owner.reports.transactions.pdf',
            compact(
                'transactions',
                'totalTransaksi',
                'totalPendapatan',
                'branch',
                'periode'
            )
        );

        $pdf->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-transaksi.pdf');
    }
}
