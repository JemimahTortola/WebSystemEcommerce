@extends('admin.layout.main')

@section('title', 'Admin Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Dashboard</h1>
        <p>Welcome back! Here's what's happening with your store.</p>
    </div>
    <div class="dashboard-date">{{ date('l, F j, Y') }}</div>
</div>

<div class="stats-overview">
    <div class="stat-card primary">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(124, 184, 124, 0.15); color: #7cb87c;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
            </div>
            <span class="stat-trend {{ $stats['revenue_change'] >= 0 ? 'positive' : 'negative' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    @if($stats['revenue_change'] >= 0)
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>
                    @else
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline>
                    @endif
                </svg>
                {{ abs($stats['revenue_change']) }}%
            </span>
        </div>
        <div class="stat-content">
            <span class="stat-value">${{ number_format($stats['total_revenue'], 2) }}</span>
            <span class="stat-label">Total Revenue</span>
        </div>
        <div class="stat-footer">
            <span class="stat-period">This Month: ${{ number_format($stats['monthly_revenue'], 2) }}</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
            </div>
            <span class="stat-trend {{ $stats['orders_change'] >= 0 ? 'positive' : 'negative' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    @if($stats['orders_change'] >= 0)
                    <polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline>
                    @else
                    <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline>
                    @endif
                </svg>
                {{ abs($stats['orders_change']) }}%
            </span>
        </div>
        <div class="stat-content">
            <span class="stat-value">{{ $stats['orders'] }}</span>
            <span class="stat-label">Total Orders</span>
        </div>
        <div class="stat-footer">
            <span class="stat-period">This Month: {{ $stats['monthly_orders'] }} orders</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(219, 39, 119, 0.1); color: #db2777;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-value">{{ $stats['products'] }}</span>
            <span class="stat-label">Products</span>
        </div>
        <div class="stat-footer">
            <span class="stat-period">{{ $stats['categories'] }} categories</span>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div class="stat-icon" style="background: rgba(217, 119, 6, 0.1); color: #d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
            </div>
        </div>
        <div class="stat-content">
            <span class="stat-value">{{ $stats['users'] }}</span>
            <span class="stat-label">Customers</span>
        </div>
        <div class="stat-footer">
            <span class="stat-period">+{{ $stats['monthly_customers'] }} this month</span>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-main">
        <div class="dashboard-card revenue-chart-card">
            <div class="card-header">
                <h3>Revenue Overview</h3>
                <div class="chart-legend">
                    <span class="legend-item"><span class="legend-dot" style="background: #7cb87c;"></span> 12-Month Trend</span>
                </div>
            </div>
            <div class="chart-container">
                <div class="chart-bars">
                    @foreach($monthlyRevenue['labels'] as $index => $label)
                        @php
                            $maxRevenue = max($monthlyRevenue['data']) ?: 1;
                            $height = ($monthlyRevenue['data'][$index] / $maxRevenue) * 100;
                        @endphp
                        <div class="chart-bar-wrapper">
                            <div class="chart-bar" style="height: {{ max($height, 5) }}@percnt;" title="${{ number_format($monthlyRevenue['data'][$index], 2) }}"></div>
                            <span class="chart-label">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="chart-summary">
                <div class="summary-item">
                    <span class="summary-label">12-Month Total</span>
                    <span class="summary-value">${{ number_format($monthlyRevenue['total'], 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Average/Month</span>
                    <span class="summary-value">${{ number_format($monthlyRevenue['total'] / 12, 2) }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Avg Order Value</span>
                    <span class="summary-value">${{ number_format($stats['avg_order_value'], 2) }}</span>
                </div>
            </div>
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Order Status</h3>
                <span class="card-badge">{{ $stats['orders'] }} Total</span>
            </div>
            <div class="order-status-grid">
                <div class="status-item">
                    <div class="status-header">
                        <span class="status-dot pending"></span>
                        <span class="status-name">Pending</span>
                    </div>
                    <span class="status-count">{{ $orderStatusBreakdown['pending']['count'] }}</span>
                    <div class="status-bar">
                        <div class="status-fill pending" style="width: {{ $orderStatusBreakdown['pending']['percentage'] }}%"></div>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-header">
                        <span class="status-dot processing"></span>
                        <span class="status-name">Processing</span>
                    </div>
                    <span class="status-count">{{ $orderStatusBreakdown['processing']['count'] }}</span>
                    <div class="status-bar">
                        <div class="status-fill processing" style="width: {{ $orderStatusBreakdown['processing']['percentage'] }}%"></div>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-header">
                        <span class="status-dot shipped"></span>
                        <span class="status-name">Shipped</span>
                    </div>
                    <span class="status-count">{{ $orderStatusBreakdown['shipped']['count'] }}</span>
                    <div class="status-bar">
                        <div class="status-fill shipped" style="width: {{ $orderStatusBreakdown['shipped']['percentage'] }}%"></div>
                    </div>
                </div>
                <div class="status-item">
                    <div class="status-header">
                        <span class="status-dot delivered"></span>
                        <span class="status-name">Delivered</span>
                    </div>
                    <span class="status-count">{{ $orderStatusBreakdown['delivered']['count'] }}</span>
                    <div class="status-bar">
                        <div class="status-fill delivered" style="width: {{ $orderStatusBreakdown['delivered']['percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="dashboard-sidebar">
        <div class="quick-actions-card">
            <h3>Quick Actions</h3>
            <div class="quick-actions">
                <a href="{{ route('admin.products.create') }}" class="quick-action">
                    <div class="action-icon" style="background: rgba(219, 39, 119, 0.1); color: #db2777;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    </div>
                    <span>Add Product</span>
                </a>
                <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="quick-action">
                    <div class="action-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                    </div>
                    <span>View Pending</span>
                </a>
                <a href="{{ route('admin.categories.create') }}" class="quick-action">
                    <div class="action-icon" style="background: rgba(34, 197, 94, 0.1); color: #22c55e;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path><line x1="12" y1="11" x2="12" y2="17"></line><line x1="9" y1="14" x2="15" y2="14"></line></svg>
                    </div>
                    <span>Add Category</span>
                </a>
                <a href="{{ route('admin.messages.index') }}" class="quick-action">
                    <div class="action-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <span>Messages</span>
                </a>
            </div>
        </div>

        <div class="dashboard-card alerts-card">
            <div class="card-header">
                <h3>Low Stock Alert</h3>
                <a href="{{ route('admin.inventory.index') }}" class="view-all">View Inventory</a>
            </div>
            <div class="alerts-list">
                @forelse($lowStockProducts as $product)
                    <div class="alert-item">
                        <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://via.placeholder.com/40' }}" 
                             alt="{{ $product->name }}" class="alert-thumb">
                        <div class="alert-info">
                            <span class="alert-name">{{ $product->name }}</span>
                            <span class="alert-stock {{ $product->stock == 0 ? 'out' : 'low' }}">
                                {{ $product->stock == 0 ? 'Out of Stock' : $product->stock . ' left' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>All products well stocked!</span>
                    </div>
                @endforelse
            </div>
            @if($quickStats['low_stock_count'] > 0 || $quickStats['out_of_stock_count'] > 0)
                <div class="alerts-summary">
                    <span class="alert-count warning">{{ $quickStats['low_stock_count'] }} low stock</span>
                    <span class="alert-count danger">{{ $quickStats['out_of_stock_count'] }} out of stock</span>
                </div>
            @endif
        </div>

        <div class="dashboard-card">
            <div class="card-header">
                <h3>Notifications</h3>
                <span class="card-badge">{{ $quickStats['pending_orders'] + $quickStats['pending_reviews'] + $quickStats['unread_messages'] }}</span>
            </div>
            <div class="notification-list">
                @if($quickStats['pending_orders'] > 0)
                    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="notification-item">
                        <div class="notif-icon" style="background: rgba(245, 158, 11, 0.1); color: #f59e0b;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path></svg>
                        </div>
                        <div class="notif-content">
                            <span class="notif-text">{{ $quickStats['pending_orders'] }} pending orders</span>
                            <span class="notif-action">Review now</span>
                        </div>
                    </a>
                @endif
                @if($quickStats['pending_reviews'] > 0)
                    <a href="{{ route('admin.reviews.index') }}" class="notification-item">
                        <div class="notif-icon" style="background: rgba(219, 39, 119, 0.1); color: #db2777;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                        </div>
                        <div class="notif-content">
                            <span class="notif-text">{{ $quickStats['pending_reviews'] }} reviews awaiting approval</span>
                            <span class="notif-action">Review now</span>
                        </div>
                    </a>
                @endif
                @if($quickStats['unread_messages'] > 0)
                    <a href="{{ route('admin.messages.index') }}" class="notification-item">
                        <div class="notif-icon" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <div class="notif-content">
                            <span class="notif-text">{{ $quickStats['unread_messages'] }} unread messages</span>
                            <span class="notif-action">View messages</span>
                        </div>
                    </a>
                @endif
                @if($quickStats['pending_orders'] == 0 && $quickStats['pending_reviews'] == 0 && $quickStats['unread_messages'] == 0)
                    <div class="empty-notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span>All caught up!</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="bottom-section">
    <div class="dashboard-card">
        <div class="card-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="view-all">View All Orders</a>
        </div>
        <div class="orders-table">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr onclick="window.location='{{ route('admin.orders.show', $order->id) }}'" style="cursor: pointer;">
                            <td>
                                <span class="order-number">#{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div class="customer-cell">
                                    <div class="customer-avatar">{{ substr($order->user->name, 0, 1) }}</div>
                                    <span>{{ $order->user->name }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td>
                                <span class="items-count">{{ $order->items->count() }} items</span>
                            </td>
                            <td>
                                <span class="order-total">${{ number_format($order->total_amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="order-date">{{ $order->created_at->format('M j, Y') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty-state">No orders yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="card-header">
            <h3>Top Selling Products</h3>
            <a href="{{ route('admin.products.index') }}" class="view-all">View All Products</a>
        </div>
        <div class="top-products">
            @forelse($topProducts as $product)
                <div class="top-product-item">
                    <img src="{{ $product->image ? asset('storage/products/' . $product->image) : 'https://via.placeholder.com/50' }}" 
                         alt="{{ $product->product_name }}" class="product-thumb">
                    <div class="product-info">
                        <span class="product-name">{{ $product->product_name }}</span>
                        <span class="product-price">${{ number_format($product->price, 2) }}</span>
                    </div>
                    <div class="product-stats">
                        <span class="product-sold">{{ $product->total_sold }} sold</span>
                        <span class="product-revenue">${{ number_format($product->total_revenue, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-state">No products sold yet</div>
            @endforelse
        </div>
    </div>
</div>

<div class="dashboard-card activity-card">
    <div class="card-header">
        <h3>Recent Activity</h3>
        <a href="{{ route('admin.analytics') }}" class="view-all">View Analytics</a>
    </div>
    <div class="activity-timeline">
        @forelse($recentActivity as $activity)
            <a href="{{ $activity['link'] }}" class="activity-item">
                <div class="activity-icon activity-{{ $activity['type'] }}">
                    @if($activity['icon'] == 'cart')
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                    @elseif($activity['icon'] == 'star')
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                    @elseif($activity['icon'] == 'message')
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    @endif
                </div>
                <div class="activity-content">
                    <span class="activity-message">{{ $activity['message'] }}</span>
                    <span class="activity-sub">{{ $activity['submessage'] }}</span>
                </div>
                <span class="activity-time">{{ $activity['time']->diffForHumans() }}</span>
            </a>
        @empty
            <div class="empty-state">No recent activity</div>
        @endforelse
    </div>
</div>
@endsection
