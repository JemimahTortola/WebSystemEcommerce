@extends('layouts.admin')

@section('title', 'Orders - Flourista Admin')

@section('page-title', 'Orders')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/orders.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h2>Orders</h2>
    <div class="view-toggle">
        <button id="gridViewBtn" class="active" onclick="switchView('grid')">Grid</button>
        <button id="calendarViewBtn" onclick="switchView('calendar')">Calendar</button>
    </div>
</div>

<div class="filters-bar">
    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
</div>

<div id="calendarView" class="calendar-view-container" style="display: none;">
    <div class="calendar-navigation">
        <button class="calendar-nav-btn" onclick="changeMonth(-1)">← Previous</button>
        <h3 id="calendarMonth">May 2026</h3>
        <button class="calendar-nav-btn" onclick="changeMonth(1)">Next →</button>
    </div>
    <div class="calendar-view" id="calendarGrid"></div>
</div>

<div class="orders-grid" id="ordersGrid"></div>

<div class="modal" id="orderModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Order Details</h2>
            <button class="modal-close" onclick="closeOrderModal()">×</button>
        </div>
        <div class="order-details" id="orderDetails"></div>
    </div>
</div>

<script src="{{ asset('js/admin/orders.js') }}"></script>
@endsection