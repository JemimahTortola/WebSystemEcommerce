@extends('frontend.layouts.main')

@section('title', 'My Wishlist - Little Blessings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/wishlist.css') }}">
@endpush

@section('content')
<div class="wishlist-page">
    <div class="container">
        <h1>My Wishlist</h1>
        
        <div class="wishlist-empty">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
            </svg>
            <p>Your wishlist is empty</p>
            <a href="{{ route('products.index') }}" class="wishlist-btn">Start Shopping</a>
        </div>
    </div>
</div>
@endsection
