<?php $__env->startSection('title', 'Dashboard - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-header">
    <div>
        <h1>Welcome back, <?php echo e(Auth::user()->name ?? 'Admin'); ?></h1>
        <p>Here's what's happening with your store today.</p>
    </div>
</div>

<div class="stats-grid" id="statsGrid">
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-info">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value" id="totalRevenue">₱0.00</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-info">
            <div class="stat-label">Total Orders</div>
            <div class="stat-value" id="totalOrders">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">👥</div>
        <div class="stat-info">
            <div class="stat-label">Total Customers</div>
            <div class="stat-value" id="totalCustomers">0</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🌸</div>
        <div class="stat-info">
            <div class="stat-label">Total Products</div>
            <div class="stat-value" id="totalProducts">0</div>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="dashboard-card wide">
        <h3>Recent Orders</h3>
        <div class="recent-orders" id="recentOrders">
            <p class="empty-state-text">Loading orders...</p>
        </div>
        <a href="<?php echo e(route('admin.orders')); ?>" class="view-all-link">View All Orders →</a>
    </div>

    <div class="dashboard-card">
        <h3>Top Products</h3>
        <div class="top-products" id="topProducts">
            <p class="empty-state-text">Loading products...</p>
        </div>
    </div>

    <div class="dashboard-card">
        <h3>Inventory Status</h3>
        <div class="inventory-status" id="inventoryStatus">
            <p class="empty-state-text">Loading inventory...</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/admin/dashboard.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>