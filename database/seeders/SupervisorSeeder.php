<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            $namaCabang = Str::slug($branch->kota, '');

            $supervisor = User::create([
                'username' => 'supervisor_' . $namaCabang,
                'first_name' => 'Supervisor',
                'last_name' => $branch->nama,
                'email' => 'supervisor.' . $namaCabang . '@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '081200000' . rand(100, 999),
                'status' => 'active',
                'branch_id' => $branch->id,
            ]);

            $supervisor->assignRole('supervisor');
        }
    }
}
