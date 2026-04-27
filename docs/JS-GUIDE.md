================================================================================
JAVASCRIPT LEARNING GUIDE - cart.js
================================================================================

This guide explains the JavaScript code for the shopping cart functionality,
written for beginners to understand each concept.

================================================================================
WHAT IS JAVASCRIPT?
================================================================================

JavaScript makes web pages interactive. While HTML defines structure and CSS defines
appearance, JavaScript handles behavior and logic.

- JavaScript runs in the browser (client-side)
- Can respond to user actions (clicks, typing)
- Can communicate with servers (fetch data)
- Can dynamically update the page

================================================================================
JAVASCRIPT BASICS EXPLAINED
================================================================================

-------------------------------------------------------------------------------
1. Variables
-------------------------------------------------------------------------------
// Ways to declare variables:

let cartHandler;          // Can be changed later (recommended)
const apiUrl = '/cart';   // Cannot be changed
var oldStyle = 'legacy';   // Old way (avoid)

-------------------------------------------------------------------------------
2. Functions
-------------------------------------------------------------------------------
// Function declaration:
function myFunction(param) {
    // code here
    return result;
}

// Arrow function (modern):
const myFunction = (param) => {
    return result;
};

// Short arrow (one-liner):
const double = (num) => num * 2;

-------------------------------------------------------------------------------
3. Classes (Object Blueprints)
-------------------------------------------------------------------------------
// A class is a blueprint for creating objects
class CartHandler {
    constructor() {
        // Runs when: new CartHandler()
        this.csrfToken = 'abc';  // 'this' refers to the object
    }
    
    methodName() {
        // Method code
    }
}

// Creating an object:
const cart = new CartHandler();
cart.methodName();  // Call the method

-------------------------------------------------------------------------------
4. Arrays and Objects
-------------------------------------------------------------------------------
// Array (ordered list):
const items = [1, 2, 3];
items.push(4);           // Add item
items.map(item => item * 2); // Transform each item

// Object (key-value pairs):
const user = {
    name: 'John',
    email: 'john@email.com'
};
user.name;              // Get value: 'John'
user['email'];          // Also gets: 'john@email.com'

-------------------------------------------------------------------------------
5. Async/Await (Asynchronous Code)
-------------------------------------------------------------------------------
// Async means "this function takes time"
// Await means "wait for this to finish"

async function fetchData() {
    const response = await fetch('/api/data');
    const data = await response.json();
    return data;
}

================================================================================
THE CART.JS CODE EXPLAINED LINE BY LINE
================================================================================

===============================================================================
CLASS DEFINITION
==============================================================================

/*
 * class CartHandler
 * -------------
 * This is a JavaScript CLASS - a blueprint for handling cart operations.
 * It encapsulates all cart-related functionality.
 */
class CartHandler {
    /*
     * constructor()
     * -------------
     * Special method that runs when we create the object.
     * Used to set up initial values.
     */
    constructor() {
        // Get CSRF token from meta tag for security
        // document.querySelector() finds HTML elements
        // ?.getAttribute() gets an attribute value
        // || '' is fallback if not found (empty string)
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        // Initialize the cart handler
        this.init();
    }

    /*
     * init()
     * -------------
     * Sets up the cart. Runs automatically in constructor.
     */
    init() {
        // Bind click events to "Add to Cart" buttons
        this.bindEvents();
        
        // Update the cart count badge in header
        this.updateCartCount();
        
        // If on cart page, load the cart items
        // document.getElementById() finds element by ID
        if (document.getElementById('cartGrid')) {
            this.loadCartItems();
        }
    }

    /*
     * bindEvents()
     * -------------
     * Attaches click event listeners to "Add to Cart" buttons
     */
    bindEvents() {
        // document.querySelectorAll() finds ALL elements matching CSS selector
        // .forEach() loops through each button
        document.querySelectorAll('.btn-add-cart').forEach(btn => {
            // Add click event listener
            btn.addEventListener('click', (e) => {
                // Get product ID from data attribute or from element
                const productId = btn.dataset.productId || this.getProductIdFromElement(btn);
                
                // Only proceed if we have a product ID
                if (productId) {
                    this.addToCart(productId);  // Call method
                }
            });
        });
    }

