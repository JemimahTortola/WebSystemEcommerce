@extends('admin.layout.main')

@section('title', 'Conversation - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/messages.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="header-back">
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Back
        </a>
    </div>
    <div>
        <h1>{{ $conversation->user->first_name }} {{ $conversation->user->last_name }}</h1>
        <p>{{ $conversation->subject ?? 'Conversation' }}</p>
    </div>
</div>

<div class="messages-container">
    <div class="messages-list">
        @forelse($conversation->messages as $message)
            <div class="message-item {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                <div class="message-bubble">
                    <p>{{ $message->content }}</p>
                    <span class="message-time">{{ $message->created_at->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        @empty
            <div class="no-messages">No messages in this conversation</div>
        @endforelse
    </div>

    <div class="message-input-container">
        <form method="POST" action="{{ route('admin.messages.send', $conversation->id) }}" class="message-form">
            @csrf
            <textarea name="content" class="form-control" rows="2" placeholder="Type your message..." required></textarea>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>
    </div>
</div>
@endsection
