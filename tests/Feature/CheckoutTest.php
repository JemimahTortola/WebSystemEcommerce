<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $product;
    protected $cart;

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

    public function test_checkout_requires_authentication()
    {
        $response = $this->get('/checkout');
        $response->assertRedirect('/login');
    }

    public function test_checkout_requires_non_empty_cart()
    {
        $this->actingAs($this->user);
        $response = $this->get('/checkout');
        $response->assertRedirect('/cart');
    }

    public function test_checkout_process_creates_order()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $response = $this->post('/checkout/process', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '1234567890',
            'shipping_address' => '123 Main Street, Apt 4',
            'payment_method' => 'cash',
            'notes' => 'Please ring doorbell',
        ]);

        $response->assertRedirect('/orders');
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'status' => 'pending',
            'payment_method' => 'cash',
        ]);
    }

    public function test_checkout_decreases_product_stock()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 3,
            'price' => $this->product->price,
        ]);

        $initialStock = $this->product->stock;

        $this->actingAs($this->user);

        $this->post('/checkout/process', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '1234567890',
            'shipping_address' => '123 Main Street',
            'payment_method' => 'cash',
        ]);

        $this->product->refresh();
        $this->assertEquals($initialStock - 3, $this->product->stock);
    }

    public function test_checkout_clears_cart()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 2,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $this->post('/checkout/process', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => '1234567890',
            'shipping_address' => '123 Main Street',
            'payment_method' => 'cash',
        ]);

        $this->assertEquals(0, $cart->fresh()->items()->count());
    }

    public function test_checkout_validates_required_fields()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $response = $this->post('/checkout/process', []);
        $response->assertSessionHasErrors(['shipping_name', 'shipping_phone', 'shipping_address', 'payment_method']);
    }

    public function test_checkout_rejects_invalid_phone()
    {
        $cart = Cart::create(['user_id' => $this->user->id]);
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);

        $this->actingAs($this->user);

        $response = $this->post('/checkout/process', [
            'shipping_name' => 'John Doe',
            'shipping_phone' => 'abc',
            'shipping_address' => '123 Main Street',
            'payment_method' => 'cash',
        ]);

        $response->assertSessionHasErrors(['shipping_phone']);
    }
}
