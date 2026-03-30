@extends('admin.layout.main')

@section('title', 'Order Details - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/orders-show.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Order #{{ $order->order_number }}</h1>
        <p>{{ $order->created_at->format('F d, Y \\a\\t g:i A') }}</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">← Back to Orders</a>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background: rgba(107, 158, 111, 0.1); border: 1px solid #6B9E6F; color: #4F7D52; padding: 1rem; border-radius: 8px; margin-bottom: 24px;">
        {{ session('success') }}
    </div>
@endif

<div class="order-invoice">
    <div class="invoice-header">
        <div class="invoice-customer">
            <div class="header-row">
                <h4>Customer</h4>
                @if($order->is_archived)
                    <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">Restore Order</button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.orders.archive', $order->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">Archive Order</button>
                    </form>
                @endif
            </div>
            <p><strong>{{ $order->user->name }}</strong></p>
            <p>{{ $order->user->email }}</p>
            <p>{{ $order->shipping_phone }}</p>
        </div>
        <div class="invoice-shipping">
            <h4>Shipping Address</h4>
            <p>{{ $order->shipping_address }}</p>
        </div>
        <div class="invoice-status">
            <div class="status-item">
                <span>Order Status</span>
                <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                    @csrf @method('PUT')
                    <select name="status" class="status-select" onchange="this.form.submit()">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </form>
            </div>
            <div class="status-item">
                <span>Payment</span>
                <form method="POST" action="{{ route('admin.orders.updatePaymentStatus', $order->id) }}">
                    @csrf @method('PUT')
                    <select name="payment_status" class="status-select" onchange="this.form.submit()">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </form>
            </div>
        </div>
    </div>

    <div class="invoice-items">
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div class="product-cell">
                                <span>{{ $item->product_name }}</span>
                            </div>
                        </td>
                        <td>${{ number_format($item->price, 2) }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-right">Subtotal</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Shipping</td>
                    <td>$0.00</td>
                </tr>
                <tr class="total-row">
                    <td colspan="3" class="text-right"><strong>Total</strong></td>
                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($order->notes)
    <div class="invoice-notes">
        <h4>Notes</h4>
        <p>{{ $order->notes }}</p>
    </div>
    @endif
</div>

<div class="tracking-card" style="margin-top: 24px;">
    <h3>
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        Shipping Tracking
    </h3>
    
    <form method="POST" action="{{ route('admin.orders.addTracking', $order->id) }}" style="margin-bottom: 20px;">
        @csrf
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Tracking Number</label>
                <input type="text" name="tracking_number" class="form-control" value="{{ $order->tracking_number ?? '' }}" placeholder="Enter tracking number">
            </div>
            <div class="form-group">
                <label class="form-label">Courier</label>
                <input type="text" name="courier" class="form-control" value="{{ $order->courier ?? '' }}" placeholder="e.g., FedEx, UPS, USPS">
            </div>
            <div class="form-group">
                <label class="form-label">Estimated Delivery</label>
                <input type="date" name="estimated_delivery" class="form-control" value="{{ $order->estimated_delivery ? date('Y-m-d', strtotime($order->estimated_delivery)) : '' }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update Tracking Info</button>
    </form>

    <div class="add-tracking-form">
        <h4>Add Tracking Update</h4>
        <form method="POST" action="{{ route('admin.orders.addTrackingStatus', $order->id) }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="">Select Status</option>
                        <option value="order_placed">Order Placed</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="out_for_delivery">Out for Delivery</option>
                        <option value="delivered">Delivered</option>
                        <option value="on_hold">On Hold</option>
                        <option value="returned">Returned</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" placeholder="e.g., Distribution Center, NY">
                </div>
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g., Package Shipped">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Enter tracking update details..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Add Update</button>
        </form>
    </div>

    @if($order->tracking->count() > 0)
        <div class="tracking-timeline" style="margin-top: 24px;">
            <h4>Tracking History</h4>
            <div class="timeline-list">
                @foreach($order->tracking as $track)
                    <div class="timeline-item">
                        <div class="timeline-marker"></div>
                        <div class="timeline-content">
                            <span class="timeline-status">{{ \App\Models\ShippingTracking::getStatusLabel($track->status) }}</span>
                            @if($track->title)
                                <p class="timeline-description"><strong>{{ $track->title }}</strong></p>
                            @endif
                            @if($track->description)
                                <p class="timeline-description">{{ $track->description }}</p>
                            @endif
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

@endsection
