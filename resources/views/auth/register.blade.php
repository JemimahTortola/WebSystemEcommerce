@extends('layouts.app')

@section('title', 'Register - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/register.css') }}">
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Create Account</h1>
            <p>Join Flourista and start ordering beautiful flowers</p>
        </div>

        <form id="registerForm" class="auth-form" method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="register-section">
                <div class="form-group">
                    <label class="form-label" for="name">
                        Full Name <span class="required">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-input" 
                        placeholder="Enter your full name"
                        required
                        value="{{ old('name') }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">
                        Email Address <span class="required">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Enter your email address"
                        required
                        value="{{ old('email') }}"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="phone">
                        Phone Number
                    </label>
                    <input 
                        type="tel" 
                        id="phone" 
                        name="phone" 
                        class="form-input" 
                        placeholder="Enter your phone number"
                        value="{{ old('phone') }}"
                    >
                    <span class="form-hint">Optional - for delivery notifications</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">
                        Password <span class="required">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Create a password"
                        required
                        minlength="8"
                    >
                    <span class="form-hint">Minimum 8 characters</span>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">
                        Confirm Password <span class="required">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Confirm your password"
                        required
                    >
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <span class="spinner"></span>
                <span class="btn-text">Create Account</span>
            </button>

            <div class="auth-links">
                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/auth.js') }}"></script>
@endsection