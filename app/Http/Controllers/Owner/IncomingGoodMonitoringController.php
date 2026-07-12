<?php

namespace App\Http\Controllers\Owner;

use App\Exports\IncomingGoodsExport;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\IncomingGood;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class IncomingGoodMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::orderBy('nama')->get();

        $query = IncomingGood::with([
            'branch',
            'product.category',
            'product.supplier',
            'user',
        ])->when($request->branch_id, function ($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        });

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereHas('product', function ($pq) use ($search) {
                    $pq->where('nama', 'like', "%{$search}%")
                        ->orWhere('kode', 'like', "%{$search}%")
                        ->orWhereHas('category', function ($cq) use ($search) {
                            $cq->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('supplier', function ($sq) use ($search) {
                            $sq->where('nama', 'like', "%{$search}%");
                        });
                })
                    ->orWhereHas('branch', function ($bq) use ($search) {
                        $bq->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_selesai);
        }

        $totalBarangMasuk = (clone $query)->count();
        $totalJumlahMasuk = (clone $query)->sum('jumlah');
        $totalBiaya = (clone $query)->select(DB::raw('COALESCE(SUM(harga_beli * jumlah), 0) as total'))->value('total');

        $chartData = IncomingGood::select(
            'branch_id',
            DB::raw('COUNT(*) as total'),
        )->when($request->branch_id, function ($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        })->groupBy('branch_id')->get();

        $chartLabels = $chartData->map(function ($item) {
            return $item->branch->nama ?? 'Cabang #'.$item->branch_id;
        });
        $chartValues = $chartData->pluck('total');

        $incomingGoods = $query->latest('tanggal_masuk')
            ->paginate(10)
            ->withQueryString();

        return view('owner.monitoring-incoming-goods.index', compact(
            'branches',
            'incomingGoods',
            'totalBarangMasuk',
            'totalJumlahMasuk',
            'totalBiaya',
            'chartLabels',
            'chartValues',
        ));
    }

    public function show(IncomingGood $incomingGood)
    {
        $incomingGood->load([
            'branch',
            'product.category',
            'product.supplier',
            'user',
        ]);

        return view('owner.monitoring-incoming-goods.show', compact('incomingGood'));
    }

    public function pdf(Request $request)
    {
        $query = IncomingGood::with([
            'branch',
            'product.category',
            'product.supplier',
            'user',
        ])->when($request->branch_id, function ($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        });

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_selesai);
        }

        $incomingGoods = $query->latest('tanggal_masuk')->get();

        $branch = null;
        if ($request->filled('branch_id')) {
            $branch = Branch::find($request->branch_id);
        }

        $totalBarangMasuk = $incomingGoods->count();
        $totalJumlahMasuk = $incomingGoods->sum('jumlah');
        $totalBiaya = $incomingGoods->sum(function ($item) {
            return $item->harga_beli * $item->jumlah;
        });

        $periode = 'Semua Data';
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $periode = \Carbon\Carbon::parse($request->tanggal_mulai)->translatedFormat('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->tanggal_selesai)->translatedFormat('d/m/Y');
        } elseif ($request->filled('tanggal_mulai')) {
            $periode = 'Sejak ' . \Carbon\Carbon::parse($request->tanggal_mulai)->translatedFormat('d/m/Y');
        } elseif ($request->filled('tanggal_selesai')) {
            $periode = 'Hingga ' . \Carbon\Carbon::parse($request->tanggal_selesai)->translatedFormat('d/m/Y');
        }

        $pdf = Pdf::loadView(
            'owner.monitoring-incoming-goods.pdf',
            compact(
                'incomingGoods',
                'branch',
                'totalBarangMasuk',
                'totalJumlahMasuk',
                'totalBiaya',
                'periode',
            )
        )->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan Barang Masuk Mini Market Jayusmart.pdf');
    }

    public function excel(Request $request)
    {
        $query = IncomingGood::with([
            'branch',
            'product.category',
            'product.supplier',
            'user',
        ])->when($request->branch_id, function ($q) use ($request) {
            $q->where('branch_id', $request->branch_id);
        });

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_selesai);
        }

        $incomingGoods = $query->latest('tanggal_masuk')->get();

        $branch = null;
        if ($request->filled('branch_id')) {
            $branch = Branch::find($request->branch_id);
        }

        $periode = 'Semua Data';
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $periode = \Carbon\Carbon::parse($request->tanggal_mulai)->translatedFormat('d/m/Y') . ' - ' . \Carbon\Carbon::parse($request->tanggal_selesai)->translatedFormat('d/m/Y');
        } elseif ($request->filled('tanggal_mulai')) {
            $periode = 'Sejak ' . \Carbon\Carbon::parse($request->tanggal_mulai)->translatedFormat('d/m/Y');
        } elseif ($request->filled('tanggal_selesai')) {
            $periode = 'Hingga ' . \Carbon\Carbon::parse($request->tanggal_selesai)->translatedFormat('d/m/Y');
        }

        return Excel::download(
            new IncomingGoodsExport($incomingGoods, $branch, $periode),
            'Laporan Barang Masuk Mini Market Jayusmart.xlsx'
        );
    }
}
