@extends('admin.layout.main')

@section('title', 'Analytics - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/analytics.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Analytics</h1>
        <p>Track your store performance</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Revenue</span>
            <span class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Orders</span>
            <span class="stat-value">{{ $stats['total_orders'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fce7f3; color: #db2777;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Total Products</span>
            <span class="stat-value">{{ $stats['total_products'] }}</span>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
        </div>
        <div class="stat-info">
            <span class="stat-label">Customers</span>
            <span class="stat-value">{{ $stats['total_customers'] }}</span>
        </div>
    </div>
</div>

<div class="content-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 60%;">Status</th>
                <th style="text-align: right;">Orders</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <span class="status-dot pending"></span>
                    <span>Pending</span>
                </td>
                <td style="text-align: right; font-weight: 600;">{{ $stats['pending_orders'] }}</td>
            </tr>
            <tr>
                <td>
                    <span class="status-dot processing"></span>
                    <span>Processing</span>
                </td>
                <td style="text-align: right; font-weight: 600;">{{ $stats['processing_orders'] }}</td>
            </tr>
            <tr>
                <td>
                    <span class="status-dot shipped"></span>
                    <span>Shipped</span>
                </td>
                <td style="text-align: right; font-weight: 600;">{{ $stats['shipped_orders'] }}</td>
            </tr>
            <tr>
                <td>
                    <span class="status-dot delivered"></span>
                    <span>Delivered</span>
                </td>
                <td style="text-align: right; font-weight: 600;">{{ $stats['delivered_orders'] }}</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="content-card" style="margin-top: 1.5rem;">
    <table class="admin-table">
        <thead>
            <tr>
                <th style="width: 60%;">Product</th>
                <th style="text-align: right;">Sold</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $product)
                <tr>
                    <td>
                        <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=50&h=50&fit=crop' }}" 
                             alt="{{ $product->product_name }}" class="item-thumb">
                        <span>{{ $product->product_name }}</span>
                    </td>
                    <td style="text-align: right; font-weight: 600;">{{ $product->total_sold }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: var(--text-light); padding: 2rem;">No products yet</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/analytics.css') }}">
@endpush

@endsection
