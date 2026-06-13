<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        Supplier::insert([
            [
                'nama' => 'PT Indofood',
                'telepon' => '081234567001',
                'alamat' => 'Jakarta',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Wings Food',
                'telepon' => '081234567002',
                'alamat' => 'Surabaya',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Mayora',
                'telepon' => '081234567003',
                'alamat' => 'Tangerang',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Unilever Indonesia',
                'telepon' => '081234567004',
                'alamat' => 'Jakarta',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Tirta Investama',
                'telepon' => '081234567005',
                'alamat' => 'Bogor',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
