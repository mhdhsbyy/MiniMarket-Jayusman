<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Branch::insert([
            [
                'kode' => 'JM001',
                'nama' => 'Jayusmart Cianjur',
                'kota' => 'Cianjur',
                'alamat' => 'Jl. Siliwangi No. 12, Cianjur',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JM002',
                'nama' => 'Jayusmart Bandung',
                'kota' => 'Bandung',
                'alamat' => 'Jl. Asia Afrika No. 45, Bandung',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JM003',
                'nama' => 'Jayusmart Bogor',
                'kota' => 'Bogor',
                'alamat' => 'Jl. Pajajaran No. 88, Bogor',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JM004',
                'nama' => 'Jayusmart Sukabumi',
                'kota' => 'Sukabumi',
                'alamat' => 'Jl. Ahmad Yani No. 21, Sukabumi',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'JM005',
                'nama' => 'Jayusmart Jakarta',
                'kota' => 'Jakarta',
                'alamat' => 'Jl. Guntur No. 10, Jakarta',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
