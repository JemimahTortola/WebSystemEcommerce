<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        
        if (!$cart) {
            $cart = Cart::create(['user_id' => Auth::id()]);
        }

        return view('frontend.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Please login to add items to cart'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock < $request->quantity) {
            return response()->json(['error' => 'Product out of stock'], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($newQuantity > $product->stock) {
                return response()->json(['error' => 'Not enough stock available'], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return response()->json(['success' => 'Product added to cart', 'cart_count' => $cart->fresh()->total_items]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        
        if ($cartItem->product->stock < $request->quantity) {
            return response()->json(['error' => 'Not enough stock available'], 400);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json(['success' => 'Cart updated']);
    }

    public function remove($id)
    {
        $cartItem = CartItem::find($id);
        
        if ($cartItem) {
            $cartItem->delete();
        }

        return redirect()->back()->with('success', 'Item removed from cart');
    }
}
