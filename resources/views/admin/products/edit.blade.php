@extends('admin.layout.main')

@section('title', 'Edit Product - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/products-form.css') }}">
@endpush

@section('content')
<div class="admin-header">
    <h1>Edit Product</h1>
</div>

<div class="product-form-container">
    <div class="card form-card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required id="input-name">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" class="form-control" required id="input-category">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $product->price) }}" required id="input-price">
                        @error('price')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required id="input-stock">
                        @error('stock')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Product Image</label>
                    @if($product->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="image" accept="image/*" class="form-control" id="image-input">
                    @error('image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                        Active
                    </label>
                </div>

                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>

    <div class="preview-card">
        <h3 class="preview-title">Preview</h3>
        <div class="product-card-preview">
            <div class="preview-image" id="preview-image">
                <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=400&h=400&fit=crop&auto=format&sat=-20' }}" alt="Product Preview" id="preview-img">
            </div>
            <div class="preview-info">
                <h4 class="preview-name" id="preview-name">{{ $product->name }}</h4>
                <div class="preview-price" id="preview-price">${{ number_format($product->price, 2) }}</div>
                <button class="btn-add-cart-preview">Add to Cart</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('input-name').addEventListener('input', function() {
    document.getElementById('preview-name').textContent = this.value || 'Product Name';
});

document.getElementById('input-price').addEventListener('input', function() {
    var price = parseFloat(this.value) || 0;
    document.getElementById('preview-price').textContent = '$' + price.toFixed(2);
});

document.getElementById('image-input').addEventListener('change', function(e) {
    var file = this.files[0];
    if (file) {
        document.getElementById('preview-img').src = URL.createObjectURL(file);
    }
});
</script>
@endpush

@endsection
