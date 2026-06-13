<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // manager
        $managers = [
            [
                'username' => 'manager_cianjur',
                'first_name' => 'Ahmad',
                'last_name' => 'Ramadhan',
                'email' => 'manager.cianjur@gmail.com',
                'no_hp' => '081111111111',
                'cabang_id' => 1,
            ],
            [
                'username' => 'manager_bandung',
                'first_name' => 'Budi',
                'last_name' => 'Santoso',
                'email' => 'manager.bandung@gmail.com',
                'no_hp' => '082222222222',
                'cabang_id' => 2,
            ],
            [
                'username' => 'manager_bogor',
                'first_name' => 'Candra',
                'last_name' => 'Wijaya',
                'email' => 'manager.bogor@gmail.com',
                'no_hp' => '083333333333',
                'cabang_id' => 3,
            ],
            [
                'username' => 'manager_sukabumi',
                'first_name' => 'Deni',
                'last_name' => 'Pratama',
                'email' => 'manager.sukabumi@gmail.com',
                'no_hp' => '084444444444',
                'cabang_id' => 4,
            ],
            [
                'username' => 'manager_garut',
                'first_name' => 'Eko',
                'last_name' => 'Saputra',
                'email' => 'manager.garut@gmail.com',
                'no_hp' => '085555555555',
                'cabang_id' => 5,
            ],
        ];

        foreach ($managers as $data) {

            $manager = User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'no_hp' => $data['no_hp'],
                'status' => 'active',
                'branch_id' => $data['cabang_id'],
            ]);

            $manager->assignRole('manager');
        }
    }
}
