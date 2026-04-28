@extends('layouts.app')

@section('title', 'Order Details - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/order-detail.css') }}">
@endsection

@section('content')
<div class="order-detail-page">
    <div class="page-header">
        <h1>Order #{{ $order->id }}</h1>
        <a href="{{ route('orders') }}" class="btn-back">← Back</a>
    </div>

    <!-- Status Card -->
    <div class="detail-status-card">
        <div class="detail-status-row">
            <div class="detail-status-item">
                <span class="label">Status</span>
                <span class="status-pill {{ $order->status }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="detail-status-item">
                <span class="label">Payment</span>
                <span class="status-pill {{ $order->payment_status ?? 'pending' }}">{{ ucfirst(str_replace('_', ' ', $order->payment_status ?? 'pending')) }}</span>
            </div>
            <div class="detail-status-item">
                <span class="label">Date</span>
                <span class="value">{{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</span>
            </div>
            <div class="detail-status-item">
                <span class="label">Total</span>
                <span class="value" style="font-size: 1.25rem; color: var(--primary);">₱{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Info Card -->
    @if($order->payment_method !== 'cod')
    <div class="detail-items-card">
        <h2>Payment Information</h2>
        @if($order->payment_method === 'gcash')
        <div class="payment-info">
            <p><strong>GCash Number:</strong> 0912-345-6789</p>
            <p><strong>Account Name:</strong> Flourista Flowers</p>
        </div>
        @elseif($order->payment_method === 'bank')
        <div class="payment-info">
            <p><strong>Bank:</strong> BPI</p>
            <p><strong>Account Number:</strong> 1234-5678-9012</p>
            <p><strong>Account Name:</strong> Flourista Flowers</p>
        </div>
        @endif
    </div>
    @endif
    
    <!-- Items Card -->
    <div class="detail-items-card">
        <h2>Items ({{ $items->count() }})</h2>
        @forelse($items as $item)
        <div class="detail-item">
            <img src="{{ $item->image }}" alt="{{ $item->name }}">
            <div class="item-info">
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-qty">Qty: {{ $item->quantity }}</div>
            </div>
            <div class="item-price">₱{{ number_format($item->price, 2) }}</div>
        </div>
        @empty
        <p style="color: var(--text-muted);">No items found.</p>
        @endforelse
        
        <div class="detail-total">
            <span class="label">Total:</span>
            <span class="amount">₱{{ number_format($order->total_amount, 2) }}</span>
        </div>
    </div>

    <!-- Upload Receipt / Verification Status -->
    @if($order->payment_method !== 'cod')
        @if(($order->payment_status ?? '') === 'rejected')
        <div class="detail-upload-card">
            <h2>Upload Payment Receipt</h2>
            <div class="alert alert-error" style="margin-bottom: 1rem;">Your previous receipt was rejected. Please upload a clear photo of your payment receipt.</div>
            <form method="POST" action="{{ route('orders.receipt', $order->id) }}" enctype="multipart/form-data" id="receipt-form">
                @csrf
                <label class="upload-area" id="upload-area">
                    <input type="file" name="receipt" accept="image/*" required onchange="handleFileSelect(event)">
                    <span class="file-label">
                        <span class="upload-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </span>
                        <span class="upload-text" id="upload-text">Choose Image</span>
                    </span>
                </label>
                <button type="submit" class="upload-btn" id="upload-submit" disabled>Upload Receipt</button>
            </form>
        </div>
        @elseif(($order->payment_status ?? '') === 'pending_verification')
        <div class="detail-upload-card" style="border-left: 4px solid #f0ad4;">
            <h2>Payment Under Verification</h2>
            <p style="color: var(--text-muted);">Your payment receipt has been uploaded and is currently under review. We will notify you once verified.</p>
        </div>
        @elseif(($order->payment_status ?? '') === 'pending')
        <div class="detail-upload-card">
            <h2>Upload Payment Receipt</h2>
            <p>Please upload your payment receipt to verify your order.</p>
            <form method="POST" action="{{ route('orders.receipt', $order->id) }}" enctype="multipart/form-data" id="receipt-form">
                @csrf
                <label class="upload-area" id="upload-area">
                    <input type="file" name="receipt" accept="image/*" required onchange="handleFileSelect(event)">
                    <span class="file-label">
                        <span class="upload-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </span>
                        <span class="upload-text" id="upload-text">Choose Image</span>
                    </span>
                </label>
                <button type="submit" class="upload-btn" id="upload-submit" disabled>Upload Receipt</button>
            </form>
        </div>
        @endif
    @endif
</div>

@push('scripts')
<script>
function handleFileSelect(event) {
    const file = event.target.files[0];
    const uploadArea = document.getElementById('upload-area');
    const uploadText = document.getElementById('upload-text');
    const submitBtn = document.getElementById('upload-submit');
    
    if (file) {
        uploadArea.classList.add('has-file');
        uploadText.textContent = file.name;
        submitBtn.disabled = false;
    } else {
        uploadArea.classList.remove('has-file');
        uploadText.textContent = 'Choose Image';
        submitBtn.disabled = true;
    }
}
</script>
@endpush
@endsection