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
            $query = Order::with('user');

            if ($request->status) {
                $query->where('status', $request->status);
            }

            $orders = $query->orderBy('created_at', 'desc')->get();

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product']);

        return response()->json($order);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

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
        DB::table('notifications')->insert([
            'user_id' => $order->user_id,
            'type' => 'payment_verified',
            'title' => 'Payment ' . ucfirst($validated['payment_status']),
            'message' => 'Your payment for Order #' . $order->id . ' has been ' . $validated['payment_status'] . '.',
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