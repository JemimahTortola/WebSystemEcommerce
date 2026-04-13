@extends('frontend.layouts.main')

@section('title', $product->name . ' - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/product-detail.css') }}">
@endpush

@section('content')
<main class="main-content">
    <section class="product-detail-page" aria-labelledby="product-title">
        <div class="product-detail-container">
            <!-- Breadcrumb -->
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('products.index') }}">Shop</a>
                @if($product->category)
                    <span aria-hidden="true">/</span>
                    <a href="{{ route('products.index', ['category' => $product->category->id]) }}">{{ $product->category->name }}</a>
                @endif
                <span aria-hidden="true">/</span>
                <span aria-current="page">{{ $product->name }}</span>
            </nav>

            <!-- Product Detail -->
            <article class="product-detail" itemscope itemtype="https://schema.org/Product">
                <div class="product-gallery">
                    <div class="main-image">
                        @if($product->image)
                            <img src="{{ asset('storage/products/' . $product->image) }}" 
                                 alt="{{ $product->name }}"
                                 itemprop="image"
                                 loading="eager">
                        @else
                            <div class="placeholder-image">
                                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                <span>No Image Available</span>
                            </div>
                        @endif
                        @if($product->created_at->gt(now()->subDays(1)))
                            <span class="product-badge badge-new">New Arrival</span>
                        @endif
                        @auth
                            <button class="wishlist-btn-detail" data-product-id="{{ $product->id }}" aria-label="Add to wishlist">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            </button>
                        @endauth
                    </div>
                </div>

                <div class="product-info" itemprop="offers" itemscope itemtype="https://schema.org/Offer">
                    <span class="product-category">{{ $product->category->name ?? 'Newborn to Toddler' }}</span>
                    <h1 id="product-title" class="product-title" itemprop="name">{{ $product->name }}</h1>
                    
                    @if($product->reviews_count > 0)
                        <div class="product-rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                            <div class="star-rating" aria-label="{{ number_format($product->average_rating, 1) }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="{{ $i <= round($product->average_rating) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                    </svg>
                                @endfor
                            </div>
                            <span class="rating-text">
                                <span itemprop="ratingValue">{{ number_format($product->average_rating, 1) }}</span>/5 
                                (<span itemprop="reviewCount">{{ $product->reviews_count }}</span> reviews)
                            </span>
                        </div>
                    @endif

                    <div class="product-price-detail">
                        <span class="product-price" itemprop="price" content="{{ $product->price }}">${{ number_format($product->price, 2) }}</span>
                        <link itemprop="availability" href="https://schema.org/InStock">
                    </div>

                    <p class="product-description" itemprop="description">{{ $product->description }}</p>

                    <div class="product-stock-status">
                        @if($product->stock > 0)
                            <span class="stock-badge in-stock">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                In Stock ({{ $product->stock }} available)
                            </span>
                        @else
                            <span class="stock-badge out-of-stock">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                Out of Stock
                            </span>
                        @endif
                    </div>

                    @auth
                        @if($product->stock > 0)
                            <div class="product-actions">
                                <div class="quantity-selector-wrapper">
                                    <label for="quantity">Quantity:</label>
                                    <div class="quantity-selector" id="quantity-selector">
                                        <button type="button" class="qty-btn" onclick="decreaseQty()" aria-label="Decrease quantity">-</button>
                                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}" aria-label="Quantity">
                                        <button type="button" class="qty-btn" onclick="increaseQty()" aria-label="Increase quantity">+</button>
                                    </div>
                                </div>

                                <button class="btn btn-primary btn-lg add-to-cart-btn" data-product-id="{{ $product->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    Add to Cart
                                </button>
                            </div>
                        @else
                            <button class="btn btn-lg" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                Out of Stock
                            </button>
                        @endif
                    @else
                        <div class="login-prompt">
                            <a href="{{ route('login.form') }}" class="btn btn-primary btn-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                                Login to Purchase
                            </a>
                        </div>
                    @endauth

                    <div class="product-meta">
                        <div class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            <span>Category: <strong>{{ $product->category->name ?? 'Uncategorized' }}</strong></span>
                        </div>
                        <div class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            <span>Free shipping on orders over $50</span>
                        </div>
                        <div class="meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                            <span>30-day happy returns</span>
                        </div>
                    </div>
                </div>
            </article>

            <!-- Reviews Section -->
            <section class="product-reviews" aria-labelledby="reviews-title">
                <h2 id="reviews-title" class="section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Customer Reviews
                </h2>
                
                @auth
                    <div class="review-form card">
                        <h3>Share Your Experience</h3>
                        <form method="POST" action="{{ route('reviews.store') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="rating-input">
                                <label>Your Rating:</label>
                                <div class="star-rating-input">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}" {{ old('rating') == $i ? 'checked' : '' }}>
                                        <label for="star{{ $i }}" title="{{ $i }} stars">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                        </label>
                                    @endfor
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="review-comment">Your Review (optional)</label>
                                <textarea id="review-comment" name="comment" rows="4" placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                Submit Review
                            </button>
                        </form>
                    </div>
                @else
                    <div class="login-prompt card">
                        <p>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <a href="{{ route('login.form') }}">Login</a> to write a review
                        </p>
                    </div>
                @endauth

                @if($product->approvedReviews->count() > 0)
                    <div class="reviews-list" role="list">
                        @foreach($product->approvedReviews as $review)
                            <article class="review-card" role="listitem">
                                <header class="review-header">
                                    <div class="reviewer-info">
                                        <div class="reviewer-avatar" aria-hidden="true">{{ strtoupper(substr($review->user->name, 0, 1)) }}</div>
                                        <div>
                                            <cite class="reviewer-name">{{ $review->user->name }}</cite>
                                            <time class="review-date" datetime="{{ $review->created_at->toIso8601String() }}">{{ $review->created_at->format('M d, Y') }}</time>
                                        </div>
                                    </div>
                                    <div class="review-rating" aria-label="{{ $review->rating }} out of 5 stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="{{ $i <= $review->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </header>
                                @if($review->comment)
                                    <p class="review-comment">{{ $review->comment }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                @else
                    <p class="no-reviews">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        No reviews yet. Be the first to review this product!
                    </p>
                @endif
            </section>

            @if($relatedProducts->count() > 0)
                <section class="related-products" aria-labelledby="related-title">
                    <h2 id="related-title" class="section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        You May Also Like
                    </h2>
                    <div class="products-grid">
                        @foreach($relatedProducts as $related)
                            <article class="product-card">
                                <a href="{{ route('products.show', $related->slug) }}" class="product-image">
                                    @if($related->image)
                                        <img src="{{ asset('storage/products/' . $related->image) }}" alt="{{ $related->name }}" loading="lazy">
                                    @else
                                        <div class="placeholder-image">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                                            <span>No Image</span>
                                        </div>
                                    @endif
                                </a>
                                <div class="product-info">
                                    <span class="product-category">{{ $related->category->name ?? 'Newborn to Toddler' }}</span>
                                    <h3 class="product-name">
                                        <a href="{{ route('products.show', $related->slug) }}">{{ $related->name }}</a>
                                    </h3>
                                    <div class="product-price-wrapper">
                                        <span class="product-price">${{ number_format($related->price, 2) }}</span>
                                    </div>
                                    @auth
                                        @if($related->stock > 0)
                                            <button class="btn-add-cart add-to-cart" data-product-id="{{ $related->id }}">Add to Cart</button>
                                        @else
                                            <button class="btn-add-cart" disabled>Out of Stock</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login.form') }}" class="btn-add-cart">Add to Cart</a>
                                    @endauth
                                </div>
                            </article>
                        @endforeach
                    </div>
                </section>
            @endif
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
function decreaseQty() {
    var input = document.getElementById('quantity');
    if (input.value > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function increaseQty() {
    var input = document.getElementById('quantity');
    var max = parseInt(input.max);
    if (input.value < max) {
        input.value = parseInt(input.value) + 1;
    }
}

$(document).ready(function() {
    function triggerCartPing() {
        $('#cartIcon').addClass('ping');
        setTimeout(function() {
            $('#cartIcon').removeClass('ping');
        }, 600);
    }

    // Add to cart buttons
    $('.add-to-cart, .add-to-cart-btn').click(function() {
        var productId = $(this).data('product-id');
        var quantity = $('#quantity').val() || 1;
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: quantity
            },
            success: function(response) {
                triggerCartPing();
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

    // Wishlist button on product detail page
    $('.wishlist-btn-detail').click(function() {
        var button = $(this);
        var productId = button.data('product-id');
        
        $.ajax({
            url: '/wishlist',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId
            },
            success: function(response) {
                button.toggleClass('active');
                if (button.hasClass('active')) {
                    button.find('svg').attr('fill', 'currentColor');
                    if (typeof showToast === 'function') {
                        showToast('success', 'Wishlist', 'Added to your wishlist');
                    }
                } else {
                    button.find('svg').attr('fill', 'none');
                    if (typeof showToast === 'function') {
                        showToast('success', 'Wishlist', 'Removed from your wishlist');
                    }
                }
            },
            error: function(xhr) {
                if(xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                }
            }
        });
    });

    // Check if product is in wishlist on page load
    $.ajax({
        url: '/wishlist/check/{{ $product->id }}',
        method: 'GET',
        success: function(response) {
            if(response.exists) {
                $('.wishlist-btn-detail').addClass('active');
                $('.wishlist-btn-detail svg').attr('fill', 'currentColor');
            }
        }
    });
});
</script>
@endpush
