@extends('admin.layout.main')

@section('title', 'Products - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/products.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Products</h1>
        <p>Manage your products inventory</p>
    </div>
    <div class="header-actions">
        <div class="header-filters-wrapper">
            <form method="GET" action="{{ route('admin.products.index') }}" class="header-filters">
                <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Add Product</a>
        <form method="GET" action="{{ route('admin.products.index') }}" class="archive-toggle-form">
            <span class="toggle-label">Archived</span>
            <button type="submit" class="archive-toggle {{ $filter === 'archived' ? 'archived' : '' }}">
                <div class="toggle-track">
                    <div class="toggle-thumb"></div>
                </div>
            </button>
            <input type="hidden" name="filter" value="{{ $filter === 'archived' ? 'active' : 'archived' }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
        </form>
    </div>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">ID</span>
        <span class="header-item">Image</span>
        <span class="header-item">Name</span>
        <span class="header-item">Category</span>
        <span class="header-item">Price</span>
        <span class="header-item">Stock</span>
        <span class="header-item">Status</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @foreach($products as $product)
            <div class="list-item">
                <span class="item-id">#{{ $product->id }}</span>
                <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=50&h=50&fit=crop' }}" 
                     alt="{{ $product->name }}" class="item-thumb">
                <span class="item-name">{{ $product->name }}</span>
                <span class="item-category">{{ $product->category->name ?? 'N/A' }}</span>
                <span class="item-price">${{ number_format($product->price, 2) }}</span>
                <span class="item-stock {{ $product->stock == 0 ? 'text-danger' : '' }}">{{ $product->stock }}</span>
                <span class="item-status">
                    @if($product->is_archived)
                        <span class="status-badge archived">Archived</span>
                    @elseif($product->stock == 0)
                        <span class="status-badge out">Out</span>
                    @elseif($product->is_active)
                        <span class="status-badge active">Active</span>
                    @else
                        <span class="status-badge inactive">Inactive</span>
                    @endif
                </span>
                <span class="item-action">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-secondary">Edit</a>
                </span>
            </div>
        @endforeach

        @if($products->isEmpty())
            <div class="empty-state">
                @if($filter === 'archived')
                    No archived products
                @else
                    No products found
                @endif
            </div>
        @endif
    </div>
</div>

@endsection
