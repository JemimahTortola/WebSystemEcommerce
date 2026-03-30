<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@babystore.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create sample user
        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create categories
        $categories = [
            ['name' => 'Newborn Essentials', 'slug' => 'newborn-essentials', 'description' => 'Essential items for newborns'],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Baby clothes and outfits'],
            ['name' => 'Feeding', 'slug' => 'feeding', 'description' => 'Bottles, formula, and feeding accessories'],
            ['name' => 'Toys', 'slug' => 'toys', 'description' => 'Educational and fun toys'],
            ['name' => 'Nursery', 'slug' => 'nursery', 'description' => 'Cribs, furniture, and decor'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Create sample products
        $products = [
            ['name' => 'Soft Cotton Onesie', 'slug' => 'soft-cotton-onesie', 'description' => 'Ultra-soft cotton onesie for newborns. Perfect for sensitive skin.', 'price' => 12.99, 'stock' => 50, 'category_id' => 2],
            ['name' => 'Baby Bottle Set', 'slug' => 'baby-bottle-set', 'description' => 'BPA-free baby bottle set with nipples. Includes 3 bottles.', 'price' => 24.99, 'stock' => 30, 'category_id' => 3],
            ['name' => 'Plush Teddy Bear', 'slug' => 'plush-teddy-bear', 'description' => 'Soft and cuddly teddy bear. Safe for all ages.', 'price' => 19.99, 'stock' => 40, 'category_id' => 4],
            ['name' => 'Crib Sheet Set', 'slug' => 'crib-sheet-set', 'description' => 'Soft cotton crib sheet set. Fits most standard cribs.', 'price' => 29.99, 'stock' => 25, 'category_id' => 5],
            ['name' => 'Baby Swaddle Blanket', 'slug' => 'baby-swaddle-blanket', 'description' => 'Breathable cotton swaddle blanket. Multiple patterns available.', 'price' => 15.99, 'stock' => 60, 'category_id' => 1],
            ['name' => 'Baby Hat Set', 'slug' => 'baby-hat-set', 'description' => 'Set of 3 soft cotton baby hats.', 'price' => 9.99, 'stock' => 80, 'category_id' => 2],
            ['name' => 'Pacifier Set', 'slug' => 'pacifier-set', 'description' => 'Orthodontic pacifiers. Set of 2.', 'price' => 8.99, 'stock' => 100, 'category_id' => 1],
            ['name' => 'Activity Mat', 'slug' => 'activity-mat', 'description' => 'Soft activity mat with hanging toys.', 'price' => 34.99, 'stock' => 20, 'category_id' => 4],
        ];

        foreach ($products as $product) {
            DB::table('products')->insert([
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'category_id' => $product['category_id'],
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
