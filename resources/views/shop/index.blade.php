@extends('layouts.app')

@section('title', 'Shop - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/shop.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
<link rel="stylesheet" href="{{ asset('css/forms.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="shop-page">
        <main class="shop-main">
            <div class="shop-header">
                <h1>{{ request('category') ? ucfirst(request('category')) : 'All Products' }}</h1>
                <div class="header-right">
                    <span class="product-count">{{ $products->count() }} products</span>
                    <div class="sort-dropdown">
                        <label>Sort by:</label>
                        <select id="sortSelect">
                            <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                    <script>document.getElementById('sortSelect').addEventListener('change', function() { var url = new URL(window.location.href); url.searchParams.set('sort', this.value); window.location.href = url; });</script>
                </div>
            </div>

            <div class="category-tabs">
                <a href="{{ route('shop') }}" class="tab {{ !request('category') ? 'active' : '' }}">All</a>
                @foreach($categories as $cat)
                <a href="{{ route('shop', ['category' => $cat->slug]) }}" class="tab {{ request('category') == $cat->slug ? 'active' : '' }}">{{ $cat->name }}</a>
                @endforeach
            </div>

            <div id="alertContainer"></div>

            <div class="products-grid">
                @forelse($products as $product)
                <div class="product-card" onclick="window.location.href='{{ route('shop.product', $product->slug) }}'">
                    <div class="product-image-wrapper">
                        <img src="{{ $product->image }}" alt="{{ $product->name }}" class="product-image">
                        <button class="btn-add-cart" data-product-id="{{ $product->id }}" onclick="event.stopPropagation()" {{ $product->stock < 1 ? 'disabled' : '' }}>Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <div class="product-name">{{ $product->name }}</div>
                        <div class="product-price">₱{{ number_format($product->price, 0) }}</div>
                        <div class="product-stock {{ $product->stock < 5 ? 'low-stock' : '' }} {{ $product->stock < 1 ? 'out-of-stock' : 'in-stock' }}">
                            @if($product->stock < 1) Out of Stock
                            @elseif($product->stock < 5) Low Stock
                            @else In Stock
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-icon">🌸</div>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters</p>
                </div>
                @endforelse
            </div>

            <div class="pagination">
            </div>
        </main>
    </div>
</div>
@endsection

@section('scripts')
@endsection