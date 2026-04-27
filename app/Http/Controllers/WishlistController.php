<?php
/**
 * ==============================================================================
 * WISHLIST CONTROLLER
 * ==============================================================================
 * 
 * Purpose: Handle user's wishlist operations
 * 
 * Methods:
 * - index()    : Show all wishlisted products
 * - store()   : Add product to wishlist
 * - destroy(): Remove product from wishlist
 */

namespace App\Http\Controllers;

use App\Models\Wishlist;         // Wishlist model for database
use App\Models\Product;        // Product model to lookup by slug
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * ==============================================================================
     * index() - Display user's wishlist
     * ==============================================================================
     * 
     * @return view - profile.wishlist
     */
    public function index()
    {
        // Get all wishlist items for current user with their product data
        // with('product') = eager load product relationship
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Return the wishlist view with data
        return view('profile.wishlist', compact('wishlists'));
    }

    /**
     * ==============================================================================
     * store() - Add product to wishlist
     * ==============================================================================
     * 
     * Accepts: product_id (can be numeric ID or slug)
     * @return JSON response for AJAX
     */
    public function store(Request $request)
    {
        // Check authenticated user
        // Auth::id() returns user ID or null if not logged in
        $userId = Auth::id();
        
        if (!$userId) {
            // Not logged in - return 401 with login redirect
            return response()->json([
                'message' => 'Please login first',
                'redirect' => route('login')
            ], 401);
        }

        // Get and validate product_id
        $productIdOrSlug = $request->input('product_id');
        
        if (!$productIdOrSlug) {
            return response()->json(['message' => 'Product ID required'], 400);
        }
        
        // Find product - by ID if numeric, else by slug
        if (is_numeric($productIdOrSlug)) {
            $product = Product::find($productIdOrSlug);
        } else {
            $product = Product::where('slug', $productIdOrSlug)->first();
        }
        
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Check duplicate entry
        $exists = Wishlist::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();
        
        if ($exists) {
            return response()->json(['message' => 'Product already in wishlist']);
        }

        // Create wishlist entry
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $product->id
        ]);

        return response()->json([
            'message' => 'Added to wishlist!', 
            'success' => true
        ]);
    }

    /**
     * ==============================================================================
     * destroy() - Remove product from wishlist
     * ==============================================================================
     * 
     * @return back with success message
     */
    public function destroy(Wishlist $wishlist)
    {
        // Security check: only owner can delete
        if ($wishlist->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized');
        }
        
        // Delete the wishlist item
        $wishlist->delete();
        
        // Return with success message
        return back()->with('success', 'Removed from wishlist');
    }
}