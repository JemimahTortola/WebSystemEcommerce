<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function data()
    {
        try {
            $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
            $totalOrders = Order::count();
            $totalCustomers = DB::table('user_roles')
                ->where('role_id', 2)
                ->count();
            $totalProducts = Product::count();

            $recentOrders = Order::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(fn($order) => [
                    'order_number' => $order->order_number,
                    'order_date' => $order->created_at->format('M d, Y'),
                    'total' => number_format($order->total_amount, 2),
                    'status' => $order->status,
                ]);

            $topProducts = DB::table('order_items')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->select('products.name', DB::raw('SUM(order_items.quantity) as sold'))
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('sold')
                ->limit(5)
                ->get()
                ->map(fn($p) => [
                    'name' => $p->name,
                    'sold' => (int) $p->sold,
                ]);

            $inStock = Product::where('stock', '>', 10)->count();
            $lowStock = Product::whereBetween('stock', [1, 10])->count();
            $outOfStock = Product::where('stock', 0)->count();

            return response()->json([
                'total_revenue' => number_format($totalRevenue, 2),
                'total_orders' => $totalOrders,
                'total_customers' => $totalCustomers,
                'total_products' => $totalProducts,
                'recent_orders' => $recentOrders,
                'top_products' => $topProducts,
                'inventory' => [
                    'in_stock' => $inStock,
                    'low_stock' => $lowStock,
                    'out_of_stock' => $outOfStock,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}