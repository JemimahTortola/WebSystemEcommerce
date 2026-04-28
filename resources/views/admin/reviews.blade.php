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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reviewsTableBody"></tbody>
    </table>
</div>

<div class="modal" id="reviewModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Review Details</h2>
            <button class="modal-close" onclick="closeReviewModal()">×</button>
        </div>
        <div class="modal-body">
            <div id="reviewDetails"></div>
            <hr>
            <div class="form-group">
                <label>Admin Comment</label>
                <textarea id="adminComment" class="form-control" rows="3" placeholder="Add your comment..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeReviewModal()">Close</button>
                <button class="btn btn-primary" onclick="saveAdminComment()">Save Comment</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin/reviews.js') }}"></script>
@endsection