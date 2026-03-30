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
                        <img src="{{ asset('storage/profile_images/' . $user->profile_image) }}" alt="Profile" class="profile-image">
                    @else
                        <div class="profile-image-placeholder">
                            {{ strtoupper(substr($user->first_name ?? $user->name, 0, 1)) }}
                        </div>
                    @endif
                    <input type="file" name="profile_image" id="profile_image" accept="image/*" class="profile-image-input">
                    <label for="profile_image" class="profile-image-label">Change Photo</label>
                </div>

                <div class="profile-form">
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

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" name="phone" id="phone" value="{{ $user->phone }}">
                    </div>

                    <div class="form-section-title">Shipping Address</div>

                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <input type="text" name="address" id="address" value="{{ $user->address }}">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" name="city" id="city" value="{{ $user->city }}">
                        </div>

                        <div class="form-group">
                            <label for="postal_code">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" value="{{ $user->postal_code }}">
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

                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>
        </div>
    </div>
</div>
@endsection
