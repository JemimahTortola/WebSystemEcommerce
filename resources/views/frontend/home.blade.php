@extends('frontend.layouts.main')

@section('title', 'TinyThreads - Newborn to Toddler Clothing')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

@section('content')
<main class="main-content">
    <!-- Hero Section -->
    <section class="hero-section" aria-labelledby="hero-title">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-text">
                    <span class="hero-badge">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                        New Arrivals
                    </span>
                    <h1 id="hero-title" class="hero-title">Adorable Styles for Little Ones</h1>
                    <p class="hero-description">Discover the softest, safest, and most adorable clothing for your precious newborn to toddler</p>
                    <div class="hero-buttons">
                        <a href="{{ route('products.index') }}" class="btn-hero btn-hero-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                            Shop Now
                        </a>
                        <a href="{{ route('about') }}" class="btn-hero btn-hero-secondary">
                            Our Story
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <div class="hero-image-wrapper">
                        <div class="placeholder-image" style="width: 100%; height: 400px; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.9rem;">
                            Hero Image
                        </div>
                    </div>
                    <div class="hero-floating-card card-1">
                        <div class="floating-icon pink">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                        </div>
                        <div class="floating-text">
                            <span>500+</span>
                            <small>Happy Parents</small>
                        </div>
                    </div>
                    <div class="hero-floating-card card-2">
                        <div class="floating-icon blue">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div class="floating-text">
                            <span>100%</span>
                            <small>Safe Materials</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-section" aria-labelledby="featured-title">
        <div class="featured-container">
            <header class="section-header">
                <span class="section-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    Curated Selection
                </span>
                <h2 id="featured-title" class="section-title">Featured Products</h2>
                <p class="section-description">Handpicked with love for your little bundle of joy</p>
            </header>
            <div class="products-grid">
                @foreach($featuredProducts as $product)
                    <article class="product-card">
                        @if($product->created_at->gt(now()->subDays(7)))
                            <span class="product-badge badge-new">New</span>
                        @elseif($product->stock < 5)
                            <span class="product-badge badge-sale">Low Stock</span>
                        @endif
                        <a href="{{ route('products.show', $product->slug) }}" class="product-image">
                            @if($product->image)
                                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                            @else
                                <div class="placeholder-image" style="width: 100%; height: 100%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">
                                    Product Image
                                </div>
                            @endif
                        </a>
                        <div class="product-info">
                            <span class="product-category">{{ $product->category->name ?? 'Newborn to Toddler' }}</span>
                            <h3 class="product-name">
                                <a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            <div class="product-price-wrapper">
                                <span class="product-price">${{ number_format($product->price, 2) }}</span>
                            </div>
                            @auth
                                @if($product->stock > 0)
                                    <button class="btn-add-cart add-to-cart" data-product-id="{{ $product->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                        Add to Cart
                                    </button>
                                @else
                                    <button class="btn-add-cart" disabled>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                        Out of Stock
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn-add-cart">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                                    Add to Cart
                                </a>
                            @endauth
                        </div>
                    </article>
                @endforeach

                @if($featuredProducts->count() < 8)
                    @for($i = $featuredProducts->count(); $i < 8; $i++)
                        <article class="product-card">
                            <div class="product-image">
                                <div class="placeholder-image" style="width: 100%; height: 100%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">
                                    Product Image
                                </div>
                            </div>
                            <div class="product-info">
                                <span class="product-category">Coming Soon</span>
                                <h3 class="product-name">New Arrival</h3>
                                <div class="product-price-wrapper">
                                    <span class="product-price">$0.00</span>
                                </div>
                                <button class="btn-add-cart" disabled>Coming Soon</button>
                            </div>
                        </article>
                    @endfor
                @endif
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section" aria-labelledby="categories-title">
        <div class="categories-container">
            <header class="section-header">
                <span class="section-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    Browse
                </span>
                <h2 id="categories-title" class="section-title">Shop by Category</h2>
                <p class="section-description">Find everything your little one needs, organized with love</p>
            </header>
            <div class="categories-grid">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->id]) }}" class="category-card">
                        <div class="category-image">
                            @if($category->image)
                                <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" loading="lazy">
                            @else
                                <div class="placeholder-image" style="width: 100%; height: 100%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">
                                    Category Image
                                </div>
                            @endif
                        </div>
                        <div class="category-overlay">
                            <h3>{{ $category->name }}</h3>
                            <span class="category-count">{{ $category->products_count ?? 0 }} products</span>
                        </div>
                    </a>
                @endforeach
                <a href="{{ route('products.index') }}" class="category-card">
                    <div class="category-image">
                        <div class="placeholder-image" style="width: 100%; height: 100%; background: var(--light-gray); display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">
                            View All Image
                        </div>
                    </div>
                    <div class="category-overlay">
                        <h3>View All</h3>
                        <span class="category-count">Browse All</span>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Trust Section -->
    <section class="trust-section" aria-labelledby="trust-title">
        <div class="trust-container">
            <header class="section-header">
                <span class="section-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Promise
                </span>
                <h2 id="trust-title" class="section-title">Why Parents Trust Us</h2>
                <p class="section-description">Our commitment to your little one's comfort and safety</p>
            </header>
            <div class="trust-grid">
                <article class="trust-item">
                    <div class="trust-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/></svg>
                    </div>
                    <h3>Safe Materials</h3>
                    <p>We use only the softest, safest materials for your little one's delicate skin</p>
                </article>
                <article class="trust-item">
                    <div class="trust-icon pink">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    </div>
                    <h3>Quality First</h3>
                    <p>Every piece is crafted with attention to detail and durability</p>
                </article>
                <article class="trust-item">
                    <div class="trust-icon green">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
                    </div>
                    <h3>Comfort Matters</h3>
                    <p>Designed for little ones to move freely and comfortably</p>
                </article>
                <article class="trust-item">
                    <div class="trust-icon purple">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                    </div>
                    <h3>Sustainable</h3>
                    <p>Committed to eco-friendly practices for a better tomorrow</p>
                </article>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section" aria-labelledby="testimonials-title">
        <div class="testimonials-container">
            <header class="section-header">
                <span class="section-badge">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                    Love
                </span>
                <h2 id="testimonials-title" class="section-title">What Parents Say</h2>
                <p class="section-description">Stories from families who trust us with their little ones</p>
            </header>
            <div class="testimonials-grid">
                <article class="testimonial-card">
                    <div class="testimonial-stars" aria-label="5 out of 5 stars">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                    <blockquote class="testimonial-text">"The quality is amazing! My little one loves wearing these clothes. So soft and comfortable. I couldn't be happier with our purchase."</blockquote>
                    <footer class="testimonial-author">
                        <div class="author-avatar" aria-hidden="true">S</div>
                        <div class="author-info">
                            <cite class="author-name">Sarah M.</cite>
                            <span class="author-role">Mom of 2</span>
                        </div>
                    </footer>
                </article>
                <article class="testimonial-card">
                    <div class="testimonial-stars" aria-label="5 out of 5 stars">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                    <blockquote class="testimonial-text">"Beautiful designs and great customer service. Fast shipping too! The packaging was so cute, it made the whole experience special."</blockquote>
                    <footer class="testimonial-author">
                        <div class="author-avatar" aria-hidden="true">J</div>
                        <div class="author-info">
                            <cite class="author-name">James L.</cite>
                            <span class="author-role">Dad of 1</span>
                        </div>
                    </footer>
                </article>
                <article class="testimonial-card">
                    <div class="testimonial-stars" aria-label="5 out of 5 stars">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    </div>
                    <blockquote class="testimonial-text">"Finally found a brand I trust! The clothes wash well and last forever. My youngest has been wearing them and they're still in perfect condition."</blockquote>
                    <footer class="testimonial-author">
                        <div class="author-avatar" aria-hidden="true">E</div>
                        <div class="author-info">
                            <cite class="author-name">Emily R.</cite>
                            <span class="author-role">Mom of 3</span>
                        </div>
                    </footer>
                </article>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section" aria-labelledby="newsletter-title">
        <div class="newsletter-container">
            <div class="newsletter-content">
                <h2 id="newsletter-title" class="newsletter-title">Stay in the Loop</h2>
                <p>Subscribe to get special offers, free giveaways, and new arrivals delivered to your inbox</p>
                <form class="newsletter-form" action="#" method="POST">
                    <input type="email" name="email" placeholder="Enter your email address" required aria-label="Email address">
                    <button type="submit">Subscribe</button>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('.add-to-cart').click(function() {
        var productId = $(this).data('product-id');
        
        $.ajax({
            url: '{{ route("cart.add") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                $('#cartIcon').addClass('ping');
                setTimeout(function() {
                    $('#cartIcon').removeClass('ping');
                }, 600);
                if (typeof updateCartBadge === 'function') {
                    updateCartBadge(response.cart_count);
                }
                if (typeof showToast === 'function') {
                    showToast('success', 'Added to Cart', response.success);
                }
            },
            error: function(xhr) {
                if(xhr.status === 401) {
                    window.location.href = '{{ route("login") }}';
                } else {
                    alert(xhr.responseJSON.error || 'An error occurred');
                }
            }
        });
    });
});
</script>
@endpush
