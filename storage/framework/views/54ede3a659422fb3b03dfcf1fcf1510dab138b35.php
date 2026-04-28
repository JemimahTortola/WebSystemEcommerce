<?php $__env->startSection('title', 'Categories - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Categories'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/categories.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <h2>Categories</h2>
    <div class="header-right">
        <button class="btn btn-primary" onclick="openCategoryModal()">Add Category</button>
    </div>
</div>

<div class="data-table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Products</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="categoriesTableBody"></tbody>
    </table>
</div>

<div class="modal" id="categoryModal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="categoryModalTitle">Add Category</h2>
            <button class="modal-close" onclick="closeCategoryModal()">×</button>
        </div>
        <form id="categoryForm">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="categoryName" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label>Image</label>
                <input type="file" name="image" accept="image/*">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeCategoryModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/admin/categories.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/categories.blade.php ENDPATH**/ ?>