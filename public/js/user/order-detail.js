class OrderDetailHandler {
    constructor() {
        this.orderId = this.getOrderId();
        this.init();
    }

    getOrderId() {
        const path = window.location.pathname;
        const match = path.match(/\/orders\/(\d+)/);
        return match ? match[1] : null;
    }

    init() {
        if (this.orderId) {
            this.loadOrder();
        }
    }

    async loadOrder() {
        try {
            const response = await fetch(`/orders/${this.orderId}`, {
                headers: { 'Accept': 'application/json' }
            });
            const order = await response.json();
            this.renderOrder(order);
        } catch (error) {
            console.error('Error loading order:', error);
        }
    }

    renderOrder(order) {
        const container = document.getElementById('orderDetail');
        if (!container || !order) return;

        container.innerHTML = `
            <div class="order-card">
                <div class="order-header">
                    <h2>Order #${order.order_number}</h2>
                    <span class="order-status status-${order.status}">${order.status}</span>
                </div>

                <div class="order-status-timeline">
                    ${this.renderTimeline(order.status)}
                </div>

                <div class="order-sections">
                    <div class="order-section">
                        <h3>Delivery Address</h3>
                        <p>${order.shipping_name}</p>
                        <p>${order.shipping_phone}</p>
                        <p>${order.shipping_address}</p>
                    </div>

                    <div class="order-section">
                        <h3>Delivery Details</h3>
                        <p><strong>Date:</strong> ${order.delivery_date}</p>
                        <p><strong>Time:</strong> ${order.delivery_time}</p>
                        ${order.delivery_notes ? `<p><strong>Notes:</strong> ${order.delivery_notes}</p>` : ''}
                        ${order.gift_message ? `<p><strong>Gift Message:</strong> ${order.gift_message}</p>` : ''}
                    </div>

                    <div class="order-section">
                        <h3>Items</h3>
                        ${(order.items || []).map(item => `
                            <div class="order-item">
                                <span>${item.quantity}x ${item.product_name}</span>
                                <span>₱${item.subtotal}</span>
                            </div>
                        `).join('')}
                    </div>

                    <div class="order-section">
                        <h3>Payment</h3>
                        <p><strong>Method:</strong> ${order.payment_method}</p>
                        <p><strong>Status:</strong> ${order.payment_status}</p>
                    </div>

                    <div class="order-totals">
                        <div class="total-row">
                            <span>Subtotal</span>
                            <span>₱${order.subtotal}</span>
                        </div>
                        <div class="total-row">
                            <span>Delivery</span>
                            <span>₱${order.delivery_fee}</span>
                        </div>
                        <div class="total-row">
                            <span>Total</span>
                            <span>₱${order.total}</span>
                        </div>
                    </div>

                    ${order.tracking_number ? `
                    <div class="order-section">
                        <h3>Tracking</h3>
                        <p><strong>Tracking #:</strong> ${order.tracking_number}</p>
                        <p><strong>Courier:</strong> ${order.courier}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;
    }

    renderTimeline(status) {
        const statuses = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
        const currentIndex = statuses.indexOf(status);

        return `
            <div class="timeline">
                ${statuses.map((s, i) => `
                    <div class="timeline-step ${i <= currentIndex ? 'active' : ''} ${i === currentIndex ? 'current' : ''}">
                        <div class="step-dot"></div>
                        <div class="step-label">${s.replace('_', ' ')}</div>
                    </div>
                `).join('')}
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new OrderDetailHandler();
});