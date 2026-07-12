<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
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
            'branch_id' => [
                'required',
                'exists:branches,id',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $branch = Branch::find($value);
                    if ($branch && $branch->status !== 'active') {
                        $fail('Cabang yang dipilih tidak aktif.');
                    }
                    if (User::role('manager')->where('branch_id', $value)->exists()) {
                        $fail('Cabang ini sudah memiliki manager.');
                    }
                },
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username',
            ],
            'first_name' => [
                'required',
                'max:100',
            ],
            'last_name' => [
                'required',
                'max:100',
            ],
            'email' => 'required|email|unique:users,email',
            'no_hp' => 'required|numeric|digits_between:10,13',
            'password' => 'required|min:8',
            'status' => 'required|in:active,inactive',
        ], [
            'branch_id.required' => 'Cabang wajib di pilih.',
            'branch_id.exists' => 'Cabang tidak valid.',
            'username.required' => 'Username wajib di isi.',
            'username.unique' => 'Username sudah digunakan.',
            'first_name.required' => 'Nama depan wajib di isi.',
            'last_name.required' => 'Nama belakang wajib di isi.',
            'email.required' => 'Email wajib di isi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'No HP wajib di isi.',
            'no_hp.numeric' => 'No HP hanya boleh angka.',
            'no_hp.digits_between' => 'No HP harus antara 10 sampai 13 digit.',
            'password.required' => 'Password wajib di isi.',
            'password.min' => 'Password minimal 8 karakter.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $manager = User::create([
            'branch_id' => $request->branch_id,
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
            'branch_id' => [
                'required',
                'exists:branches,id',
                function (string $attribute, mixed $value, \Closure $fail) use ($manager) {
                    $branch = Branch::find($value);
                    if ($branch && $branch->status !== 'active') {
                        $fail('Cabang yang dipilih tidak aktif.');
                    }
                    if (User::role('manager')->where('branch_id', $value)->where('id', '!=', $manager->id)->exists()) {
                        $fail('Cabang ini sudah memiliki manager.');
                    }
                },
            ],
            'username' => [
                'required',
                'string',
                'max:100',
                'unique:users,username,'.$manager->id,
            ],
            'first_name' => [
                'required',
                'max:100',
            ],
            'last_name' => [
                'required',
                'max:100',
            ],
            'email' => 'required|email|unique:users,email,'.$manager->id,
            'no_hp' => 'required|numeric|digits_between:10,13',
            'status' => 'required|in:active,inactive',
        ], [
            'branch_id.required' => 'Cabang wajib di pilih.',
            'branch_id.exists' => 'Cabang tidak valid.',
            'username.required' => 'Username wajib di isi.',
            'username.unique' => 'Username sudah digunakan.',
            'first_name.required' => 'Nama depan wajib di isi.',
            'last_name.required' => 'Nama belakang wajib di isi.',
            'email.required' => 'Email wajib di isi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'No HP wajib di isi.',
            'no_hp.numeric' => 'No HP hanya boleh angka.',
            'no_hp.digits_between' => 'No HP harus antara 10 sampai 13 digit.',
            'status.required' => 'Status wajib di pilih.',
        ]);

        $manager->update([
            'branch_id' => $request->branch_id,
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
        $manager->update([
            'status' => $manager->status == 'active' ? 'inactive' : 'active',
        ]);

        return redirect()
            ->route('owner.managers.index')
            ->with('success', 'Status manajer berhasil diperbarui.');
    }
}
