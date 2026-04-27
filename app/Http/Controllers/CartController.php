<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
public function index()
    {
        if (!Auth::check()) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Please login first'], 401);
            }
            return redirect()->route('login');
        }
        
        $cart = DB::table('carts')->where('user_id', Auth::id())->first();
        
        $cartItems = collect();
        if ($cart) {
            $cartItems = DB::table('cart_items')
                ->join('products', 'cart_items.product_id', '=', 'products.id')
                ->where('cart_items.cart_id', $cart->id)
                ->select('cart_items.id as cart_item_id', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.image', 'products.slug')
                ->get();
        }
        
        if (request()->expectsJson()) {
            return response()->json(['cartItems' => $cartItems]);
        }
             
        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;

        if (!Auth::check()) {
            return response()->json(['message' => 'Please login first'], 401);
        }

        $product = DB::table('products')->where('id', $productId)->first();
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $cart = DB::table('carts')->where('user_id', Auth::id())->first();
        
        if (!$cart) {
            $cartId = DB::table('carts')->insertGetId(['user_id' => Auth::id(), 'created_at' => now(), 'updated_at' => now()]);
            $cart = (object) ['id' => $cartId, 'user_id' => Auth::id()];
        }

        $existing = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            DB::table('cart_items')
                ->where('id', $existing->id)
                ->update(['quantity' => $existing->quantity + $quantity]);
        } else {
            DB::table('cart_items')->insert([
                'cart_id' => $cart->id,
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price
            ]);
        }

        return response()->json(['message' => 'Added to cart', 'success' => true]);
    }

    public function count()
    {
        if (!Auth::check()) {
            return response()->json(['count' => 0]);
        }
        
        $cart = DB::table('carts')->where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return response()->json(['count' => 0]);
        }
        
        $count = DB::table('cart_items')
            ->where('cart_id', $cart->id)
            ->sum('quantity');
            
        return response()->json(['count' => $count ?? 0]);
    }

    public function update(Request $request, $cartItem)
    {
        $cart = DB::table('carts')->where('user_id', Auth::id())->first();
        
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }

        $item = DB::table('cart_items')
            ->where('id', $cartItem)
            ->where('cart_id', $cart->id)
            ->first();
        
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 404);
        }

        if ($request->has('change')) {
            $newQuantity = $item->quantity + $request->change;
            if ($newQuantity <= 0) {
                DB::table('cart_items')->where('id', $cartItem)->delete();
            } else {
                DB::table('cart_items')
                    ->where('id', $cartItem)
                    ->update(['quantity' => $newQuantity]);
            }
        } else {
            DB::table('cart_items')
                ->where('id', $cartItem)
                ->where('cart_id', $cart->id)
                ->update(['quantity' => $request->quantity]);
        }

        $cartItems = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->where('cart_items.cart_id', $cart->id)
            ->select('cart_items.id as cart_item_id', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.image', 'products.slug')
            ->get();

        return response()->json(['cartItems' => $cartItems]);
    }

    public function remove($cartItem)
    {
        $cart = DB::table('carts')->where('user_id', Auth::id())->first();
        
        if (!$cart) {
            if (request()->expectsJson()) {
                return response()->json(['message' => 'Cart not found'], 404);
            }
            return redirect()->back();
        }

        DB::table('cart_items')
            ->where('id', $cartItem)
            ->where('cart_id', $cart->id)
            ->delete();

        $cartItems = DB::table('cart_items')
            ->join('products', 'cart_items.product_id', '=', 'products.id')
            ->where('cart_items.cart_id', $cart->id)
            ->select('cart_items.id as cart_item_id', 'cart_items.quantity', 'products.id', 'products.name', 'products.price', 'products.image', 'products.slug')
            ->get();

        if (request()->expectsJson()) {
            return response()->json(['cartItems' => $cartItems]);
        }
        
        return redirect()->back();
    }
}