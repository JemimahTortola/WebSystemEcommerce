@extends('frontend.layouts.main')

@section('title', 'Checkout - Little Blessings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/checkout.css') }}">
@endpush

@section('content')
<main class="main-content">
    <section class="checkout-page" aria-labelledby="checkout-title">
        <div class="checkout-container">
            <header class="checkout-header">
                <h1 id="checkout-title" class="checkout-title">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Checkout
                </h1>
                
                <nav class="checkout-steps" aria-label="Checkout progress">
                    <div class="checkout-step completed" aria-current="step">
                        <div class="checkout-step-number">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="checkout-step-label">Cart</span>
                    </div>
                    <div class="checkout-step-divider"></div>
                    <div class="checkout-step active">
                        <div class="checkout-step-number">2</div>
                        <span class="checkout-step-label">Details</span>
                    </div>
                    <div class="checkout-step-divider"></div>
                    <div class="checkout-step">
                        <div class="checkout-step-number">3</div>
                        <span class="checkout-step-label">Confirm</span>
                    </div>
                </nav>
            </header>

            <div class="checkout-content">
                <div class="checkout-form-section">
                    <form method="POST" action="{{ route('checkout.process') }}" class="checkout-form" novalidate>
                    @csrf
                    
                    {{-- Honeypot fields (hidden from users, visible to bots) --}}
                    <div class="hp-field" aria-hidden="true">
                        <input type="text" name="website" tabindex="-1" autocomplete="off">
                        <input type="text" name="phone2" tabindex="-1" autocomplete="off">
                    </div>
                    
                    {{-- Timestamp field for bot detection --}}
                    <input type="hidden" name="form_timestamp" value="{{ time() }}">
                    
                    @if($errors->any())
                        <div class="form-error-message">
                            @foreach($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif
                    
                        <section class="form-section" aria-labelledby="shipping-title">
                            <h2 id="shipping-title" class="form-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                                Shipping Information
                            </h2>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="shipping_name" class="form-label">Full Name *</label>
                                    <input type="text" id="shipping_name" name="shipping_name" class="form-control" 
                                           value="{{ auth()->user()->name ?? old('shipping_name') }}" 
                                           placeholder="Enter your full name" required>
                                </div>
                                <div class="form-group">
                                    <label for="shipping_phone" class="form-label">Phone Number *</label>
                                    <input type="tel" id="shipping_phone" name="shipping_phone" class="form-control" 
                                           value="{{ old('shipping_phone') }}"
                                           placeholder="(555) 123-4567" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="shipping_address" class="form-label">Shipping Address *</label>
                                <textarea id="shipping_address" name="shipping_address" class="form-control" rows="3" 
                                          placeholder="Street address, apartment, suite, unit, building, floor, etc." required>{{ old('shipping_address') }}</textarea>
                            </div>
                        </section>

                        <section class="form-section" aria-labelledby="payment-title">
                            <h2 id="payment-title" class="form-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                Payment Method
                            </h2>

                            <div class="payment-methods">
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="cash" {{ old('payment_method') == 'cash' ? 'checked' : '' }} required>
                                    <div class="payment-option-content">
                                        <div class="payment-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                        </div>
                                        <div class="payment-text">
                                            <span class="payment-name">Cash on Delivery</span>
                                            <span class="payment-desc">Pay when you receive your order</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="card" {{ old('payment_method') == 'card' ? 'checked' : '' }}>
                                    <div class="payment-option-content">
                                        <div class="payment-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
                                        </div>
                                        <div class="payment-text">
                                            <span class="payment-name">Credit/Debit Card</span>
                                            <span class="payment-desc">Pay securely with your card</span>
                                        </div>
                                    </div>
                                </label>
                                
                                <label class="payment-option">
                                    <input type="radio" name="payment_method" value="paypal" {{ old('payment_method') == 'paypal' ? 'checked' : '' }}>
                                    <div class="payment-option-content">
                                        <div class="payment-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
                                        </div>
                                        <div class="payment-text">
                                            <span class="payment-name">PayPal</span>
                                            <span class="payment-desc">Pay with your PayPal account</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </section>

                        <section class="form-section" aria-labelledby="notes-title">
                            <h2 id="notes-title" class="form-section-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Order Notes (Optional)
                            </h2>
                            
                            <div class="form-group">
                                <label for="notes" class="form-label">Special Instructions</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3" 
                                          placeholder="Any special instructions for your order, like gift wrapping or delivery preferences...">{{ old('notes') }}</textarea>
                            </div>
                        </section>

                        <button type="submit" class="btn btn-primary btn-lg btn-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            Place Order
                        </button>
                    </form>
                </div>

                <aside class="checkout-summary" aria-labelledby="order-summary-title">
                    <div class="summary-card">
                        <h2 id="order-summary-title" class="summary-title">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                            Order Summary
                        </h2>

                        <div class="summary-items">
                            @foreach($cart->items as $item)
                                <div class="summary-item">
                                    <div class="summary-item-image">
                                        @if($item->product->image)
                                            <img src="{{ asset('storage/products/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                        @else
                                            <div class="placeholder-image" style="width: 100%; height: 100%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.6rem;">
                                                IMG
                                            </div>
                                        @endif
                                        <span class="item-quantity">{{ $item->quantity }}</span>
                                    </div>
                                    <div class="summary-item-details">
                                        <span class="item-name">{{ $item->product->name }}</span>
                                    </div>
                                    <span class="item-price">${{ number_format($item->subtotal, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="summary-totals">
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span>${{ number_format($cart->total, 2) }}</span>
                            </div>
                            <div class="summary-row">
                                <span>Shipping</span>
                                <span>
                                    @if($cart->total >= 50)
                                        <span class="badge badge-success">Free</span>
                                    @else
                                        Calculated next step
                                    @endif
                                </span>
                            </div>
                            <div class="summary-row">
                                <span>Tax</span>
                                <span>$0.00</span>
                            </div>
                        </div>

                        <div class="summary-total-row">
                            <span>Total</span>
                            <span class="total-value">${{ number_format($cart->total, 2) }}</span>
                        </div>

                        <div class="summary-security">
                            <div class="security-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                <span>Secure checkout</span>
                            </div>
                            <div class="security-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                <span>Protected purchase</span>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</main>
@endsection
