class OrdersHandler {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadOrders();
    }

    bindEvents() {
    }

    async loadOrders() {
        try {
            const response = await fetch('/orders', {
                headers: { 'Accept': 'application/json' }
            });
            const orders = await response.json();
            this.renderOrders(orders);
        } catch (error) {
            console.error('Error loading orders:', error);
        }
    }

    renderOrders(orders) {
        const list = document.getElementById('ordersList');
        const empty = document.getElementById('emptyState');

        if (!list) return;

        if (!orders || orders.length === 0) {
            list.innerHTML = '';
            if (empty) empty.style.display = 'block';
            return;
        }

        if (empty) empty.style.display = 'none';

        list.innerHTML = orders.map(order => `
            <div class="order-card" data-id="${order.id}">
                <div class="order-header">
                    <span class="order-id">#${order.order_number}</span>
                    <span class="order-status status-${order.status}">${order.status}</span>
                </div>
                <div class="order-info">
                    <div class="order-date">${this.formatDate(order.created_at)}</div>
                    <div class="order-total">₱${order.total}</div>
                </div>
                <div class="order-items-count">${order.items_count || 0} items</div>
                <a href="/orders/${order.id}" class="btn btn-secondary">View Details</a>
            </div>
        `).join('');
    }

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new OrdersHandler();
});