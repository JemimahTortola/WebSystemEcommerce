@extends('frontend.layouts.main')

@section('title', 'Server Error - Lux Littles')

@section('content')
<section class="error-page">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#e57373" stroke-width="1.5">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>
            <h1>500</h1>
            <h2>Something Went Wrong</h2>
            <p>We're experiencing technical difficulties. Please try again later.</p>
            <div class="error-actions">
                <a href="{{ route('home') }}" class="btn-primary">Go to Homepage</a>
                <a href="javascript:location.reload()" class="btn-secondary">Refresh Page</a>
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
    color: #e57373;
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
