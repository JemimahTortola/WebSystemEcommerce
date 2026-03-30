<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use App\Models\Review;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = $this->getStats();
        $recentOrders = Order::with(['user', 'items'])->latest()->take(5)->get();
        $topProducts = $this->getTopProducts(5);
        $lowStockProducts = $this->getLowStockProducts();
        $orderStatusBreakdown = $this->getOrderStatusBreakdown();
        $monthlyRevenue = $this->getMonthlyRevenue();
        $recentActivity = $this->getRecentActivity();
        $quickStats = $this->getQuickStats();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'lowStockProducts',
            'orderStatusBreakdown',
            'monthlyRevenue',
            'recentActivity',
            'quickStats'
        ));
    }

    private function getStats()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->sum('total_amount');
        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->sum('total_amount');

        $monthlyOrders = Order::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();
        $lastMonthOrders = Order::whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();

        $monthlyCustomers = User::where('is_admin', false)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $avgOrderValue = Order::where('payment_status', 'paid')->avg('total_amount') ?? 0;

        return [
            'products' => Product::count(),
            'categories' => Category::count(),
            'orders' => Order::count(),
            'users' => User::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'last_month_revenue' => $lastMonthRevenue,
            'monthly_orders' => $monthlyOrders,
            'last_month_orders' => $lastMonthOrders,
            'monthly_customers' => $monthlyCustomers,
            'avg_order_value' => $avgOrderValue,
            'revenue_change' => $lastMonthRevenue > 0 
                ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
                : 0,
            'orders_change' => $lastMonthOrders > 0 
                ? round((($monthlyOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1) 
                : 0,
        ];
    }

    private function getQuickStats()
    {
        return [
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'low_stock_count' => Product::where('stock', '<=', 5)->where('stock', '>', 0)->count(),
            'out_of_stock_count' => Product::where('stock', 0)->count(),
            'pending_reviews' => Review::where('is_approved', false)->count(),
            'unread_messages' => Message::where('is_read', false)->whereNull('sender_id')->count(),
        ];
    }

    private function getTopProducts($limit = 5)
    {
        return DB::table('order_items')
            ->selectRaw('product_id, SUM(quantity) as total_sold, SUM(subtotal) as total_revenue')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                $item->product_name = $product ? $product->name : 'Unknown';
                $item->image = $product ? $product->image : null;
                $item->price = $product ? $product->price : 0;
                $item->stock = $product ? $product->stock : 0;
                return $item;
            });
    }

    private function getLowStockProducts($limit = 5)
    {
        return Product::where('is_archived', false)
            ->where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->take($limit)
            ->get();
    }

    private function getOrderStatusBreakdown()
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $breakdown = [];

        foreach ($statuses as $status) {
            $count = Order::where('status', $status)->count();
            $breakdown[$status] = [
                'count' => $count,
                'percentage' => Order::count() > 0 
                    ? round(($count / Order::count()) * 100) 
                    : 0
            ];
        }

        return $breakdown;
    }

    private function getMonthlyRevenue()
    {
        $months = [];
        $revenue = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthData = Order::where('payment_status', 'paid')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_amount');
            
            $months[] = $date->format('M');
            $revenue[] = round($monthData, 2);
        }

        return [
            'labels' => $months,
            'data' => $revenue,
            'total' => array_sum($revenue),
        ];
    }

    private function getRecentActivity($limit = 10)
    {
        $activities = [];

        $recentOrders = Order::with('user')
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($order) {
                return [
                    'type' => 'order',
                    'icon' => 'cart',
                    'message' => 'Order #' . $order->order_number . ' - ' . $order->status,
                    'submessage' => $order->user->name . ' - $' . number_format($order->total_amount, 2),
                    'time' => $order->created_at,
                    'link' => route('admin.orders.show', $order->id),
                ];
            });

        $recentReviews = Review::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($review) {
                return [
                    'type' => 'review',
                    'icon' => 'star',
                    'message' => 'New review' . ($review->product ? ' on ' . $review->product->name : ''),
                    'submessage' => $review->user->name . ' - ' . $review->rating . ' stars',
                    'time' => $review->created_at,
                    'link' => route('admin.reviews.index'),
                ];
            });

        $recentMessages = Message::with(['sender', 'conversation'])
            ->where('sender_id', '!=', null)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($message) {
                return [
                    'type' => 'message',
                    'icon' => 'message',
                    'message' => 'New message from ' . ($message->sender ? $message->sender->name : 'Customer'),
                    'submessage' => \Str::limit($message->content, 50),
                    'time' => $message->created_at,
                    'link' => route('admin.messages.show', $message->conversation_id ?? 0),
                ];
            });

        $newCustomers = User::where('is_admin', false)
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'customer',
                    'icon' => 'user',
                    'message' => 'New customer registered',
                    'submessage' => $user->name . ' - ' . $user->email,
                    'time' => $user->created_at,
                    'link' => route('admin.customers.index'),
                ];
            });

        $activities = $recentOrders
            ->merge($recentReviews)
            ->merge($recentMessages)
            ->merge($newCustomers)
            ->sortByDesc('time')
            ->take($limit)
            ->values();

        return $activities;
    }

    public function analytics()
    {
        $stats = [
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => Order::count(),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_products' => Product::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
        ];

        $monthlySales = Order::selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->where('payment_status', 'paid')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->get();

        $topProducts = DB::table('order_items')
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get()
            ->map(function ($item) {
                $product = Product::find($item->product_id);
                $item->product_name = $product ? $product->name : 'Unknown';
                $item->image = $product ? $product->image : null;
                return $item;
            });

        return view('admin.analytics', compact('stats', 'monthlySales', 'topProducts'));
    }
}
