<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class WarehouseController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $warehouses = User::role('warehouse')
            ->where('branch_id', $branchId)
            ->oldest()
            ->get();

        return view('manager.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
        return view('manager.warehouses.create');
    }

    public function store(Request $request)
    {
        $branchId = Auth::user()->branch_id;

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'nullable|string|max:20',
            'password' => 'required|min:8',
            'status' => 'required|in:active,inactive',
        ]);

        $warehouse = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'branch_id' => $branchId,
            'status' => $request->status,
        ]);

        $warehouse->assignRole('warehouse');

        return redirect()
            ->route('manager.warehouses.index')
            ->with('success', 'Pegawai gudang berhasil ditambahkan.');
    }

    public function edit(User $warehouse)
    {
        $branchId = Auth::user()->branch_id;

        if ($warehouse->branch_id != $branchId || ! $warehouse->hasRole('warehouse')) {
            abort(403);
        }

        return view('manager.warehouses.edit', compact('warehouse'));
    }

    public function update(Request $request, User $warehouse)
    {
        $branchId = Auth::user()->branch_id;

        if ($warehouse->branch_id != $branchId || ! $warehouse->hasRole('warehouse')) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'username' => 'required|string|max:100|unique:users,username,' . $warehouse->id,
            'email' => 'required|email|unique:users,email,' . $warehouse->id,
            'no_hp' => 'nullable|string|max:20',
            'password' => 'nullable|min:8',
            'status' => 'required|in:active,inactive',
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

        $warehouse->update($data);

        return redirect()
            ->route('manager.warehouses.index')
            ->with('success', 'Pegawai gudang berhasil diperbarui.');
    }

    public function destroy(User $warehouse)
    {
        $branchId = Auth::user()->branch_id;

        if ($warehouse->branch_id != $branchId || ! $warehouse->hasRole('warehouse')) {
            abort(403);
        }

        $warehouse->update([
            'status' => $warehouse->status == 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('manager.warehouses.index')
            ->with('success', 'Status pegawai gudang berhasil diperbarui.');
    }
}
