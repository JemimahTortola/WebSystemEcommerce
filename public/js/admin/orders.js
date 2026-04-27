class OrdersHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.modal = document.getElementById('orderModal');
        this.init();
    }

    init() {
        this.loadOrders();
        this.bindEvents();
    }

    bindEvents() {
        document.getElementById('statusFilter')?.addEventListener('change', () => this.loadOrders());
    }

    async loadOrders() {
        const status = document.getElementById('statusFilter')?.value || '';
        const grid = document.getElementById('ordersGrid');
        
        grid.innerHTML = '<div class="loading">Loading orders...</div>';

        try {
            const url = status ? '/admin/orders/data?status=' + status : '/admin/orders/data';
            const response = await fetch(url);
            const orders = await response.json();
            
            this.renderOrders(orders);
        } catch (error) {
            console.error('Error:', error);
            grid.innerHTML = '<div class="error">Error loading orders</div>';
        }
    }

    init() {
        this.loadOrders();
        this.bindEvents();
    }

    bindEvents() {
        document.getElementById('statusFilter')?.addEventListener('change', () => this.loadOrders());
    }

    async loadOrders() {
        const status = document.getElementById('statusFilter')?.value || '';

        try {
            const params = new URLSearchParams({ status });
            const response = await fetch(`/admin/orders/data?${params}`, {
                headers: { 'Accept': 'application/json' }
            });
            const orders = await response.json();
            this.renderOrders(orders);
        } catch (error) {
            console.error('Error loading orders:', error);
        }
    }

    renderOrders(orders) {
        const grid = document.getElementById('ordersGrid');
        if (!orders.length) {
            grid.innerHTML = '<div class="empty-state">No orders found</div>';
            return;
        }

        grid.innerHTML = orders.map(order => `
            <div class="order-card">
                <div class="order-header">
                    <span class="order-number">#${order.order_number}</span>
                    <span class="status-badge status-${order.status}">${order.status}</span>
                </div>
                <div class="order-info">
                    <p><strong>Customer:</strong> ${order.user?.name || 'N/A'}</p>
                    <p><strong>Total:</strong> ₱${Number(order.total_amount).toFixed(2)}</p>
                    <p><strong>Payment:</strong> <span class="status-badge status-${order.payment_status}">${order.payment_status || 'pending'}</span></p>
                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                </div>
                <div class="order-actions">
                    <button class="btn btn-secondary" onclick="viewOrder(${order.id})">View Details</button>
                    <select onchange="updateOrderStatus(${order.id}, this.value)" class="status-select">
                        <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                        <option value="processing" ${order.status === 'processing' ? 'selected' : ''}>Processing</option>
                        <option value="completed" ${order.status === 'completed' ? 'selected' : ''}>Completed</option>
                        <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                    </select>
                </div>
            </div>
        `).join('');
    }

    async viewOrder(id) {
        try {
            const response = await fetch(`/admin/orders/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            const order = await response.json();
            this.showOrderDetails(order);
        } catch (error) {
            console.error('Error loading order:', error);
        }
    }

    showOrderDetails(order) {
        const details = document.getElementById('orderDetails');
        const hasReceipt = order.payment_receipt;
        
        details.innerHTML = `
            <div class="detail-section">
                <h4>Order #${order.order_number}</h4>
                <p><strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span></p>
                <p><strong>Payment:</strong> <span class="status-badge status-${order.payment_status}">${order.payment_status || 'pending'}</span></p>
                <p><strong>Customer:</strong> ${order.user?.name} (${order.user?.email})</p>
                <p><strong>Total:</strong> ₱${Number(order.total_amount).toFixed(2)}</p>
                <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
            </div>
            ${hasReceipt ? `
            <div class="detail-section">
                <h4>Payment Receipt</h4>
                <img src="/storage/${order.payment_receipt}" alt="Payment Receipt" 
                     class="receipt-thumbnail" 
                     onclick="openReceiptModal('/storage/${order.payment_receipt}')"
                     title="Click to enlarge">
                <div style="margin-top: 1rem;">
                    <button class="btn btn-success" onclick="verifyPayment(${order.id}, 'verified')">Verify Payment</button>
                    <button class="btn btn-danger" onclick="verifyPayment(${order.id}, 'rejected')">Reject</button>
                </div>
            </div>
            ` : `
            <div class="detail-section">
                <h4>Payment Receipt</h4>
                <p style="color: var(--text-muted);">No receipt uploaded yet</p>
            </div>
            `}
            <div class="detail-section">
                <h4>Items</h4>
                ${order.items?.map(item => `
                    <div class="order-item">
                        <span>${item.product?.name || item.product_name || 'Product'}</span>
                        <span>x${item.quantity}</span>
                        <span>₱${Number(item.price).toFixed(2)}</span>
                    </div>
                `).join('') || '<p>No items</p>'}
            </div>
        `;
        this.modal.classList.add('active');
    }

    closeOrderModal() {
        this.modal.classList.remove('active');
    }

    async updateOrderStatus(id, status) {
        try {
            await fetch(`/admin/orders/${id}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({ status }),
            });
            this.loadOrders();
        } catch (error) {
            console.error('Error updating status:', error);
        }
    }

    async verifyPayment(id, status) {
        try {
            await fetch(`/admin/orders/${id}/verify-payment`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken,
                },
                body: JSON.stringify({ payment_status: status }),
            });
            this.loadOrders();
            this.closeOrderModal();
        } catch (error) {
            console.error('Error verifying payment:', error);
        }
    }
}

let ordersHandler;

document.addEventListener('DOMContentLoaded', () => {
    ordersHandler = new OrdersHandler();
});

function viewOrder(id) {
    ordersHandler?.viewOrder(id);
}

function closeOrderModal() {
    ordersHandler?.closeOrderModal();
}

function updateOrderStatus(id, status) {
    ordersHandler?.updateOrderStatus(id, status);
}

function verifyPayment(id, status) {
    ordersHandler?.verifyPayment(id, status);
}

function openReceiptModal(src) {
    let modal = document.getElementById('receiptModal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'receiptModal';
        modal.className = 'image-modal';
        modal.innerHTML = `
            <span class="modal-close" onclick="closeReceiptModal()">&times;</span>
            <img class="modal-content" id="receiptFullImage">
        `;
        document.body.appendChild(modal);
    }
    document.getElementById('receiptFullImage').src = src;
    modal.classList.add('active');
}

function closeReceiptModal() {
    const modal = document.getElementById('receiptModal');
    if (modal) modal.classList.remove('active');
}