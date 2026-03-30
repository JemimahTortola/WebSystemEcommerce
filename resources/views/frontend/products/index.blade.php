@extends('frontend.layouts.main')

@section('title', 'Shop - TinyThreads Newborn to Toddler Clothing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/shop.css') }}">
@endpush

@section('content')
<main class="shop-page">
    <section class="shop-hero">
        <div class="shop-hero-content">
            <h1>Newborn to Toddler Clothing</h1>
            <p>Discover adorable and comfortable clothing for your little ones</p>
        </div>
    </section>

    <div class="shop-container">
        <div class="shop-categories">
            <nav class="shop-categories-nav" aria-label="Category navigation">
                <a href="{{ route('products.index') }}" class="shop-category-btn {{ !request('category') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    All
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" 
                       class="shop-category-btn {{ request('category') == $category->id ? 'active' : '' }}">
                        {{ $category->name }}
                    </a>
                @endforeach
            </nav>
        </div>

        <div class="shop-layout">
            <aside class="shop-sidebar">
                <h3 class="sidebar-title">Filters</h3>
                
                <form action="{{ route('products.index') }}" method="GET" id="filter-form">
                    @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                    @endif

                    <div class="filter-section">
                        <p class="filter-label">Shop By Age</p>
                        <div class="filter-options">
                            @foreach($categories as $category)
                                <label class="filter-option {{ request('category') == $category->id ? 'active' : '' }}">
                                    <input type="radio" name="category" value="{{ $category->id }}" {{ request('category') == $category->id ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="filter-section">
                        <p class="filter-label">Price Range</p>
                        <div class="filter-options">
                            <label class="filter-option {{ request('price_range') == '0-25' ? 'active' : '' }}">
                                <input type="radio" name="price_range" value="0-25" {{ request('price_range') == '0-25' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                <span>Under $25</span>
                            </label>
                            <label class="filter-option {{ request('price_range') == '25-50' ? 'active' : '' }}">
                                <input type="radio" name="price_range" value="25-50" {{ request('price_range') == '25-50' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                <span>$25 - $50</span>
                            </label>
                            <label class="filter-option {{ request('price_range') == '50-100' ? 'active' : '' }}">
                                <input type="radio" name="price_range" value="50-100" {{ request('price_range') == '50-100' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                <span>$50 - $100</span>
                            </label>
                            <label class="filter-option {{ request('price_range') == '100+' ? 'active' : '' }}">
                                <input type="radio" name="price_range" value="100+" {{ request('price_range') == '100+' ? 'checked' : '' }} onchange="document.getElementById('filter-form').submit()">
                                <span>$100+</span>
                            </label>
                        </div>
                    </div>

                    @if(request()->has('search') || request()->has('price_range') || (request()->has('category') && request('category')))
                        <button type="button" class="clear-filters-btn" onclick="window.location.href='{{ route('products.index') }}'">
                            Clear All Filters
                        </button>
                    @endif
                </form>
            </aside>

            <section class="shop-products">
                <div class="shop-toolbar">
                    <p class="results-count">
                        Showing <strong>{{ $products->count() }}</strong> of <strong>{{ $products->total() }}</strong> products
                        @if(request('category'))
                            in <strong>{{ $categories->find(request('category'))->name ?? '' }}</strong>
                        @endif
                    </p>

                    <div class="shop-controls">
                        <form action="{{ route('products.index') }}" method="GET" class="search-input-wrapper">
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" onchange="this.form.submit()">
                        </form>

                        <form>
                            @if(request('category'))
                                <input type="hidden" name="category" value="{{ request('category') }}">
                            @endif
                            @if(request('search'))
                                <input type="hidden" name="search" value="{{ request('search') }}">
                            @endif
                            @if(request('price_range'))
                                <input type="hidden" name="price_range" value="{{ request('price_range') }}">
                            @endif
                            <select name="sort" class="sort-select" onchange="this.form.submit()">
                                <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if($products->isNotEmpty())
                    <div class="products-grid" role="list">
                        @foreach($products as $product)
                            <article class="product-card" role="listitem">
                                @if($product->created_at->gt(now()->subDays(7)))
                                    <span class="product-badge badge-new">New</span>
                                @elseif($product->sale_price)
                                    <span class="product-badge badge-sale">Sale</span>
                                @elseif($product->stock < 5)
                                    <span class="product-badge badge-low-stock">Low Stock</span>
                                @endif
                                <div class="product-image-wrapper">
                                    <a href="{{ route('products.show', $product->slug) }}" class="product-image">
                                    @if($product->image)
                                        <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                                    @else
                                        <div class="placeholder-image">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                    </a>
                                </div>
                                @auth
                                    <button class="product-wishlist" aria-label="Add to wishlist">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                                    </button>
                                @endauth
                                <div class="product-info">
                                    <span class="product-category">{{ $product->category->name ?? 'Newborn to Toddler' }}</span>
                                    <h3 class="product-name">
                                        <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                                    </h3>
                                    <div class="product-price-wrapper">
                                        <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                        @if($product->sale_price)
                                            <span class="product-price-original">${{ number_format($product->sale_price, 2) }}</span>
                                        @endif
                                    </div>
                                    @auth
                                        @if($product->stock > 0)
                                            <button class="btn-add-cart add-to-cart" data-product-id="{{ $product->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                                Add to Cart
                                            </button>
                                        @else
                                            <button class="btn-add-cart" disabled>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                                Out of Stock
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn-add-cart">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                            Add to Cart
                                        </a>
                                    @endauth
                                </div>
                            </article>
                        @endforeach
                    </div>

                    @if($products->hasPages())
                        <nav class="pagination" aria-label="Product pagination">
                            @if($products->onFirstPage())
                                <span class="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                                </span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" aria-label="Previous page">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                                </a>
                            @endif

                            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                @if($page == $products->currentPage())
                                    <span class="active">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}">{{ $page }}</a>
                                @endif
                            @endforeach

                            @if($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" aria-label="Next page">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                </a>
                            @else
                                <span class="disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
                                </span>
                            @endif
                        </nav>
                    @endif
                @else
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        <h3>No products found</h3>
                        <p>Try adjusting your filters or check back later for new arrivals.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">View All Products</a>
                    </div>
                @endif
            </section>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                $('#cartIcon').addClass('ping');
                setTimeout(function() {
                    $('#cartIcon').removeClass('ping');
                }, 600);
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(response.cart_count);
                }
                if (typeof showToast === 'function') {
                    showToast('success', 'Added to Cart', response.success);
                }
            },
            error: function(xhr) {
                if(xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert(xhr.responseJSON.error || 'An error occurred');
                }
            }
        });
    });

    $('.product-wishlist').click(function(e) {
        e.preventDefault();
        $(this).toggleClass('active');
    });
});
</script>
@endpush
