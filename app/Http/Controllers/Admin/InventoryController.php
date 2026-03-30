<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $stockFilter = $request->get('stock', 'all');
        
        $query = Product::with('category');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        if ($stockFilter === 'out') {
            $query->where('stock', 0);
        } elseif ($stockFilter === 'low') {
            $query->where('stock', '>', 0)->where('stock', '<=', 10);
        } elseif ($stockFilter === 'in') {
            $query->where('stock', '>', 10);
        }
        
        $products = $query->latest()->get();
        
        $stats = [
            'total' => Product::count(),
            'in_stock' => Product::where('stock', '>', 10)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 10])->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
        ];
        
        return view('admin.inventory.index', compact('products', 'stats', 'stockFilter'));
    }
}