<?php

namespace App\Http\Controllers\Warehouse;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomingGoodController extends Controller
{
    public function index()
    {
        $incomingGoods = IncomingGood::with(['product', 'branch'])
            ->where('branch_id', Auth::user()->cabang_id)
            ->latest()
            ->get();

        return view('warehouse.incoming-goods.index', compact('incomingGoods'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')->get();

        return view('warehouse.incoming-goods.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required',
        ]);

        $incoming = IncomingGood::create([
            'branch_id' => Auth::user()->cabang_id,
            'product_id' => $request->product_id,
            'jumlah' => $request->jumlah,
            'tanggal_masuk' => $request->tanggal_masuk,
            'keterangan' => $request->keterangan,
        ]);

        $stock = Stock::where('branch_id', Auth::user()->cabang_id)
            ->where('product_id', $request->product_id)
            ->first();

        $stock->increment('jumlah', $request->jumlah);

        return redirect()
            ->route('warehouse.incoming-goods.index')
            ->with('success', 'Barang masuk berhasil ditambahkan.');
    }
}
