<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;


class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::orderBy('kode')->get();

        return view('owner.branches.kelola-cabang', compact('branches'));
    }

    public function show(Branch $branch)
{
    $branch->load([
        'manager',
        'employees',
        'stocks.product.category',
        'stocks.product.supplier',
    ]);

    $totalProduk = $branch->stocks->count();

    $totalKaryawan = $branch->employees->count();

    $totalStok = $branch->stocks->sum('jumlah_stok');

    return view('owner.branches.show', compact(
        'branch',
        'totalProduk',
        'totalKaryawan',
        'totalStok'
    ));
}

    public function create()
    {
        return view('owner.branches.tambah-cabang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:branches,kode',
            'nama' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        Branch::create([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('owner.branches.index')
            ->with('success', 'Cabang berhasil ditambahkan.');
    }

    public function edit(Branch $branch)
    {
        return view('owner.branches.edit-cabang', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $request->validate([
            'kode' => 'required|string|max:20|unique:branches,kode,' . $branch->id,
            'nama' => 'required|string|max:255',
            'kota' => 'required|string|max:100',
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $branch->update([
            'kode' => $request->kode,
            'nama' => $request->nama,
            'kota' => $request->kota,
            'alamat' => $request->alamat,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('owner.branches.index')
            ->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $hasUser = User::where('cabang_id', $branch->id)->exists();

        if ($hasUser) {
            return redirect()
                ->route('owner.branches.index')
                ->with('error', 'Cabang tidak dapat dihapus. Hapus atau pindahkan seluruh pegawai terlebih dahulu.');
        }

        $branch->delete();

        return redirect()
            ->route('owner.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
