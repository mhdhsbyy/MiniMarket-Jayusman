<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            BranchSeeder::class,
            UserSeeder::class,
            ManagerSeeder::class,
            SupervisorSeeder::class,
            CashierSeeder::class,
            WarehouseSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            StockSeeder::class,
            IncomingGoodSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
