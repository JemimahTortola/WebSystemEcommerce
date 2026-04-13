@extends('frontend.layouts.main')

@section('title', 'Login - TinyThreads')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/login.css') }}">
@endsection

@section('content')
<main class="login-page">
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>
            </div>
            <h1>TinyThreads</h1>
            <p class="subtitle">Newborn to Toddler Clothing</p>
        </div>

        <div class="auth-title">
            <h2>Sign in to your account</h2>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
            
            <div class="form-group">
                <input type="text" name="login" class="form-control" value="{{ old('login') }}" required autocomplete="username" placeholder="Email or Username">
            </div>

            <div class="form-group">
                <div class="password-wrapper">
                    <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password" placeholder="Password">
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        <svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</main>

@endsection

@push('scripts')
<script>
function togglePassword(id) {
    var input = document.getElementById(id);
    var button = input.nextElementSibling;
    var eyeOpen = button.querySelector('.eye-open');
    var eyeClosed = button.querySelector('.eye-closed');
    
    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.style.display = 'none';
        eyeClosed.style.display = 'block';
    } else {
        input.type = 'password';
        eyeOpen.style.display = 'block';
        eyeClosed.style.display = 'none';
    }
}
</script>
@endpush
