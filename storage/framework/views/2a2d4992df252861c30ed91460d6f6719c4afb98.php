<?php $__env->startSection('title', 'Shopping Cart - Flourista'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/user/cart.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="cart-page">
    <div class="page-header">
        <h1>Shopping Cart</h1>
    </div>

    <div id="alertContainer"></div>

    <div class="cart-layout">
        <div class="cart-items" id="cartGrid">
            <div class="empty-state" id="emptyCart" style="display: none;">
                <div class="empty-icon">🛒</div>
                <h3>Your Cart is Empty</h3>
                <p>Looks like you haven't added anything yet!</p>
                <a href="<?php echo e(route('shop')); ?>" class="btn btn-primary">Browse Shop</a>
            </div>
        </div>

        <div class="cart-summary" id="cartSummary">
            <h3>Order Summary</h3>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>₱0.00</span>
            </div>
            <div class="summary-row total">
                <span>Total</span>
                <span>₱0.00</span>
            </div>
            <a href="<?php echo e(route('checkout')); ?>" class="btn btn-primary btn-checkout">
                Proceed to Checkout
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/user/cart.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/cart/index.blade.php ENDPATH**/ ?>