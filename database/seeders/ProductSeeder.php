<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = Supplier::pluck('kode', 'id');

        $products = [
            // Supplier 1: PT Indofood — Makanan Instan, Minuman
            ['nama' => 'Indomie Goreng', 'category_id' => 2, 'supplier_id' => 1, 'harga_beli' => 2500, 'harga_jual' => 3500, 'satuan' => 'pcs'],
            ['nama' => 'Indomie Soto', 'category_id' => 2, 'supplier_id' => 1, 'harga_beli' => 2500, 'harga_jual' => 3500, 'satuan' => 'pcs'],
            ['nama' => 'Indomie Kari Ayam', 'category_id' => 2, 'supplier_id' => 1, 'harga_beli' => 2500, 'harga_jual' => 3500, 'satuan' => 'pcs'],
            ['nama' => 'Indomie Ayam Bawang', 'category_id' => 2, 'supplier_id' => 1, 'harga_beli' => 2500, 'harga_jual' => 3500, 'satuan' => 'pcs'],
            ['nama' => 'Pop Ice Coklat', 'category_id' => 3, 'supplier_id' => 1, 'harga_beli' => 1000, 'harga_jual' => 2000, 'satuan' => 'pcs'],

            // Supplier 2: PT Wings Food — Makanan Instan, Perawatan Tubuh, Perlengkapan Rumah
            ['nama' => 'Mie Sedaap Goreng', 'category_id' => 2, 'supplier_id' => 2, 'harga_beli' => 2400, 'harga_jual' => 3300, 'satuan' => 'pcs'],
            ['nama' => 'Mie Sedaap Soto', 'category_id' => 2, 'supplier_id' => 2, 'harga_beli' => 2400, 'harga_jual' => 3300, 'satuan' => 'pcs'],
            ['nama' => 'Nuvo Sabun Batang', 'category_id' => 4, 'supplier_id' => 2, 'harga_beli' => 3000, 'harga_jual' => 5000, 'satuan' => 'pcs'],
            ['nama' => 'So Klin Pewangi', 'category_id' => 5, 'supplier_id' => 2, 'harga_beli' => 5000, 'harga_jual' => 7500, 'satuan' => 'pcs'],
            ['nama' => 'Sabun Ekonomi', 'category_id' => 4, 'supplier_id' => 2, 'harga_beli' => 2000, 'harga_jual' => 3500, 'satuan' => 'pcs'],

            // Supplier 3: PT Mayora — Makanan Instan, Minuman
            ['nama' => 'Roma Kelapa', 'category_id' => 2, 'supplier_id' => 3, 'harga_beli' => 5000, 'harga_jual' => 7000, 'satuan' => 'pcs'],
            ['nama' => 'Roma Marie Susu', 'category_id' => 2, 'supplier_id' => 3, 'harga_beli' => 6000, 'harga_jual' => 8500, 'satuan' => 'pcs'],
            ['nama' => 'Teh Pucuk Harum', 'category_id' => 3, 'supplier_id' => 3, 'harga_beli' => 2500, 'harga_jual' => 4000, 'satuan' => 'botol'],
            ['nama' => 'Kopi Torabika', 'category_id' => 3, 'supplier_id' => 3, 'harga_beli' => 3000, 'harga_jual' => 4500, 'satuan' => 'pcs'],
            ['nama' => 'Energen Coklat', 'category_id' => 2, 'supplier_id' => 3, 'harga_beli' => 1500, 'harga_jual' => 2500, 'satuan' => 'pcs'],

            // Supplier 4: PT Unilever Indonesia — Perawatan Tubuh, Perlengkapan Rumah
            ['nama' => 'Pepsodent 190 gr', 'category_id' => 4, 'supplier_id' => 4, 'harga_beli' => 12000, 'harga_jual' => 16000, 'satuan' => 'pcs'],
            ['nama' => 'Lifebuoy Merah', 'category_id' => 4, 'supplier_id' => 4, 'harga_beli' => 4000, 'harga_jual' => 6000, 'satuan' => 'pcs'],
            ['nama' => 'Rinso Cair', 'category_id' => 5, 'supplier_id' => 4, 'harga_beli' => 12000, 'harga_jual' => 16500, 'satuan' => 'pcs'],
            ['nama' => 'Molto Pewangi', 'category_id' => 5, 'supplier_id' => 4, 'harga_beli' => 5000, 'harga_jual' => 8000, 'satuan' => 'pcs'],
            ['nama' => 'Lifebuoy Lemon', 'category_id' => 4, 'supplier_id' => 4, 'harga_beli' => 4000, 'harga_jual' => 6000, 'satuan' => 'pcs'],

            // Supplier 5: PT Tirta Investama — Minuman
            ['nama' => 'Aqua 600 ml', 'category_id' => 3, 'supplier_id' => 5, 'harga_beli' => 2000, 'harga_jual' => 3500, 'satuan' => 'botol'],
            ['nama' => 'Aqua 1500 ml', 'category_id' => 3, 'supplier_id' => 5, 'harga_beli' => 3000, 'harga_jual' => 5000, 'satuan' => 'botol'],
            ['nama' => 'Le Minerale 600 ml', 'category_id' => 3, 'supplier_id' => 5, 'harga_beli' => 2000, 'harga_jual' => 3500, 'satuan' => 'botol'],
            ['nama' => 'Ultra Milk Coklat', 'category_id' => 3, 'supplier_id' => 5, 'harga_beli' => 6000, 'harga_jual' => 9000, 'satuan' => 'kotak'],
            ['nama' => 'Teh Botol Sosro', 'category_id' => 3, 'supplier_id' => 5, 'harga_beli' => 3000, 'harga_jual' => 5000, 'satuan' => 'botol'],
        ];

        $counters = [];

        foreach ($products as $item) {
            $supplierId = $item['supplier_id'];

            if (! isset($counters[$supplierId])) {
                $counters[$supplierId] = 1;
            }

            $kode = 'PRD-'.$suppliers[$supplierId].'-'.str_pad($counters[$supplierId], 3, '0', STR_PAD_LEFT);

            Product::create([
                'category_id' => $item['category_id'],
                'supplier_id' => $supplierId,
                'kode' => $kode,
                'nama' => $item['nama'],
                'harga_beli' => $item['harga_beli'],
                'harga_jual' => $item['harga_jual'],
                'satuan' => $item['satuan'],
                'status' => 'active',
            ]);

            $counters[$supplierId]++;
        }
    }
}
