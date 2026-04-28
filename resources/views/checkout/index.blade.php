@extends('layouts.app')

@section('title', 'Checkout - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/cart.css') }}">
<link rel="stylesheet" href="{{ asset('css/user/checkout.css') }}">
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const receiptUpload = document.getElementById('receiptUpload');
    const receiptInput = document.getElementById('payment_receipt');
    const paymentInfo = document.getElementById('paymentInfo');
    const gcashInfo = document.getElementById('gcashInfo');
    const bankInfo = document.getElementById('bankInfo');
    
    function toggleReceipt() {
        const selected = document.querySelector('input[name="payment_method"]:checked');
        if (!selected) return;
        
        if (selected.value === 'cod') {
            receiptUpload.style.display = 'none';
            paymentInfo.style.display = 'none';
            receiptInput.removeAttribute('required');
        } else {
            receiptUpload.style.display = 'block';
            paymentInfo.style.display = 'block';
            receiptInput.setAttribute('required', 'required');
            
            // Show appropriate payment info
            if (selected.value === 'gcash') {
                gcashInfo.style.display = 'block';
                bankInfo.style.display = 'none';
            } else if (selected.value === 'bank') {
                gcashInfo.style.display = 'none';
                bankInfo.style.display = 'block';
            }
        }
    }
    
    paymentRadios.forEach(radio => {
        radio.addEventListener('change', toggleReceipt);
    });
    
    // Check initial state
    toggleReceipt();
});
</script>
@endsection

@section('content')
<div class="container">
    <div class="checkout-page">
        <div class="page-header">
            <h1>Checkout</h1>
        </div>

        @if($cartItems->count() === 0)
        <div class="empty-cart">
            <div class="empty-icon">🛒</div>
            <h3>Your Cart is Empty</h3>
            <p>Add some beautiful flowers!</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Browse Shop</a>
        </div>
        @else
        <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="checkout-layout">
                <div class="checkout-form">
                    <div class="form-section">
                        <h2>Delivery Address</h2>
                        
                        @if($addresses->count() > 0)
                        <div class="address-list">
                            @foreach($addresses as $address)
                            <label class="address-option {{ $address->is_default ? 'default' : '' }}">
                                <input type="radio" name="address_id" value="{{ $address->id }}" {{ $address->is_default ? 'checked' : '' }} required>
                                <div class="address-card">
                                    <span class="address-name">{{ $address->full_name }}</span>
                                    <span class="address-text">{{ $address->address }}, {{ $address->city }}, {{ $address->postal_code }}</span>
                                    <span class="address-phone">{{ $address->phone }}</span>
                                    @if($address->is_default)
                                    <span class="badge-default">Default</span>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @else
                        <p class="no-address">No saved address. <a href="{{ route('profile.addresses') }}">Add an address</a></p>
                        @endif
                    </div>

                    <div class="form-section">
                        <h2>Payment Method</h2>
                        
                        <div class="payment-methods">
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="cod" checked>
                                <div class="payment-card">
                                    <span class="payment-icon">💵</span>
                                    <span>Cash on Delivery</span>
                                </div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="gcash">
                                <div class="payment-card">
                                    <span class="payment-icon">📱</span>
                                    <span>E-Wallet (GCash)</span>
                                </div>
                            </label>
                            <label class="payment-option">
                                <input type="radio" name="payment_method" value="bank">
                                <div class="payment-card">
                                    <span class="payment-icon">🏦</span>
                                    <span>Bank Transfer</span>
                                </div>
                            </label>
                        </div>
                        
                        <div id="receiptUpload" style="display: none; margin-top: 1rem;">
                            <div id="paymentInfo" style="display: none; margin-bottom: 1rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
                                <h4 style="margin-bottom: 0.5rem;">Payment Details</h4>
                                <div id="gcashInfo" style="display: none;">
                                    <p><strong>GCash Number:</strong> 0912-345-6789</p>
                                    <p><strong>Account Name:</strong> Flourista Flowers</p>
                                </div>
                                <div id="bankInfo" style="display: none;">
                                    <p><strong>Bank:</strong> BPI</p>
                                    <p><strong>Account Number:</strong> 1234-5678-9012</p>
                                    <p><strong>Account Name:</strong> Flourista Flowers</p>
                                </div>
                            </div>
                            
                            <label for="payment_receipt"><strong>Upload Payment Receipt</strong></label>
                            <input type="file" name="payment_receipt" id="payment_receipt" accept="image/*" class="form-input" style="margin-top: 0.5rem;">
                            <p style="color: var(--text-muted); font-size: 0.85rem; margin-top: 0.5rem;">Please upload your payment receipt for verification</p>
                        </div>
                    </div>
                </div>

                <div class="order-summary">
                    <h3>Order Summary</h3>
                    
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                    @php $subtotal = $item->price * $item->quantity; $total += $subtotal; @endphp
                    <div class="summary-item">
                        <img src="{{ $item->image }}" alt="{{ $item->name }}">
                        <div class="item-details">
                            <span class="item-name">{{ $item->name }}</span>
                            <span class="item-qty">x{{ $item->quantity }}</span>
                            @if($item->delivery_date)
                            <span class="item-delivery">📅 {{ \Carbon\Carbon::parse($item->delivery_date)->format('M d, Y') }}</span>
                            @endif
                        </div>
                        <span class="item-price">₱{{ number_format($subtotal, 0) }}</span>
                    </div>
                    @endforeach

                    <div class="summary-row">
                        <span>Total</span>
                        <span class="total-price">₱{{ number_format($total, 0) }}</span>
                    </div>

                    @if($addresses->count() > 0)
                    <button type="submit" class="btn btn-primary btn-place-order">Place Order</button>
                    @else
                    <button type="submit" class="btn btn-primary" disabled>Add address first</button>
                    @endif
                </div>
            </div>
        </form>
        @endif
    </div>
</div>
@endsection