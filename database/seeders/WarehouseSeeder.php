<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class WarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warehouses = [

            // Cabang 1
            [
                'username' => 'warehouse_cianjur',
                'first_name' => 'Warehouse',
                'last_name' => 'Cianjur',
                'email' => 'warehouse.cianjur@gmail.com',
                'no_hp' => '081111111011',
                'branch_id' => 1,
            ],

            // Cabang 2
            [
                'username' => 'warehouse_bandung',
                'first_name' => 'Warehouse',
                'last_name' => 'Bandung',
                'email' => 'warehouse.bandung@gmail.com',
                'no_hp' => '082222222321',
                'branch_id' => 2,
            ],

            // Cabang 3
            [
                'username' => 'warehouse_bogor',
                'first_name' => 'Warehouse',
                'last_name' => 'Bogor',
                'email' => 'warehouse.bogor1@gmail.com',
                'no_hp' => '083333333531',
                'branch_id' => 3,
            ],

            // Cabang 4
            [
                'username' => 'warehouse_sukabumi',
                'first_name' => 'Warehouse',
                'last_name' => 'Sukabumi',
                'email' => 'warehouse.sukabumi@gmail.com',
                'no_hp' => '084444444741',
                'branch_id' => 4,
            ],

            // Cabang 5
            [
                'username' => 'warehouse_jakarta',
                'first_name' => 'Warehouse',
                'last_name' => 'Jakarta',
                'email' => 'warehouse.garut@gmail.com',
                'no_hp' => '085555555951',
                'branch_id' => 5,
            ],
        ];

        foreach ($warehouses as $data) {

            $user = User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'no_hp' => $data['no_hp'],
                'status' => 'active',
                'branch_id' => $data['branch_id'],
            ]);

            $user->assignRole('warehouse');
        }
    }
}
