@extends('layouts.app')

@section('title', 'Forgot Password - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/login.css') }}">
@endsection

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Forgot Password?</h1>
            <p>Enter your email to receive a password reset link</p>
        </div>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
        <div class="alert alert-error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <form class="auth-form" method="POST" action="{{ route('password.email') }}">
            @csrf
            
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

            <button type="submit" class="btn-submit">
                <span class="btn-text">Send Reset Link</span>
            </button>

            <div class="auth-links">
                <p><a href="{{ route('login') }}">← Back to Login</a></p>
            </div>
        </form>
    </div>
</div>
@endsection
