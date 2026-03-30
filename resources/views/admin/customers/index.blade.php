@extends('admin.layout.main')

@section('title', 'Customers - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customers.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Customers</h1>
        <p>Manage your customers</p>
    </div>
    <form method="GET" action="{{ route('admin.customers.index') }}" class="header-filters">
        <input type="text" name="search" class="form-control" placeholder="Search customers..." value="{{ $search ?? '' }}">
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">Customer</span>
        <span class="header-item">Username</span>
        <span class="header-item">Phone</span>
        <span class="header-item">Orders</span>
        <span class="header-item">Joined</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @foreach($customers as $customer)
            <div class="list-item">
                <div class="item-customer">
                    <span class="item-avatar">{{ substr($customer->first_name ?? $customer->name, 0, 1) }}</span>
                    <div class="item-info">
                        <span class="item-name">{{ $customer->first_name }} {{ $customer->last_name }}</span>
                        <span class="item-email">{{ $customer->email }}</span>
                    </div>
                </div>
                <span class="item-username">{{ $customer->username }}</span>
                <span class="item-phone">{{ $customer->phone ?? '-' }}</span>
                <span class="item-orders">{{ $customer->orders->count() }}</span>
                <span class="item-date">{{ $customer->created_at->format('M d, Y') }}</span>
                <span class="item-action">
                    <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-secondary">View</a>
                </span>
            </div>
        @endforeach

        @if($customers->isEmpty())
            <div class="empty-state">No customers found</div>
        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/customers.css') }}">
@endpush

@endsection
