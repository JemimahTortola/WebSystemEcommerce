<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $cartItems = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->where('cart_items.user_id', Auth::id())
            ->select('cart_items.id as cart_item_id', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.image', 'products.slug', 'products.stock')
            ->get();
            
        if ($cartItems->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        $addresses = DB::table('addresses')
            ->where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->get();
            
        return view('checkout.index', compact('cartItems', 'addresses'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,gcash,bank'
        ]);
        
        $address = DB::table('addresses')->where('id', $request->address_id)->first();
        
        $cartItems = DB::table('cart_items')
            ->where('user_id', Auth::id())
            ->get();
            
        if ($cartItems->count() === 0) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        $total = 0;
        foreach ($cartItems as $item) {
            $product = DB::table('products')->where('id', $item->product_id)->first();
            $total += $product->price * $item->quantity;
        }
        
        $orderNumber = 'ORD-' . strtoupper(uniqid());
        
        $orderId = DB::table('orders')->insertGetId([
            'user_id' => Auth::id(),
            'order_number' => $orderNumber,
            'status' => 'pending',
            'total_amount' => $total,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_name' => $address->full_name,
            'shipping_phone' => $address->phone,
            'shipping_address' => $address->address . ', ' . $address->city . ', ' . $address->postal_code,
            'delivery_date' => now()->addDays(1),
            'delivery_time' => '9:00 AM - 5:00 PM',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        foreach ($cartItems as $item) {
            $product = DB::table('products')->where('id', $item->product_id)->first();
            DB::table('order_items')->insert([
                'order_id' => $orderId,
                'product_id' => $item->product_id,
                'product_name' => $product->name,
                'quantity' => $item->quantity,
                'price' => $product->price,
                'subtotal' => $product->price * $item->quantity
            ]);
        }
        
        // Check if order has items, if not delete order
        $orderItemsCount = DB::table('order_items')->where('order_id', $orderId)->count();
        if ($orderItemsCount === 0) {
            DB::table('orders')->where('id', $orderId)->delete();
            return redirect()->route('cart')->with('error', 'Unable to process order. Please try again.');
        }
        
        DB::table('cart_items')
            ->where('user_id', Auth::id())
            ->delete();
        
        return redirect()->route('orders.show', $orderId)->with('success', 'Order placed successfully!');
    }
}