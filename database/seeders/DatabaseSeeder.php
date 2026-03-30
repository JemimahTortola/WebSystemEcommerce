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
            'email' => 'admin@tinythreads.com',
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

        // Create categories - Newborn to Toddler Clothing
        $categories = [
            ['name' => 'Newborn (0-3M)', 'slug' => 'newborn', 'description' => 'Adorable clothing for newborns 0-3 months'],
            ['name' => 'Baby (3-12M)', 'slug' => 'baby', 'description' => 'Comfortable clothing for babies 3-12 months'],
            ['name' => 'Toddler (1-3Y)', 'slug' => 'toddler', 'description' => 'Stylish clothing for toddlers 1-3 years'],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Hats, socks, bibs, booties and more'],
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

        // Create sample products - Newborn to Toddler Clothing
        $products = [
            ['name' => 'Soft Cotton Onesie Set', 'slug' => 'soft-cotton-onesie-set', 'description' => 'Ultra-soft cotton onesie set for newborns. Perfect for sensitive skin. Includes 3 onesies.', 'price' => 24.99, 'stock' => 50, 'category_id' => 1],
            ['name' => 'Cozy Sleep Gown', 'slug' => 'cozy-sleep-gown', 'description' => 'Adorable floral print sleep gown for newborns. Easy snap closure.', 'price' => 18.99, 'stock' => 40, 'category_id' => 1],
            ['name' => 'Knit Beanie Set', 'slug' => 'knit-beanie-set', 'description' => 'Cozy knit beanie set to keep your little one warm. Set of 3.', 'price' => 14.99, 'stock' => 80, 'category_id' => 1],
            ['name' => 'Romper Jumpsuit', 'slug' => 'romper-jumpsuit', 'description' => 'Cute and comfortable romper jumpsuit for babies. Easy snap buttons.', 'price' => 22.99, 'stock' => 45, 'category_id' => 2],
            ['name' => 'Cotton Dress', 'slug' => 'cotton-dress', 'description' => 'Pretty cotton dress for baby girls. Perfect for any occasion.', 'price' => 26.99, 'stock' => 35, 'category_id' => 2],
            ['name' => 'Leggings Set', 'slug' => 'leggings-set', 'description' => 'Set of 3 soft cotton leggings. Stretchy and comfortable.', 'price' => 15.99, 'stock' => 60, 'category_id' => 2],
            ['name' => 'Tee & Shorts Set', 'slug' => 'tee-shorts-set', 'description' => 'Cute summer set for toddlers. Breathable cotton fabric.', 'price' => 28.99, 'stock' => 40, 'category_id' => 3],
            ['name' => 'Denim Overalls', 'slug' => 'denim-overalls', 'description' => 'Adorable denim overalls for active toddlers. Adjustable straps.', 'price' => 34.99, 'stock' => 30, 'category_id' => 3],
            ['name' => 'Pajama Set', 'slug' => 'pajama-set', 'description' => 'Cozy pajama set for toddlers. Soft and breathable.', 'price' => 25.99, 'stock' => 50, 'category_id' => 3],
            ['name' => 'Sock Bundle', 'slug' => 'sock-bundle', 'description' => 'Bundle of 6 pairs of soft cotton socks. Non-slip grips.', 'price' => 14.99, 'stock' => 70, 'category_id' => 4],
            ['name' => 'Organic Bib Set', 'slug' => 'organic-bib-set', 'description' => 'Set of 4 organic cotton bibs. Easy to clean.', 'price' => 19.99, 'stock' => 55, 'category_id' => 4],
            ['name' => 'Knit Booties', 'slug' => 'knit-booties', 'description' => 'Soft knit booties to keep tiny feet warm. Multiple sizes.', 'price' => 16.99, 'stock' => 65, 'category_id' => 4],
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
