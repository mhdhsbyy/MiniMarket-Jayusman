<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Product;
use App\Models\Stock;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::all();

        foreach ($branches as $branch) {

            $cashiers = User::role('cashier')
                ->where('branch_id', $branch->id)
                ->get();

            for ($i = 1; $i <= 15; $i++) {

                $cashier = $cashiers->random();

                $transaction = Transaction::create([
                    'branch_id' => $branch->id,
                    'cashier_id' => $cashier->id,
                    'tanggal_transaksi' => now()
                        ->subDays(rand(0, 30))
                        ->setTime(rand(8, 21), rand(0, 59)),
                    'total_bayar' => 0,
                    'uang_dibayar' => 0,
                    'kembalian' => 0,
                    'status' => 'success',
                ]);

                $products = Product::inRandomOrder()
                    ->take(rand(1, 3))
                    ->get();

                $totalBayar = 0;

                foreach ($products as $product) {
                    $stock = Stock::where('branch_id', $branch->id)
                        ->where('product_id', $product->id)
                        ->first();

                    if (! $stock || $stock->jumlah_stok <= 10) {
                        continue;
                    }

                    $jumlah = rand(1, min(3, $stock->jumlah_stok - 10));

                    $hargaSatuan = $product->harga_jual;
                    $subtotal = $jumlah * $hargaSatuan;

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $product->id,
                        'jumlah' => $jumlah,
                        'harga_satuan' => $hargaSatuan,
                        'subtotal' => $subtotal,
                    ]);

                    $totalBayar += $subtotal;

                    $stock->update([
                        'jumlah_stok' => $stock->jumlah_stok - $jumlah,
                    ]);
                }

                if ($totalBayar == 0) {
                    $transaction->delete();

                    continue;
                }

                $uangDibayar = $totalBayar + rand(5000, 50000);

                $transaction->update([
                    'total_bayar' => $totalBayar,
                    'uang_dibayar' => $uangDibayar,
                    'kembalian' => $uangDibayar - $totalBayar,
                ]);
            }
        }

        Stock::inRandomOrder()
            ->take(5)
            ->get()
            ->each(function ($stock) {
                $stock->update([
                    'jumlah_stok' => rand(5, 25),
                ]);
            });
    }
}
