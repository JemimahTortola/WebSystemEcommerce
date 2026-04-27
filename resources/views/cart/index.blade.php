@extends('layouts.app')

@section('title', 'Shopping Cart - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/cart.css') }}">
@endsection

@section('content')
<div class="cart-page">
    <div class="page-header">
        <h1>Shopping Cart</h1>
    </div>

    <div id="alertContainer"></div>

    <div class="cart-layout">
        <div class="cart-items" id="cartGrid">
            <div class="empty-state" id="emptyCart" style="display: none;">
                <div class="empty-icon">🛒</div>
                <h3>Your Cart is Empty</h3>
                <p>Looks like you haven't added anything yet!</p>
                <a href="{{ route('shop') }}" class="btn btn-primary">Browse Shop</a>
            </div>
        </div>

        <div class="cart-summary" id="cartSummary">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₱0.00</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span>₱0.00</span>
            </div>
            <a href="{{ route('checkout') }}" class="btn btn-primary btn-checkout">
                Proceed to Checkout
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/cart.js') }}"></script>
@endsection