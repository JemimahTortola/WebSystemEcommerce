<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.orders');
    }

    public function data(Request $request)
    {
        try {
            $query = Order::with(['user', 'shipping']);
            
            if ($request->status) {
                $query->where('status', $request->status);
            }
            
            $orders = $query->orderBy('created_at', 'desc')->get();
            
            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function calendarData(Request $request)
    {
        try {
            $query = Order::with(['user', 'shipping'])
                ->whereNotNull('delivery_date')
                ->whereIn('status', ['processing', 'pending']);
            
            $orders = $query->orderBy('delivery_date', 'asc')->get();
            
            // Return array with properly formatted dates (avoid Eloquent cast issues)
            $result = $orders->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'delivery_date' => $order->delivery_date ? $order->delivery_date->format('Y-m-d') : null,
                    'status' => $order->status,
                    'total_amount' => $order->total_amount,
                    'user' => $order->user ? ['name' => $order->user->name] : null,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                ];
            });
            
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        if (request()->has('print')) {
            return view('admin.order-print', compact('order'));
        }

        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $oldStatus = $order->status;

        // If cancelling order, return items to stock
        if ($validated['status'] === 'cancelled' && $order->status !== 'cancelled') {
            $orderItems = DB::table('order_items')->where('order_id', $order->id)->get();
            foreach ($orderItems as $item) {
                DB::table('products')->where('id', $item->product_id)->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => $validated['status']]);

        // Send notification to user about status change
        if ($oldStatus !== $validated['status']) {
            DB::table('notifications')->insert([
                'user_id' => $order->user_id,
                'type' => 'order_status',
                'title' => 'Order Status Updated',
                'message' => 'Your order #' . $order->order_number . ' status has been changed from ' . $oldStatus . ' to ' . $validated['status'],
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'message' => 'Order status updated!',
            'order' => $order,
        ]);
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:verified,rejected',
        ]);

        $order->update(['payment_status' => $validated['payment_status']]);

        // Notify user about payment verification
        $title = $validated['payment_status'] === 'verified' ? 'Payment Verified' : 'Payment Rejected';
        $message = $validated['payment_status'] === 'verified' 
            ? 'Your payment for Order #' . $order->order_number . ' has been verified. Your order is now being processed.'
            : 'Your payment for Order #' . $order->order_number . ' has been rejected. Please upload a valid receipt or contact support.';

        DB::table('notifications')->insert([
            'user_id' => $order->user_id,
            'type' => 'payment_verified',
            'title' => $title,
            'message' => $message,
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payment ' . $validated['payment_status'] . '!',
            'order' => $order,
        ]);
    }

    public function notifications()
    {
        return view('admin.notifications');
    }

    public function notificationsData()
    {
        $notifications = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $unreadCount = DB::table('notifications')
            ->where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    public function markAllRead()
    {
        DB::table('notifications')
            ->where('user_id', Auth::id())
            ->update(['is_read' => true, 'updated_at' => now()]);

        return response()->json(['message' => 'Notifications marked as read']);
    }

    public function markRead(Request $request)
    {
        DB::table('notifications')
            ->where('id', $request->id)
            ->where('user_id', Auth::id())
            ->update(['is_read' => true, 'updated_at' => now()]);

        return response()->json(['message' => 'Notification marked as read']);
    }
}