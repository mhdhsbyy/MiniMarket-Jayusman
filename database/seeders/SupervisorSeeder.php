<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            $supervisor = User::create([
                'username' => 'supervisor_' . strtolower($branch->kota),
                'first_name' => 'Supervisor',
                'last_name' => $branch->kota,
                'email' => 'supervisor.' . strtolower($branch->kota) . '@gmail.com',
                'password' => Hash::make('password'),
                'no_hp' => '0812' . rand(10000000,99999999),
                'status' => 'active',
                'branch_id' => $branch->id,
            ]);

            $supervisor->assignRole('supervisor');
        }
    }
}
