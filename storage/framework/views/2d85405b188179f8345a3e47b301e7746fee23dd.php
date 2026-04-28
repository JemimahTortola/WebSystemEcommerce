<?php $__env->startSection('title', 'My Profile - Flourista'); ?>

<?php $__env->startSection('styles'); ?>
<link rel="stylesheet" href="<?php echo e(asset('css/user/profile.css')); ?>">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="profile-page">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h1>My Profile</h1>
        </div>

        <div class="profile-content">
            <!-- Profile Form Card -->
            <div class="profile-card">
                <h2>Account Information</h2>
                <p class="card-subtitle">Update your personal details below</p>
                
                <div id="alertContainer"></div>
                
                <!-- Quick alert for success/error -->
                <?php if(session('success')): ?>
                <div class="alert alert-success"><?php echo e(session('success')); ?></div>
                <?php endif; ?>
                <?php if(session('error')): ?>
                <div class="alert alert-error"><?php echo e(session('error')); ?></div>
                <?php endif; ?>

                <form id="profileForm" method="POST" action="<?php echo e(route('profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            class="form-input" 
                            placeholder="Enter your full name"
                            required
                            value="<?php echo e(auth()->user()->name ?? old('name')); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            class="form-input" 
                            placeholder="Enter your email address"
                            required
                            value="<?php echo e(auth()->user()->email ?? old('email')); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="phone">Phone Number</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input" 
                            placeholder="Enter your phone number"
                            value="<?php echo e(auth()->user()->phone ?? old('phone')); ?>"
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-submit">
                        <span class="btn-text">Save Changes</span>
                    </button>
                </form>
            </div>

            <!-- Quick Links Card -->
            <div class="profile-card">
                <h2>Quick Links</h2>
                <p class="card-subtitle">Access your account features</p>
                
                <div class="quick-links">
                    <a href="<?php echo e(route('orders')); ?>" class="quick-link">
                        <span class="link-icon">📦</span>
                        <span class="link-text">My Orders</span>
                    </a>
                    <a href="<?php echo e(route('wishlist')); ?>" class="quick-link">
                        <span class="link-icon">❤️</span>
                        <span class="link-text">My Wishlist</span>
                    </a>
                    <a href="<?php echo e(route('profile.addresses')); ?>" class="quick-link">
                        <span class="link-icon">📍</span>
                        <span class="link-text">My Addresses</span>
                    </a>
                    <a href="<?php echo e(route('cart')); ?>" class="quick-link">
                        <span class="link-icon">🛒</span>
                        <span class="link-text">Shopping Cart</span>
                    </a>
                    <a href="<?php echo e(route('checkout')); ?>" class="quick-link">
                        <span class="link-icon">💳</span>
                        <span class="link-text">Checkout</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script src="<?php echo e(asset('js/user/profile.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/profile/index.blade.php ENDPATH**/ ?>