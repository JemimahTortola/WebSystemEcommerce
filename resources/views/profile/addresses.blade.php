@extends('layouts.app')

@section('title', 'My Addresses - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/addresses.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <h1>My Addresses</h1>
    </div>

    <div id="alertContainer">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
        @endif
    </div>

    <div class="addresses-container">
        <div class="addresses-grid">
            @forelse($addresses as $address)
            <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                @if($address->is_default)
                <span class="default-badge">Default</span>
                @endif
                <div class="address-details">
                    <div class="address-name">{{ $address->full_name }}</div>
                    <div class="address-phone">{{ $address->phone }}</div>
                    <div class="address-text">{{ $address->address }}</div>
                    <div class="address-city">{{ $address->city }}{{ $address->postal_code ? ', ' . $address->postal_code : '' }}</div>
                    @if($address->country)
                    <div class="address-country">{{ $address->country }}</div>
                    @endif
                </div>
                <div class="address-actions">
                    @if(!$address->is_default)
                    <form method="POST" action="{{ route('profile.addresses.setDefault', $address->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-default">Set as Default</button>
                    </form>
                    @endif
                    <button class="btn btn-edit" onclick="editAddress({{ $address->id }}, '{{ $address->full_name }}', '{{ $address->phone }}', '{{ $address->address }}', '{{ $address->city }}', '{{ $address->postal_code }}', '{{ $address->country }}')">Edit</button>
                    <form method="POST" action="{{ route('profile.addresses.destroy', $address->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">📍</div>
                <h3>No Addresses Yet</h3>
                <p>Add an address for faster checkout.</p>
            </div>
            @endforelse
        </div>

        <div class="address-form-card">
            <h2 id="formTitle">Add New Address</h2>
            <form method="POST" id="addressForm" action="{{ route('profile.addresses.store') }}">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="address_id" id="addressId">

                <div class="form-group">
                    <label for="full_name">Full Name</label>
                    <input type="text" name="full_name" id="full_name" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone" required>
                </div>

                <div class="form-group">
                    <label for="address">Address</label>
                    <textarea name="address" id="address" rows="2" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" required>
                    </div>
                    <div class="form-group">
                        <label for="postal_code">Postal Code</label>
                        <input type="text" name="postal_code" id="postal_code">
                    </div>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" placeholder="Philippines">
                </div>

                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_default" value="1">
                        Set as default address
                    </label>
                </div>

                <button type="submit" class="btn btn-primary" id="submitBtn">Add Address</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editAddress(id, fullName, phone, address, city, postalCode, country) {
    document.getElementById('formTitle').textContent = 'Edit Address';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('addressForm').action = '/profile/addresses/' + id;
    document.getElementById('addressId').value = id;
    document.getElementById('full_name').value = fullName;
    document.getElementById('phone').value = phone;
    document.getElementById('address').value = address;
    document.getElementById('city').value = city;
    document.getElementById('postal_code').value = postalCode || '';
    document.getElementById('country').value = country || '';
    document.getElementById('submitBtn').textContent = 'Update Address';
}
</script>
@endsection