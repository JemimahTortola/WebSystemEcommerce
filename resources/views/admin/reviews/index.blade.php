@extends('admin.layout.main')

@section('title', 'Reviews - Admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/reviews.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div>
        <h1>Reviews</h1>
        <p>Manage customer reviews</p>
    </div>
</div>

<div class="content-card">
    <div class="list-header">
        <span class="header-item">Product</span>
        <span class="header-item">Customer</span>
        <span class="header-item">Rating</span>
        <span class="header-item">Review</span>
        <span class="header-item"></span>
    </div>
    <div class="list-items">
        @foreach($reviews as $review)
            <div class="list-item">
                <img src="{{ $review->product->image ? asset('storage/products/' . $review->product->image) : 'https://images.unsplash.com/photo-1555252333-9f8e92e65df9?w=50&h=50&fit=crop' }}" 
                     alt="{{ $review->product->name }}" class="item-thumb">
                <span class="item-product">{{ $review->product->name }}</span>
                <span class="item-user">{{ $review->user->name }}</span>
                <span class="item-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="{{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                    @endfor
                </span>
                <span class="item-comment">{{ Str::limit($review->comment, 30) }}</span>
                <span class="item-actions">
                    <button type="button" class="btn btn-sm btn-secondary" onclick="openMessageModal({{ $review->user->id }}, {{ $review->id }})" title="Message Customer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </button>
                </span>
            </div>
        @endforeach

        @if($reviews->isEmpty())
            <div class="empty-state">No reviews found</div>
        @endif
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin/reviews.css') }}">
@endpush

@section('modals')
<div id="messageModal" class="modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeMessageModal()"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3>Send Message</h3>
            <button type="button" class="modal-close" onclick="closeMessageModal()">&times;</button>
        </div>
        <form method="POST" action="{{ route('admin.messages.start') }}">
            @csrf
            <input type="hidden" name="user_id" id="messageUserId">
            <input type="hidden" name="review_id" id="messageReviewId">
            <div class="modal-body">
                <div class="form-group">
                    <label for="messageSubject">Subject</label>
                    <input type="text" name="subject" id="messageSubject" class="form-control" placeholder="Regarding your review...">
                </div>
                <div class="form-group">
                    <label for="messageContent">Message</label>
                    <textarea name="content" id="messageContent" class="form-control" rows="5" required placeholder="Type your message here..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeMessageModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openMessageModal(userId, reviewId) {
    document.getElementById('messageUserId').value = userId;
    document.getElementById('messageReviewId').value = reviewId;
    document.getElementById('messageModal').style.display = 'block';
}

function closeMessageModal() {
    document.getElementById('messageModal').style.display = 'none';
}
</script>
@endpush

@endsection
