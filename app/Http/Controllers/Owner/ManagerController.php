<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = User::role('manager')
            ->with('branch')
            ->orderBy('id', 'asc')
            ->get();

        return view('owner.managers.kelola-manager', compact('managers'));
    }

    public function create()
    {
        $branches = Branch::where('status', 'active')->orderBy('kode')->get();

        return view('owner.managers.tambah-manager', compact('branches'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cabang_id' => 'required|exists:branches,id',
            'username' => 'required|string|max:100|unique:users,username',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|string|max:20',
            'password' => 'required|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $manager = User::create([
            'cabang_id' => $request->cabang_id,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

        $manager->assignRole('manager');

        return redirect()
            ->route('owner.managers.index')
            ->with('success', 'Manager berhasil ditambahkan.');
    }

    public function edit(User $manager)
    {
        $branches = Branch::where('status', 'active')
            ->orderBy('kode')
            ->get();

        return view('owner.managers.edit-manager', compact('manager', 'branches'));
    }

    public function update(Request $request, User $manager)
    {
        $request->validate([
            'cabang_id' => 'required|exists:branches,id',
            'username' => 'required|string|max:100|unique:users,username,' . $manager->id,
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $manager->id,
            'no_hp' => 'required|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $manager->update([
            'cabang_id' => $request->cabang_id,
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $manager->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()
            ->route('owner.managers.index')
            ->with('success', 'Manager berhasil diperbarui.');
    }

    public function destroy(User $manager)
    {
        $manager->delete();

        return redirect()
            ->route('owner.managers.index')
            ->with('success', 'Manager berhasil dihapus.');
    }
}
