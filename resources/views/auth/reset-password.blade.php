@extends('layouts.app')

@section('title', 'Reset Password - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/login.css') }}">
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Reset Password</h1>
            <p>Enter your new password below</p>
        </div>

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
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
                <label class="form-label" for="password">
                    New Password <span class="required">*</span>
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-input" 
                    placeholder="Enter new password"
                    required
                >
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
                    placeholder="Confirm new password"
                    required
                >
            </div>

            <button type="submit" class="btn-submit">
                <span class="btn-text">Reset Password</span>
            </button>

            <div class="auth-links">
                <p><a href="{{ route('login') }}">← Back to Login</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
