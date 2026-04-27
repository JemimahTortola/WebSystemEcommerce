<?php $__env->startSection('title', 'Flourista - Beautiful Flowers in Butuan City'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/user/home.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="hero-section">
    <div class="hero-content">
        <h1>Beautiful Flowers for Every Occasion</h1>
        <p>Send love with our handcrafted floral arrangements in Butuan City</p>
        <a href="<?php echo e(route('shop')); ?>" class="btn-hero">Shop Now</a>
    </div>
</div>

<div class="main-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Products</h2>
            <a href="<?php echo e(route('shop')); ?>">View All &rarr;</a>
        </div>

        <div class="featured-grid">
            <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="featured-card">
                <img src="<?php echo e($product->image ?: 'https://via.placeholder.com/300x200/e8a5aa/ffffff?text=' . urlencode($product->name)); ?>" alt="<?php echo e($product->name); ?>">
                <div class="featured-info">
                    <h3><?php echo e($product->name); ?></h3>
                    <div class="featured-price">₱<?php echo e(number_format($product->price, 0)); ?></div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="grid-column: 1/-1; text-align: center; color: var(--text-muted);">No products available yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="category-banner">
    <div class="container">
        <div class="section-header">
            <h2>Shop by Category</h2>
        </div>

        <div class="category-grid">
            <a href="<?php echo e(route('shop')); ?>?category=bouquets" class="category-card">
                <div class="cat-icon">💐</div>
                <h3>Bouquets</h3>
                <p>Hand-tied with love</p>
            </a>

            <a href="<?php echo e(route('shop')); ?>?category=arrangements" class="category-card">
                <div class="cat-icon">🌸</div>
                <h3>Arrangements</h3>
                <p>Vase arrangements</p>
            </a>

            <a href="<?php echo e(route('shop')); ?>?category=single" class="category-card">
                <div class="cat-icon">🌹</div>
                <h3>Single Stems</h3>
                <p>Perfect for gifting</p>
            </a>

            <a href="<?php echo e(route('shop')); ?>?category=bundles" class="category-card">
                <div class="cat-icon">🎁</div>
                <h3>Gift Sets</h3>
                <p>Complete gift ideas</p>
            </a>
        </div>
    </div>
</div>

<div class="why-choose-section">
    <div class="container">
        <div class="section-header">
            <h2>Why Choose Flourista</h2>
        </div>

        <div class="why-choose-grid">
            <div class="why-choose-item">
                <div class="icon">🌸</div>
                <h4>Fresh Flowers</h4>
                <p>Sourced daily from local farms for maximum freshness</p>
            </div>

            <div class="why-choose-item">
                <div class="icon">🚚</div>
                <h4>Same Day Delivery</h4>
                <p>Free delivery within Butuan City</p>
            </div>

            <div class="why-choose-item">
                <div class="icon">💐</div>
                <h4>Custom Designs</h4>
                <p>Our florists create unique arrangements</p>
            </div>

            <div class="why-choose-item">
                <div class="icon">❤️</div>
                <h4>100% Satisfaction</h4>
                <p>Guaranteed happiness with every order</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/home.blade.php ENDPATH**/ ?>