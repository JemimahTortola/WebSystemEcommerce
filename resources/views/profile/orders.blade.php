@extends('layouts.app')

@section('title', 'My Orders - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/orders.css') }}">
@endsection

@section('content')
<div class="orders-page">
    <div class="page-header">
        <h1>My Orders</h1>
    </div>

    @forelse($orders as $order)
    <div class="order-card">
        <div class="order-info-left">
            <div class="order-number">Order #{{ $order->id }}</div>
            <div class="order-meta">
                <span>{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</span>
                <span>{{ $order->item_count ?? 0 }} item(s)</span>
                <span class="order-status-badge {{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
        </div>
        <div class="order-info-right">
            <div class="order-amount">₱{{ number_format($order->total_amount, 2) }}</div>
            <div class="order-actions">
                <a href="{{ route('orders.show', $order->id) }}" class="btn-details">View</a>
                @if(($order->payment_status ?? '') === 'pending' || ($order->payment_status ?? '') === 'pending_verification')
                <button class="btn-upload-receipt" onclick="openReceiptModal({{ $order->id }})">Receipt</button>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="orders-empty">
        <div class="empty-icon">📦</div>
        <h3>No Orders Yet</h3>
        <p>Start shopping to see your orders here!</p>
        <a href="{{ route('shop') }}" class="btn-shop">Browse Shop</a>
    </div>
    @endforelse
</div>

<!-- Receipt Upload Modal -->
<div id="receiptModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" onclick="closeModal()">&times;</span>
        <h2>Upload Receipt</h2>
        <form id="receiptForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="receipt" accept="image/*" required>
            <button type="submit" class="btn-submit-receipt">Upload</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function openReceiptModal(orderId) {
    var modal = document.getElementById('receiptModal');
    var form = document.getElementById('receiptForm');
    form.action = '/orders/' + orderId + '/receipt';
    modal.classList.add('active');
}

function closeModal() {
    var modal = document.getElementById('receiptModal');
    modal.classList.remove('active');
}

window.onclick = function(event) {
    var modal = document.getElementById('receiptModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endsection