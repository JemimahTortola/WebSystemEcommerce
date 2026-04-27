<?php $__env->startSection('title', $product->name . ' - Flourista'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/user/product.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="product-detail-page">
        <!-- Product Main Info -->
        <div class="product-main">
            <div class="product-image-section">
                <img src="<?php echo e($product->image); ?>" alt="<?php echo e($product->name); ?>" class="main-product-image">
            </div>
            
            <div class="product-info-section">
                <div class="product-category"><?php echo e($product->category_name); ?></div>
                <h1 class="product-title"><?php echo e($product->name); ?></h1>
                <div class="product-price">₱<?php echo e(number_format($product->price, 0)); ?></div>
                
                <div class="product-stock-status">
                    <?php if($product->stock < 1): ?>
                        <span class="stock out">Out of Stock</span>
                    <?php elseif($product->stock < 5): ?>
                        <span class="stock low">Low Stock - Only <?php echo e($product->stock); ?> left</span>
                    <?php else: ?>
                        <span class="stock in">In Stock</span>
                    <?php endif; ?>
                </div>
                
                <p class="product-description"><?php echo e($product->description); ?></p>
                
                <div class="product-actions">
                    <div class="quantity-selector">
                        <button type="button" class="qty-btn minus">−</button>
                        <input type="number" value="1" min="1" max="<?php echo e($product->stock); ?>" class="qty-input" <?php echo e($product->stock < 1 ? 'disabled' : ''); ?>>
                        <button type="button" class="qty-btn plus">+</button>
                    </div>
                    
                    <button type="button" class="btn btn-add-cart" data-product-id="<?php echo e($product->id); ?>" <?php echo e($product->stock < 1 ? 'disabled' : ''); ?>>
                        🛒 Add to Cart
                    </button>
                    
                    <button type="button" class="btn btn-wishlist">
                        ❤️ Add to Wishlist
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Reviews Section -->
        <div class="product-reviews-section">
            <h2>Customer Reviews</h2>
            
            <?php if($reviews->count() > 0): ?>
                <div class="reviews-list">
                    <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="review-item">
                        <div class="review-header">
                            <span class="review-user"><?php echo e($review->user_name); ?></span>
                            <span class="review-rating"><?php echo e(str_repeat('⭐', $review->rating)); ?></span>
                        </div>
                        <p class="review-comment"><?php echo e($review->comment); ?></p>
                        <span class="review-date"><?php echo e(\Carbon\Carbon::parse($review->created_at)->format('M d, Y')); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
            <?php endif; ?>
            
            <?php if(auth()->guard()->check()): ?>
            <form class="review-form" method="POST" action="<?php echo e(route('reviews')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                <h3>Write a Review</h3>
                <div class="rating-select">
                    <label>Your Rating:</label>
                    <select name="rating" class="form-input" required>
                        <option value="">Select rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ (5) - Excellent</option>
                        <option value="4">⭐⭐⭐⭐ (4) - Great</option>
                        <option value="3">⭐⭐⭐ (3) - Good</option>
                        <option value="2">⭐⭐ (2) - Fair</option>
                        <option value="1">⭐ (1) - Poor</option>
                    </select>
                </div>
                <div class="form-group">
                    <textarea name="comment" class="form-input" rows="4" placeholder="Share your experience with this product..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </form>
            <?php else: ?>
            <p class="login-to-review">Please <a href="<?php echo e(route('login')); ?>">login</a> to write a review.</p>
            <?php endif; ?>
        </div>
        
        <!-- Related Products -->
        <?php if($relatedProducts->count() > 0): ?>
        <div class="related-products-section">
            <h2>You May Also Like</h2>
            <div class="related-products-grid">
                <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="related-product-card" onclick="window.location.href='<?php echo e(route('shop.product', $related->slug)); ?>'">
                    <img src="<?php echo e($related->image); ?>" alt="<?php echo e($related->name); ?>">
                    <div class="related-info">
                        <h4><?php echo e($related->name); ?></h4>
                        <span class="price">₱<?php echo e(number_format($related->price, 0)); ?></span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/user/product.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/shop/product.blade.php ENDPATH**/ ?>