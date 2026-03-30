<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Clear old data except admin
        DB::table('reviews')->delete();
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        DB::table('cart_items')->delete();
        DB::table('carts')->delete();
        DB::table('products')->delete();
        DB::table('categories')->delete();
        DB::table('users')->where('id', '!=', 1)->delete();

        // Create categories - Newborn to Toddler Clothing
        $categories = [
            ['name' => 'Newborn (0-3M)', 'slug' => 'newborn'],
            ['name' => 'Baby (3-12M)', 'slug' => 'baby'],
            ['name' => 'Toddler (1-3Y)', 'slug' => 'toddler'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            DB::table('categories')->insert([
                'name' => $category['name'],
                'slug' => $category['slug'],
                'description' => 'Clothing for little ones',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Newborn to Toddler Clothing products
        $products = [
            // Newborn (0-3M) - category 1
            ['name' => 'Soft Cotton Onesie Set', 'category' => 1, 'price' => 24.99, 'stock' => 45, 'desc' => 'Ultra-soft organic cotton onesie set for newborns. Perfect for everyday comfort. Includes 3 onesies.'],
            ['name' => 'Knit Beanie Set', 'category' => 1, 'price' => 14.99, 'stock' => 80, 'desc' => 'Cozy knit beanie set to keep your little one warm. Set of 3 colors.'],
            ['name' => 'Butterfly Print Romper', 'category' => 1, 'price' => 22.99, 'stock' => 35, 'desc' => 'Adorable butterfly print romper with snap buttons for easy dressing.'],
            ['name' => 'Hooded Towel Set', 'category' => 1, 'price' => 34.99, 'stock' => 25, 'desc' => 'Super absorbent hooded towel set for bath time. Includes hooded towel and washcloth.'],
            ['name' => 'Cotton Mittens', 'category' => 1, 'price' => 9.99, 'stock' => 100, 'desc' => 'Soft cotton mittens to prevent scratching. Adjustable wrist closure.'],
            ['name' => 'Fleece Sleepsack', 'category' => 1, 'price' => 32.99, 'stock' => 40, 'desc' => 'Warm and cozy fleece sleepsack for peaceful nights. Reversible design.'],
            ['name' => 'Bibs Pack of 6', 'category' => 1, 'price' => 18.99, 'stock' => 60, 'desc' => 'Waterproof dribble bibs pack - easy to clean. Set of 6.'],
            ['name' => 'Booties and Headband Set', 'category' => 1, 'price' => 22.99, 'stock' => 50, 'desc' => 'Matching knit booties and headband set for your princess.'],
            ['name' => 'Swaddle Blanket Muslin', 'category' => 1, 'price' => 26.99, 'stock' => 55, 'desc' => 'Breathable muslin swaddle blanket. Multiple uses as stroller cover or nursing cover.'],
            ['name' => 'Bodysuits 5-Pack', 'category' => 1, 'price' => 29.99, 'stock' => 70, 'desc' => 'Essential bodysuits 5-pack in various colors. Snap closure for easy changes.'],

            // Baby (3-12M) - category 2
            ['name' => 'Denim Overalls', 'category' => 2, 'price' => 38.99, 'stock' => 30, 'desc' => 'Cute denim overalls for toddlers. Adjustable straps with metal buckles.'],
            ['name' => 'Rainbow Tutu Dress', 'category' => 2, 'price' => 32.99, 'stock' => 25, 'desc' => 'Sparkly tutu dress perfect for parties. Layered tulle skirt.'],
            ['name' => 'Striped Leggings', 'category' => 2, 'price' => 16.99, 'stock' => 65, 'desc' => 'Comfortable striped leggings for everyday wear. Stretchy cotton blend.'],
            ['name' => 'Ruffle Sleeve Top', 'category' => 2, 'price' => 24.99, 'stock' => 40, 'desc' => 'Pretty ruffle sleeve top for little girls. Soft cotton fabric.'],
            ['name' => 'Cargo Shorts Set', 'category' => 2, 'price' => 27.99, 'stock' => 35, 'desc' => 'Cool cargo shorts set for boys. Includes matching t-shirt.'],
            ['name' => 'Polo Shirt and Shorts', 'category' => 2, 'price' => 29.99, 'stock' => 45, 'desc' => 'Classic polo shirt with matching cotton shorts. Perfect for smart casual.'],
            ['name' => 'Floral Print Dress', 'category' => 2, 'price' => 35.99, 'stock' => 28, 'desc' => 'Beautiful floral print dress. Ruffled hemline.'],
            ['name' => 'Unicorn Costume', 'category' => 2, 'price' => 42.99, 'stock' => 20, 'desc' => 'Magical unicorn costume for dress-up. Includes headband and tutu.'],
            ['name' => 'Dinosaur T-Shirt', 'category' => 2, 'price' => 18.99, 'stock' => 55, 'desc' => 'Roar-some dinosaur t-shirt for boys. Funny graphic print.'],
            ['name' => 'Tiered Ruffle Dress', 'category' => 2, 'price' => 38.99, 'stock' => 22, 'desc' => 'Elegant tiered ruffle dress. Perfect for special occasions.'],

            // Toddler (1-3Y) - category 3
            ['name' => 'Princess Party Dress', 'category' => 3, 'price' => 55.99, 'stock' => 15, 'desc' => 'Stunning princess party dress with sequins and tulle.'],
            ['name' => 'Superhero Cape Set', 'category' => 3, 'price' => 32.99, 'stock' => 35, 'desc' => 'Become a superhero with this cape set. Includes mask and cuffs.'],
            ['name' => 'Pirate Costume', 'category' => 3, 'price' => 39.99, 'stock' => 20, 'desc' => 'Ahoy! Pirate costume for little adventurers. Includes hat and eyepatch.'],
            ['name' => 'Ballet Tutu Outfit', 'category' => 3, 'price' => 36.99, 'stock' => 25, 'desc' => 'Perfect ballet tutu outfit for little dancers.'],
            ['name' => 'Firefighter Costume', 'category' => 3, 'price' => 38.99, 'stock' => 18, 'desc' => 'Realistic firefighter costume. Includes helmet and jacket.'],
            ['name' => 'Cowboy Outfit', 'category' => 3, 'price' => 42.99, 'stock' => 15, 'desc' => 'Yee-haw! Complete cowboy costume with hat and vest.'],
            ['name' => 'Prince Costume', 'category' => 3, 'price' => 49.99, 'stock' => 12, 'desc' => 'Royal prince costume. Includes cape and crown.'],
            ['name' => 'Chef Costume Set', 'category' => 3, 'price' => 29.99, 'stock' => 30, 'desc' => 'Little chef costume set. Includes hat and apron.'],
            ['name' => 'Fairy Dress', 'category' => 3, 'price' => 38.99, 'stock' => 25, 'desc' => 'Enchanting fairy dress with butterfly wings.'],
            ['name' => 'Robot Suit', 'category' => 3, 'price' => 54.99, 'stock' => 10, 'desc' => 'Futuristic robot suit. Lights up and makes sounds!'],

            // Accessories - category 4
            ['name' => 'Winter Puffer Jacket', 'category' => 4, 'price' => 64.99, 'stock' => 30, 'desc' => 'Warm puffer jacket for winter. Water-resistant outer shell.'],
            ['name' => 'Rain Boots Dino Print', 'category' => 4, 'price' => 28.99, 'stock' => 40, 'desc' => 'Fun dinosaur print rain boots. Non-slip sole.'],
            ['name' => 'Knit Scarf and Gloves', 'category' => 4, 'price' => 22.99, 'stock' => 55, 'desc' => 'Cozy knit scarf and gloves set. Matching colors.'],
            ['name' => 'Beanie with Pom', 'category' => 4, 'price' => 18.99, 'stock' => 70, 'desc' => 'Fluffy beanie with pom-pom. Sherpa lined for warmth.'],
            ['name' => 'Fleece Lined Leggings', 'category' => 4, 'price' => 24.99, 'stock' => 50, 'desc' => 'Warm fleece lined leggings. Perfect for cold weather.'],
            ['name' => 'Down Vest', 'category' => 4, 'price' => 44.99, 'stock' => 35, 'desc' => 'Lightweight down vest for layering. Packable design.'],
            ['name' => 'Snowsuit One-Piece', 'category' => 4, 'price' => 74.99, 'stock' => 20, 'desc' => 'All-in-one snowsuit. Waterproof and windproof.'],
            ['name' => 'Knit Booties', 'category' => 4, 'price' => 19.99, 'stock' => 65, 'desc' => 'Soft knit booties to keep tiny feet warm. Multiple colors.'],
            ['name' => 'Sock Bundle', 'category' => 4, 'price' => 14.99, 'stock' => 70, 'desc' => 'Bundle of 6 pairs of soft cotton socks. Non-slip grips.'],
            ['name' => 'Organic Bib Set', 'category' => 4, 'price' => 19.99, 'stock' => 55, 'desc' => 'Set of 4 organic cotton bibs. Easy to clean and machine washable.'],
        ];

        // Insert products
        $productIds = [];
        foreach ($products as $p) {
            $id = DB::table('products')->insertGetId([
                'category_id' => $p['category'],
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => $p['desc'],
                'price' => $p['price'],
                'stock' => $p['stock'],
                'image' => null,
                'is_active' => true,
                'is_archived' => false,
                'created_at' => Carbon::now()->subDays(rand(1, 60)),
                'updated_at' => Carbon::now(),
            ]);
            $productIds[] = $id;
        }

        // Create 50 new users
        $firstNames = ['Emma', 'Liam', 'Olivia', 'Noah', 'Ava', 'Ethan', 'Sophia', 'Mason', 'Isabella', 'William',
                       'Mia', 'James', 'Charlotte', 'Benjamin', 'Amelia', 'Lucas', 'Harper', 'Henry', 'Evelyn', 'Alexander',
                       'Luna', 'Michael', 'Camila', 'Daniel', 'Gianna', 'Sebastian', 'Aria', 'Jack', 'Penelope', 'Aiden',
                       'Layla', 'Owen', 'Chloe', 'Samuel', 'Victoria', 'Ryan', 'Madison', 'Nathan', 'Eleanor', 'Caleb',
                       'Grace', 'Isaac', 'Nora', 'Leo', 'Riley', 'Lincoln', 'Zoey', 'Jordan', 'Hannah', 'Aaron'];
        
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                      'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
                      'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
                      'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores',
                      'Green', 'Adams', 'Nelson', 'Baker', 'Hall', 'Rivera', 'Campbell', 'Mitchell', 'Carter', 'Roberts'];

        $countries = ['United States', 'United Kingdom', 'Canada', 'Australia', 'Philippines'];
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'London', 'Manchester', 'Toronto', 'Vancouver', 'Sydney', 'Melbourne', 'Manila', 'Quezon City'];
        
        $userIds = [];
        for ($i = 0; $i < 50; $i++) {
            $firstName = $firstNames[$i];
            $lastName = $lastNames[array_rand($lastNames)];
            $username = strtolower($firstName) . $lastName . rand(1, 999);
            
            $id = DB::table('users')->insertGetId([
                'name' => $firstName . ' ' . $lastName,
                'username' => $username,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => strtolower($firstName) . '.' . strtolower($lastName) . rand(1, 99) . '@example.com',
                'phone' => '+1' . rand(2000000000, 9999999999),
                'address' => rand(1, 999) . ' ' . ['Oak', 'Maple', 'Cedar', 'Pine', 'Elm'][rand(0, 4)] . ' Street',
                'city' => $cities[array_rand($cities)],
                'postal_code' => rand(10000, 99999),
                'country' => $countries[array_rand($countries)],
                'password' => Hash::make('password123'),
                'is_admin' => false,
                'created_at' => Carbon::now()->subDays(rand(1, 120)),
                'updated_at' => Carbon::now(),
            ]);
            $userIds[] = $id;
        }

        // Create reviews for products
        $reviewTexts = [
            'Absolutely love this! Perfect quality and so cute on my little one.',
            'Great product, fast shipping, will buy again!',
            'The material is so soft, my child loves it.',
            'Beautiful design and great value for money.',
            'Exactly as pictured, highly recommend!',
            'Perfect for little ones. Everyone loves it!',
            'Good quality but runs a bit small.',
            'Exceeded expectations! Will order more.',
            'Comfortable and stylish. Great for everyday use.',
            'My child looks adorable in this!',
            'Very well made, worth every penny.',
            'Softest fabric ever! Must buy!',
            'Perfect for all occasions.',
            'Great colors and patterns.',
            'Durable and easy to clean.',
        ];

        foreach ($productIds as $productId) {
            $numReviews = rand(1, 4);
            for ($i = 0; $i < $numReviews; $i++) {
                DB::table('reviews')->insert([
                    'user_id' => $userIds[array_rand($userIds)],
                    'product_id' => $productId,
                    'rating' => rand(3, 5),
                    'comment' => $reviewTexts[array_rand($reviewTexts)],
                    'is_approved' => true,
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        // Create orders
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        
        for ($i = 0; $i < 40; $i++) {
            $userId = $userIds[array_rand($userIds)];
            $user = DB::table('users')->where('id', $userId)->first();
            $numItems = rand(1, 4);
            $total = 0;
            $orderItems = [];
            
            $selectedProducts = array_rand(array_flip($productIds), min($numItems, count($productIds)));
            if (!is_array($selectedProducts)) $selectedProducts = [$selectedProducts];
            
            foreach ($selectedProducts as $prodId) {
                $product = DB::table('products')->where('id', $prodId)->first();
                $quantity = rand(1, 3);
                $price = $product->price;
                $total += $price * $quantity;
                $orderItems[] = [
                    'product_id' => $prodId,
                    'quantity' => $quantity,
                    'price' => $price,
                ];
            }

            $status = $statuses[array_rand($statuses)];
            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount' => $total,
                'status' => $status,
                'payment_method' => 'Credit Card',
                'payment_status' => $paymentStatuses[array_rand($paymentStatuses)],
                'shipping_address' => $user->address,
                'shipping_name' => $user->name,
                'shipping_phone' => $user->phone,
                'is_archived' => false,
                'created_at' => Carbon::now()->subDays(rand(1, 45)),
                'updated_at' => Carbon::now(),
            ]);

            foreach ($orderItems as $item) {
                $product = DB::table('products')->where('id', $item['product_id'])->first();
                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => Carbon::now()->subDays(rand(1, 45)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Created: 50 users, ' . count($products) . ' products, reviews, and 40 orders');
    }
}
