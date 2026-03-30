<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CheckoutRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        return view('frontend.checkout.index', compact('cart'));
    }

    public function process(CheckoutRequest $request)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $cart = Cart::where('user_id', Auth::id())->with('items.product')->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        foreach ($cart->items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->back()->with('error', 'Product ' . $item->product->name . ' does not have enough stock');
            }
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'order_number' => 'ORD-' . strtoupper(Str::random(10)),
            'total_amount' => $cart->total,
            'status' => 'pending',
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'shipping_name' => strip_tags($request->shipping_name),
            'shipping_phone' => strip_tags($request->shipping_phone),
            'shipping_address' => strip_tags($request->shipping_address),
            'notes' => strip_tags($request->notes),
        ]);

        foreach ($cart->items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'product_name' => $item->product->name,
                'price' => $item->price,
                'quantity' => $item->quantity,
                'subtotal' => $item->price * $item->quantity,
            ]);

            $product = Product::find($item->product_id);
            $product->stock -= $item->quantity;
            $product->save();
        }

        $cart->items()->delete();

        return redirect('/orders')->with('success', 'Order placed successfully! Order number: ' . $order->order_number);
    }

    public function orders()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $orders = Order::where('user_id', Auth::id())
            ->with('items')
            ->latest()
            ->paginate(10);

        return view('frontend.orders.index', compact('orders'));
    }

    public function showOrder($id)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('items')
            ->firstOrFail();

        return view('frontend.orders.show', compact('order'));
    }
}
