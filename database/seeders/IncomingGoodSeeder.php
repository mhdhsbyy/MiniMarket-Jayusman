<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\IncomingGood;
use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Seeder;

class IncomingGoodSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            $warehouse = User::role('warehouse')
                ->where('branch_id', $branch->id)
                ->first();

            if (! $warehouse) {
                continue;
            }

            $products = Product::whereHas('stocks', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })->get();

            if ($products->isEmpty()) {
                continue;
            }

            for ($i = 1; $i <= 5; $i++) {

                $product = $products->random();

                $jumlah = rand(10, 50);
                $hargaBeli = $product->harga_beli ?: rand(5000, 50000);

                $incomingGood = IncomingGood::create([
                    'branch_id' => $branch->id,
                    'user_id' => $warehouse->id,
                    'product_id' => $product->id,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'tanggal_masuk' => now()
                        ->subDays(rand(0, 30))
                        ->format('Y-m-d'),
                    'keterangan' => 'Barang masuk dari supplier',
                ]);

                $stock = Stock::firstOrCreate(
                    [
                        'branch_id' => $branch->id,
                        'product_id' => $product->id,
                    ],
                    [
                        'jumlah_stok' => 0,
                    ]
                );

                $stock->increment('jumlah_stok', $jumlah);

                if ((float) $product->harga_beli !== (float) $hargaBeli) {
                    $product->update([
                        'harga_beli' => $hargaBeli,
                    ]);
                }
            }
        }
    }
}
