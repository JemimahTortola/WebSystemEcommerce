<?php $__env->startSection('title', 'Customers - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Customers'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/users.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Customers</h2>
        <p>Total: <span id="userCount">0</span> customers</p>
    </div>
</div>

<div class="filters-bar">
    <input type="text" id="searchInput" placeholder="Search customers...">
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Orders</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTableBody"></tbody>
    </table>
</div>

<!-- Ban Modal -->
<div class="modal" id="banModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Ban User</h2>
            <button class="modal-close" onclick="closeBanModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="banDays">Ban Duration (days)</label>
                <input type="number" id="banDays" min="1" max="365" value="7" class="form-control">
            </div>
            <div class="form-group">
                <label for="banReason">Reason (optional)</label>
                <textarea id="banReason" class="form-control" rows="3" placeholder="Enter ban reason..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeBanModal()">Cancel</button>
                <button class="btn btn-danger" onclick="confirmBan()">Ban User</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/admin/users.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/users.blade.php ENDPATH**/ ?>