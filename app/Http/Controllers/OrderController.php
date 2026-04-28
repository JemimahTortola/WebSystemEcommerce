<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $ordersQuery = DB::table('orders')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
            
        $orders = [];
        foreach ($ordersQuery as $order) {
            $itemCount = DB::table('order_items')->where('order_id', $order->id)->count();
            if ($itemCount === 0) {
                DB::table('orders')->where('id', $order->id)->delete();
            } else {
                $order->item_count = $itemCount;
                $orders[] = $order;
            }
        }
        
        return view('profile.orders', compact('orders'));
    }

    public function show($orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->where('user_id', Auth::id())->first();
        
        if (!$order) {
            return redirect()->route('orders')->with('error', 'Order not found');
        }
        
        // Get order items with product details
        $items = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('order_items.order_id', $orderId)
            ->select(
                'order_items.id',
                'order_items.quantity',
                'order_items.price',
                'products.name',
                'products.image',
                'products.slug'
            )
            ->get();
        
        return view('profile.order-detail', compact('order', 'items'));
    }

    public function uploadReceipt(Request $request, $orderId)
    {
        $order = DB::table('orders')->where('id', $orderId)->where('user_id', Auth::id())->first();
        
        if (!$order) {
            return redirect()->route('orders')->with('error', 'Order not found');
        }

        $request->validate([
            'receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = $request->file('receipt')->store('receipts', 'public'); 
        
        DB::table('orders')->where('id', $orderId)->update([
            'payment_receipt' => $path,
            'payment_status' => 'pending_verification'
        ]);
        
        \Log::info("Receipt uploaded for order #{$orderId}, status set to pending_verification");

        // Notify all admins about the receipt upload
        $admins = DB::table('users')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('roles.name', 'admin')
            ->select('users.id')
            ->get();

        foreach ($admins as $admin) {
            DB::table('notifications')->insert([
                'user_id' => $admin->id,
                'type' => 'payment_receipt',
                'title' => 'Payment Receipt Uploaded',
                'message' => 'Order #' . $orderId . ' has a new payment receipt waiting for verification.',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Receipt uploaded! We will verify your payment shortly.');
    }
}