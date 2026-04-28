class OrdersHandler {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.modal = document.getElementById('orderModal');
        if (!this.modal) {
            console.error('Order modal not found!');
        }
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
        
        if (!grid) {
            console.error('ordersGrid not found!');
            return;
        }

        grid.innerHTML = '<div class="loading">Loading orders...</div>';

        try {
            const params = new URLSearchParams({ status });
            const response = await fetch(`/admin/orders/data?${params}`, {
                headers: { 'Accept': 'application/json' }
            });
            const orders = await response.json();
            console.log('Orders loaded:', orders);
            this.renderOrders(orders);
        } catch (error) {
            console.error('Error loading orders:', error);
            grid.innerHTML = '<div class="error">Error loading orders</div>';
        }
    }

    renderOrders(orders) {
        const grid = document.getElementById('ordersGrid');
        if (!orders.length) {
            grid.innerHTML = '<div class="empty-state">No orders found</div>';
            return;
        }

        grid.innerHTML = orders.map(order => {
            const paymentMethod = order.payment_method === 'gcash' ? 'E-Wallet' : 
                               order.payment_method === 'bank' ? 'Bank' : 
                               order.payment_method === 'cod' ? 'COD' : 'N/A';
            
            return `
            <div class="order-card">
                <div class="order-header">
                    <span class="order-number">#${order.order_number}</span>
                    <span class="status-badge status-${order.status}">${order.status}</span>
                </div>
                <div class="order-info">
                    <p><strong>Customer:</strong> ${order.user?.name || 'N/A'}</p>
                    <p><strong>Total:</strong> ₱${Number(order.total_amount).toFixed(2)}</p>
                    <p><strong>Payment:</strong> ${paymentMethod} <span class="status-badge status-${order.payment_status}">${order.payment_status || 'pending'}</span></p>
                    <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleDateString()}</p>
                    ${order.delivery_date ? `<p><strong>Delivery:</strong> 📅 ${new Date(order.delivery_date).toLocaleDateString()}</p>` : ''}
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
        `}).join('');
    }

    async viewOrder(id) {
        try {
            const response = await fetch(`/admin/orders/${id}`, {
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) {
                console.error('Failed to load order:', response.status);
                alert('Failed to load order details');
                return;
            }
            const order = await response.json();
            this.showOrderDetails(order);
        } catch (error) {
            console.error('Error loading order:', error);
            alert('Error loading order details');
        }
    }

    showOrderDetails(order) {
        const details = document.getElementById('orderDetails');
        const hasReceipt = order.payment_receipt;
        
        const paymentMethod = order.payment_method === 'gcash' ? 'E-Wallet' : 
                           order.payment_method === 'bank' ? 'Bank' : 
                           order.payment_method === 'cod' ? 'COD' : 'N/A';
        
        const shipping = order.shipping || {};
        
        details.innerHTML = `
            <div class="detail-section">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h4>Order #${order.order_number || order.id}</h4>
                    <button class="btn btn-secondary" onclick="printOrder(${order.id})">🖨️ Print</button>
                </div>
                <p><strong>Status:</strong> <span class="status-badge status-${order.status}">${order.status}</span></p>
                <p><strong>Payment Method:</strong> ${paymentMethod}</p>
                <p><strong>Payment Status:</strong> <span class="status-badge status-${order.payment_status}">${order.payment_status || 'pending'}</span></p>
                <p><strong>Customer:</strong> ${order.user?.name || 'N/A'} (${order.user?.email || 'N/A'})</p>
                <p><strong>Total:</strong> ₱${Number(order.total_amount).toFixed(2)}</p>
                <p><strong>Date:</strong> ${new Date(order.created_at).toLocaleString()}</p>
            </div>
            <div class="detail-section">
                <h4>Delivery Address</h4>
                <p><strong>Name:</strong> ${shipping.shipping_name || 'N/A'}</p>
                <p><strong>Phone:</strong> ${shipping.shipping_phone || 'N/A'}</p>
                <p><strong>Address:</strong> ${shipping.shipping_address || 'N/A'}</p>
                ${shipping.delivery_date ? `<p><strong>Delivery Date:</strong> ${shipping.delivery_date}</p>` : ''}
                ${shipping.delivery_time ? `<p><strong>Delivery Time:</strong> ${shipping.delivery_time}</p>` : ''}
                ${shipping.delivery_notes ? `<p><strong>Notes:</strong> ${shipping.delivery_notes}</p>` : ''}
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
                ${(order.items && order.items.length) ? order.items.map(item => `
                    <div class="order-item">
                        <div style="flex: 1;">
                            <span>${item.product?.name || item.product_name || 'Product'}</span>
                            <span style="margin-left: 1rem;">x${item.quantity}</span>
                            <span style="margin-left: 1rem;">₱${Number(item.price).toFixed(2)}</span>
                        </div>
                        ${item.delivery_date ? `<div class="item-delivery">📅 ${new Date(item.delivery_date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' })}</div>` : '<div class="item-delivery" style="color: #999;">📅 No delivery date</div>'}
                    </div>
                `).join('') : '<p>No items found</p>'}
            </div>
        `;
        this.modal.classList.add('active');
    }

    closeOrderModal() {
        this.modal.classList.remove('active');
    }

    printOrder(id) {
        const printWindow = window.open(`/admin/orders/${id}?print=true`, '_blank');
        if (!printWindow) {
            alert('Please allow popups for this site to print orders.');
            return;
        }
        // Fallback: trigger print after a delay if onload doesn't work
        setTimeout(() => {
            try {
                printWindow.focus();
                printWindow.print();
            } catch (e) {
                console.log('Print triggered via button');
            }
        }, 1000);
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

function printOrder(id) {
    ordersHandler?.printOrder(id);
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

let currentMonth = new Date().getMonth();
let currentYear = new Date().getFullYear();
let allOrders = [];

function switchView(view) {
    document.getElementById('gridViewBtn').classList.toggle('active', view === 'grid');
    document.getElementById('calendarViewBtn').classList.toggle('active', view === 'calendar');
    document.getElementById('ordersGrid').style.display = view === 'grid' ? 'grid' : 'none';
    document.getElementById('calendarView').style.display = view === 'calendar' ? 'block' : 'none';
    if (view === 'calendar') {
        loadOrdersForCalendar();
    }
}

async function loadOrdersForCalendar() {
    try {
        const response = await fetch('/admin/orders/calendar-data');
        allOrders = await response.json();
        console.log('Calendar data loaded:', allOrders);
        
        // Auto-navigate to month with orders
        if (allOrders.length > 0) {
            const firstOrderDate = new Date(allOrders[0].delivery_date);
            currentMonth = firstOrderDate.getMonth();
            currentYear = firstOrderDate.getFullYear();
        }
        
        renderCalendar();
    } catch (error) {
        console.error('Error loading calendar data:', error);
        // Fallback to regular orders data
        try {
            const response = await fetch('/admin/orders/data');
            allOrders = await response.json();
            console.log('Fallback data loaded:', allOrders);
            renderCalendar();
        } catch (e) {
            console.error('Error loading fallback data:', e);
        }
    }
}

function renderCalendar() {
    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
        'July', 'August', 'September', 'October', 'November', 'December'];
    document.getElementById('calendarMonth').textContent = `${monthNames[currentMonth]} ${currentYear}`;
    
    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();
    
    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    let html = days.map(d => `<div class="calendar-header">${d}</div>`).join('');
    
    // Add empty cells for days before the first day of month
    for (let i = 0; i < firstDay; i++) {
        html += '<div class="calendar-day" style="background: #f9f9f9;"></div>';
    }
    
    // Generate calendar days
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayOrders = allOrders.filter(o => {
            if (!o.delivery_date) return false;
            // Handle both "2026-04-29" and "2026-04-29 00:00:00" formats
            const orderDate = o.delivery_date.split(' ')[0];
            return orderDate === dateStr;
        });
        
        html += `
            <div class="calendar-day ${dayOrders.length > 0 ? 'has-orders' : ''}">
                <div class="calendar-day-number">${day}</div>
                ${dayOrders.map(o => `
                    <div class="calendar-order status-${o.status}" onclick="viewOrder(${o.id})" 
                         title="Order #${o.order_number} - ${o.payment_method?.toUpperCase() || 'N/A'} - ₱${Number(o.total_amount).toFixed(2)}">
                        #${o.order_number}
                    </div>
                `).join('')}
            </div>
        `;
    }
    
    document.getElementById('calendarGrid').innerHTML = html;
}

function changeMonth(delta) {
    currentMonth += delta;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    } else if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}