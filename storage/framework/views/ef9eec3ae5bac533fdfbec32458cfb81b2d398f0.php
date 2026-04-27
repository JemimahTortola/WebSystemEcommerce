<?php $__env->startSection('title', 'Orders - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Orders'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/orders.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2>Orders</h2>
</div>

<div class="filters-bar">
    <select id="statusFilter">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="processing">Processing</option>
        <option value="completed">Completed</option>
        <option value="cancelled">Cancelled</option>
    </select>
</div>

<div class="orders-grid" id="ordersGrid"></div>

<div class="modal" id="orderModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Order Details</h2>
            <button class="modal-close" onclick="closeOrderModal()">×</button>
        </div>
        <div class="order-details" id="orderDetails"></div>
    </div>
</div>

<script src="<?php echo e(asset('js/admin/orders.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/orders.blade.php ENDPATH**/ ?>