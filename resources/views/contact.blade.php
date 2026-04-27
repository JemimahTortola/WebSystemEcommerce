@extends('layouts.app')

@section('title', 'Contact Us - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/contact.css') }}">
@endsection

@section('content')
<div class="contact-page">
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="hero-content">
            <h1>Contact Us</h1>
            <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>
    </section>

    <div class="container">
        <div class="contact-grid">
            <!-- Contact Info -->
            <div class="contact-info">
                <div class="info-card">
                    <h2>Get in Touch</h2>
                    <p class="info-intro">Have a question or need help? Reach out to us through any of these channels.</p>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon">📍</div>
                            <div class="info-details">
                                <h3>Visit Us</h3>
                                <p>Butuan City, Philippines</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">📞</div>
                            <div class="info-details">
                                <h3>Call Us</h3>
                                <p>+63 XXX-XXX-XXXX</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">✉️</div>
                            <div class="info-details">
                                <h3>Email Us</h3>
                                <p>hello@flourista.com</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon">🕐</div>
                            <div class="info-details">
                                <h3>Shop Hours</h3>
                                <p>Mon - Sat: 8:00 AM - 6:00 PM</p>
                                <p>Sunday: 9:00 AM - 2:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="social-card">
                    <h3>Follow Us</h3>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">f</a>
                        <a href="#" aria-label="Instagram">📷</a>
                        <a href="#" aria-label="Twitter">𝕏</a>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form -->
            <div class="contact-form-wrapper">
                <div class="form-card">
                    <h2>Send Us a Message</h2>
                    <p class="form-intro">Fill out the form below and we'll get back to you within 24 hours.</p>
                    
                    <div id="alertContainer"></div>
                    
                    <form id="contactForm" method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="name">Full Name</label>
                                <input type="text" id="name" name="name" class="form-input" placeholder="Your name" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label" for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" placeholder="your@email.com" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="phone">Phone Number (Optional)</label>
                            <input type="tel" id="phone" name="phone" class="form-input" placeholder="+63 XXX-XXX-XXXX">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea id="message" name="message" class="form-input" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-submit">
                            <span class="btn-text">Send Message</span>
                            <span class="btn-icon">→</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/contact.js') }}"></script>
@endsection