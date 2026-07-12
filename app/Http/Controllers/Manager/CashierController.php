<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CashierController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $cashiers = User::role('cashier')
            ->where('branch_id', $branchId)
            ->oldest()
            ->get();

        return view('manager.cashiers.index', compact('cashiers'));
    }

    public function create()
    {
        return view('manager.cashiers.create');
    }

    public function store(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|numeric|digits_between:10,13',
            'password' => 'required|min:8',
            'status' => 'required|in:active,inactive',
        ], [
            'first_name.required' => 'Nama depan wajib di isi.',
            'last_name.required' => 'Nama belakang wajib di isi.',
            'username.required' => 'Username wajib di isi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib di isi.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'No HP wajib di isi.',
            'no_hp.numeric' => 'No HP hanya boleh angka.',
            'no_hp.digits_between' => 'No HP harus antara 10 sampai 13 digit.',
            'password.required' => 'Password wajib di isi.',
            'password.min' => 'Password minimal 8 karakter.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $cashier = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'branch_id' => $branchId,
            'status' => $request->status,
        ]);

        $cashier->assignRole('cashier');

        return redirect()
            ->route('manager.cashiers.index')
            ->with('success', 'Kasir berhasil ditambahkan.');
    }

    public function edit(User $cashier)
    {
        $branchId = Auth::user()->branch_id;

        if ($cashier->branch_id != $branchId || ! $cashier->hasRole('cashier')) {
            abort(403);
        }

        return view('manager.cashiers.edit', compact('cashier'));
    }

    public function update(Request $request, User $cashier)
    {
        $branchId = Auth::user()->branch_id;

        if ($cashier->branch_id != $branchId || ! $cashier->hasRole('cashier')) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'username' => 'required|string|max:100|unique:users,username,'.$cashier->id,
            'email' => 'required|email|unique:users,email,'.$cashier->id,
            'no_hp' => 'required|numeric|digits_between:10,13',
            'password' => 'nullable|min:8',
            'status' => 'required|in:active,inactive',
        ], [
            'first_name.required' => 'Nama depan wajib di isi.',
            'last_name.required' => 'Nama belakang wajib di isi.',
            'username.required' => 'Username wajib di isi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib di isi.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'No HP wajib di isi.',
            'no_hp.numeric' => 'No HP hanya boleh angka.',
            'no_hp.digits_between' => 'No HP harus antara 10 sampai 13 digit.',
            'password.min' => 'Password minimal 8 karakter.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $cashier->update($data);

        return redirect()
            ->route('manager.cashiers.index')
            ->with('success', 'Kasir berhasil diperbarui.');
    }

    public function destroy(User $cashier)
    {
        $branchId = Auth::user()->branch_id;

        if ($cashier->branch_id != $branchId || ! $cashier->hasRole('cashier')) {
            abort(403);
        }

        $cashier->update([
            'status' => $cashier->status == 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('manager.cashiers.index')
            ->with('success', 'Status kasir berhasil diperbarui.');
    }
}
