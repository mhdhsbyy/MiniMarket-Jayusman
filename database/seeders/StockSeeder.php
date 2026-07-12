<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();
        $products = Product::all();

        foreach ($branches as $branch) {

            $selectedProducts = $products->count() > 10
                ? $products->random(10)
                : $products;

            foreach ($selectedProducts as $product) {

                Stock::create([
                    'branch_id' => $branch->id,
                    'product_id' => $product->id,
                    'jumlah_stok' => rand(20, 100),
                ]);

            }
        }
    }
}
