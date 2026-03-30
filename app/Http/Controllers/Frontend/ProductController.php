<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::all();
        
        $products = Product::where('is_active', true)
            ->with('category')
            ->when($request->category, function($query) use ($request) {
                $query->where('category_id', $request->category);
            })
            ->when($request->search, function($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->price_range, function($query) use ($request) {
                $range = $request->price_range;
                if ($range === '100+') {
                    $query->where('price', '>=', 100);
                } elseif (strpos($range, '-') !== false) {
                    $parts = explode('-', $range);
                    $query->whereBetween('price', [(float)$parts[0], (float)$parts[1]]);
                }
            })
            ->when($request->sort == 'price_low', function($query) {
                $query->orderBy('price', 'asc');
            })
            ->when($request->sort == 'price_high', function($query) {
                $query->orderBy('price', 'desc');
            })
            ->when($request->sort == 'newest' || !$request->sort, function($query) {
                $query->latest();
            })
            ->paginate(12);
        
        return view('frontend.products.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with('category', 'approvedReviews.user')
            ->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();
        
        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }
}
