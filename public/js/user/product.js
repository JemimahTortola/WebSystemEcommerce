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
            addCartBtn.addEventListener('click', () => this.addToCart());
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
        // Get quantity from input field
        const input = document.querySelector('.qty-input');
        const quantity = parseInt(input?.value) || 1;
        
        // Get product ID from the add to cart button's data attribute
        const addCartBtn = document.querySelector('.btn-add-cart');
        const productId = addCartBtn?.dataset?.productId;

        if (!productId) {
            this.showAlert('Product not found', 'error');
            return;
        }
        
        try {
            // Send POST request to /cart endpoint
            const response = await fetch('/cart', {
                method: 'POST',                           // HTTP method for creating data
                headers: {
                    'Content-Type': 'application/json', // We're sending JSON
                    'X-CSRF-TOKEN': this.csrfToken,    // Security token
                    'Accept': 'application/json'       // We expect JSON back
                },
                // Convert object to JSON string
                body: JSON.stringify({ 
                    product_id: productId,  // Product ID
                    quantity: quantity // How many to add
                })
            });

            // Parse JSON response
            const result = await response.json();

            // Check if request was successful
            if (response.ok) {
                // Show success message
                this.showAlert('Added to cart!', 'success');
            } else {
                // Show error from server or generic message
                this.showAlert(result.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            // Network error or other issue
            this.showAlert('Please login to add items to cart', 'error');
        }
    }

    /**
     * ==============================================================================
     * addToWishlist() - Add product to wishlist
     * ==============================================================================
     */
    async addToWishlist() {
        // Get product slug from URL
        const productSlug = window.location.pathname.split('/').pop();

        try {
            // Send POST request to /wishlist endpoint
            const response = await fetch('/wishlist', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                // Send product_id as the slug
                body: JSON.stringify({ product_id: productSlug })
            });

            // Parse JSON response
            const result = await response.json();

            // Log full response for debugging
            console.log('Wishlist response:', response.status, result);

            // Check response status
            if (response.status === 401) {
                // Not logged in - redirect to login
                window.location.href = result.redirect || '/login';
                return;
            }
            
            if (response.ok) {
                // Success!
                this.showAlert('Added to wishlist!', 'success');
            } else {
                // Error from server
                this.showAlert(result.message || 'Failed to add to wishlist', 'error');
            }
        } catch (error) {
            // Network error
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

        // Get product slug from URL
        const productSlug = window.location.pathname.split('/').pop();

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
                    product_id: productSlug,
                    rating: rating,
                    comment: comment
                })
            });

            // Parse JSON response
            const result = await response.json();

            // Check if successful
            if (response.ok) {
                this.showAlert('Review submitted! It will appear after approval.', 'success');
                form.reset();  // Clear the form
            } else {
                this.showAlert(result.message || 'Failed to submit review', 'error');
            }
        } catch (error) {
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
        // Template literal: `alert alert-${type}` becomes 'alert alert-success' or 'alert alert-error'
        alert.className = `alert alert-${type} product-alert`;
        
        // Set message text
        alert.textContent = message;

        // Find container to insert alert
        const container = document.querySelector('.product-info-section');
        
        // Insert at beginning of container
        if (container) {
            container.insertBefore(alert, container.firstChild);
            
            // Auto-remove after 4 seconds
            setTimeout(() => alert.remove(), 4000);
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