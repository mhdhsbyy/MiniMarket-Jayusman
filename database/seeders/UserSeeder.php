<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'owner']);
        Role::create(['name' => 'manager']);
        Role::create(['name' => 'supervisor']);
        Role::create(['name' => 'cashier']);
        Role::create(['name' => 'warehouse']);

        // owner
        User::create([
            'username' => 'jayusman',
            'first_name' => 'Pak Jayusman',
            'last_name' => 'Solehudin',
            'email' => 'jayusman@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'no_hp' => '085294644147',
            'status' => 'active',
            'branch_id' => null,
        ])->assignRole('Owner');

    }
}
