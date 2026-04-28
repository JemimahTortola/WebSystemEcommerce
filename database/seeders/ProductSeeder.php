<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        DB::table('cart_items')->delete();
        DB::table('wishlists')->delete();
        DB::table('products')->delete();
        
        $categories = [
            'bouquets' => 'Bouquets',
            'vase-arrangements' => 'Vase Arrangements',
            'boxes' => 'Boxes',
        ];

        foreach ($categories as $slug => $name) {
            $exists = DB::table('categories')->where('slug', $slug)->first();
            if (!$exists) {
                $categoryId = DB::table('categories')->insertGetId([
                    'name' => $name,
                    'slug' => $slug,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $products = [
            // BOUQUETS
            ['name' => 'Pinkness Sweet - Elegant 10 Pink Carnations Bouquet', 'slug' => 'pinkness-sweet', 'description' => 'A breathtakingly elegant bouquet featuring 10 premium pink carnations delicately arranged with lush green fillers, wrapped in pristine paper and adorned with a beautiful satin pink ribbon. Perfect for expressing love, gratitude, or Simply Sweet emotions on any special occasion.', 'price' => 1899, 'category' => 'bouquets', 'image' => '/images/products/PinknessSweet-1746450764274.webp'],
            ['name' => 'Perfect Choice - Luxurious Floral Arrangement', 'slug' => 'perfect-choice', 'description' => 'The ultimate expression of refined taste! This stunning arrangement features hand-picked premium blooms artfully arranged by our master florists. A luxurious choice for birthdays, anniversaries, or when you want to make an unforgettable impression on someone special.', 'price' => 2199, 'category' => 'bouquets', 'image' => '/images/products/2008-1769663034385.webp'],
            ['name' => 'Emotions - 40 Long-Stem Red Roses Masterpiece', 'slug' => 'emotions', 'description' => 'An extraordinary masterpiece of love! 40 stunning long-stem red roses, each one carefully selected for perfect bloom. This magnificent bouquet screams passionate love anddevotion. The ultimate romantic gesture that will leave anyone speechless!', 'price' => 3999, 'category' => 'bouquets', 'image' => '/images/products/Emotions_29_2017.webp'],
            ['name' => 'Summer Medley - Enchanting Lily & Rose Combo', 'slug' => 'summer-medley', 'description' => 'A delightful summer symphony! 2 fragrant pink lilies paired with 10 gorgeous pink roses, creating a dreamy combination that captures the essence of summer romance. Beautiful greenery and fillers complete this magical arrangement that brightens any day!', 'price' => 1699, 'category' => 'bouquets', 'image' => '/images/products/2018-1746175559413.webp'],
            ['name' => 'Simply Sweet - Irresistibly Charming Bouquet', 'slug' => 'simply-sweet', 'description' => 'Sometimes simplest is best! This wonderfully charming bouquet features soft, delicate blooms that convey pure, innocent love. Perfect for first dates, thinking of you moments, or telling someone just how much they mean to you without saying a word.', 'price' => 1499, 'category' => 'bouquets', 'image' => '/images/products/Simply_Sweet_42_2022.webp'],
            ['name' => 'Sonia Orchids - Exotic Purple orchid Arrangement', 'slug' => 'sonia-orchids', 'description' => 'An exotic escape! 10 stunning Purple Sonia Orchids elegantly arranged to create a luxurious, sophisticated display. These rare beauties symbolize love, luxury, and refinement. Perfect for the extraordinary woman in your life!', 'price' => 1699, 'category' => 'bouquets', 'image' => '/images/products/2026-1746175580618.webp'],
            ['name' => 'Orchid Bouquet - 5 Stunning Purple Orchids', 'slug' => 'orchid-bouquet', 'description' => 'Elegance personified! 5 gorgeous purple orchids arranged to perfection, showcasing nature\'s most exotic beauty. These long-lasting blooms symbolize luxury, refinement, and love. An unforgettable gift for any occasion!', 'price' => 1299, 'category' => 'bouquets', 'image' => '/images/products/OrchidBouquet-1745397789138.webp'],
            ['name' => 'Teddy Love - Roses & Plush Teddy Combo', 'slug' => 'teddy-love', 'description' => 'The ultimate combo of love! 15 beautiful red and white roses paired with an adorable 18-inch plush teddy bear. Double the love, double the affection! This combo melts hearts and creates unforgettable moments!', 'price' => 2499, 'category' => 'bouquets', 'image' => '/images/products/2041_91_2041.webp'],
            ['name' => 'Posh Pinks - Luxurious Lily & Rose Blend', 'slug' => 'posh-pinks', 'description' => 'Absolutely posh! 4 luxurious stems of pink lilies (approximately 12 stunning blooms) combined with 10 premium red roses. A lavish, extravagant arrangement that screams luxury and passionate love. Perfect for making a grand statement!', 'price' => 2299, 'category' => 'bouquets', 'image' => '/images/products/2044-1746100312340.webp'],
            ['name' => 'Perfect Lilies - Tri-Color Lily Symphony', 'slug' => 'perfect-lilies', 'description' => 'A harmonious symphony of colors! Beautiful white, orange, and yellow lilies artfully arranged to create a stunning visual and olfactory experience. These fragrant blooms symbolize purity, passion, and gratitude. Pure perfection!', 'price' => 1899, 'category' => 'bouquets', 'image' => '/images/products/2049_82_2049.webp'],
            ['name' => 'Yellow Tulip Love - 20 Bright Tulips', 'slug' => 'yellow-tulip-love', 'description' => 'Sunshine in a bouquet! 20 cheerful bright yellow tulips that light up any room and bring joy to any heart. These happy blooms represent cheerful thoughts, sunshine, and hope. Perfect for celebrations!', 'price' => 2099, 'category' => 'bouquets', 'image' => '/images/products/2050-1747114841263.webp'],
            ['name' => 'Romance Roses 100 - Ultimate Love Declaration', 'slug' => 'romance-roses-100', 'description' => 'When 100 roses aren\'t enough to say I love you! This magnificent grand bouquet is the ultimate declaration of eternal love. A showstopping display that screams devotion, passion, and commitment. Perfect for proposals, anniversaries, or grand romantic gestures!', 'price' => 5999, 'category' => 'bouquets', 'image' => '/images/products/2052-1746100410847.webp'],
            ['name' => 'Positivity - 30 Sunny Yellow Roses', 'slug' => 'positivity', 'description' => 'Spread sunshine and happiness! 30 vibrant yellow roses that illuminate any space with joy and positivity. This cheerful bouquet is perfect for celebrating friendships, new beginnings, or brightening someone\'s day!', 'price' => 2499, 'category' => 'bouquets', 'image' => '/images/products/2059-1746686599029.webp'],
            ['name' => 'Uniqueness - 20 Colorful Gerberas', 'slug' => 'uniqueness', 'description' => 'Celebrate individuality! 20 vibrant mixed gerberas in a stunning array of colors, each bloom representing joy and uniqueness. This colorful arrangement is a celebration of personality and makes an unforgettable statement!', 'price' => 1799, 'category' => 'bouquets', 'image' => '/images/products/2060-1746175603535.webp'],
            ['name' => 'Truly Magnificent - 51 Red Roses Crown', 'slug' => 'truly-magnificent', 'description' => 'A crown fit for royalty! 51 stunning long-stem red roses artfully arranged to create a truly magnificent display. This extraordinary bouquet is the ultimate symbol of devotion and makes a royal statement of love!', 'price' => 3999, 'category' => 'bouquets', 'image' => '/images/products/2061-1747115022218.webp'],
            ['name' => 'Blush Of Love - Delicate Lily Rose Blend', 'slug' => 'blush-of-love', 'description' => 'A tender expression of developing love! 3 fragrant pink lilies beautifully paired with 8 soft pink roses. This delicate, romantic arrangement captures the innocence and beauty of new love. Perfect for first anniversaries!', 'price' => 1599, 'category' => 'bouquets', 'image' => '/images/products/2064-1746175618385.webp'],
            ['name' => 'Breathtaking 50 - 50 Premium Red Roses', 'slug' => 'breathtaking-50', 'description' => 'Absolutely breathtaking! 50 premium long-stem red roses that take the breath away. This magnificent bouquet is a powerful declaration of deep, passionate love. Perfect for the most important romantic moments!', 'price' => 3499, 'category' => 'bouquets', 'image' => '/images/products/2068-1769663053079.webp'],

            // VASE ARRANGEMENTS
            ['name' => 'Three in One - Colorful Gerbera Trio', 'slug' => 'three-in-one', 'description' => 'Three times the joy! Three vibrant gerberas elegantly arranged in a stunning glass vase, creating a colorful centerpiece that brings joy to any space. This cheerful arrangement is perfect for brightening homes or offices with lasting beauty!', 'price' => 1299, 'category' => 'vase-arrangements', 'image' => '/images/products/4891-1746428804039.webp'],
            ['name' => '36 Mixed Roses - Grand Vase Display', 'slug' => '36-mixed-roses', 'description' => 'A magnificent display of love! 36 stunning mixed roses beautifully arranged with lush fillers in an elegant glass vase. This grand arrangement creates a breathtaking centerpiece that commands attention!', 'price' => 2999, 'category' => 'vase-arrangements', 'image' => '/images/products/4897-1747132456638.webp'],
            ['name' => 'Classic All White - Pure Elegance', 'slug' => 'classic-all-white-blooms', 'description' => 'Pure, classic elegance! A beautiful arrangement of white lilies, alstroemerias, and carnations accented with baby\'s breath. This clean, sophisticated design brings timeless beauty to any setting!', 'price' => 1499, 'category' => 'vase-arrangements', 'image' => '/images/products/4892-1746428835868.webp'],
            ['name' => 'Summer Sunflower - Sunshine in a Vase', 'slug' => 'summer-sunflower-12', 'description' => 'Bring sunshine inside! A stunning sunflower arrangement in a beautiful glass vase that radiates warmth and happiness. These cheerful blooms brighten any room and lift spirits instantly!', 'price' => 1599, 'category' => 'vase-arrangements', 'image' => '/images/products/2369-1747122852690.webp'],
            ['name' => 'Soft and Delicate - Romantic Blend', 'slug' => 'soft-and-delicate', 'description' => 'Soft romance in a vase! 6 delicate pink carnations and alstroemerias artfully arranged, creating a softly romantic display. Perfect for expressing tender love and affection!', 'price' => 1399, 'category' => 'vase-arrangements', 'image' => '/images/products/4890-1746604771214.webp'],
            ['name' => 'Truly White - Pristine Lily Display', 'slug' => 'truly-white', 'description' => 'Purity and grace! 6 elegant white lilies in a stunning cylinder glass vase create a pristine, sophisticated display. These fragrant blooms bring tranquility and beauty to any space!', 'price' => 1699, 'category' => 'vase-arrangements', 'image' => '/images/products/4889-1747132318346.webp'],
            ['name' => 'Oriental Love - 10 Fragrant Pink Lilies', 'slug' => 'oriental-love', 'description' => 'Love that fills the room! 10 stunning pink lilies in an elegant glass vase release an intoxicating fragrance. These exotic beauties symbolize devotion and make a powerful romantic statement!', 'price' => 1899, 'category' => 'vase-arrangements', 'image' => '/images/products/4888-1746428784067.webp'],
            ['name' => 'All White Rose - Pure Romance', 'slug' => 'all-white-rose', 'description' => 'Pure, timeless romance! 20 pristine white roses in an elegant glass vase create a stunning display of innocence and devotion. Perfect for weddings, anniversaries, or expressing pure love!', 'price' => 1999, 'category' => 'vase-arrangements', 'image' => '/images/products/4883-1746604742519.webp'],
            ['name' => '10 Blue Orchids - Exotic Elegance', 'slug' => '10-blue-orchids', 'description' => 'Exotic sophistication! 10 stunning blue orchids gracefully arranged in a beautiful glass vase. These rare, luxurious blooms represent love, beauty, and refinement. A unique gift for someone extraordinary!', 'price' => 2299, 'category' => 'vase-arrangements', 'image' => '/images/products/4883-1746604742519.webp'],
            ['name' => 'Fragrant White - 5 Elegant Lilies', 'slug' => 'fragrant-white', 'description' => 'Fragrant perfection! 5 stunning white lilies in a beautiful vase fill the room with an irresistible heavenly fragrance. These elegant blooms symbolize purity and devotion!', 'price' => 1499, 'category' => 'vase-arrangements', 'image' => '/images/products/4871-1746687031501.webp'],
            ['name' => '18 Yellow Roses - Sunny Delight', 'slug' => '18-yellow-roses', 'description' => 'Sunshine and joy! 18 bright yellow roses in a lovely glass vase brighten any space with happiness. These cheerful blooms represent friendship and new beginnings!', 'price' => 1799, 'category' => 'vase-arrangements', 'image' => '/images/products/4868-1746604707669.webp'],
            ['name' => 'One Dozen Mixed - Colorful Romance', 'slug' => 'one-dozen-mixed-roses', 'description' => 'A rainbow of love! 12 beautifully mixed roses in an elegant vase create a stunning display of colorful romance. Each color represents different aspects of your love!', 'price' => 1599, 'category' => 'vase-arrangements', 'image' => '/images/products/4866-1746428664104.webp'],
            ['name' => 'Lavender & Red - Royal Blend', 'slug' => 'lavender-red-roses', 'description' => 'A royal combination! Purple mystery meets passionate red in this stunning arrangement. 9 purple and 9 red roses create a majestic display of love and admiration. Fit for royalty!', 'price' => 1899, 'category' => 'vase-arrangements', 'image' => '/images/products/4862-1746604695063.webp'],
            ['name' => 'Enchanting Love - Red Rose Elegance', 'slug' => 'enchanting-love', 'description' => 'Simple enchanting beauty! 5 premium red roses with elegant palm leaves in a beautiful glass vase create a romantic display. The perfect intimate gesture of love!', 'price' => 1299, 'category' => 'vase-arrangements', 'image' => '/images/products/4849-1746428523204.webp'],
            ['name' => '8 White Lilies - Pure Fragrance', 'slug' => '8-white-lilies', 'description' => 'Heavenly fragrance! 8 pristine white lilies in an elegant vase create a breathtaking display of purity and grace. These luxurious blooms fill any space with an intoxicating heavenly scent!', 'price' => 1899, 'category' => 'vase-arrangements', 'image' => '/images/products/4879-1747129552052.webp'],
            ['name' => 'Touch Of Heart - 30 Red Roses Passion', 'slug' => 'touch-of-heart', 'description' => 'Passionate love that touches the heart! 30 magnificent red roses in a beautiful vase make a powerful statement of devotion. This grand gesture says it all!', 'price' => 2799, 'category' => 'vase-arrangements', 'image' => '/images/products/2028-1746686585155.webp'],
            ['name' => 'Classy Bouquet - Rose Lily Blend', 'slug' => 'classy-bouquet', 'description' => 'Classy sophistication! 6 pink roses combined with 3 fragrant white lilies create an elegant blend of romance and elegance. A classy gesture for someone special!', 'price' => 1599, 'category' => 'vase-arrangements', 'image' => '/images/products/2388-1746428128944.webp'],

            // BOXES
            ['name' => 'Romance Blooms 15 - Elegant Round Box', 'slug' => 'romance-blooms-15', 'description' => 'Round perfection! 15 gorgeous red roses beautifully arranged in an elegant round white box, tied with a luxurious pink ribbon. A stunning statement of love that arrives in style!', 'price' => 2499, 'category' => 'boxes', 'image' => '/images/products/5256-1746441838311.webp'],
            ['name' => 'Spotless - Pristine Rose Elegance', 'slug' => 'spotless', 'description' => 'Absolutely spotless beauty! A stunning bouquet of red roses with delicate baby\'s breath, wrapped in elegant paper. Timeless elegance delivered to your loved one!', 'price' => 1999, 'category' => 'boxes', 'image' => '/images/products/7312-1746442109277.webp'],
            ['name' => 'Mixed Roses Black - Modern Square Luxury', 'slug' => 'mixed-roses-in-black-box', 'description' => 'Bold modern elegance! 25 vibrant mixed roses artfully arranged in a sleek black square box. A contemporary masterpiece that makes a bold statement of love!', 'price' => 2999, 'category' => 'boxes', 'image' => '/images/products/7605-1747137941674.webp'],
            ['name' => 'Lady Lux - Black Round Box Royalty', 'slug' => 'lady-lux', 'description' => 'Lady-like luxury! 30 stunning roses (15 red, 15 white) elegantly arranged in a luxurious black round box. A regal display fit for a queen!', 'price' => 3499, 'category' => 'boxes', 'image' => '/images/products/7606-1746442436561.webp'],
            ['name' => 'Pink Heart Box - 60 Roses of Devotion', 'slug' => 'pink-roses-in-heart-box', 'description' => 'An overflowing heart of love! 60 beautiful pink roses artfully arranged in a stunning heart-shaped box. The ultimate expression of devoted, endless love!', 'price' => 4999, 'category' => 'boxes', 'image' => '/images/products/5228_96_5228.webp'],
            ['name' => 'Roses Birthday - Black Round Surprise', 'slug' => 'roses-birthday-box', 'description' => 'Birthday brilliance! 15 gorgeous red roses in a sleek black round box - the perfect birthday surprise! This elegant gift makes their special day unforgettable!', 'price' => 2299, 'category' => 'boxes', 'image' => '/images/products/7603-1746442386747.webp'],
            ['name' => '25 Red Heart Box - Heart-Shaped Love', 'slug' => '25-red-roses-in-heart-box', 'description' => 'Love shaped perfectly! 25 radiant red roses beautifully arranged in an elegant heart-shaped box. The most romantic way to say I love you!', 'price' => 3299, 'category' => 'boxes', 'image' => '/images/products/5252-1746687193026.webp'],
            ['name' => 'Tropical Birthday - Colorful Round Box', 'slug' => 'tropical-birthday-box', 'description' => 'Tropical paradise! A stunning round box filled with red, peach, and white roses creating a vibrant birthday display. A tropical birthday surprise they\'ll never forget!', 'price' => 2799, 'category' => 'boxes', 'image' => '/images/products/7610-1747138028010.webp'],
            ['name' => 'Flame - Premium Red Box', 'slug' => 'flame', 'description' => 'Set their heart on fire! 20 premium red roses in an elegant premium box. A passionate, romantic gesture that burns with eternal love!', 'price' => 2599, 'category' => 'boxes', 'image' => '/images/products/7321-1746687220281.webp'],
            ['name' => 'White & Black - Classic Contrast', 'slug' => 'white-and-black', 'description' => 'Timeless classic! 20 pristine white roses in a sleek black box create a stunning contrast. Pure elegance that never goes out of style!', 'price' => 2499, 'category' => 'boxes', 'image' => '/images/products/7607-1746687258261.webp'],
            ['name' => 'Pure Beauty - Cube Box Elegance', 'slug' => 'pure-beauty', 'description' => 'Geometric perfection! Beautiful red roses elegantly arranged in a stylish cube box. Modern sophistication meets romantic beauty in this stunning gift!', 'price' => 2699, 'category' => 'boxes', 'image' => '/images/products/5251-1746687176094.webp'],
            ['name' => 'Both Lush Luxurious - RoseMedley', 'slug' => 'both-lush-and-luxurious', 'description' => 'Ultimate luxury! 30 stunning red and pink roses create a lush, luxurious display. A grand romantic gesture that shows undying devotion!', 'price' => 3299, 'category' => 'boxes', 'image' => '/images/products/5255-1746687205644.webp'],
            ['name' => 'Premium Red - Round Box Classic', 'slug' => 'premium-red-roses', 'description' => 'Classic premium quality! 24 gorgeous red roses beautifully arranged in a round box. This timeless romantic gift speaks volumes of your love!', 'price' => 2899, 'category' => 'boxes', 'image' => '/images/products/7614-1747138061015.webp'],
            ['name' => 'Cube Arrangement - 42 White Roses', 'slug' => 'cube-box-arrangement', 'description' => 'Geometrically stunning! 42 pure white roses arranged in a sophisticated square box, forming a beautiful cube. A magnificent display of pure love!', 'price' => 3999, 'category' => 'boxes', 'image' => '/images/products/7604_63_7604.webp'],
            ['name' => 'True Feelings - Ribbon Rose Cube', 'slug' => 'true-feelings', 'description' => 'Say it with roses! 25 beautiful red roses in a cube box, tied with an elegant ribbon. A classic expression of true feelings!', 'price' => 2799, 'category' => 'boxes', 'image' => '/images/products/7615-1747138079669.webp'],
            ['name' => 'Pure Love - Round Box Romance', 'slug' => 'pure-love', 'description' => 'Love in full bloom! 24 beautiful red and pink roses in a romantic round box. A stunning display of passionate love!', 'price' => 2699, 'category' => 'boxes', 'image' => '/images/products/7608-1747137997607.webp'],
            ['name' => 'Sealed Love - White Cube Romance', 'slug' => 'sealed-love', 'description' => 'Sealed with a kiss! 30 pristine white roses arranged in an elegant cube box. A pure, romantic gesture that seals your love forever!', 'price' => 3299, 'category' => 'boxes', 'image' => '/images/products/7602-1747137921545.webp'],
            ['name' => 'Red Heart - Heart-Shaped Romance', 'slug' => 'red-heart', 'description' => 'Heart of my world! A beautiful heart-shaped rose arrangement that captures all your love. The most romantic gift ever!', 'price' => 5999, 'category' => 'boxes', 'image' => '/images/products/7617_89_7617.webp'],
            ['name' => 'Unconditional 100 - Ultimate Love', 'slug' => 'unconditional-love-100', 'description' => '100 reasons I love you! The ultimate grand gesture of unconditional love. This magnificent box of 100 roses shows devotion beyond words!', 'price' => 6999, 'category' => 'boxes', 'image' => '/images/products/7618_26_7618.webp'],
        ];

        foreach ($products as $product) {
            $category = DB::table('categories')->where('slug', $product['category'])->first();
            $type = $product['category'] === 'bouquets' ? 'bouquet' : ($product['category'] === 'vase-arrangements' ? 'arrangement' : 'box');
            
            DB::table('products')->insert([
                'name' => $product['name'],
                'slug' => $product['slug'],
                'description' => $product['description'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category_id' => $category->id,
                'type' => $type,
                'stock' => rand(5, 20),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Created ' . count($products) . ' products in 3 categories.');
    }
}