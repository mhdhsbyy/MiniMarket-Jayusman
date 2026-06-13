<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CashierSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            for ($i = 1; $i <= 3; $i++) {

                $cashier = User::create([
                    'username' => 'cashier'.$i.'_'.$branch->id,
                    'first_name' => 'Cashier',
                    'last_name' => $i,
                    'email' => 'cashier'.$i.'.branch'.$branch->id.'@gmail.com',
                    'password' => Hash::make('password'),
                    'no_hp' => '0813' . rand(10000000,99999999),
                    'status' => 'active',
                    'branch_id' => $branch->id,
                ]);

                $cashier->assignRole('cashier');
            }
        }
    }
}
