/**
 * ==============================================================================
 * PRODUCT.JS - Product Detail Page Handler
 * ==============================================================================
 * 
 * Purpose: Handle product page interactions
 * - Add to cart
 * - Add to wishlist
 * - Submit reviews
 * - Update quantity
 */

class ProductDetailHandler {
    /**
     * ==============================================================================
     * constructor() - Initialize the handler
     * ==============================================================================
     */
    constructor() {
        // Get CSRF token from meta tag for security
        // document.querySelector() finds HTML elements
        // ?.getAttribute() safely gets attribute (returns null if not found)
        // || '' provides default empty string if token not found
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Call init() to set up everything
        this.init();
    }

    /**
     * ==============================================================================
     * init() - Set up event listeners
     * ==============================================================================
     */
    init() {
        // Bind click events to buttons
        this.bindEvents();
    }

    /**
     * ==============================================================================
     * bindEvents() - Attach click event listeners
     * ==============================================================================
     */
    bindEvents() {
        // Find quantity buttons
        const minusBtn = document.querySelector('.qty-btn.minus');
        const plusBtn = document.querySelector('.qty-btn.plus');
        
        // Find action buttons
        const addCartBtn = document.querySelector('.btn-add-cart');
        const wishlistBtn = document.querySelector('.btn-wishlist');
        
        // Find review form
        const reviewForm = document.querySelector('.review-form');

        // Attach click event to minus button
        if (minusBtn) {
            // addEventListener(type, callback) - listens for clicks
            // () => this.method() - arrow function calls class method
            minusBtn.addEventListener('click', () => this.updateQuantity(-1));
        }

        // Attach click event to plus button
        if (plusBtn) {
            plusBtn.addEventListener('click', () => this.updateQuantity(1));
        }

        // Attach click event to Add to Cart button (if not disabled)
        if (addCartBtn && !addCartBtn.disabled) {
            // Remove any existing event listeners by cloning
            const newBtn = addCartBtn.cloneNode(true);
            addCartBtn.parentNode.replaceChild(newBtn, addCartBtn);
            newBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.addToCart();
            });
        }

        // Attach click event to Wishlist button
        if (wishlistBtn) {
            wishlistBtn.addEventListener('click', () => this.addToWishlist());
        }

        // Attach submit event to review form
        if (reviewForm) {
            reviewForm.addEventListener('submit', (e) => this.submitReview(e));
        }
    }

    /**
     * ==============================================================================
     * updateQuantity() - Increase/decrease quantity
     * ==============================================================================
     * @param change - Number to add (+1 or -1)
     */
    updateQuantity(change) {
        // Find quantity input field
        const input = document.querySelector('.qty-input');
        
        // Get min/max values from input attributes
        // parseInt() converts string to number
        // || provides default value if undefined
        const min = parseInt(input?.min) || 1;
        const max = parseInt(input?.max) || 99;
        
        // Get current value
        let value = parseInt(input?.value) || 1;
        
        // Calculate new value, bounded between min and max
        // Math.max(min, value) - ensures not below minimum
        // Math.min(max, value) - ensures not above maximum
        value = Math.max(min, Math.min(max, value + change));
        
        // Update the input value
        if (input) {
            input.value = value;
        }
    }

    /**
     * ==============================================================================
     * addToCart() - Send product to cart
     * ==============================================================================
     */
    async addToCart() {
        const input = document.querySelector('.qty-input');
        let quantity = parseInt(input?.value) || 1;
        if (isNaN(quantity)) quantity = 1;
        quantity = Math.max(1, quantity);
        
        const deliveryDate = document.querySelector('.delivery-date-input')?.value;
        
        const addCartBtn = document.querySelector('.btn-add-cart');
        const productId = addCartBtn?.dataset?.productId;

        if (!productId) {
            this.showAlert('Product not found', 'error');
            return;
        }
        
        if (!deliveryDate) {
            this.showAlert('Please select a delivery date', 'error');
            return;
        }

        // Disable button to prevent double-clicks
        addCartBtn.disabled = true;
        addCartBtn.textContent = 'Adding...';
        
        try {
            // Send POST request to /cart endpoint
            const response = await fetch('/cart', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ 
                    product_id: productId,
                    quantity: quantity,
                    delivery_date: deliveryDate
                })
            });

            const result = await response.json();

            if (response.ok) {
                this.showAlert('Added to cart!', 'success');
                // Update cart count in header
                this.updateCartCount();
            } else {
                this.showAlert(result.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            this.showAlert('Please login to add items to cart', 'error');
        } finally {
            // Re-enable button
            addCartBtn.disabled = false;
            addCartBtn.textContent = 'Add to Cart';
        }
    }

    /**
     * ==============================================================================
     * addToWishlist() - Add product to wishlist
     * ==============================================================================
     */
    async addToWishlist() {
        const wishlistBtn = document.querySelector('.btn-wishlist');
        const productId = wishlistBtn?.dataset?.productId;
        console.log('Adding to wishlist, Product ID:', productId);

        if (!productId) {
            this.showAlert('Product not found', 'error');
            return;
        }

        try {
            const response = await fetch('/wishlist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            });

            const result = await response.json();
            console.log('Wishlist response:', response.status, result);

            if (response.ok) {
                this.showAlert('Added to wishlist!', 'success');
            } else if (response.status === 401) {
                window.location.href = result.redirect || '/login';
            } else {
                this.showAlert(result.message || 'Failed to add to wishlist', 'error');
            }
        } catch (error) {
            console.error('Wishlist error:', error);
            this.showAlert('Please login to add items to wishlist', 'error');
        }
    }

    /**
     * ==============================================================================
     * submitReview() - Submit product review
     * ==============================================================================
     * @param e - Event object from form submission
     */
    async submitReview(e) {
        // Prevent default form submission (page reload)
        e.preventDefault();
        
        // Get form element
        const form = e.target;
        
        // Get review data from form fields
        // ?.value uses optional chaining (safer if field missing)
        const rating = form.rating?.value;
        const comment = form.comment?.value;

        // Validate required fields
        if (!rating || !comment) {
            this.showAlert('Please provide rating and comment', 'error');
            return;
        }

        // Get product ID from hidden input
        const productIdInput = form.querySelector('input[name="product_id"]');
        const productId = productIdInput?.value;

        if (!productId) {
            this.showAlert('Product not found', 'error');
            return;
        }

        try {
            // Send POST request to /reviews endpoint
            const response = await fetch('/reviews', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                // Send all review data
                body: JSON.stringify({
                    product_id: productId,
                    rating: rating,
                    comment: comment
                })
            });

            // Parse JSON response
            const result = await response.json();

            // Check if successful
            if (response.ok) {
                this.showAlert('Thank you! Your review has been submitted.', 'success');
                form.reset();  // Clear the form
                // Optionally reload to show the new review
                setTimeout(() => window.location.reload(), 2000);
            } else {
                this.showAlert(result.message || 'Failed to submit review', 'error');
            }
        } catch (error) {
            console.error('Review error:', error);
            this.showAlert('Please login to write a review', 'error');
        }
    }

    /**
     * ==============================================================================
     * showAlert() - Display message to user
     * ==============================================================================
     * @param message - The text to show
     * @param type   - 'success' or 'error'
     */
    showAlert(message, type) {
        // Remove existing alert if any
        const existing = document.querySelector('.product-alert');
        if (existing) existing.remove();

        // Create new alert div element
        const alert = document.createElement('div');
        
        // Set CSS class based on type
        alert.className = `alert alert-${type} product-alert`;
        
        // Set message text
        alert.textContent = message;

        // Find container to insert alert
        const container = document.querySelector('.product-info-section');
        
        // Insert at beginning of container
        if (container) {
            container.insertBefore(alert, container.firstChild);
            
            // Auto-remove after 3 seconds (faster removal)
            setTimeout(() => alert.remove(), 3000);
        }
    }

    /**
     * ==============================================================================
     * updateCartCount() - Update cart badge in header
     * ==============================================================================
     */
    async updateCartCount() {
        try {
            const response = await fetch('/cart/count', {
                headers: { 'Accept': 'application/json' }
            });
            if (response.ok) {
                const data = await response.json();
                const badge = document.getElementById('cartCount');
                if (badge) {
                    badge.textContent = data.count || 0;
                }
            }
        } catch (error) {
            console.error('Error updating cart count:', error);
        }
    }
}

// ==============================================================================
// INITIALIZATION
// ==============================================================================

// Run when page is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Create new instance of the handler
    new ProductDetailHandler();
});

/**
 * ==============================================================================
 * KEY JAVASCRIPT CONCEPTS SUMMARY
 * ==============================================================================
 * 
 * class ProductDetailHandler {
 *     // Blueprint for product page behavior
 * }
 * 
 * document.querySelector('.classname')  - Find element by CSS selector
 * document.querySelectorAll('.classname') - Find all matching elements
 * 
 * element.addEventListener('click', callback) - Listen for clicks
 * element.addEventListener('submit', callback) - Listen for form submit
 * 
 * async function name() {
 *     // Function that can use await
 * }
 * 
 * await fetch(url, options) - Make HTTP request and wait for response
 * 
 * JSON.stringify(object)  - Convert JS object to JSON string
 * JSON.parse(jsonString) - Convert JSON string to JS object
 * 
 * template literals: `hello ${variable}` - String with embedded variables
 * arrow functions: (params) => expression - Short function syntax
 */