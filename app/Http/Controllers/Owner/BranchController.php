<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\IncomingGood;
use App\Models\Stock;
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
            'kode' => [
                'required',
                'max:20',
                'unique:branches,kode',
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/',
            ],
            'nama' => [
                'required',
                'max:255',
            ],
            'kota' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'kode.required' => 'Kode cabang wajib di isi.',
            'kode.regex' => 'Kode cabang harus gabungan antara karakter dan angka',
            'kode.unique' => 'Kode cabang sudah digunakan.',
            'nama.required' => 'Nama cabang wajib di isi.',
            'kota.required' => 'Kota wajib di isi.',
            'kota.regex' => 'Kota harus karakter.',
            'alamat.required' => 'Alamat wajib di isi.',
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
            'kode' => [
                'required',
                'max:20',
                'unique:branches,kode,'.$branch->id,
                'regex:/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/',
            ],
            'nama' => [
                'required',
                'max:255',
            ],
            'kota' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z\s]+$/',
            ],
            'alamat' => 'required|string',
            'status' => 'required|in:active,inactive',
        ], [
            'kode.required' => 'Kode cabang wajib di isi.',
            'kode.regex' => 'Kode cabang harus gabungan antara karakter dan angka',
            'kode.unique' => 'Kode cabang sudah digunakan.',
            'nama.required' => 'Nama cabang wajib di isi.',
            'kota.required' => 'Kota wajib di isi.',
            'kota.regex' => 'Kota harus karakter.',
            'alamat.required' => 'Alamat wajib di isi.',
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
        $hasRelation = Transaction::where('branch_id', $branch->id)->exists()
            || Stock::where('branch_id', $branch->id)->exists()
            || IncomingGood::where('branch_id', $branch->id)->exists()
            || User::where('branch_id', $branch->id)->exists();

        if ($hasRelation) {
            return redirect()
                ->route('owner.branches.index')
                ->with('error', 'Cabang tidak dapat dihapus. Pindahkan manager dan seluruh pegawai terlebih dahulu.');
        }

        $branch->delete();

        return redirect()
            ->route('owner.branches.index')
            ->with('success', 'Cabang berhasil dihapus.');
    }
}
