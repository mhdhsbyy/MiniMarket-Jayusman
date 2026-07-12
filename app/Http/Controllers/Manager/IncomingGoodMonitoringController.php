<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomingGoodMonitoringController extends Controller
{
    public function index(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $query = IncomingGood::with([
            'product.category',
            'product.supplier',
            'user',
        ])->where('branch_id', $branchId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($pq) use ($search) {
                $pq->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($cq) use ($search) {
                        $cq->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('nama', 'like', "%{$search}%");
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

        $chartIncoming = (clone $query)
            ->select(
                DB::raw('DATE(tanggal_masuk) as tanggal'),
                DB::raw('SUM(jumlah) as total')
            )
            ->groupBy(DB::raw('DATE(tanggal_masuk)'))
            ->orderBy('tanggal')
            ->get();

        $chartLabels = $chartIncoming->map(function ($item) {
            return \Carbon\Carbon::parse($item->tanggal)->format('d M');
        });

        $chartData = $chartIncoming->pluck('total');

        $incomingGoods = $query->latest('tanggal_masuk')
            ->paginate(10)
            ->withQueryString();

        return view('manager.incoming-goods.index', compact(
            'incomingGoods',
            'totalBarangMasuk',
            'totalJumlahMasuk',
            'totalBiaya',
            'chartLabels',
            'chartData',
        ));
    }

    public function pdf(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $query = IncomingGood::with([
            'branch',
            'product.category',
            'product.supplier',
            'user',
        ])->where('branch_id', $branchId);

        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_selesai);
        }

        $incomingGoods = $query->latest('tanggal_masuk')->get();

        $branch = Auth::user()->branch;

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
            'manager.incoming-goods.pdf',
            compact(
                'incomingGoods',
                'branch',
                'totalBarangMasuk',
                'totalJumlahMasuk',
                'totalBiaya',
                'periode',
            )
        )->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan Barang Masuk '.($branch->nama ?? 'Cabang').'.pdf');
    }
}
