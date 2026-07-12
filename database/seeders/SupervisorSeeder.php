<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    public function run(): void
    {
        $supervisor = [
            [
                'username' => 'supervisor_cianjur',
                'first_name' => 'Supervisor',
                'last_name' => 'Cianjur',
                'email' => 'supervisor.cianjur@gmail.com',
                'no_hp' => '081111111110',
                'branch_id' => 1,
            ],
            [
                'username' => 'supervisor_bandung',
                'first_name' => 'Supervisor',
                'last_name' => 'Bandung',
                'email' => 'supervisor.bandung@gmail.com',
                'no_hp' => '082222222221',
                'branch_id' => 2,
            ],
            [
                'username' => 'supervisor_bogor',
                'first_name' => 'Supervisor',
                'last_name' => 'Bogor',
                'email' => 'supervisor.bogor@gmail.com',
                'no_hp' => '083333333332',
                'branch_id' => 3,
            ],
            [
                'username' => 'supervisor_sukabumi',
                'first_name' => 'Supervisor',
                'last_name' => 'Sukabumi',
                'email' => 'supervisor.sukabumi@gmail.com',
                'no_hp' => '084444444443',
                'branch_id' => 4,
            ],
            [
                'username' => 'supervisor_jakarta',
                'first_name' => 'Supervisor',
                'last_name' => 'Jakarta',
                'email' => 'supervisor.jakarta@gmail.com',
                'no_hp' => '085555555554',
                'branch_id' => 5,
            ],
        ];

        foreach ($supervisor as $data) {

            $supervisor = User::create([
                'username' => $data['username'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make('password'),
                'no_hp' => $data['no_hp'],
                'status' => 'active',
                'branch_id' => $data['branch_id'],
            ]);

            $supervisor->assignRole('supervisor');
        }
    }
}
