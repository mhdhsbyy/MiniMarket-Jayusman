<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomingGoodController extends Controller
{
    public function index(Request $request)
    {
        $warehouse = Auth::user();
        $branchId = $warehouse->branch_id;

        $query = IncomingGood::with([
            'product.category',
            'product.supplier',
            'user',
        ])->where('branch_id', $branchId);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('product', function ($productQuery) use ($search) {
                $productQuery->where('nama', 'like', "%{$search}%")
                    ->orWhere('kode', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('supplier', function ($supplierQuery) use ($search) {
                        $supplierQuery->where('nama', 'like', "%{$search}%");
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

        $incomingGoods = $query->latest('tanggal_masuk')
            ->paginate(10)
            ->withQueryString();

        return view('warehouse.incoming-goods.index', compact(
            'incomingGoods',
            'totalBarangMasuk',
            'totalJumlahMasuk',
            'totalBiaya',
        ));
    }

    public function create()
    {
        $products = Product::with(['supplier', 'category'])
            ->where('status', 'active')
            ->whereHas('supplier', function ($q) {
                $q->where('status', 'active');
            })
            ->orderBy('nama')
            ->get();

        return view('warehouse.incoming-goods.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'harga_beli' => ['required', 'numeric', 'min:0'],
            'tanggal_masuk' => ['required', 'date'],
        ]);

        $warehouse = Auth::user();
        $branchId = $warehouse->branch_id;

        $product = Product::with('supplier')->findOrFail($request->product_id);

        if ($product->status !== 'active') {
            return back()
                ->withInput()
                ->with('error', 'Produk '.$product->nama.' tidak aktif, tidak bisa menambah stok.');
        }

        if ($product->supplier && $product->supplier->status !== 'active') {
            return back()
                ->withInput()
                ->with('error', 'Supplier '.$product->supplier->nama.' tidak aktif, tidak bisa menambah stok produk ini.');
        }

        DB::transaction(function () use ($request, $warehouse, $branchId, $product) {
            IncomingGood::create([
                'user_id' => $warehouse->id,
                'branch_id' => $branchId,
                'product_id' => $product->id,
                'jumlah' => $request->jumlah,
                'harga_beli' => $request->harga_beli,
                'tanggal_masuk' => $request->tanggal_masuk,
            ]);

            $stock = Stock::firstOrCreate(
                [
                    'branch_id' => $branchId,
                    'product_id' => $product->id,
                ],
                [
                    'jumlah_stok' => 0,
                ]
            );

            $stock->increment('jumlah_stok', $request->jumlah);

            if ((float) $product->harga_beli !== (float) $request->harga_beli) {
                $product->update([
                    'harga_beli' => $request->harga_beli,
                ]);
            }
        });

        return redirect()
            ->route('warehouse.incoming-goods.index')
            ->with('success', 'Barang masuk berhasil disimpan, stok diperbarui, dan harga beli produk disesuaikan.');
    }

    public function show(IncomingGood $incomingGood)
    {
        $warehouse = Auth::user();
        $branchId = $warehouse->branch_id;

        abort_if($incomingGood->branch_id != $branchId, 403);

        $incomingGood->load([
            'product.category',
            'product.supplier',
            'user',
            'branch',
        ]);

        return view('warehouse.incoming-goods.show', compact('incomingGood'));
    }
}
