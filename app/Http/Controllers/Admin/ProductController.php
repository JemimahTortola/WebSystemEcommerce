<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Shows the products management page (admin/products)
    public function index()
    {
        return view('admin.products');
    }

    // Returns product list as JSON (for AJAX loading with search/filter/pagination)
    public function data(Request $request)
    {
        // Start building the query, include the category relationship
        $query = Product::with('category');

        // Filter by search term if provided
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by category if selected
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Get 10 products per page, newest first
        $products = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($products);
    }

    // Saves a new product to the database
    public function store(Request $request)
    {
        // Check that all required fields are present and valid
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:products'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'category_id' => ['required', 'exists:categories,id'],
            'type' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $data = $validated;
        // Set default type if not provided
        $data['type'] = $data['type'] ?? 'bouquet';

        // Handle image upload - save to storage/app/public/products
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        // Create the product in the database
        $product = Product::create($data);

        return response()->json([
            'message' => 'Product created successfully!',
            'product' => $product,
        ]);
    }

    // Returns a single product as JSON (used when editing a product)
    public function show($id)
    {
        // Find product or return 404 if not found
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    // Updates an existing product in the database
    public function update(Request $request, Product $product)
    {
        // Validate the incoming data (some fields are optional with 'sometimes')
        $validated = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'slug' => ['sometimes', 'string', 'max:255', 'unique:products,slug,' . $product->id],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'category_id' => ['sometimes', 'exists:categories,id'],
            'type' => ['sometimes', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'is_active' => ['boolean'],
        ]);

        $data = $validated;

        // Handle image upload - save to storage/app/public/products
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image'] = $path;
        }

        // Update the product in the database
        $product->update($data);

        return response()->json([
            'message' => 'Product updated successfully!',
            'product' => $product,
        ]);
    }

    // Deletes a product from the database
    public function destroy(Product $product)
    {
        // Delete the product (soft delete - data stays in DB but marked as deleted)
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully!',
        ]);
    }
}
