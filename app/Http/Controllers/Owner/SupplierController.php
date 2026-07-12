<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\IncomingGood;
use App\Models\Stock;
use App\Models\Supplier;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::oldest()->get();

        return view('owner.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('owner.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => [
                'required',
                'max:20',
                'unique:suppliers,kode',
                'regex:/^[A-Za-z]+$/',
            ],
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'telepon' => [
                'required',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'kode.required' => 'Kode supplier wajib di isi.',
            'kode.unique' => 'Kode supplier sudah digunakan.',
            'kode.regex' => 'Kode supplier harus karakter.',
            'nama.required' => 'Nama supplier wajib di isi.',
            'nama.regex' => 'Nama supplier harus karakter.',
            'telepon.required' => 'Telepon wajib di isi.',
            'telepon.regex' => 'Telepon harus angka.',
            'alamat.required' => 'Alamat wajib di isi.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        Supplier::create([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('owner.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('owner.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'kode' => [
                'required',
                'max:20',
                'unique:suppliers,kode,'.$supplier->id,
                'regex:/^[A-Za-z]+$/',
            ],
            'nama' => [
                'required',
                'max:255',
                'regex:/^[a-zA-Z0-9\s]+$/',
            ],
            'telepon' => [
                'required',
                'max:20',
                'regex:/^[0-9]+$/',
            ],
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'kode.required' => 'Kode supplier wajib di isi.',
            'kode.unique' => 'Kode supplier sudah digunakan.',
            'kode.regex' => 'Kode supplier harus karakter.',
            'nama.required' => 'Nama supplier wajib di isi.',
            'nama.regex' => 'Nama supplier harus karakter.',
            'telepon.required' => 'Telepon wajib di isi.',
            'telepon.regex' => 'Telepon harus angka.',
            'alamat.required' => 'Alamat wajib di isi.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $supplier->update([
            'kode' => strtoupper($request->kode),
            'nama' => $request->nama,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('owner.suppliers.index')
            ->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $productIds = $supplier->products()->pluck('id');

        $hasRelation = false;

        if ($supplier->products()->exists()) {
            if (TransactionDetail::whereIn('product_id', $productIds)->exists()) {
                $hasRelation = true;
            }

            if (Stock::whereIn('product_id', $productIds)->exists()) {
                $hasRelation = true;
            }

            if (IncomingGood::whereIn('product_id', $productIds)->exists()) {
                $hasRelation = true;
            }
        }

        if ($hasRelation) {
            return redirect()
                ->route('owner.suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena produknya sudah memiliki data transaksi, stok, atau barang masuk.');
        }

        if ($supplier->products()->exists()) {
            return redirect()
                ->route('owner.suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki produk.');
        }

        $supplier->delete();

        return redirect()
            ->route('owner.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }
}
