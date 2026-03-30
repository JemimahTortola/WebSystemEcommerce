<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }
        
        return $slug;
    }
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'active');
        $search = $request->get('search');
        
        $query = Product::with('category');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        if ($filter === 'active') {
            $query->where('is_active', true)->where('is_archived', false);
        } elseif ($filter === 'archived') {
            $query->where('is_archived', true);
        }
        
        $products = $query->latest()->get();
        
        return view('admin.products.index', compact('products', 'filter'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->slug = $this->generateUniqueSlug($request->name);
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->is_active = $request->is_active ?? true;
        $product->is_archived = $request->stock == 0;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $imageName, 'public');
            $product->image = $imageName;
        }

        $product->save();

        if ($product->is_archived) {
            return redirect()->route('admin.products.index')->with('warning', 'Product archived automatically due to zero stock');
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $product = Product::findOrFail($id);
        $product->name = $request->name;
        $product->slug = $this->generateUniqueSlug($request->name);
        $product->category_id = $request->category_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->description = $request->description;
        $product->is_active = $request->is_active ?? true;
        $product->is_archived = $request->stock == 0;

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('products', $imageName, 'public');
            $product->image = $imageName;
        }

        $product->save();

        return redirect()->route('admin.products.index', ['filter' => 'active'])->with('success', 'Product updated successfully');
    }

    public function archive($id)
    {
        $product = Product::findOrFail($id);
        $product->is_archived = true;
        $product->is_active = false;
        $product->save();

        return redirect()->route('admin.products.index', ['filter' => 'archived'])->with('success', 'Product archived successfully');
    }

    public function restore($id)
    {
        $product = Product::findOrFail($id);
        $product->is_archived = false;
        $product->is_active = true;
        $product->save();

        return redirect()->route('admin.products.index', ['filter' => 'active'])->with('success', 'Product restored successfully');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        if ($product->image) {
            Storage::disk('public')->delete('products/' . $product->image);
        }
        
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
    }
}
