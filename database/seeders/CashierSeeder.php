<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CashierSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            $namaCabang = Str::slug($branch->kota, '');

            for ($i = 1; $i <= 3; $i++) {

                $cashier = User::create([
                    'username' => 'cashier_' . $namaCabang . '_' . $i,
                    'first_name' => 'Cashier',
                    'last_name' => $i,
                    'email' => 'cashier.' . $namaCabang . '.' . $i . '@gmail.com',
                    'password' => Hash::make('password'),
                    'no_hp' => '081300000' . rand(100,999),
                    'status' => 'active',
                    'branch_id' => $branch->id,
                ]);

                $cashier->assignRole('cashier');
            }
        }
    }
}
