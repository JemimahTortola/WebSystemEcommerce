@extends('frontend.layouts.main')

@section('title', 'Order Details - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
@endpush

@section('content')
<main class="order-detail-page">
    <div class="order-detail-container">
        <a href="{{ route('orders.index') }}" class="back-to-orders">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
            Back to Orders
        </a>

        <div class="order-header-card">
            <h2>Order #{{ $order->order_number }}</h2>
            <div class="order-status-row">
                <span class="order-status {{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="payment-status {{ $order->payment_status }}">
                    Payment: {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>

        @if($order->hasTracking())
            <div class="tracking-card">
                <h3>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                    Shipment Tracking
                </h3>
                
                @if($order->latestTracking)
                    <div class="tracking-status-display">
                        <div class="tracking-status-icon">
                            @if($order->status === 'delivered')
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                            @elseif($order->status === 'shipped')
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                            @elseif($order->status === 'processing')
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            @endif
                        </div>
                        <div class="tracking-status-info">
                            <span class="tracking-current-status">{{ \App\Models\ShippingTracking::getStatusLabel($order->latestTracking->status) }}</span>
                            <p class="tracking-description">{{ $order->latestTracking->description ?? 'Your order is being processed.' }}</p>
                        </div>
                    </div>
                @endif

                <div class="tracking-progress-bar">
                    <div class="tracking-progress-fill" style="width: {{ $order->getStatusProgress() }}%"></div>
                </div>

                <div class="tracking-progress-steps">
                    <div class="progress-step {{ in_array($order->status, ['pending', 'processing', 'shipped', 'delivered']) ? 'active' : '' }} {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                        <div class="step-dot"></div>
                        <span>Order Placed</span>
                    </div>
                    <div class="progress-step {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'active' : '' }} {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                        <div class="step-dot"></div>
                        <span>Processing</span>
                    </div>
                    <div class="progress-step {{ in_array($order->status, ['shipped', 'delivered']) ? 'active' : '' }} {{ $order->status === 'delivered' ? 'completed' : '' }}">
                        <div class="step-dot"></div>
                        <span>Shipped</span>
                    </div>
                    <div class="progress-step {{ $order->status === 'delivered' ? 'active' : '' }} {{ $order->status === 'delivered' ? 'completed' : '' }}">
                        <div class="step-dot"></div>
                        <span>Delivered</span>
                    </div>
                </div>

                <div class="tracking-details">
                    @if($order->tracking_number)
                        <div class="tracking-detail-item">
                            <span class="detail-label">Tracking Number:</span>
                            <span class="detail-value">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                    @if($order->courier)
                        <div class="tracking-detail-item">
                            <span class="detail-label">Courier:</span>
                            <span class="detail-value">{{ $order->courier }}</span>
                        </div>
                    @endif
                    @if($order->estimated_delivery)
                        <div class="tracking-detail-item">
                            <span class="detail-label">Estimated Delivery:</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>

                @if($order->tracking->count() > 0)
                    <div class="tracking-timeline">
                        <h4>Tracking History</h4>
                        <div class="timeline-list">
                            @foreach($order->tracking as $track)
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <span class="timeline-status">{{ \App\Models\ShippingTracking::getStatusLabel($track->status) }}</span>
                                        <p class="timeline-description">{{ $track->description }}</p>
                                        @if($track->location)
                                            <span class="timeline-location">{{ $track->location }}</span>
                                        @endif
                                        <span class="timeline-date">{{ $track->created_at->format('M d, Y - h:i A') }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="order-info-grid">
            <div class="order-info-card">
                <h3>Shipping Information</h3>
                <p><strong>Name:</strong> {{ $order->shipping_name }}</p>
                <p><strong>Phone:</strong> {{ $order->shipping_phone }}</p>
                <p><strong>Address:</strong> {{ $order->shipping_address }}</p>
                @if($order->notes)
                    <p><strong>Notes:</strong> {{ $order->notes }}</p>
                @endif
            </div>

            <div class="order-info-card">
                <h3>Order Summary</h3>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                @if($order->payment_status === 'paid')
                    <p><strong>Paid:</strong> Yes</p>
                @endif
            </div>
        </div>

        <div class="order-items-card">
            <h3>Order Items</h3>
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="product-name">{{ $item->product_name }}</td>
                            <td class="product-price">${{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="product-subtotal">${{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="total-label"><strong>Total:</strong></td>
                        <td class="total-value">${{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</main>
@endsection