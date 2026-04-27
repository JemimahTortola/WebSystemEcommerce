@extends('layouts.app')

@section('title', 'Track Order - Flourista')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/user/track-order.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="track-order-container">
        <h1>Track Your Order</h1>
        <p>Enter your order details to track its status</p>

        <div id="alertContainer"></div>

        <form id="trackOrderForm" method="POST" action="{{ route('track-order') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label" for="order_id">Order ID</label>
                <input type="text" id="order_id" name="order_id" class="form-input" placeholder="e.g., ORD-12345">
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email">
            </div>

            <button type="submit" class="btn btn-primary btn-submit">
                <span class="spinner"></span>
                <span class="btn-text">Track Order</span>
            </button>
        </form>

        <div class="tracking-result" id="trackingResult" style="display: none;">
            <div class="order-status-timeline" id="orderTimeline">
            </div>
            <div class="order-info" id="orderInfo">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/user/track-order.js') }}"></script>
@endsection