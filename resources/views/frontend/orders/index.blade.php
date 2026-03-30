@extends('frontend.layouts.main')

@section('title', 'My Orders - TinyThreads')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/orders.css') }}">
@endpush

@section('content')
<main class="orders-page">
    <div class="orders-container">
        <header class="orders-header">
            <h1 class="orders-title">My Orders</h1>
        </header>

        @if(session('success'))
            <div class="order-success-message">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count() > 0)
            <div class="orders-card">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order Number</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="order-number">{{ $order->order_number }}</td>
                                <td class="order-date">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="order-total">${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="order-status {{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="payment-status {{ $order->payment_status }}">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="order-actions">
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <nav class="pagination" style="margin-top: 2rem;">
                {{ $orders->links() }}
            </nav>
        @else
            <div class="orders-empty">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
                <h3>No orders yet</h3>
                <p>Start shopping to see your orders here!</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Products</a>
            </div>
        @endif
    </div>
</main>
@endsection