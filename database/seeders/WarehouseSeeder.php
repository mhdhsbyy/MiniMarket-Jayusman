<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
                'username' => 'warehouse_cianjur_1',
                'first_name' => 'Rizky',
                'last_name' => 'Pratama',
                'email' => 'warehouse.cianjur1@gmail.com',
                'no_hp' => '081111111111',
                'branch_id' => 1,
            ],
            [
                'username' => 'warehouse_cianjur_2',
                'first_name' => 'Dimas',
                'last_name' => 'Saputra',
                'email' => 'warehouse.cianjur2@gmail.com',
                'no_hp' => '081111111112',
                'branch_id' => 1,
            ],

            // Cabang 2
            [
                'username' => 'warehouse_bandung_1',
                'first_name' => 'Fajar',
                'last_name' => 'Ramadhan',
                'email' => 'warehouse.bandung1@gmail.com',
                'no_hp' => '082222222221',
                'branch_id' => 2,
            ],
            [
                'username' => 'warehouse_bandung_2',
                'first_name' => 'Andi',
                'last_name' => 'Wijaya',
                'email' => 'warehouse.bandung2@gmail.com',
                'no_hp' => '082222222222',
                'branch_id' => 2,
            ],

            // Cabang 3
            [
                'username' => 'warehouse_bogor_1',
                'first_name' => 'Reza',
                'last_name' => 'Kurniawan',
                'email' => 'warehouse.bogor1@gmail.com',
                'no_hp' => '083333333331',
                'branch_id' => 3,
            ],
            [
                'username' => 'warehouse_bogor_2',
                'first_name' => 'Arif',
                'last_name' => 'Setiawan',
                'email' => 'warehouse.bogor2@gmail.com',
                'no_hp' => '083333333332',
                'branch_id' => 3,
            ],

            // Cabang 4
            [
                'username' => 'warehouse_sukabumi_1',
                'first_name' => 'Bima',
                'last_name' => 'Nugraha',
                'email' => 'warehouse.sukabumi1@gmail.com',
                'no_hp' => '084444444441',
                'branch_id' => 4,
            ],
            [
                'username' => 'warehouse_sukabumi_2',
                'first_name' => 'Yoga',
                'last_name' => 'Firmansyah',
                'email' => 'warehouse.sukabumi2@gmail.com',
                'no_hp' => '084444444442',
                'branch_id' => 4,
            ],

            // Cabang 5
            [
                'username' => 'warehouse_garut_1',
                'first_name' => 'Ilham',
                'last_name' => 'Maulana',
                'email' => 'warehouse.garut1@gmail.com',
                'no_hp' => '085555555551',
                'branch_id' => 5,
            ],
            [
                'username' => 'warehouse_garut_2',
                'first_name' => 'Akbar',
                'last_name' => 'Hidayat',
                'email' => 'warehouse.garut2@gmail.com',
                'no_hp' => '085555555552',
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
