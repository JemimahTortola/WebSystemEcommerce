<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getOrCreateCart(): Cart
    {
        if (!Auth::check()) {
            throw new \Exception('User must be authenticated');
        }

        return Cart::firstOrCreate(['user_id' => Auth::id()]);
    }

    public function addToCart(int $productId, int $quantity = 1): array
    {
        if (!Auth::check()) {
            return ['success' => false, 'error' => 'Please login to add items to cart'];
        }

        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return ['success' => false, 'error' => 'Product out of stock'];
        }

        $cart = $this->getOrCreateCart();

        $existingItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if ($newQuantity > $product->stock) {
                return ['success' => false, 'error' => 'Not enough stock available'];
            }
            $existingItem->quantity = $newQuantity;
            $existingItem->save();
            $cartItem = $existingItem;
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);
        }

        return [
            'success' => true,
            'message' => 'Product added to cart',
            'cart_count' => $cart->fresh()->total_items
        ];
    }

    public function updateQuantity(int $cartItemId, int $quantity): array
    {
        $cartItem = CartItem::findOrFail($cartItemId);

        if ($cartItem->product->stock < $quantity) {
            return ['success' => false, 'error' => 'Not enough stock available'];
        }

        $cartItem->quantity = $quantity;
        $cartItem->save();

        return ['success' => true, 'message' => 'Cart updated'];
    }

    public function removeItem(int $cartItemId): bool
    {
        $cartItem = CartItem::find($cartItemId);
        
        if ($cartItem) {
            $cartItem->delete();
            return true;
        }

        return false;
    }

    public function clearCart(int $cartId): void
    {
        CartItem::where('cart_id', $cartId)->delete();
    }

    public function validateStock(Cart $cart): array
    {
        $issues = [];

        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                $issues[] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'requested' => $item->quantity,
                    'available' => $item->product->stock
                ];
            }
        }

        return $issues;
    }
}
