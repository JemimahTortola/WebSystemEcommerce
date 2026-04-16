@extends('frontend.layouts.main')

@section('title', 'My Profile - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/profile.css') }}">
@endpush

@section('content')
<div class="profile-page">
    <div class="container">
        <h1>My Profile</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="profile-card">
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="profile-image-section">
                    @if($user->profile_image)
                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Profile" class="profile-image" loading="lazy">
                    @else
                        <div class="profile-image-placeholder">
                            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                        </div>
                    @endif
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="profile-image-input">
                    <label for="profile_image" class="profile-image-label">Change Photo</label>
                </div>

                <div class="profile-form">
                    <div class="form-section">
                        <div class="form-section-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span>Personal Information</span>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" value="{{ $user->username }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" value="{{ $user->email }}" required>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <span>Contact Information</span>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ $user->phone }}" placeholder="Enter your phone number">
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-header">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                <circle cx="12" cy="10" r="3"></circle>
                            </svg>
                            <span>Shipping Address</span>
                        </div>
                        <div class="form-group">
                            <label for="address">Street Address</label>
                            <input type="text" name="address" id="address" value="{{ $user->address }}" placeholder="Enter your street address">
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" name="city" id="city" value="{{ $user->city }}" placeholder="Enter your city">
                            </div>

                            <div class="form-group">
                                <label for="postal_code">Postal Code</label>
                                <input type="text" name="postal_code" id="postal_code" value="{{ $user->postal_code }}" placeholder="Enter postal code">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="country">Country</label>
                            <select name="country" id="country">
                                <option value="">Select a country</option>
                                <option value="United States" {{ $user->country == 'United States' ? 'selected' : '' }}>United States</option>
                                <option value="United Kingdom" {{ $user->country == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                                <option value="Canada" {{ $user->country == 'Canada' ? 'selected' : '' }}>Canada</option>
                                <option value="Australia" {{ $user->country == 'Australia' ? 'selected' : '' }}>Australia</option>
                                <option value="Philippines" {{ $user->country == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