    /*
     * getProductIdFromElement()
     * -------------
     * Extracts product ID from the button or its parent elements
     */
    getProductIdFromElement(btn) {
        // Find the parent .product-card element
        const card = btn.closest('.product-card');
        
        if (card) {
            // Find link containing 'shop.product'
            const link = card.querySelector('[onclick*="shop.product"]');
            if (link) {
                // Use regex to extract number from onclick attribute
                // match() returns array of matches
                const match = link.getAttribute('onclick').match(/shop\.product.*\/(\d+)/);
                if (match) return match[1];
            }
        }
        return null;
    }

===============================================================================
LOADING CART ITEMS
==============================================================================

    /*
     * async loadCartItems()
     * -------------
     * Fetches cart items from the server using AJAX/Fetch API
     */
    async loadCartItems() {
        try {
            // Fetch data from server
            // await means wait for the response
            const response = await fetch('/cart', {
                // Headers tell server what we want
                headers: { 'Accept': 'application/json' }
            });
            
            // Check if request succeeded
            if (response.ok) {
                // Parse JSON response
                const { cartItems } = await response.json();
                // Render the items on page
                this.renderCartItems(cartItems);
            }
        } catch (error) {
            // Log error to console for debugging
            console.error('Error loading cart:', error);
        }
    }

    /*
     * renderCartItems()
     * -------------
     * Displays cart items in the HTML
     */
    renderCartItems(cartItems) {
        // Find the HTML elements
        const cartGrid = document.getElementById('cartGrid');
        const emptyCart = document.getElementById('emptyCart');
        
        // If no items, show empty state
        if (!cartItems || cartItems.length === 0) {
            cartGrid.innerHTML = '';      // Clear content
            emptyCart.style.display = 'block';  // Show message
            return;
        }

        // Hide empty state, show items
        emptyCart.style.display = 'none';
        
        // Transform cartItems array into HTML strings
        // .map() transforms each item into HTML
        // .join('') combines all strings into one
        cartGrid.innerHTML = cartItems.map(item => `
            <div class="cart-item" data-cart-item-id="${item.cart_item_id}">
                <a href="/shop/${item.slug}" class="cart-item-image">
                    <img src="${item.image}" alt="${item.name}">
                </a>
                <div class="cart-item-details">
                    <a href="/shop/${item.slug}" class="cart-item-name">${item.name}</a>
                    <div class="cart-item-price">$${item.price}</div>
                    <div class="cart-item-quantity">
                        <button class="qty-btn" onclick="cartHandler.updateQuantity(${item.cart_item_id}, -1)">-</button>
                        <span>${item.quantity}</span>
                        <button class="qty-btn" onclick="cartHandler.updateQuantity(${item.cart_item_id}, 1)">+</button>
                    </div>
                </div>
                <button class="cart-item-remove" onclick="cartHandler.removeItem(${item.cart_item_id})">
                    <svg>...</svg>
                </button>
            </div>
        `).join('');
    }

===============================================================================
ADDING TO CART
==============================================================================

    /*
     * async addToCart()
     * -------------
     * Sends product to server to add to cart
     */
    async addToCart(productId, quantity = 1) {
        try {
            // Send POST request to server
            const response = await fetch('/cart', {
                method: 'POST',                         // HTTP method
                headers: {
                    'Content-Type': 'application/json',  // Sending JSON
                    'X-CSRF-TOKEN': this.csrfToken,  // Security token
                    'Accept': 'application/json'
                },
                // Convert object to JSON string
                body: JSON.stringify({ product_id: productId, quantity })
            });

            // Parse response
            const data = await response.json();

            // Check response status
            if (response.ok) {
                // Success!
                this.showAlert('Added to cart!', 'success');
                this.updateCartCount();
            } else if (response.status === 401) {
                // Not logged in - redirect to login
                window.location.href = '/login';
            } else {
                // Other error
                this.showAlert(data.message || 'Error adding to cart', 'error');
            }
        } catch (error) {
            console.error('Error adding to cart:', error);
            this.showAlert('Error adding to cart', 'error');
        }
    }

===============================================================================
UPDATING CART COUNT
==============================================================================

