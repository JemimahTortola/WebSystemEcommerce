<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_can_be_created()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'price' => 29.99,
        ]);
    }

    public function test_product_belongs_to_category()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        $this->assertEquals($category->id, $product->category->id);
    }

    public function test_active_products_only()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        Product::create([
            'name' => 'Active Product',
            'slug' => 'active-product',
            'category_id' => $category->id,
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        Product::create([
            'name' => 'Inactive Product',
            'slug' => 'inactive-product',
            'category_id' => $category->id,
            'price' => 19.99,
            'stock' => 5,
            'is_active' => false,
        ]);

        $activeProducts = Product::where('is_active', true)->get();

        $this->assertEquals(1, $activeProducts->count());
    }

    public function test_product_stock_decreases_on_purchase()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);

        $product->stock -= 3;
        $product->save();

        $this->assertEquals(7, $product->fresh()->stock);
    }
}