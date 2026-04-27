<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['name' => 'Bouquets', 'slug' => 'bouquets', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Arrangements', 'slug' => 'arrangements', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Single Stems', 'slug' => 'single', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gift Sets', 'slug' => 'bundles', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}