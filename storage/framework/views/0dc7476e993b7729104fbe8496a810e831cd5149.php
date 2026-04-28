<?php $__env->startSection('title', 'Products - Flourista Admin'); ?>

<?php $__env->startSection('page-title', 'Products'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/admin/products.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="page-header">
    <div>
        <h2>Products</h2>
        <p>Total: <span id="productCount">0</span> products</p>
    </div>
    <div class="header-right">
        <button class="btn btn-primary" onclick="openProductModal()">Add Product</button>
    </div>
</div>

<div class="filters-bar">
    <input type="text" id="searchInput" placeholder="Search products...">
    <select id="categoryFilter">
        <option value="">All Categories</option>
    </select>
</div>

<div class="data-table-wrapper">
    <table class="data-table" id="productsTable">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productsTableBody"></tbody>
    </table>
</div>

<div class="pagination-wrapper" id="paginationWrapper"></div>

<div class="modal" id="productModal">
    <div class="modal-content modal-lg">
        <div class="modal-header">
            <h2 id="productModalTitle">Add Product</h2>
            <button class="modal-close" onclick="closeProductModal()">×</button>
        </div>
        <form id="productForm" enctype="multipart/form-data">
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="productName" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" id="categorySelect" required></select>
                </div>
                <div class="form-group">
                    <label>Price (₱)</label>
                    <input type="number" name="price" step="0.01" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Stock</label>
                    <input type="number" name="stock" required>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" id="productImage" accept="image/*">
                    <div id="currentImage" style="margin-top: 8px; display: none;">
                        <small>Current: <img src="" id="currentImagePreview" style="width: 60px; height: 60px; object-fit: cover;"></small>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="3"></textarea>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" name="is_active" id="productActive" checked>
                <label for="productActive">Active</label>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeProductModal()">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitProductForm()">Save</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/admin/products.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/admin/products.blade.php ENDPATH**/ ?>