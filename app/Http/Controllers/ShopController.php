<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $sort = $request->query('sort', 'newest');
        
        $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name')
            ->where('products.is_active', true);
        
        if ($categorySlug) {
            $query->where('categories.slug', $categorySlug);
        }
        
        switch ($sort) {
            case 'price_low':
                $query->orderBy('products.price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('products.price', 'desc');
                break;
            default:
                $query->orderBy('products.created_at', 'desc');
        }
        
        $products = $query->get();
        $categories = DB::table('categories')->get();
        
        return view('shop.index', compact('products', 'categories'));
    }

    public function show($slug)
    {
        $product = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name', 'categories.id as category_id')
            ->where('products.slug', $slug)
            ->first();
        
        if (!$product) {
            abort(404);
        }
        
        // Related products from same category
        $relatedProducts = DB::table('products')
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->limit(4)
            ->get();
        
        // Reviews for this product (show any for demo purposes)
        $reviews = DB::table('reviews')
            ->join('users', 'reviews.user_id', '=', 'users.id')
            ->select('reviews.id', 'reviews.user_id', 'reviews.product_id', 'reviews.rating', 'reviews.comment', 'reviews.admin_comment', 'reviews.created_at', 'users.name as user_name')
            ->where('reviews.product_id', $product->id)
            ->orderBy('reviews.created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('shop.product', compact('product', 'relatedProducts', 'reviews'));
    }
}