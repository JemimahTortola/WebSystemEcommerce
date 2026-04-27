@extends('layouts.app')

@section('title', 'About Us - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/about.css') }}">
@endsection

@section('content')
<div class="about-page">

    <!-- Hero Section -->
    <section class="about-hero">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1>About Flourista</h1>
            <p>Bringing Beauty to Every Moment</p>
        </div>
        <div class="hero-flowers">
            <span class="flower flower-1">🌸</span>
            <span class="flower flower-2">🌺</span>
            <span class="flower flower-3">🌷</span>
            <span class="flower flower-4">🌹</span>
            <span class="flower flower-5">🌻</span>
        </div>
    </section>

    <div class="container">

        <!-- Welcome Section -->
        <section class="welcome-section">
            <div class="welcome-grid">
                <div class="welcome-image">
                    <img src="https://picsum.photos/seed/florist1/600/500" alt="Flourista Florist Shop">
                    <div class="image-accent"></div>
                </div>
                <div class="welcome-content">
                    <span class="section-tag">Welcome to Flourista</span>
                    <h2>Your Premier Floral Destination in Butuan City</h2>
                    <p>Welcome to Flourista, your premier destination for beautiful floral arrangements in Butuan City. We specialize in creating stunning bouquets and arrangements for all occasions, from weddings to birthdays, anniversaries to expressions of love.</p>
                    <p>Each arrangement is carefully crafted by our expert florists using the freshest blooms, ensuring your gift is as beautiful as the moment it represents.</p>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <h2 class="section-title">Why Choose Flourista</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">💐</div>
                    <h3>Fresh Flowers</h3>
                    <p>Sourced daily from local farms to ensure maximum freshness and longevity</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🚚</div>
                    <h3>Same-Day Delivery</h3>
                    <p>Quick and reliable delivery across Butuan City</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🎨</div>
                    <h3>Custom Arrangements</h3>
                    <p>Tailored designs to match your unique vision and style</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">💰</div>
                    <h3>Fair Pricing</h3>
                    <p>Premium quality at competitive, affordable prices</p>
                </div>
            </div>
        </section>

        <!-- Story Section -->
        <section class="story-section">
            <div class="story-content">
                <span class="section-tag">Our Story</span>
                <h2>Passion Blooms Here</h2>
                <p>Founded with a deep passion for flowers and an unwavering commitment to customer service, Flourista has been serving the Butuan City community with premium floral designs for years.</p>
                <p>Our team of expert florists treats each arrangement as a unique work of art, carefully selecting each bloom and arranging it with precision and love. We believe that every flower tells a story, and we're here to help you tell yours.</p>
                <div class="story-stats">
                    <div class="stat">
                        <span class="stat-number">500+</span>
                        <span class="stat-label">Happy Customers</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">1000+</span>
                        <span class="stat-label">Bouquets Created</span>
                    </div>
                    <div class="stat">
                        <span class="stat-number">50+</span>
                        <span class="stat-label">Flower Varieties</span>
                    </div>
                </div>
            </div>
            <div class="story-images">
                <img src="https://picsum.photos/seed/florist2/400/300" alt="Floral Arrangement" class="story-img-1">
                <img src="https://picsum.photos/seed/florist3/300/400" alt="Fresh Flowers" class="story-img-2">
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="cta-content">
                <h2>Ready to Spread Joy with Flowers?</h2>
                <p>Let us help you create the perfect floral arrangement for your special moment.</p>
                <div class="cta-buttons">
                    <a href="{{ route('shop') }}" class="btn btn-primary">Browse Our Collection</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline">Contact Us</a>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection