@extends('frontend.layouts.main')

@section('title', 'Settings - Little Blessings')

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
    </div>
</div>
@endsection
