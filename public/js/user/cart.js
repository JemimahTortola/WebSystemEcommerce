if (!window.CartHandler) {
    window.CartHandler = class CartHandler {
        constructor() {
            window.cartHandlerInitialized = true;
            this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            this.init();
        }

        init() {
            this.updateCartCount();
            this.bindEvents();
            if (document.getElementById('cartGrid')) {
                this.loadCartItems();
            }
        }

        bindEvents() {
            // Don't bind on product pages - product.js handles those
            if (document.querySelector('.delivery-date-input')) {
                return;
            }
            
            const buttons = document.querySelectorAll('.btn-add-cart');
            buttons.forEach(btn => {
                btn.onclick = (e) => {
                    e.stopPropagation();
                    const productId = btn.dataset.productId;
                    if (productId) {
                        this.addToCart(productId);
                    }
                };
            });
        }

        getProductIdFromElement(btn) {
            const card = btn.closest('.product-card');
            if (card) {
                const link = card.querySelector('[onclick*="shop.product"]');
                if (link) {
                    const match = link.getAttribute('onclick').match(/shop\.product.*\/(\d+)/);
                    if (match) return match[1];
                }
            }
            return null;
        }

        async loadCartItems() {
            try {
                const response = await fetch('/cart', {
                    headers: { 
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                
                if (!response.ok) return;
                
                const data = await response.json();
                this.renderCartItems(data.cartItems || []);
            } catch (error) {
                console.error('Error loading cart:', error);
            }
        }

        renderCartItems(cartItems) {
            const cartGrid = document.getElementById('cartGrid');
            const emptyCart = document.getElementById('emptyCart');
            
            if (!cartItems || cartItems.length === 0) {
                cartGrid.innerHTML = '';
                if (emptyCart) emptyCart.style.display = 'block';
                this.updateSummary({ subtotal: 0, discount: 0, total: 0 });
                return;
            }

            if (emptyCart) emptyCart.style.display = 'none';
            
            let subtotal = 0;
            cartItems.forEach(item => {
                subtotal += (parseFloat(item.price) || 0) * (parseInt(item.quantity) || 1);
            });

            this.updateSummary({ subtotal, discount: 0, total: subtotal });

            cartGrid.innerHTML = cartItems.map(item => {
                const name = item.name || 'Unnamed Product';
                const imageUrl = item.image || `https://via.placeholder.com/200x200/e8a5aa/ffffff?text=${encodeURIComponent(name)}`;
                const price = parseFloat(item.price) || 0;
                const quantity = parseInt(item.quantity) || 1;
                const deliveryDate = item.delivery_date ? new Date(item.delivery_date + 'T00:00:00').toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : null;
                return `
                <div class="cart-item" data-cart-item-id="${item.cart_item_id}">
                    <a href="/shop/${item.slug}" class="cart-item-image">
                        <img src="${imageUrl}" alt="${name}">
                    </a>
                    <div class="cart-item-details">
                        <a href="/shop/${item.slug}" class="cart-item-name">${name}</a>
                        <div class="cart-item-price">₱${price.toFixed(2)}</div>
                        <div class="cart-item-quantity">
                            <button class="qty-btn" onclick="cartHandler.updateQuantity(${item.cart_item_id}, -1)">−</button>
                            <span>${quantity}</span>
                            <button class="qty-btn" onclick="cartHandler.updateQuantity(${item.cart_item_id}, 1)">+</button>
                        </div>
                        ${deliveryDate ? `<div class="cart-item-delivery">📅 Delivery: ${deliveryDate}</div>` : ''}
                    </div>
                    <div class="cart-item-total">₱${(price * quantity).toFixed(2)}</div>
                    <button class="cart-item-remove" onclick="cartHandler.removeItem(${item.cart_item_id})">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 6h18M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"></path>
                        </svg>
                    </button>
                </div>
            `;
            }).join('');
        }

        updateSummary({ subtotal, discount, total }) {
            const summaryContainer = document.getElementById('cartSummary');
            if (!summaryContainer) return;

            summaryContainer.innerHTML = `
                <h3>Order Summary</h3>
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>₱${subtotal.toFixed(2)}</span>
                </div>
                ${discount > 0 ? `
                <div class="summary-row discount">
                    <span>Discount</span>
                    <span>-₱${discount.toFixed(2)}</span>
                </div>
                ` : ''}
                <div class="summary-row total">
                    <span>Total</span>
                    <span>₱${total.toFixed(2)}</span>
                </div>
                <a href="/checkout" class="btn btn-primary btn-checkout">
                    Proceed to Checkout
                </a>
            `;
        }

        async addToCart(productId, quantity = 1, deliveryDate = null) {
            // Don't add to cart if on product page with delivery date input
            if (document.querySelector('.delivery-date-input')) {
                return;
            }
            
            try {
                const body = { product_id: productId, quantity };
                if (deliveryDate) {
                    body.delivery_date = deliveryDate;
                }
                
                const response = await fetch('/cart', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(body)
                });

                const data = await response.json();
                console.log('Add to cart response:', response.ok, data);

                if (response.ok) {
                    this.showAlert('Added to cart!', 'success');
                    this.updateCartCount();
                } else if (response.status === 401) {
                    window.location.href = '/login';
                } else {
                    this.showAlert(data.message || 'Error adding to cart', 'error');
                }
            } catch (error) {
                console.error('Error adding to cart:', error);
                this.showAlert('Error adding to cart', 'error');
            }
        }

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
                console.error('Error getting cart count:', error);
            }
        }

        showAlert(message, type) {
            const container = document.getElementById('alertContainer');
            if (!container) {
                alert(message);
                return;
            }

            const alertDiv = document.createElement('div');
            alertDiv.className = `alert alert-${type}`;
            alertDiv.textContent = message;
            container.innerHTML = '';
            container.appendChild(alertDiv);
            setTimeout(() => alertDiv.remove(), 3000);
        }

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
    };

    window.CartHandler = new CartHandler();
}

var cartHandler = window.CartHandler;