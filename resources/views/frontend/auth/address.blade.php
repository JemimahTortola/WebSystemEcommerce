@extends('frontend.layouts.main')

@section('title', 'Add Address - Lux Littles')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/pages/addressregister.css') }}">
@endsection

@section('content')
<main class="address-page">
    <div class="address-container">
        <div class="address-header">
            <h2>Add Shipping Address</h2>
            <p>Please provide your shipping address to complete your profile.</p>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('address.save') }}" class="address-form">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="+1 234 567 8900">
                @error('phone')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Street Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $user->address) }}" placeholder="123 Main Street, Apt 4B">
                @error('address')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->city) }}" required placeholder="New York">
                    @error('city')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $user->postal_code) }}" required placeholder="10001">
                    @error('postal_code')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Country</label>
                <select name="country" class="form-control" required>
                    <option value="">Select a country</option>
                    <option value="United States" {{ old('country', $user->country) == 'United States' ? 'selected' : '' }}>United States</option>
                    <option value="United Kingdom" {{ old('country', $user->country) == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                    <option value="Canada" {{ old('country', $user->country) == 'Canada' ? 'selected' : '' }}>Canada</option>
                    <option value="Australia" {{ old('country', $user->country) == 'Australia' ? 'selected' : '' }}>Australia</option>
                    <option value="Philippines" {{ old('country', $user->country) == 'Philippines' ? 'selected' : '' }}>Philippines</option>
                </select>
                @error('country')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">Save Address</button>
        </form>

        <div class="address-footer">
            <form action="{{ route('address.skip') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="skip-btn">Skip for now</button>
            </form>
        </div>
    </div>
</main>
@endsection
