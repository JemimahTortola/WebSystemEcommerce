@extends('layouts.app')

@section('title', 'My Profile - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/profile.css') }}">
@endsection

@section('content')
<div class="profile-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>My Profile</h1>
        </div>

        <div class="profile-content">
            <!-- Profile Form Card -->
            <div class="profile-card">
                <h2>Account Information</h2>
                <p class="card-subtitle">Update your personal details below</p>
                
                <div id="alertContainer"></div>
                
                <!-- Quick alert for success/error -->
                @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                <form id="profileForm" method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            placeholder="Enter your full name"
                            required
                            value="{{ auth()->user()->name ?? old('name') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Enter your email address"
                            required
                            value="{{ auth()->user()->email ?? old('email') }}"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            placeholder="Enter your phone number"
                            value="{{ auth()->user()->phone ?? old('phone') }}"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit">
                        <span class="btn-text">Save Changes</span>
                    </button>
                </form>
            </div>

            <!-- Quick Links Card -->
            <div class="profile-card">
                <h2>Quick Links</h2>
                <p class="card-subtitle">Access your account features</p>
                
                <div class="quick-links">
                    <a href="{{ route('orders') }}" class="quick-link">
                        <span class="link-icon">📦</span>
                        <span class="link-text">My Orders</span>
                    </a>
                    <a href="{{ route('wishlist') }}" class="quick-link">
                        <span class="link-icon">❤️</span>
                        <span class="link-text">My Wishlist</span>
                    </a>
                    <a href="{{ route('profile.addresses') }}" class="quick-link">
                        <span class="link-icon">📍</span>
                        <span class="link-text">My Addresses</span>
                    </a>
                    <a href="{{ route('cart') }}" class="quick-link">
                        <span class="link-icon">🛒</span>
                        <span class="link-text">Shopping Cart</span>
                    </a>
                    <a href="{{ route('checkout') }}" class="quick-link">
                        <span class="link-icon">💳</span>
                        <span class="link-text">Checkout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/profile.js') }}"></script>
@endsection