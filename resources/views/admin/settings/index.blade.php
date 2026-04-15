@extends('admin.layout.main')

@section('title', 'Admin Settings - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/adminsettings.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Notification Settings</h1>
        <p>Manage your email notification preferences</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" id="settings-form">
    @csrf
    @method('PUT')

    <div class="settings-section">
        <div class="content-card">
            <h2>Email Notifications</h2>
            <p class="card-description">Choose which events should trigger email notifications.</p>
            
            <div class="form-group">
                <label class="form-label">Notification Email</label>
                <input type="email" name="notification_email" class="form-control" value="{{ old('notification_email', 'admin@tinythreads.com') }}" placeholder="admin@tinythreads.com">
            </div>

            <div class="notification-toggles">
                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Order</span>
                        <span class="toggle-description">Get notified when a new order is placed</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_order" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Low Stock Alert</span>
                        <span class="toggle-description">Get notified when product stock falls below threshold</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_low_stock" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Out of Stock Alert</span>
                        <span class="toggle-description">Get notified when a product runs out of stock</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_out_of_stock" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Review</span>
                        <span class="toggle-description">Get notified when a customer leaves a product review</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_review" value="1" checked>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">New Customer</span>
                        <span class="toggle-description">Get notified when a new customer registers</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_new_customer" value="1">
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <div class="toggle-item">
                    <div class="toggle-info">
                        <span class="toggle-label">Weekly Analytics Report</span>
                        <span class="toggle-description">Receive a summary of orders and sales every Monday</span>
                    </div>
                    <label class="toggle-switch">
                        <input type="checkbox" name="notif_weekly_report" value="1">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
            Save Changes
        </button>
    </div>
</form>

@push('scripts')
<script>
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('success', 'Settings Updated', '{{ session('success') }}');
    });
@endif
</script>
@endpush

@endsection
