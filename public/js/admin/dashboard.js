// Handles dashboard data loading and rendering (stats, charts, tables)
class DashboardHandler {
    constructor() {
        this.init();
    }

    // Initialize - load dashboard data
    init() {
        this.loadDashboard();
    }

    // Fetches dashboard data from server
    async loadDashboard() {
        try {
            const response = await fetch('/admin/dashboard-data');
            
            if (!response.ok) {
                return;
            }
            
            const data = await response.json();
            this.renderDashboard(data);
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // Updates dashboard widgets with fetched data
    renderDashboard(data) {
        const revenueEl = document.getElementById('totalRevenue');
        const ordersEl = document.getElementById('totalOrders');
        const customersEl = document.getElementById('totalCustomers');
        const productsEl = document.getElementById('totalProducts');
        
        // Update stat cards (with null checks)
        if (revenueEl) revenueEl.textContent = `₱${data.total_revenue || '0.00'}`;
        if (ordersEl) ordersEl.textContent = data.total_orders || 0;
        if (customersEl) customersEl.textContent = data.total_customers || 0;
        if (productsEl) productsEl.textContent = data.total_products || 0;

        // Render recent orders list
        const recentOrders = document.getElementById('recentOrders');
        if (recentOrders && data.recent_orders && data.recent_orders.length > 0) {
            recentOrders.innerHTML = data.recent_orders.map(order => `
                <div class="recent-order-item">
                    <div class="order-info">
                        <span class="order-number">#${order.order_number}</span>
                        <span class="order-date">${order.order_date}</span>
                    </div>
                    <span class="order-status ${order.status}">${order.status}</span>
                    <span class="order-total">₱${order.total}</span>
                </div>
            `).join('');
        } else if (recentOrders) {
            recentOrders.innerHTML = '<p class="empty-state-text">No orders yet</p>';
        }

        // Render top products list
        const topProducts = document.getElementById('topProducts');
        if (topProducts && data.top_products && data.top_products.length > 0) {
            topProducts.innerHTML = data.top_products.map((product, i) => `
                <div class="top-product-item">
                    <span class="rank">${i + 1}</span>
                    <div class="product-info">
                        <span class="product-name">${product.name}</span>
                        <span class="product-sales">${product.sold} sold</span>
                    </div>
                </div>
            `).join('');
        } else if (topProducts) {
            topProducts.innerHTML = '<p class="empty-state-text">No products yet</p>';
        }

        // Render inventory status cards (in stock, low stock, out of stock)
        const inventoryStatus = document.getElementById('inventoryStatus');
        if (inventoryStatus) {
            const inv = data.inventory || { in_stock: 0, low_stock: 0, out_of_stock: 0 };
            inventoryStatus.innerHTML = `
                <div class="inventory-item in-stock">
                    <span>${inv.in_stock}</span>
                    <label>In Stock</label>
                </div>
                <div class="inventory-item low-stock">
                    <span>${inv.low_stock}</span>
                    <label>Low Stock</label>
                </div>
                <div class="inventory-item out-of-stock">
                    <span>${inv.out_of_stock}</span>
                    <label>Out of Stock</label>
                </div>
            `;
        }
    }
}

// Initialize dashboard when page loads
document.addEventListener('DOMContentLoaded', () => {
    new DashboardHandler();
});