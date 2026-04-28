@extends('layouts.admin')

@section('title', 'Customers - Flourista Admin')

@section('page-title', 'Customers')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
@endsection

@section('content')
<div class="page-header">
    <div>
        <h2>Customers</h2>
        <p>Total: <span id="userCount">0</span> customers</p>
    </div>
</div>

<div class="filters-bar">
    <input type="text" id="searchInput" placeholder="Search customers...">
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTableBody"></tbody>
    </table>
</div>

<!-- Ban Modal -->
<div class="modal" id="banModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ban User</h2>
            <button class="modal-close" onclick="closeBanModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="banDays">Ban Duration (days)</label>
                <input type="number" id="banDays" min="1" max="365" value="7" class="form-control">
            </div>
            <div class="form-group">
                <label for="banReason">Reason (optional)</label>
                <textarea id="banReason" class="form-control" rows="3" placeholder="Enter ban reason..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeBanModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmBan()">Ban User</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/users.js') }}"></script>
@endsection