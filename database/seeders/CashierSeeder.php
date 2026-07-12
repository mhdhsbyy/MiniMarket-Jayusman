<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CashierSeeder extends Seeder
{
    public function run(): void
    {
        $cashier = [
            // cashier 1
            [
                'username' => 'cashier_cianjur_1',
                'first_name' => 'Cashier 1',
                'last_name' => 'Cianjur',
                'email' => 'cashier.cianjur1@gmail.com',
                'no_hp' => '081111111101',
                'branch_id' => 1,
            ],
            [
                'username' => 'cashier_bandung_1',
                'first_name' => 'Cashier 1',
                'last_name' => 'Bandung',
                'email' => 'cashier.bandung1@gmail.com',
                'no_hp' => '082222222212',
                'branch_id' => 2,
            ],
            [
                'username' => 'cashier_bogor_1',
                'first_name' => 'Cashier 1',
                'last_name' => 'Bogor',
                'email' => 'cashier.bogor1@gmail.com',
                'no_hp' => '083333333323',
                'branch_id' => 3,
            ],
            [
                'username' => 'cashier_sukabumi_1',
                'first_name' => 'Cashier 1',
                'last_name' => 'Sukabumi',
                'email' => 'cashier.sukabumi1@gmail.com',
                'no_hp' => '084444444434',
                'branch_id' => 4,
            ],
            [
                'username' => 'cashier_jakarta_1',
                'first_name' => 'Cashier 1',
                'last_name' => 'Jakarta',
                'email' => 'cashier.jakarta1@gmail.com',
                'no_hp' => '085555555545',
                'branch_id' => 5,
            ],
            // cashier 2
            [
                'username' => 'cashier_cianjur_2',
                'first_name' => 'Cashier 2',
                'last_name' => 'Cianjur',
                'email' => 'cashier.cianjur2@gmail.com',
                'no_hp' => '081111111151',
                'branch_id' => 1,
            ],
            [
                'username' => 'cashier_bandung_2',
                'first_name' => 'Cashier 2',
                'last_name' => 'Bandung',
                'email' => 'cashier.bandung2@gmail.com',
                'no_hp' => '082222222262',
                'branch_id' => 2,
            ],
            [
                'username' => 'cashier_bogor_2',
                'first_name' => 'Cashier 2',
                'last_name' => 'Bogor',
                'email' => 'cashier.bogor2@gmail.com',
                'no_hp' => '083333333373',
                'branch_id' => 3,
            ],
            [
                'username' => 'cashier_sukabumi_2',
                'first_name' => 'Cashier 2',
                'last_name' => 'Sukabumi',
                'email' => 'cashier.sukabumi2@gmail.com',
                'no_hp' => '084444444484',
                'branch_id' => 4,
            ],
            [
                'username' => 'cashier_jakarta_2',
                'first_name' => 'Cashier 2',
                'last_name' => 'Jakarta',
                'email' => 'cashier.jakarta2@gmail.com',
                'no_hp' => '085555555595',
                'branch_id' => 5,
            ],
        ];

        foreach ($cashier as $data) {

            $cashier = User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'no_hp' => $data['no_hp'],
                'status' => 'active',
                'branch_id' => $data['branch_id'],
            ]);

            $cashier->assignRole('cashier');
        }
    }
}
