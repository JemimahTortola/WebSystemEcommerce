<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Cart;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function createOrder(array $data): Order
    {
        $cart = Cart::where('user_id', auth()->id())->with('items.product')->firstOrFail();

        $stockIssues = $this->cartService->validateStock($cart);
        if (!empty($stockIssues)) {
            throw new \Exception('Some products have insufficient stock');
        }

        return DB::transaction(function () use ($cart, $data) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'total_amount' => $cart->total,
                'status' => 'pending',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'payment_status' => 'pending',
                'shipping_name' => strip_tags($data['shipping_name'] ?? ''),
                'shipping_phone' => strip_tags($data['shipping_phone'] ?? ''),
                'shipping_address' => strip_tags($data['shipping_address'] ?? ''),
                'notes' => strip_tags($data['notes'] ?? ''),
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

                AuditLog::log('stock_updated', $product, [
                    'stock' => $product->stock + $item->quantity
                ], [
                    'stock' => $product->stock
                ]);
            }

            $this->cartService->clearCart($cart->id);

            AuditLog::log('order_created', $order, [], [
                'total_amount' => $order->total_amount,
                'item_count' => $cart->items->count()
            ]);

            return $order;
        });
    }

    public function updateStatus(Order $order, string $status): Order
    {
        $oldStatus = $order->status;
        
        $order->status = $status;
        $order->save();

        AuditLog::log('order_status_updated', $order, [
            'status' => $oldStatus
        ], [
            'status' => $status
        ]);

        return $order;
    }

    public function updatePaymentStatus(Order $order, string $paymentStatus): Order
    {
        $order->payment_status = $paymentStatus;
        $order->save();

        AuditLog::log('payment_status_updated', $order, [], [
            'payment_status' => $paymentStatus
        ]);

        return $order;
    }

    public function addTracking(Order $order, array $trackingData): Order
    {
        $order->tracking_number = $trackingData['tracking_number'] ?? null;
        $order->courier = $trackingData['courier'] ?? null;
        $order->estimated_delivery = $trackingData['estimated_delivery'] ?? null;
        $order->save();

        AuditLog::log('tracking_added', $order, [], $trackingData);

        return $order;
    }

    public function getUserOrders(int $userId, int $perPage = 10)
    {
        return Order::where('user_id', $userId)
            ->with('items')
            ->latest()
            ->paginate($perPage);
    }

    public function getOrderStats(int $days = 30): array
    {
        $startDate = now()->subDays($days);

        return [
            'total_orders' => Order::where('created_at', '>=', $startDate)->count(),
            'pending_orders' => Order::where('status', 'pending')->where('created_at', '>=', $startDate)->count(),
            'completed_orders' => Order::where('status', 'delivered')->where('created_at', '>=', $startDate)->count(),
            'total_revenue' => Order::where('created_at', '>=', $startDate)->sum('total_amount'),
        ];
    }
}
