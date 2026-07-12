<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
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

        return view('supervisor.incoming-goods.index', compact(
            'incomingGoods',
            'totalBarangMasuk',
            'totalJumlahMasuk',
            'totalBiaya',
            'chartLabels',
            'chartData',
        ));
    }
}
