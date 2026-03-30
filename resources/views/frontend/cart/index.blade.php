@extends('frontend.layouts.main')

@section('title', 'Shopping Cart - Little Blessings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/cart.css') }}">
<style>
.visually-hidden {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}
</style>
@endpush

@section('content')
<main class="cart-page" aria-labelledby="cart-title">
    <div class="cart-container">
        <header class="cart-header">
            <h1 id="cart-title" class="cart-title">Shopping Cart</h1>
        </header>

            @if($cart && $cart->items->count() > 0)
                <div class="cart-content">
                    <div class="cart-items" role="list" aria-label="Cart items">
                        @foreach($cart->items as $item)
                            <article class="cart-item" role="listitem">
                                <div class="cart-item-image">
                                    <img src="{{ $item->product->image ? asset('storage/products/' . $item->product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=150&h=150&fit=crop' }}" 
                                         alt="{{ $item->product->name }}"
                                         loading="lazy">
                                </div>
                                <div class="cart-item-details">
                                    <h3 class="cart-item-name">
                                        <a href="{{ route('products.show', $item->product->slug) }}">{{ $item->product->name }}</a>
                                    </h3>
                                    <span class="cart-item-category">{{ $item->product->category->name ?? 'Baby Care' }}</span>
                                    <span class="cart-item-price">${{ number_format($item->price, 2) }}</span>
                                </div>
                                <div class="cart-item-quantity">
                                    <label for="qty-{{ $item->id }}" class="visually-hidden">Quantity</label>
                                    <div class="quantity-selector">
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" aria-label="Decrease quantity" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="number" id="qty-{{ $item->id }}" value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock }}" 
                                               onchange="updateQuantity({{ $item->id }}, this.value)"
                                               aria-label="Quantity">
                                        <button type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" aria-label="Increase quantity" {{ $item->quantity >= $item->product->stock ? 'disabled' : '' }}>+</button>
                                    </div>
                                </div>
                                <div class="cart-item-subtotal">
                                    <span class="subtotal-label">Subtotal</span>
                                    <span class="subtotal-value">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                                <button type="button" class="cart-item-remove" onclick="removeFromCart({{ $item->id }}, this)" aria-label="Remove item">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                </button>
                            </article>
                        @endforeach
                    </div>

                    <aside class="cart-summary" aria-labelledby="summary-title">
                        <h2 id="summary-title" class="summary-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                            Order Summary
                        </h2>
                        
                        <div class="summary-rows">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span class="shipping-note">
                                    @if($cart->total >= 50)
                                        <span class="badge badge-success">Free</span>
                                    @else
                                        Calculated at checkout
                                    @endif
                                </span>
                            </div>
                            @if($cart->total < 50)
                                <div class="free-shipping-progress">
                                    <p>Add <strong>${{ number_format(50 - $cart->total, 2) }}</strong> more for free shipping!</p>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ min(($cart->total / 50) * 100, 100) }}%"></div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span class="total-amount">${{ number_format($cart->total, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                            Proceed to Checkout
                        </a>

                        <div class="secure-checkout">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                            Secure Checkout
                        </div>

                        <a href="{{ route('products.index') }}" class="continue-shopping">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
                            Continue Shopping
                        </a>

                        <div class="cart-benefits">
                            <div class="cart-benefit">
                                <div class="cart-benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                </div>
                                <span>Free Shipping $50+</span>
                            </div>
                            <div class="cart-benefit">
                                <div class="cart-benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                </div>
                                <span>30-Day Returns</span>
                            </div>
                            <div class="cart-benefit">
                                <div class="cart-benefit-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                </div>
                                <span>Secure Payment</span>
                            </div>
                        </div>
                    </aside>
                </div>
            @else
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                    </div>
                    <h2>Your cart is empty</h2>
                    <p>Looks like you haven't added anything to your cart yet. Let's find something special for your little one!</p>
                    <a href="{{ route('products.index') }}" class="btn-shop">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
function updateQuantity(itemId, quantity) {
    if (quantity < 1) return;
    
    $.ajax({
        url: '/cart/update/' + itemId,
        type: 'PUT',
        data: {
            _token: '{{ csrf_token() }}',
            quantity: quantity
        },
        success: function() {
            location.reload();
        },
        error: function(xhr) {
            alert(xhr.responseJSON.error || 'An error occurred');
            location.reload();
        }
    });
}

function removeFromCart(itemId, btn) {
    if (btn.disabled) return;
    btn.disabled = true;
    
    $.ajax({
        url: '/cart/remove/' + itemId,
        type: 'GET',
        success: function() {
            location.reload();
        },
        error: function() {
            btn.disabled = false;
            location.reload();
        }
    });
}
</script>
@endpush
