@extends('frontend.layouts.main')

@section('title', 'My Wishlist - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/wishlist.css') }}">
@endpush

@section('content')
<div class="wishlist-page">
    <div class="container">
        <h1>My Wishlist</h1>
        
        @if($wishlists->isEmpty())
        <div class="wishlist-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <p>Your wishlist is empty</p>
            <a href="{{ route('products.index') }}" class="wishlist-btn">Start Shopping</a>
        </div>
        @else
        <div class="wishlist-grid">
            @foreach($wishlists as $item)
            @if($item->product)
            <div class="wishlist-card" data-product-id="{{ $item->product_id }}">
                <a href="{{ route('products.show', $item->product->slug) }}" class="wishlist-image">
                    @if($item->product->primaryImage)
                        <img src="{{ asset('storage/products/' . $item->product->primaryImage->image) }}" alt="{{ $item->product->name }}">
                    @else
                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $item->product->name }}">
                    @endif
                </a>
                <div class="wishlist-details">
                    <h3><a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a></h3>
                    <p class="wishlist-price">${{ number_format($item->product->price, 2) }}</p>
                    <div class="wishlist-actions">
                        @if($item->product->stock > 0)
                        <button class="btn-add-cart add-to-cart" data-product-id="{{ $item->product_id }}">Add to Cart</button>
                        @else
                        <button class="btn-add-cart" disabled>Out of Stock</button>
                        @endif
                        <button class="btn-remove-wishlist" data-product-id="{{ $item->product_id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.wishlist-card .add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const btn = this;
            const card = btn.closest('.wishlist-card');
            
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Remove from wishlist
                    fetch(`/wishlist/${productId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        }
                    });
                    
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        if (document.querySelectorAll('.wishlist-card').length === 0) {
                            location.reload();
                        }
                    }, 300);
                    
                    if (typeof updateCartBadge === 'function') {
                        updateCartBadge(data.cart_count);
                    }
                }
            });
        });
    });

    // Remove from wishlist functionality
    document.querySelectorAll('.btn-remove-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const card = this.closest('.wishlist-card');
            
            fetch(`/wishlist/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    card.remove();
                    if (document.querySelectorAll('.wishlist-card').length === 0) {
                        location.reload();
                    }
                }, 300);
            });
        });
    });
});
</script>
@endpush
