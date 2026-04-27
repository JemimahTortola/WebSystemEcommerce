class WishlistHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadWishlist();
    }

    bindEvents() {
    }

    async loadWishlist() {
        try {
            const response = await fetch('/wishlist', {
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) return;

            const products = await response.json();
            this.renderWishlist(products);
        } catch (error) {
            console.error('Error loading wishlist:', error);
        }
    }

    renderWishlist(products) {
        const grid = document.getElementById('wishlistGrid');
        const empty = document.getElementById('emptyWishlist');

        if (!grid) return;

        if (!products || products.length === 0) {
            grid.innerHTML = '';
            if (empty) empty.style.display = 'block';
            return;
        }

        if (empty) empty.style.display = 'none';

        grid.innerHTML = products.map(product => `
            <div class="wishlist-card" data-id="${product.id}">
                <img class="wishlist-image" src="${product.image || '/images/placeholder.jpg'}" alt="${this.escapeHtml(product.name)}">
                <div class="wishlist-details">
                    <div class="wishlist-name">${this.escapeHtml(product.name)}</div>
                    <div class="wishlist-price">₱${product.price}</div>
                </div>
                <div class="wishlist-actions">
                    <button class="btn btn-primary btn-add-cart" data-id="${product.id}">Add to Cart</button>
                    <button class="btn btn-remove btn-remove-wishlist" data-id="${product.id}">&times;</button>
                </div>
            </div>
        `).join('');

        grid.querySelectorAll('.btn-add-cart').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.addToCart(btn.dataset.id);
            });
        });

        grid.querySelectorAll('.btn-remove-wishlist').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                this.removeFromWishlist(btn.dataset.id);
            });
        });
    }

    async addToCart(productId) {
        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId, quantity: 1 })
            });

            const result = await response.json();

            if (!response.ok) {
                this.showAlert(result.message || 'Failed to add to cart', 'error');
                return;
            }

            this.showAlert('Added to cart!', 'success');

        } catch (error) {
            this.showAlert('An error occurred.', 'error');
            console.error('Add to cart error:', error);
        }
    }

    async removeFromWishlist(productId) {
        try {
            const response = await fetch(`/wishlist/${productId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (!response.ok) {
                this.showAlert(result.message || 'Failed to remove', 'error');
                return;
            }

            this.showAlert('Removed from wishlist', 'success');
            this.loadWishlist();

        } catch (error) {
            this.showAlert('An error occurred.', 'error');
            console.error('Remove error:', error);
        }
    }

    showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        if (!alertContainer) return;

        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;

        alertContainer.innerHTML = '';
        alertContainer.appendChild(alertDiv);

        setTimeout(() => alertDiv.remove(), 5000);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new WishlistHandler();
});