    /*
     * async updateCartCount()
     * -------------
     * Fetches and updates the cart badge number in header
     */
    async updateCartCount() {
        try {
            const response = await fetch('/cart/count', {
                headers: { 'Accept': 'application/json' }
            });
            if (response.ok) {
                const data = await response.json();
                // Find badge element
                const badge = document.getElementById('cartCount');
                // Update text content
                if (badge) {
                    badge.textContent = data.count || 0;
                }
            }
        } catch (error) {
            console.error('Error getting cart count:', error);
        }
    }

===============================================================================
ALERTS/MESSAGES
==============================================================================

    /*
     * showAlert()
     * -------------
     * Shows a temporary message to the user
     */
    showAlert(message, type) {
        // Try to find alert container
        const container = document.getElementById('alertContainer');
        
        // If no container, use browser alert
        if (!container) {
            alert(message);
            return;
        }

        // Create new div element
        const alertDiv = document.createElement('div');
        // Set CSS class
        alertDiv.className = `alert alert-${type}`;
        // Set message text
        alertDiv.textContent = message;
        
        // Clear and add to container
        container.innerHTML = '';
        container.appendChild(alertDiv);
        
        // Remove after 3 seconds
        setTimeout(() => alertDiv.remove(), 3000);
    }

===============================================================================
UPDATE QUANTITY
==============================================================================

    /*
     * async updateQuantity()
     * -------------
     * Updates item quantity (+ or -)
     */
    async updateQuantity(cartItemId, change) {
        try {
            const response = await fetch(`/cart/${cartItemId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ change })
            });
            
            if (response.ok) {
                const { cartItems } = await response.json();
                this.renderCartItems(cartItems);
                this.updateCartCount();
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
        }
    }

===============================================================================
REMOVE ITEM
==============================================================================

    /*
     * async removeItem()
     * -------------
     * Removes item from cart
     */
    async removeItem(cartItemId) {
        try {
            const response = await fetch(`/cart/${cartItemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const { cartItems } = await response.json();
                this.renderCartItems(cartItems);
                this.updateCartCount();
            }
        } catch (error) {
            console.error('Error removing item:', error);
        }
    }
}

===============================================================================
INITIALIZATION
==============================================================================

// Create global variable so HTML can access it
let cartHandler;

// Run when page is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Create new CartHandler instance
    cartHandler = new CartHandler();
});

/*
 * ========================================================================
 * KEY JAVASCRIPT CONCEPTS SUMMARY
 * ========================================================================
 * 
 * fetch(url, options)    - Make HTTP requests
 * await               - Wait for async operation
 * .then()             - Handle async result
 * JSON.parse()        - Convert string to object
 * JSON.stringify()    - Convert object to string
 * document.getElementById() - Find element by ID
 * document.querySelector() - Find element by CSS selector
 * element.innerHTML   - Get/set HTML content
 * element.textContent - Get/set text content
 * element.style      - Get/set CSS styles
 * addEventListener()  - Add click/other events
 * classList.add()    - Add CSS class
 * localStorage       - Store data in browser
 * 
 * ========================================================================
 * HTTP METHODS
 * ========================================================================
 * 
 * GET    - Retrieve data (browsing a page)
 * POST   - Create new data (submit form, add to cart)
 * PUT    - Update existing data (edit item)
 * DELETE - Remove data (delete item)
 * 
 * ========================================================================
 * RESPONSE CODES
 * ========================================================================
 * 
 * 200 = OK success
 * 201 = Created (new item added)
 * 301 = Redirect
 * 400 = Bad Request (invalid data)
 * 401 = Unauthorized (not logged in)
 * 403 = Forbidden (no permission)
 * 404 = Not Found
 * 500 = Server Error
 */