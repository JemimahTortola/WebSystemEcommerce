<?php $__env->startSection('title', 'Shop - Flourista'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/user/shop.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/buttons.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('css/forms.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="shop-page">
        <main class="shop-main">
            <div class="shop-header">
                <h1><?php echo e(request('category') ? ucfirst(request('category')) : 'All Products'); ?></h1>
                <div class="header-right">
                    <span class="product-count"><?php echo e($products->count()); ?> products</span>
                    <div class="sort-dropdown">
                        <label>Sort by:</label>
                        <select id="sortSelect">
                            <option value="newest" <?php echo e(request('sort', 'newest') == 'newest' ? 'selected' : ''); ?>>Newest</option>
                            <option value="price_low" <?php echo e(request('sort') == 'price_low' ? 'selected' : ''); ?>>Price: Low to High</option>
                            <option value="price_high" <?php echo e(request('sort') == 'price_high' ? 'selected' : ''); ?>>Price: High to Low</option>
                        </select>
                    </div>
                    <script>document.getElementById('sortSelect').addEventListener('change', function() { var url = new URL(window.location.href); url.searchParams.set('sort', this.value); window.location.href = url; });</script>
                </div>
            </div>

            <div class="category-tabs">
                <a href="<?php echo e(route('shop')); ?>" class="tab <?php echo e(!request('category') ? 'active' : ''); ?>">All</a>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('shop', ['category' => $cat->slug])); ?>" class="tab <?php echo e(request('category') == $cat->slug ? 'active' : ''); ?>"><?php echo e($cat->name); ?></a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div id="alertContainer"></div>

            <div class="products-grid">
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="product-card" onclick="window.location.href='<?php echo e(route('shop.product', $product->slug)); ?>'">
                    <div class="product-image-wrapper">
                        <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="product-image">
                        <button class="btn-add-cart" data-product-id="<?php echo e($product->id); ?>" onclick="event.stopPropagation()" <?php echo e($product->stock < 1 ? 'disabled' : ''); ?>>Add to Cart</button>
                    </div>
                    <div class="product-info">
                        <div class="product-name"><?php echo e($product->name); ?></div>
                        <div class="product-price">₱<?php echo e(number_format($product->price, 0)); ?></div>
                        <div class="product-stock <?php echo e($product->stock < 5 ? 'low-stock' : ''); ?> <?php echo e($product->stock < 1 ? 'out-of-stock' : 'in-stock'); ?>">
                            <?php if($product->stock < 1): ?> Out of Stock
                            <?php elseif($product->stock < 5): ?> Low Stock
                            <?php else: ?> In Stock
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="empty-state" style="grid-column: 1 / -1;">
                    <div class="empty-icon">🌸</div>
                    <h3>No Products Found</h3>
                    <p>Try adjusting your filters</p>
                </div>
                <?php endif; ?>
            </div>

            <div class="pagination">
            </div>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/shop/index.blade.php ENDPATH**/ ?>