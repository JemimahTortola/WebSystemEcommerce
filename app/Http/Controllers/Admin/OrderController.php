<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingTracking;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'active');
        $status = $request->get('status');
        $search = $request->get('search');
        
        $query = Order::with('user');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($filter === 'archived') {
            $query->where('is_archived', true);
        } else {
            $query->where(function($q) {
                $q->where('is_archived', false)
                  ->orWhereIn('status', ['delivered', 'cancelled']);
            });
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function($q2) use ($search) {
                        $q2->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }
        
        $orders = $query->latest()->get();

        return view('admin.orders.index', compact('orders', 'filter', 'status'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'items.product', 'tracking'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $order->status = $request->status;
        
        if (in_array($request->status, ['delivered', 'cancelled'])) {
            $order->is_archived = true;
        }
        
        $order->save();

        ShippingTracking::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'title' => 'Status Updated',
            'description' => 'Order status changed from ' . ucfirst($oldStatus) . ' to ' . ucfirst($request->status),
            'location' => 'System',
            'is_public' => true,
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|string|in:pending,paid,failed,refunded',
        ]);

        $order = Order::findOrFail($id);
        $order->payment_status = $request->payment_status;
        $order->save();

        return redirect()->back()->with('success', 'Payment status updated successfully');
    }

    public function archive($id)
    {
        $order = Order::findOrFail($id);
        $order->is_archived = true;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Order archived successfully');
    }

    public function restore($id)
    {
        $order = Order::findOrFail($id);
        $order->is_archived = false;
        $order->save();

        return redirect()->route('admin.orders.index', ['filter' => 'archived'])->with('success', 'Order restored successfully');
    }

    public function addTracking(Request $request, $id)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'courier' => 'nullable|string|max:100',
            'estimated_delivery' => 'nullable|date',
        ]);

        $order = Order::findOrFail($id);
        
        if ($request->tracking_number) {
            $order->tracking_number = $request->tracking_number;
        }
        
        if ($request->courier) {
            $order->courier = $request->courier;
        }
        
        if ($request->estimated_delivery) {
            $order->estimated_delivery = $request->estimated_delivery;
        }
        
        $order->save();

        if ($request->tracking_number || $request->courier) {
            ShippingTracking::create([
                'order_id' => $order->id,
                'status' => $order->status,
                'title' => 'Tracking Information Updated',
                'description' => 'Tracking number: ' . ($request->tracking_number ?? 'N/A') . ' | Courier: ' . ($request->courier ?? 'N/A'),
                'location' => 'System',
                'is_public' => true,
            ]);
        }

        return redirect()->back()->with('success', 'Tracking information updated successfully');
    }

    public function addTrackingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:order_placed,processing,shipped,out_for_delivery,delivered,cancelled,on_hold,returned',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'location' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        ShippingTracking::create([
            'order_id' => $order->id,
            'status' => $request->status,
            'title' => $request->title ?? \App\Models\ShippingTracking::getStatusLabel($request->status),
            'description' => $request->description,
            'location' => $request->location,
            'is_public' => true,
        ]);

        $order->status = $request->status;
        
        if (in_array($request->status, ['delivered', 'cancelled'])) {
            $order->is_archived = true;
        }
        
        $order->save();

        return redirect()->back()->with('success', 'Tracking status added successfully');
    }
}
