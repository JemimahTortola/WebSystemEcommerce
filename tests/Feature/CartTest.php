<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $this->product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'category_id' => $category->id,
            'price' => 29.99,
            'stock' => 10,
            'is_active' => true,
        ]);
    }

    public function test_cart_requires_authentication()
    {
        $response = $this->get('/cart');
        $response->assertRedirect('/login');
    }

    public function test_user_can_add_product_to_cart()
    {
        $this->actingAs($this->user);

        $response = $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertJson(['success' => 'Product added to cart']);
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $this->product->id,
            'quantity' => 2,
        ]);
    }

    public function test_cart_updates_quantity_for_existing_item()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $response = $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 3,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertJson(['success' => 'Product added to cart']);
        
        $cartItem = CartItem::where('product_id', $this->product->id)->first();
        $this->assertEquals(4, $cartItem->quantity);
    }

    public function test_cannot_add_more_than_available_stock()
    {
        $this->actingAs($this->user);

        $response = $this->post('/cart/add', [
            'product_id' => $this->product->id,
            'quantity' => 15,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertJson(['error' => 'Product out of stock'], false);
    }

    public function test_user_can_update_cart_item_quantity()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $response = $this->put("/cart/update/{$cartItem->id}", [
            'quantity' => 5,
        ], ['X-Requested-With' => 'XMLHttpRequest']);

        $response->assertJson(['success' => 'Cart updated']);
        
        $cartItem->refresh();
        $this->assertEquals(5, $cartItem->quantity);
    }

    public function test_user_can_remove_item_from_cart()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $this->get("/cart/remove/{$cartItem->id}");

        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }
}
