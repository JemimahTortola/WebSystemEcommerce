// Handles all review-related functionality (view, comment, toggle visibility)
class ReviewsHandler {
    constructor() {
        // Get CSRF token for security
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        // Get reference to the review modal
        this.modal = document.getElementById('reviewModal');
        // Track which review we're currently viewing/commenting on
        this.currentReviewId = null;
        this.init();
    }

    // Initialize - load reviews when page loads
    init() {
        this.loadReviews();
    }

    // Loads all reviews from the server and displays them in the table
    async loadReviews() {
        try {
            const response = await fetch('/admin/reviews/data', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await response.json();
            this.renderTable(data.data || data);
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    }

    // Displays reviews in the HTML table with star ratings
    renderTable(reviews) {
        const tbody = document.getElementById('reviewsTableBody');
        if (!reviews.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="empty-state">No reviews found</td></tr>';
            return;
        }

        // Create HTML for each review row (with star rating display)
        tbody.innerHTML = reviews.map(r => `
            <tr>
                <td>${r.product?.name || 'N/A'}</td>
                <td>${r.user?.name || 'Anonymous'}</td>
                <td>${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</td>
                <td>${r.comment?.substring(0, 50)}${r.comment?.length > 50 ? '...' : ''}</td>
                <td>${new Date(r.created_at).toLocaleDateString()}</td>
                <td>
                    <button class="btn-action" onclick="viewReview(${r.id})">Comment</button>
                </td>
            </tr>
        `).join('');
    }

    // Loads a single review and opens the comment modal
    async viewReview(id) {
        try {
            const response = await fetch(`/admin/reviews/${id}`);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const review = await response.json();
            this.showReviewModal(review);
        } catch (error) {
            console.error('Error loading review:', error);
            alert('Failed to load review details.');
        }
    }

    // Displays the review details and admin comment form in the modal
    showReviewModal(review) {
        this.currentReviewId = review.id;
        const details = document.getElementById('reviewDetails');
        
        // Build HTML with review info and comment
        let html = `
            <div style="margin-bottom: 1rem;">
                <strong>Product:</strong> ${review.product?.name || 'N/A'}<br>
                <strong>User:</strong> ${review.user?.name || 'Anonymous'}<br>
                <strong>Rating:</strong> ${'★'.repeat(review.rating)}${'☆'.repeat(5 - review.rating)}<br>
                <strong>Date:</strong> ${new Date(review.created_at).toLocaleDateString()}<br>
            </div>
            <div style="margin-bottom: 1rem;">
                <strong>Comment:</strong>
                <p style="margin-top: 0.5rem; padding: 1rem; background: #f9fafb; border-radius: 4px;">${review.comment || 'No comment'}</p>
            </div>
        `;
        
        // Show existing admin comment if present
        if (review.admin_comment) {
            html += `
                <div style="margin-bottom: 1rem;">
                    <strong>Admin Comment:</strong>
                    <p style="margin-top: 0.5rem; padding: 1rem; background: #fef3c7; border-radius: 4px;">${review.admin_comment}</p>
                </div>
            `;
        }
        
        details.innerHTML = html;
        document.getElementById('adminComment').value = review.admin_comment || '';
        this.modal.classList.add('active'); // Show the modal
    }

    // Closes the review modal
    closeReviewModal() {
        this.modal.classList.remove('active');
        this.currentReviewId = null;
    }

    // Saves the admin's comment on a review
    async saveAdminComment() {
        const comment = document.getElementById('adminComment').value;
        
        try {
            const response = await fetch(`/admin/reviews/${this.currentReviewId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({ admin_comment: comment }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            if (result.message) {
                this.closeReviewModal();
                this.loadReviews(); // Reload the reviews table
            }
        } catch (error) {
            console.error('Error saving admin comment:', error);
            alert('Failed to save comment. Please try again.');
        }
    }

    // Toggles review visibility (show/hide on shop)
    async toggleReview(id) {
        try {
            await fetch(`/admin/reviews/${id}/toggle`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': this.csrfToken },
            });
            this.loadReviews(); // Reload the table
        } catch (error) {
            console.error('Error toggling review:', error);
        }
    }
}

// Create global instance
let reviewsHandler;

// Initialize when page loads
document.addEventListener('DOMContentLoaded', () => {
    reviewsHandler = new ReviewsHandler();
});

// Global functions for HTML buttons
function viewReview(id) {
    reviewsHandler?.viewReview(id);
}

function closeReviewModal() {
    reviewsHandler?.closeReviewModal();
}

function saveAdminComment() {
    reviewsHandler?.saveAdminComment();
}

// Toggle review visibility
function toggleReview(id) {
    reviewsHandler?.toggleReview(id);
}