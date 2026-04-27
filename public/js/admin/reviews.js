class ReviewsHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.init();
    }

    init() {
        this.loadReviews();
    }

    async loadReviews() {
        try {
            const response = await fetch('/api/reviews', {
                headers: { 'Accept': 'application/json' }
            });
            const reviews = await response.json();
            this.renderTable(reviews);
        } catch (error) {
            console.error('Error loading reviews:', error);
        }
    }

    renderTable(reviews) {
        const tbody = document.getElementById('reviewsTableBody');
        if (!reviews.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="empty-state">No reviews found</td></tr>';
            return;
        }

        tbody.innerHTML = reviews.map(r => `
            <tr>
                <td>${r.product?.name || 'N/A'}</td>
                <td>${r.user?.name || 'Anonymous'}</td>
                <td>${'★'.repeat(r.rating)}${'☆'.repeat(5 - r.rating)}</td>
                <td>${r.comment?.substring(0, 50)}${r.comment?.length > 50 ? '...' : ''}</td>
                <td>${new Date(r.created_at).toLocaleDateString()}</td>
                <td><span class="status-badge ${r.is_visible ? 'active' : 'inactive'}">${r.is_visible ? 'Visible' : 'Hidden'}</span></td>
                <td>
                    <button class="btn-action" onclick="toggleReview(${r.id})">${r.is_visible ? 'Hide' : 'Show'}</button>
                </td>
            </tr>
        `).join('');
    }

    async toggleReview(id) {
        try {
            await fetch(`/admin/reviews/${id}/toggle`, {
                method: 'PUT',
                headers: { 'X-CSRF-TOKEN': this.csrfToken },
            });
            this.loadReviews();
        } catch (error) {
            console.error('Error toggling review:', error);
        }
    }
}

let reviewsHandler;

document.addEventListener('DOMContentLoaded', () => {
    reviewsHandler = new ReviewsHandler();
});

function toggleReview(id) {
    reviewsHandler?.toggleReview(id);
}