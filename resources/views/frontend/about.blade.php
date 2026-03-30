@extends('frontend.layouts.main')

@section('title', 'About Us - Little Blessings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/about.css') }}">
@endpush

@php
$storeInfo = \App\Models\StoreInfo::first();
@endphp

@section('content')
<main class="about-page">
    <section class="about-hero">
        <div class="container">
            <h1>About {{ $storeInfo->store_name ?? 'Little Blessings' }}</h1>
            <p>{{ $storeInfo->store_description ?? 'Premium baby essentials crafted with love, care, and the softest materials for your little one' }}</p>
        </div>
    </section>

    <section class="about-content">
        <div class="container">
            <div class="about-grid">
                <div class="about-text">
                    <h2>Our Story</h2>
                    @if($storeInfo->store_description)
                        <p>{{ $storeInfo->store_description }}</p>
                    @else
                        <p>Welcome to Little Blessings, where we believe every child deserves the very best. Founded with a passion for quality and a love for little ones, we specialize in creating comfortable, safe, and stylish essentials for babies and toddlers.</p>
                        <p>Our collection features carefully selected materials that are gentle on delicate skin. From cozy sleepwear to everyday essentials, each piece is designed with your little one's comfort and safety in mind.</p>
                        <p>We understand that parents want nothing but the best for their children, which is why we rigorously test and carefully curate every product to ensure it meets our high standards.</p>
                    @endif
                </div>
                <div class="about-image">
                    <div class="placeholder-image" style="width: 100%; height: 350px; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.9rem; border-radius: var(--radius-md);">
                        About Image
                    </div>
                </div>
            </div>

            <div class="values-section">
                <h2>What We Stand For</h2>
                <div class="values-grid">
                    <div class="value-card">
                        <div class="value-icon orange">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </div>
                        <h3>Made with Love</h3>
                        <p>Every piece is crafted with care and attention to detail for your precious little ones</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon terracotta">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                        <h3>Safety First</h3>
                        <p>We prioritize safety in everything we do, using only certified safe materials</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon mustard">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                        </div>
                        <h3>Quality Materials</h3>
                        <p>We use premium fabrics that are soft, durable, and gentle on delicate skin</p>
                    </div>
                    <div class="value-card">
                        <div class="value-icon beige">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                        </div>
                        <h3>Sustainability</h3>
                        <p>Committed to eco-friendly practices for a better tomorrow</p>
                    </div>
                </div>
            </div>

            <div class="mission-section">
                <h2>Our Mission</h2>
                <p>To provide parents with trustworthy, high-quality baby essentials that combine safety, comfort, and style. We believe every little blessing deserves the very best start in life, and we're honored to be part of your journey.</p>
            </div>

            <div class="stats-section">
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Happy Families</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Safe Materials</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Products</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">4.9</div>
                        <div class="stat-label">Average Rating</div>
                    </div>
                </div>
            </div>

            <div class="team-section">
                <h2>Meet the Team</h2>
                <div class="team-grid">
                    <div class="team-card">
                        <div class="team-avatar">S</div>
                        <h3>Sarah Mitchell</h3>
                        <div class="team-role">Founder</div>
                        <p>A mother of two with a passion for creating safe, comfortable products for babies.</p>
                    </div>
                    <div class="team-card">
                        <div class="team-avatar">J</div>
                        <h3>James Chen</h3>
                        <div class="team-role">Product Director</div>
                        <p>Ensures every product meets our high standards for quality and safety.</p>
                    </div>
                    <div class="team-card">
                        <div class="team-avatar">E</div>
                        <h3>Emily Rodriguez</h3>
                        <div class="team-role">Customer Care</div>
                        <p>Dedicated to providing exceptional service to our Little Blessings family.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection