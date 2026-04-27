@extends('layouts.admin')

@section('title', 'Dashboard - Flourista Admin')

@section('page-title', 'Dashboard')

@section('content')
<div class="dashboard-header">
    <div>
        <h1>Welcome back, {{ Auth::user()->name ?? 'Admin' }}</h1>
        <p>Here's what's happening with your store today.</p>
    </div>
</div>

<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-info">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value" id="totalRevenue">₱0.00</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-info">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value" id="totalOrders">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Customers</div>
            <div class="stat-value" id="totalCustomers">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🌸</div>
        <div class="stat-info">
            <div class="stat-label">Total Products</div>
            <div class="stat-value" id="totalProducts">0</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card wide">
        <h3>Recent Orders</h3>
        <div class="recent-orders" id="recentOrders">
            <p class="empty-state-text">Loading orders...</p>
        </div>
        <a href="{{ route('admin.orders') }}" class="view-all-link">View All Orders →</a>
    </div>

    <div class="dashboard-card">
        <h3>Top Products</h3>
        <div class="top-products" id="topProducts">
            <p class="empty-state-text">Loading products...</p>
        </div>
    </div>

    <div class="dashboard-card">
        <h3>Inventory Status</h3>
        <div class="inventory-status" id="inventoryStatus">
            <p class="empty-state-text">Loading inventory...</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection