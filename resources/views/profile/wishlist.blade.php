@extends('layouts.app')

@section('title', 'My Wishlist - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/wishlist.css') }}">
@endsection

@section('content')
<div class="wishlist-page">
    <div class="page-header">
        <h1>My Wishlist</h1>
    </div>

    <div id="alertContainer">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
        @endif
    </div>

    <div class="wishlist-container">
        @if($wishlists->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">❤️</div>
            <h3>Your Wishlist is Empty</h3>
            <p>Save items you love to your wishlist to buy later.</p>
            <a href="{{ route('shop') }}" class="btn btn-primary">Browse Shop</a>
        </div>
        @else
        <div class="wishlist-grid">
            @foreach($wishlists as $wishlist)
            <div class="wishlist-item">
                <a href="{{ route('shop.product', $wishlist->product->slug) }}" class="item-image">
                    <img src="{{ $wishlist->product->image }}" alt="{{ $wishlist->product->name }}">
                </a>
                <div class="item-details">
                    <a href="{{ route('shop.product', $wishlist->product->slug) }}" class="item-name">{{ $wishlist->product->name }}</a>
                    <div class="item-price">₱{{ number_format($wishlist->product->price, 2) }}</div>
                </div>
                <div class="item-actions">
                    <button class="btn btn-primary btn-add-cart" data-product-id="{{ $wishlist->product->id }}">Add to Cart</button>
                    <form method="POST" action="{{ route('wishlist.destroy', $wishlist->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline">Remove</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/cart.js') }}"></script>
@endsection