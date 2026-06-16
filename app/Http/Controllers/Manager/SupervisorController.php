<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function index()
    {
        $branchId = Auth::user()->branch_id;

        $supervisors = User::role('supervisor')
            ->where('branch_id', $branchId)
            ->oldest()
            ->get();

        return view('manager.supervisors.index', compact('supervisors'));
    }

    public function create()
    {
        return view('manager.supervisors.create');
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

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
            'branch_id' => $branchId,
            'status' => $request->status,
        ]);

        $user->assignRole('supervisor');

        return redirect()
            ->route('manager.supervisors.index')
            ->with('success', 'Supervisor berhasil ditambahkan.');
    }

    public function edit(User $supervisor)
    {
        $branchId = Auth::user()->branch_id;

        if ($supervisor->branch_id !== $branchId || ! $supervisor->hasRole('supervisor')) {
            abort(403);
        }

        return view('manager.supervisors.edit', compact('supervisor'));
    }

    public function update(Request $request, User $supervisor)
    {
        $branchId = Auth::user()->branch_id;

        if ($supervisor->branch_id !== $branchId || ! $supervisor->hasRole('supervisor')) {
            abort(403);
        }

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'username' => 'required|string|max:100|unique:users,username,' . $supervisor->id,
            'email' => 'required|email|unique:users,email,' . $supervisor->id,
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

        $supervisor->update($data);

        return redirect()
            ->route('manager.supervisors.index')
            ->with('success', 'Supervisor berhasil diperbarui.');
    }

    public function destroy(User $supervisor)
    {
        $branchId = Auth::user()->branch_id;

        if (
            $supervisor->branch_id != $branchId ||
            !$supervisor->hasRole('supervisor')
        ) {
            abort(403);
        }

        $supervisor->update([
            'status' => $supervisor->status == 'active'
                ? 'inactive'
                : 'active'
        ]);

        return redirect()
            ->route('manager.supervisors.index')
            ->with('success', 'Status supervisor berhasil diperbarui.');
    }
}
