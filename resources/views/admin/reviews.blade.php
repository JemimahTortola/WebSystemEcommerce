@extends('layouts.admin')

@section('title', 'Reviews - Flourista Admin')

@section('page-title', 'Reviews')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/reviews.css') }}">
@endsection

@section('content')
<div class="page-header">
    <h2>Reviews</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reviewsTableBody"></tbody>
    </table>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/reviews.js') }}"></script>
@endsection