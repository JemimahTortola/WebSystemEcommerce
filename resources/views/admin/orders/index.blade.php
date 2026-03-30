@extends('admin.layout.main')

@section('title', 'Orders - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Orders</h1>
        <p>Manage customer orders</p>
    </div>
    <div class="header-actions">
        <div class="header-filters-wrapper">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="header-filters">
                <input type="text" name="search" class="form-control" placeholder="Search order number or customer..." value="{{ request('search') }}">
                <select name="status" class="form-control">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
        <form method="GET" action="{{ route('admin.orders.index') }}" class="archive-toggle-form">
            <span class="toggle-label">Archived</span>
            <button type="submit" class="archive-toggle {{ $filter === 'archived' ? 'archived' : '' }}">
                <div class="toggle-track">
                    <div class="toggle-thumb"></div>
                </div>
            </button>
            <input type="hidden" name="filter" value="{{ $filter === 'archived' ? 'active' : 'archived' }}">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>
    </div>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">Order #</span>
        <span class="header-item">Customer</span>
        <span class="header-item">Date</span>
        <span class="header-item">Items</span>
        <span class="header-item">Total</span>
        <span class="header-item">Status</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @foreach($orders as $order)
            <div class="list-item">
                <span class="item-id">#{{ $order->order_number }}</span>
                <span class="item-customer">{{ $order->user->name }}</span>
                <span class="item-date">{{ $order->created_at->format('M d, Y') }}</span>
                <span class="item-items">{{ $order->items->count() }} items</span>
                <span class="item-total">${{ number_format($order->total_amount, 2) }}</span>
                <span class="item-status">
                    @if($order->is_archived && !in_array($order->status, ['delivered', 'cancelled']))
                        <span class="status-badge archived">Archived</span>
                    @else
                        <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
                    @endif
                </span>
                <span class="item-action">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-secondary">View</a>
                </span>
            </div>
        @endforeach

        @if($orders->isEmpty())
            <div class="empty-state">
                @if($filter === 'archived')
                    No archived orders
                @else
                    No orders found
                @endif
            </div>
        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/orders.css') }}">
@endpush

@endsection
