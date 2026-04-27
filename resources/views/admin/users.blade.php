@extends('layouts.admin')

@section('title', 'Customers - Flourista Admin')

@section('page-title', 'Customers')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/users.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h2>Customers</h2>
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
                <th>Joined</th>
            </tr>
        </thead>
        <tbody id="usersTableBody"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/users.js') }}"></script>
@endsection