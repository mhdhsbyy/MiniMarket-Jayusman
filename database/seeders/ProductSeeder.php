<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            'Indomie Goreng',
            'Indomie Soto',
            'Mie Sedaap Soto',
            'Mie Sedaap Goreng',
            'Aqua 600 ml',
            'Aqua 1500 ml',
            'Le Minerale 600 ml',
            'Teh Botol Sosro',
            'Pucuk Harum',
            'Ultra Milk Coklat',
            'Beras Ramos 5 Kg',
            'Beras Pandan Wangi 5 Kg',
            'Gula Pasir 1 Kg',
            'Minyak Goreng Bimoli 1 L',
            'Minyak Goreng Sunco 1 L',
            'Pepsodent 190 gr',
            'Pepsodent 120 gr',
            'Lifebuoy Merah',
            'Lifebuoy Lemon',
            'Nuvo Family',
            'Tissue Paseo',
            'Tissue Nice',
            'Rinso Cair',
            'Rinso Bubuk',
            'Molto Pewangi',
            'Kapal Api Special',
            'ABC Kopi Susu',
            'Good Day Cappuccino',
            'Roma Kelapa',
            'Oreo Vanilla',
        ];

        foreach ($products as $index => $product) {

            $hargaBeli = rand(2000, 50000);

            Product::create([
                'category_id' => rand(1, 5),
                'supplier_id' => rand(1, 5),

                'kode' => 'BRG' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),

                'nama' => $product,

                'harga_beli' => $hargaBeli,

                'harga_jual' => $hargaBeli + rand(1000, 10000),

                'satuan' => collect([
                    'pcs',
                    'botol',
                    'pack',
                    'dus'
                ])->random(),

                'status' => 'active',
            ]);
        }
    }
}
