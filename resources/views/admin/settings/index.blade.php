@extends('admin.layout.main')

@section('title', 'Admin Settings - Little Blessings')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/adminsettings.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Admin Settings</h1>
        <p>Manage your store information</p>
    </div>
    <div class="header-actions">
        <button type="button" class="btn btn-primary" id="edit-btn" onclick="enableEdit()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
            Edit Settings
        </button>
    </div>
</div>

<div class="settings-tabs">
    <button class="settings-tab active" onclick="showSection('store')">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
        Store Information
    </button>
    <button class="settings-tab" onclick="showSection('hero')">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
        Homepage Hero
    </button>
    <button class="settings-tab" onclick="showSection('notifications')">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
        Notifications
    </button>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" id="settings-form">
    @csrf
    @method('PUT')

    {{-- Store Information Section --}}
    <div class="settings-section" id="section-store">
        <div class="content-card">
            <h2>Store Logo & Branding</h2>
            <div class="form-group">
                <label class="form-label">Store Logo</label>
                <div class="image-upload">
                    @if($storeInfo->logo)
                        <img src="{{ asset('storage/logos/' . $storeInfo->logo) }}" alt="Store Logo" class="image-preview" id="logo-preview-img">
                    @else
                        <div class="image-placeholder" id="logo-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/*" class="file-input" id="logo-input" disabled>
                    <label for="logo-input" class="btn btn-secondary" id="logo-btn">Change Logo</label>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h2>Store Details</h2>
            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label">Store Name</label>
                    <input type="text" name="store_name" class="form-control" value="{{ old('store_name', $storeInfo->store_name) }}" required disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Store Email</label>
                    <input type="email" name="store_email" class="form-control" value="{{ old('store_email', $storeInfo->store_email) }}" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Store Phone</label>
                    <input type="text" name="store_phone" class="form-control" value="{{ old('store_phone', $storeInfo->store_phone) }}" disabled>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label">Store Address</label>
                    <textarea name="store_address" class="form-control" rows="2" disabled>{{ old('store_address', $storeInfo->store_address) }}</textarea>
                </div>
                <div class="form-group full-width">
                    <label class="form-label">Store Description</label>
                    <textarea name="store_description" class="form-control" rows="3" disabled>{{ old('store_description', $storeInfo->store_description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Homepage Hero Section --}}
    <div class="settings-section" id="section-hero" style="display: none;">
        <div class="content-card">
            <h2>Hero Content</h2>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Hero Title</label>
                    <input type="text" name="hero_title" class="form-control" value="{{ old('hero_title', $heroEdit->hero_title) }}" placeholder="e.g., Sweet Dreams Start Here" disabled>
                </div>
                <div class="form-group">
                    <label class="form-label">Hero Subtitle</label>
                    <input type="text" name="hero_subtitle" class="form-control" value="{{ old('hero_subtitle', $heroEdit->hero_subtitle) }}" placeholder="e.g., Discover the cutest and coziest outfits" disabled>
                </div>
            </div>
        </div>

        <div class="content-card">
            <h2>Hero Image</h2>
            <div class="hero-image-row">
                <div class="hero-image-preview">
                    @if($heroEdit->hero_image)
                        <img src="{{ asset('storage/hero/' . $heroEdit->hero_image) }}" alt="Hero Image" id="hero-preview-img">
                    @else
                        <div class="hero-placeholder" id="hero-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                            <span>No image</span>
                        </div>
                    @endif
                </div>
                <div class="hero-image-actions">
                    <label class="form-label">Upload New Image</label>
                    <input type="file" name="hero_image" accept="image/*" class="file-input" id="hero-input" disabled>
                    <label for="hero-input" class="btn btn-secondary" id="hero-btn">Choose File</label>
                    <small class="form-hint">Recommended: 600x500px or larger</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Notifications Section --}}
    <div class="settings-section" id="section-notifications" style="display: none;">
        <div class="content-card">
            <h2>Email Notifications</h2>
            <p class="card-description">Choose which events should trigger email notifications. Notifications will be sent to the email address below.</p>
            
            <div class="form-group">
                <label class="form-label">Notification Email</label>
                <input type="email" name="notification_email" class="form-control" value="{{ old('notification_email', $notifInfo->notification_email) }}" placeholder="admin@luxlittles.com" disabled>
                <small class="form-hint">Leave empty to use the store email</small>
            </div>

            <div class="notification-toggles">
                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Order</span>
                        <span class="toggle-description">Get notified when a new order is placed</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_order" value="1" {{ old('notif_new_order', $notifInfo->notif_new_order) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Low Stock Alert</span>
                        <span class="toggle-description">Get notified when product stock falls below threshold (5 items)</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_low_stock" value="1" {{ old('notif_low_stock', $notifInfo->notif_low_stock) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Out of Stock Alert</span>
                        <span class="toggle-description">Get notified when a product runs out of stock</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_out_of_stock" value="1" {{ old('notif_out_of_stock', $notifInfo->notif_out_of_stock) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Review</span>
                        <span class="toggle-description">Get notified when a customer leaves a product review</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_review" value="1" {{ old('notif_new_review', $notifInfo->notif_new_review) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Customer</span>
                        <span class="toggle-description">Get notified when a new customer registers</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_customer" value="1" {{ old('notif_new_customer', $notifInfo->notif_new_customer) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Weekly Analytics Report</span>
                        <span class="toggle-description">Receive a summary of orders, sales, and store performance every Monday</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_weekly_report" value="1" {{ old('notif_weekly_report', $notifInfo->notif_weekly_report) ? 'checked' : '' }} disabled>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions" id="form-actions" style="display: none;">
        <button type="button" class="btn btn-secondary" onclick="cancelEdit()">Cancel</button>
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Save Changes
        </button>
    </div>
</form>

@push('scripts')
<script>
function showSection(section) {
    document.getElementById('section-store').style.display = section === 'store' ? 'flex' : 'none';
    document.getElementById('section-hero').style.display = section === 'hero' ? 'flex' : 'none';
    document.getElementById('section-notifications').style.display = section === 'notifications' ? 'flex' : 'none';
    
    document.querySelectorAll('.settings-tab').forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
}

function enableEdit() {
    var inputs = document.querySelectorAll('#settings-form input, #settings-form textarea, #settings-form select');
    inputs.forEach(function(input) {
        input.disabled = false;
    });
    
    document.getElementById('edit-btn').style.display = 'none';
    document.getElementById('form-actions').style.display = 'flex';
}

function cancelEdit() {
    var inputs = document.querySelectorAll('#settings-form input, #settings-form textarea, #settings-form select');
    inputs.forEach(function(input) {
        input.disabled = true;
    });
    
    document.getElementById('edit-btn').style.display = 'inline-flex';
    document.getElementById('form-actions').style.display = 'none';
    
    // Reset form to original values
    document.getElementById('settings-form').reset();
}

document.getElementById('logo-input').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('logo-preview-img');
            var placeholder = document.getElementById('logo-placeholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.outerHTML = '<img src="' + e.target.result + '" class="image-preview" id="logo-preview-img">';
            }
        };
        reader.readAsDataURL(this.files[0]);
    }
});

document.getElementById('hero-input').addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var preview = document.getElementById('hero-preview-img');
            var placeholder = document.getElementById('hero-placeholder');
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.outerHTML = '<img src="' + e.target.result + '" id="hero-preview-img">';
            }
        };
        reader.readAsDataURL(this.files[0]);
    }
});

@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('success', 'Settings Updated', '{{ session('success') }}');
    });
@endif
</script>
@endpush

@endsection
