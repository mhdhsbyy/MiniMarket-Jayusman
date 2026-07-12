<?php

namespace Database\Seeders;

use App\Models\User;
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
                'first_name' => 'Manager',
                'last_name' => 'Cianjur',
                'email' => 'manager.cianjur@gmail.com',
                'no_hp' => '081111111111',
                'branch_id' => 1,
            ],
            [
                'username' => 'manager_bandung',
                'first_name' => 'Manager',
                'last_name' => 'Bandung',
                'email' => 'manager.bandung@gmail.com',
                'no_hp' => '082222222222',
                'branch_id' => 2,
            ],
            [
                'username' => 'manager_bogor',
                'first_name' => 'Manager',
                'last_name' => 'Bogor',
                'email' => 'manager.bogor@gmail.com',
                'no_hp' => '083333333333',
                'branch_id' => 3,
            ],
            [
                'username' => 'manager_sukabumi',
                'first_name' => 'Manager',
                'last_name' => 'Sukabumi',
                'email' => 'manager.sukabumi@gmail.com',
                'no_hp' => '084444444444',
                'branch_id' => 4,
            ],
            [
                'username' => 'manager_jakarta',
                'first_name' => 'Manager',
                'last_name' => 'Jakarta',
                'email' => 'manager.jakarta@gmail.com',
                'no_hp' => '085555555555',
                'branch_id' => 5,
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
                'branch_id' => $data['branch_id'],
            ]);

            $manager->assignRole('manager');
        }
    }
}
