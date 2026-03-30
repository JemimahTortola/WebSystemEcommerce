@extends('admin.layout.main')

@section('title', 'Inventory - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Inventory</h1>
        <p>Manage product stock levels</p>
    </div>
    <div class="header-actions">
        <form method="GET" action="{{ route('admin.inventory.index') }}" class="header-filters">
            <input type="text" name="search" class="form-control" placeholder="Search by name or SKU..." value="{{ request('search') }}">
            <select name="stock" class="form-control">
                <option value="all" {{ $stockFilter === 'all' ? 'selected' : '' }}>All Stock</option>
                <option value="in" {{ $stockFilter === 'in' ? 'selected' : '' }}>In Stock (>10)</option>
                <option value="low" {{ $stockFilter === 'low' ? 'selected' : '' }}>Low Stock (1-10)</option>
                <option value="out" {{ $stockFilter === 'out' ? 'selected' : '' }}>Out of Stock</option>
            </select>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['total'] }}</span>
            <span class="stat-label">Total Products</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon in-stock">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['in_stock'] }}</span>
            <span class="stat-label">In Stock</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon low-stock">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['low_stock'] }}</span>
            <span class="stat-label">Low Stock</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon out-of-stock">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
        </div>
        <div class="stat-info">
            <span class="stat-value">{{ $stats['out_of_stock'] }}</span>
            <span class="stat-label">Out of Stock</span>
        </div>
    </div>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">ID</span>
        <span class="header-item">Product</span>
        <span class="header-item">SKU</span>
        <span class="header-item">Category</span>
        <span class="header-item">Stock</span>
        <span class="header-item">Status</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @foreach($products as $product)
            <div class="list-item">
                <span class="item-id">#{{ $product->id }}</span>
                <span class="item-product">
                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=40&h=40&fit=crop' }}" 
                         alt="{{ $product->name }}" class="item-thumb">
                    {{ $product->name }}
                </span>
                <span class="item-sku">{{ $product->sku ?? 'N/A' }}</span>
                <span class="item-category">{{ $product->category->name ?? 'N/A' }}</span>
                <span class="item-stock {{ $product->stock == 0 ? 'out-of-stock' : ($product->stock <= 10 ? 'low-stock' : 'in-stock') }}">
                    {{ $product->stock }}
                </span>
                <span class="item-status">
                    @if($product->stock == 0)
                        <span class="status-badge out">Out</span>
                    @elseif($product->stock <= 10)
                        <span class="status-badge low">Low</span>
                    @else
                        <span class="status-badge in">In Stock</span>
                    @endif
                </span>
                <span class="item-action">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                </span>
            </div>
        @endforeach

        @if($products->isEmpty())
            <div class="empty-state">No products found</div>
        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/inventory.css') }}">
@endpush

@endsection