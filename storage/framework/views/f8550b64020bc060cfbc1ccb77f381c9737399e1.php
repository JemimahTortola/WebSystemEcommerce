<?php $__env->startSection('title', 'Reviews - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Reviews'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/reviews.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2>Reviews</h2>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>User</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="reviewsTableBody"></tbody>
    </table>
</div>

<div class="modal" id="reviewModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2>Review Details</h2>
            <button class="modal-close" onclick="closeReviewModal()">×</button>
        </div>
        <div class="modal-body">
            <div id="reviewDetails"></div>
            <hr>
            <div class="form-group">
                <label>Admin Comment</label>
                <textarea id="adminComment" class="form-control" rows="3" placeholder="Add your comment..."></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn btn-secondary" onclick="closeReviewModal()">Close</button>
                <button class="btn btn-primary" onclick="saveAdminComment()">Save Comment</button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/admin/reviews.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/reviews.blade.php ENDPATH**/ ?>