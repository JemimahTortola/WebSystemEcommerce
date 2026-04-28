<header class="header">
    <div class="header-inner">
        <a href="<?php echo e(route('home')); ?>" class="logo">
            Flour<span>ista</span>
        </a>
        
        <nav class="header-nav">
            <a href="<?php echo e(route('shop')); ?>">Shop</a>
            <a href="<?php echo e(route('about')); ?>">About</a>
            <a href="<?php echo e(route('contact')); ?>">Contact</a>
            <a href="<?php echo e(route('delivery-areas')); ?>">Delivery Areas</a>
        </nav>
        
        <div class="header-right">
            <a href="<?php echo e(route('cart')); ?>" class="header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="9" cy="21" r="1"></circle>
                    <circle cx="20" cy="21" r="1"></circle>
                    <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>
                <span class="cart-badge" id="cartCount">0</span>
            </a>
            
            <?php if(auth()->guard()->check()): ?>
            <div class="notification-wrapper">
                <button class="header-icon" id="notificationBtn" title="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                    <span class="cart-badge" id="notifBadge" style="display: none;">0</span>
                </button>
                <div class="notification-dropdown" id="notifDropdown">
                    <div class="notif-header">
                        <h4>Notifications</h4>
                        <button onclick="markAllRead()">Mark all read</button>
                    </div>
                    <div class="notif-list" id="notifList">
                        <div class="notif-empty">No notifications</div>
                    </div>
                </div>
            </div>
            
            <div class="user-dropdown">
                <button class="user-toggle">
                    <span class="user-avatar"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></span>
                    <span class="user-name"><?php echo e(Auth::user()->name); ?></span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </button>
                <div class="dropdown-menu">
                    <a href="<?php echo e(route('profile')); ?>">My Profile</a>
                    <a href="<?php echo e(route('orders')); ?>">My Orders</a>
                    <a href="<?php echo e(route('wishlist')); ?>">My Wishlist</a>
                    <a href="<?php echo e(route('profile.addresses')); ?>">My Address</a>
                    <hr>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <a href="<?php echo e(route('login')); ?>" class="btn-login">Login</a>
            <?php endif; ?>
        </div>
    </div>
</header><?php /**PATH C:\Users\jemim\Ecoms-apps\ecoms-florist\resources\views/partials/header.blade.php ENDPATH**/ ?>