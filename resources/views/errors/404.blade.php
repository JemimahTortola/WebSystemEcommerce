@extends('frontend.layouts.main')

@section('title', 'Page Not Found - TinyThreads')

@section('content')
<section class="error-page" role="region" aria-labelledby="error-heading">
    <div class="container">
        <div class="error-content">
            <div class="error-icon" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#a8c5d8" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M16 16s-1.5-2-4-2-4 2-4 2"></path>
                    <line x1="9" y1="9" x2="9.01" y2="9"></line>
                    <line x1="15" y1="9" x2="15.01" y2="9"></line>
                </svg>
            </div>
            <h1 id="error-heading">404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>Sorry, the page you're looking for doesn't exist or has been moved.</p>
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn-primary">Go to Homepage</a>
                <a href="{{ route('products.index') }}" class="btn-secondary">Browse Products</a>
            </div>
        </div>
    </div>
</section>

<style>
.error-page {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 50px 0;
}
.error-content {
    text-align: center;
    max-width: 500px;
}
.error-icon {
    margin-bottom: 2rem;
}
.error-content h1 {
    font-size: 6rem;
    color: #a8c5d8;
    margin: 0;
    line-height: 1;
}
.error-content h2 {
    font-size: 1.5rem;
    color: #4a4a4a;
    margin: 1rem 0;
}
.error-content p {
    color: #7a7a7a;
    margin-bottom: 2rem;
}
.error-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-primary {
    background: #a8c5d8;
    color: white;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.3s;
}
.btn-primary:hover {
    background: #8bb0c9;
}
.btn-secondary {
    background: white;
    color: #a8c5d8;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    border: 2px solid #a8c5d8;
    transition: all 0.3s;
}
.btn-secondary:hover {
    background: #fce4e4;
}
</style>
@endsection
