@extends('frontend.layouts.main')

@section('title', 'Settings - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/settings.css') }}">
@endpush

@section('content')
<div class="settings-page">
    <div class="container">
        <h1>Account Settings</h1>
        
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="settings-card">
            <h3 class="settings-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                Notification Preferences
            </h3>

            <form method="POST" action="{{ route('settings.update') }}" id="settings-form">
                @csrf
                @method('PUT')

                <div class="settings-toggle">
                    <div class="settings-toggle-info">
                        <h4>Email Notifications</h4>
                        <p>Receive updates about your orders via email</p>
                    </div>
                    <label class="settings-toggle-switch">
                        <input type="checkbox" name="email_notifications" value="1" {{ $user->email_notifications ?? true ? 'checked' : '' }} onchange="document.getElementById('settings-form').submit()">
                        <span class="settings-toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle">
                    <div class="settings-toggle-info">
                        <h4>SMS Notifications</h4>
                        <p>Receive order updates via text message</p>
                    </div>
                    <label class="settings-toggle-switch">
                        <input type="checkbox" name="sms_notifications" value="1" {{ $user->sms_notifications ?? false ? 'checked' : '' }} onchange="document.getElementById('settings-form').submit()">
                        <span class="settings-toggle-slider"></span>
                    </label>
                </div>

                <div class="settings-toggle">
                    <div class="settings-toggle-info">
                        <h4>Marketing Emails</h4>
                        <p>Receive promotional offers and newsletters</p>
                    </div>
                    <label class="settings-toggle-switch">
                        <input type="checkbox" name="marketing_emails" value="1" {{ $user->marketing_emails ?? false ? 'checked' : '' }} onchange="document.getElementById('settings-form').submit()">
                        <span class="settings-toggle-slider"></span>
                    </label>
                </div>
            </form>
        </div>

        <div class="settings-card">
            <h3 class="settings-section-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Your Data
            </h3>
            <p class="settings-description">Download or delete your personal data.</p>
            <div class="settings-data-actions">
                <form method="POST" action="{{ route('data.export') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Download My Data
                    </button>
                </form>
                <button type="button" class="btn btn-danger" onclick="showDeleteModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="modal" style="display: none;">
    <div class="modal-content">
        <h3>Delete Account</h3>
        <p>Are you sure you want to delete your account? This action cannot be undone.</p>
        <form method="POST" action="{{ route('account.delete') }}">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="password">Enter your password to confirm</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-outline" onclick="hideDeleteModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete Account</button>
            </div>
        </form>
    </div>
</div>

<style>
.settings-data-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}
.settings-description {
    color: #666;
    font-size: 0.9rem;
    margin-bottom: 1rem;
}
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}
.modal-content {
    background: #fff;
    padding: 2rem;
    border-radius: 8px;
    max-width: 400px;
    width: 100%;
}
.modal-content h3 {
    margin-top: 0;
}
.modal-actions {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}
.btn-danger {
    background: #dc2626;
    color: #fff;
    border: none;
}
.btn-danger:hover {
    background: #b91c1c;
}
</style>

<script>
function showDeleteModal() {
    document.getElementById('deleteModal').style.display = 'flex';
}
function hideDeleteModal() {
    document.getElementById('deleteModal').style.display = 'none';
}
</script>
@endsection
