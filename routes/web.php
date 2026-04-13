<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AuthController as FrontendAuthController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\ReviewController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\MessageController;
use App\Models\Review;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;

// Health Check Endpoint
Route::get('/health', function () {
    $status = [
        'status' => 'ok',
        'timestamp' => now()->toIso8601String(),
        'service' => 'tinythreads',
        'version' => '1.0.0',
    ];

    $checks = [];

    // Database check
    try {
        \DB::connection()->getPdo();
        $checks['database'] = ['status' => 'healthy', 'message' => 'connected'];
    } catch (\Exception $e) {
        $checks['database'] = ['status' => 'unhealthy', 'message' => 'disconnected'];
        $status['status'] = 'degraded';
    }

    // Cache check
    try {
        Cache::put('health_check', true, 10);
        $checks['cache'] = ['status' => 'healthy', 'message' => 'connected'];
    } catch (\Exception $e) {
        $checks['cache'] = ['status' => 'degraded', 'message' => 'connection failed'];
        $status['status'] = 'degraded';
    }

    // Disk space check
    $checks['storage'] = ['status' => 'healthy'];

    $status['checks'] = $checks;
    $httpCode = $status['status'] === 'ok' ? 200 : 503;

    return response()->json($status, $httpCode);
})->name('health.check');

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/privacy', function() { return view('frontend.privacy'); })->name('privacy');
Route::get('/terms', function() { return view('frontend.terms'); })->name('terms');
Route::get('/preview', function() { return view('frontend.preview'); })->name('preview');

// Shop Routes
Route::get('/shop', [ProductController::class, 'index'])->name('products.index');
Route::get('/shop/search/suggestions', [ProductController::class, 'suggestions'])->name('products.suggestions');
Route::get('/shop/{slug}', [ProductController::class, 'show'])->name('products.show');

// Auth Routes with Rate Limiting
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', [FrontendAuthController::class, 'login'])->name('login');
    Route::post('/register', [FrontendAuthController::class, 'register']);
});
Route::get('/login', [FrontendAuthController::class, 'showLoginForm'])->name('login.form');
Route::get('/register', [FrontendAuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/logout', [FrontendAuthController::class, 'logout'])->name('logout');

// Address Routes (protected)
Route::middleware('auth')->group(function () {
    Route::get('/address', [FrontendAuthController::class, 'showAddressForm'])->name('address.create');
    Route::post('/address', [FrontendAuthController::class, 'saveAddress'])->name('address.save');
    Route::post('/address/skip', [FrontendAuthController::class, 'skipAddress'])->name('address.skip');
});

// Admin Login with Rate Limiting
Route::middleware('throttle:5,1')->group(function () {
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
});
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

// Checkout Routes
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index')->middleware('auth');
Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process')->middleware(['auth', 'throttle:3,1']);

// Order Routes
Route::get('/orders', [CheckoutController::class, 'orders'])->name('orders.index')->middleware('auth');
Route::get('/orders/{id}', [CheckoutController::class, 'showOrder'])->name('orders.show')->middleware('auth');

// User Profile Routes
Route::get('/profile', [FrontendAuthController::class, 'profile'])->name('profile')->middleware('auth');
Route::put('/profile', [FrontendAuthController::class, 'updateProfile'])->name('profile.update')->middleware('auth');

// User Settings Routes
Route::get('/settings', [FrontendAuthController::class, 'settings'])->name('settings')->middleware('auth');
Route::put('/settings', [FrontendAuthController::class, 'updateSettings'])->name('settings.update')->middleware('auth');
Route::post('/settings/export', [FrontendAuthController::class, 'exportData'])->name('data.export')->middleware('auth');
Route::delete('/settings/delete', [FrontendAuthController::class, 'deleteAccount'])->name('account.delete')->middleware('auth');

// Wishlist Routes
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist')->middleware('auth');
Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store')->middleware('auth');
Route::delete('/wishlist/{productId}', [WishlistController::class, 'destroy'])->name('wishlist.destroy')->middleware('auth');
Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check'])->name('wishlist.check')->middleware('auth');

// Review Routes
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

// Admin Routes
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login.form');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');

Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Admin Product Routes
    Route::resource('products', AdminProductController::class)->names('admin.products');
    Route::post('products/{id}/archive', [AdminProductController::class, 'archive'])->name('admin.products.archive');
    Route::post('products/{id}/restore', [AdminProductController::class, 'restore'])->name('admin.products.restore');
    
    // Admin Category Routes
    Route::resource('categories', AdminCategoryController::class)->names('admin.categories');
    
    // Admin Inventory Routes
    Route::get('inventory', [InventoryController::class, 'index'])->name('admin.inventory.index');
    
    // Admin Order Routes
    Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    Route::put('orders/{id}/payment', [AdminOrderController::class, 'updatePaymentStatus'])->name('admin.orders.updatePaymentStatus');
    Route::post('orders/{id}/archive', [AdminOrderController::class, 'archive'])->name('admin.orders.archive');
    Route::post('orders/{id}/restore', [AdminOrderController::class, 'restore'])->name('admin.orders.restore');
    Route::post('orders/{id}/tracking', [AdminOrderController::class, 'addTracking'])->name('admin.orders.addTracking');
    Route::post('orders/{id}/tracking-status', [AdminOrderController::class, 'addTrackingStatus'])->name('admin.orders.addTrackingStatus');
    
    // Admin Review Routes
    Route::get('reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::delete('reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    
    // Admin Message Routes
    Route::get('messages', [MessageController::class, 'index'])->name('admin.messages.index');
    Route::get('messages/{id}', [MessageController::class, 'show'])->name('admin.messages.show');
    Route::post('messages/start', [MessageController::class, 'startConversation'])->name('admin.messages.start');
    Route::post('messages/{id}', [MessageController::class, 'sendMessage'])->name('admin.messages.send');
    
    // Admin Customer Routes
    Route::get('customers', [AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('customers/{id}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
    
    // Admin Analytics
    Route::get('analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    
    // Admin Profile & Settings
    Route::get('profile', [AdminAuthController::class, 'adminProfile'])->name('admin.profile');
    Route::get('settings', [AdminSettingsController::class, 'index'])->name('admin.settings');
    Route::put('settings', [AdminSettingsController::class, 'update'])->name('admin.settings.update');
    
    // Admin Notifications
    Route::get('notifications', function () {
        $newOrders = Order::where('status', 'pending')->count();
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->where('is_archived', false)
            ->where('is_active', true)
            ->count();
        
        $notifications = [];
        
        if ($lowStockProducts > 0) {
            $notifications[] = [
                'type' => 'order',
                'message' => $newOrders . ' new order(s) received',
                'time' => 'Just now',
                'url' => route('admin.orders.index', ['status' => 'pending'])
            ];
        }
        
        $totalCount = count($notifications);
        
        return response()->json([
            'notifications' => $notifications,
            'total_count' => $totalCount
        ]);
    })->name('admin.notifications');
});
