@extends('layouts.app')

@section('title', $product->name . ' - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/product.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="product-detail-page">
        <!-- Product Main Info -->
        <div class="product-main">
            <div class="product-image-section">
                <img src="{{ $product->image }}" alt="{{ $product->name }}" class="main-product-image">
            </div>
            
            <div class="product-info-section">
                <div class="product-category">{{ $product->category_name }}</div>
                <h1 class="product-title">{{ $product->name }}</h1>
                <div class="product-price">₱{{ number_format($product->price, 0) }}</div>
                
                <div class="product-stock-status">
                    @if($product->stock < 1)
                        <span class="stock out">Out of Stock</span>
                    @elseif($product->stock < 5)
                        <span class="stock low">Low Stock - Only {{ $product->stock }} left</span>
                    @else
                        <span class="stock in">In Stock</span>
                    @endif
                </div>
                
                <p class="product-description">{{ $product->description }}</p>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn minus">−</button>
                        <input type="number" value="1" min="1" max="{{ $product->stock }}" class="qty-input" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                    
                    <button type="button" class="btn btn-add-cart" data-product-id="{{ $product->id }}" {{ $product->stock < 1 ? 'disabled' : '' }}>
                        🛒 Add to Cart
                    </button>
                    
                    <button type="button" class="btn btn-wishlist">
                        ❤️ Add to Wishlist
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="product-reviews-section">
            <h2>Customer Reviews</h2>
            
            @if($reviews->count() > 0)
                <div class="reviews-list">
                    @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-user">{{ $review->user_name }}</span>
                            <span class="review-rating">{{ str_repeat('⭐', $review->rating) }}</span>
                        </div>
                        <p class="review-comment">{{ $review->comment }}</p>
                        <span class="review-date">{{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}</span>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
            @endif
            
            @auth
            <form class="review-form" method="POST" action="{{ route('reviews') }}">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <h3>Write a Review</h3>
                <div class="rating-select">
                    <label>Your Rating:</label>
                    <select name="rating" class="form-input" required>
                        <option value="">Select rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5) - Excellent</option>
                        <option value="4">⭐⭐⭐⭐ (4) - Great</option>
                        <option value="3">⭐⭐⭐ (3) - Good</option>
                        <option value="2">⭐⭐ (2) - Fair</option>
                        <option value="1">⭐ (1) - Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="comment" class="form-input" rows="4" placeholder="Share your experience with this product..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
            @else
            <p class="login-to-review">Please <a href="{{ route('login') }}">login</a> to write a review.</p>
            @endauth
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="related-products-section">
            <h2>You May Also Like</h2>
            <div class="related-products-grid">
                @foreach($relatedProducts as $related)
                <div class="related-product-card" onclick="window.location.href='{{ route('shop.product', $related->slug) }}'">
                    <img src="{{ $related->image }}" alt="{{ $related->name }}">
                    <div class="related-info">
                        <h4>{{ $related->name }}</h4>
                        <span class="price">₱{{ number_format($related->price, 0) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/product.js') }}"></script>
@endsection