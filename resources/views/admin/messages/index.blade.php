@extends('admin.layout.main')

@section('title', 'Messages - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/messages.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Messages</h1>
        <p>Conversations with customers</p>
    </div>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">Customer</span>
        <span class="header-item">Subject</span>
        <span class="header-item">Last Message</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @forelse($conversations as $conversation)
            <div class="list-item">
                <span class="item-customer">
                    <span class="avatar">{{ substr($conversation->user->first_name ?? $conversation->user->name, 0, 1) }}</span>
                    <span class="name">{{ $conversation->user->first_name }} {{ $conversation->user->last_name }}</span>
                </span>
                <span class="item-subject">{{ $conversation->subject ?? 'No subject' }}</span>
                <span class="item-time">
                    @if($conversation->lastMessage)
                        {{ $conversation->lastMessage->created_at->diffForHumans() }}
                    @else
                        -
                    @endif
                </span>
                <span class="item-action">
                    <a href="{{ route('admin.messages.show', $conversation->id) }}" class="btn btn-sm btn-secondary">View</a>
                </span>
            </div>
        @empty
            <div class="empty-state">No messages yet</div>
        @endforelse
    </div>
</div>
@endsection
