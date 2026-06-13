<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::insert([
            ['nama' => 'Sembako'],
            ['nama' => 'Makanan Instan'],
            ['nama' => 'Minuman'],
            ['nama' => 'Perawatan Tubuh'],
            ['nama' => 'Perlengkapan Rumah'],
        ]);
    }
}
