<?php
/**
 * ==============================================================================
 * ROUTES FILE - web.php
 * ==============================================================================
 * This file defines all the URL routes for the application.
 * Think of routes like a map - they tell the application which code to run
 * when someone visits a particular URL.
 * 
 * Format: Route::METHOD('/url', [Controller::class, 'method'])->name('route.name');
 * - METHOD: GET, POST, PUT, DELETE (HTTP request types)
 * - url: The web address path
 * - Controller::class: The PHP class that handles the request
 * - method: The function in that class to run
 * - name: A short name to refer to this route in the app
 */

// Use statements - Importing necessary classes
// These tell PHP which files/classes we need to use in this file
use Illuminate\Support\Facades\Route;              // Laravel's Route system
use App\Http\Controllers\AuthController;         // Handles login/register
use App\Http\Controllers\ShopController;        // Handles product browsing
use App\Http\Controllers\PageController;         // Handles static pages
use App\Http\Controllers\ReviewController;    // Handles product reviews
use App\Http\Controllers\AddressController;    // Handles user addresses
use App\Http\Controllers\OrderController;      // Handles orders
use App\Http\Controllers\WishlistController;   // Handles wishlists
use App\Http\Controllers\CartController;        // Handles shopping cart
use App\Http\Controllers\CheckoutController;   // Handles checkout
use App\Http\Controllers\Admin\DashboardController;   // Admin dashboard
use App\Http\Controllers\Admin\ProductController;     // Admin products
use App\Http\Controllers\Admin\CategoryController;   // Admin categories
// Rename Admin OrderController to avoid conflict with regular OrderController
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\UserController;        // Admin users
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

/*
|--------------------------------------------------------------------------
| Web Routes - Password Reset (Public)
|--------------------------------------------------------------------------
*/
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/forgot-password', function (Illuminate\Http\Request $request) {
    $request->validate(['email' => 'required|email']);
    
    $status = Illuminate\Support\Facades\Password::sendResetLink(
        $request->only('email')
    );
    
    return $status === Illuminate\Support\Facades\Password::RESET_LINK_SENT
        ? back()->with(['success' => __($status)])
        : back()->withErrors(['email' => __($status)]);
})->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('/reset-password', function (Illuminate\Http\Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:8',
    ]);
 
    $status = Illuminate\Support\Facades\Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->save();
        }
    );
 
    return $status === Illuminate\Support\Facades\Password::PASSWORD_RESET
        ? redirect()->route('login')->with('success', __($status))
        : back()->withErrors(['email' => [__($status)]]);
})->name('password.update');

/*
|--------------------------------------------------------------------------
| Web Routes - Admin Section (lines 27-65)
|--------------------------------------------------------------------------
|
| Routes for the admin panel. These start with /admin in the URL.
| Admin routes are protected - only logged-in admins can access them.
*/

// Admin login/logout routes (no CSRF protection needed for API-style)
Route::prefix('admin')->name('admin.')->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class])->group(function () {
    // prefix('admin') = all URLs start with /admin
    // name('admin.') = all route names start with admin.
    // withoutMiddleware() = skip CSRF check for these routes
    
    // Show admin login form - GET /admin/login
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    // Process admin login - POST /admin/login
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    // Process admin logout - POST /admin/logout
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
});

// Admin protected routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard-data', [DashboardController::class, 'data'])->name('dashboard.data');
    
    Route::get('/products', [ProductController::class, 'index'])->name('products');
    Route::get('/products/data', [ProductController::class, 'data'])->name('products.data');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders');
    Route::get('/orders/data', [AdminOrderController::class, 'data'])->name('orders.data');
    Route::get('/orders/calendar-data', [AdminOrderController::class, 'calendarData'])->name('orders.calendarData');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::put('/orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verifyPayment');

    Route::get('/notifications', [AdminOrderController::class, 'notifications'])->name('notifications');
    Route::get('/notifications/data', [AdminOrderController::class, 'notificationsData'])->name('notifications.data');
    Route::post('/notifications/read-all', [AdminOrderController::class, 'markAllRead'])->name('notifications.readAll');
    Route::post('/notifications/mark-read', [AdminOrderController::class, 'markRead'])->name('notifications.markRead');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/ban', [UserController::class, 'ban'])->name('users.ban');
    Route::post('/users/{user}/unban', [UserController::class, 'unban'])->name('users.unban');

    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('reviews');
    Route::get('/reviews/data', [AdminReviewController::class, 'data'])->name('reviews.data');
    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])->name('reviews.show');
    Route::put('/reviews/{review}', [AdminReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [AdminReviewController::class, 'destroy'])->name('reviews.destroy');
});

/*
|--------------------------------------------------------------------------
| Web Routes - Public Section (lines 73-133)
|--------------------------------------------------------------------------
|
| Routes visible to everyone - no login required
*/

// Public Pages - Static content pages
Route::get('/', [PageController::class, 'home'])->name('home');           // Homepage - /
Route::get('/about', [PageController::class, 'about'])->name('about');     // About page - /about
Route::get('/contact', [PageController::class, 'contact'])->name('contact'); // Contact page - /contact
Route::post('/contact', [PageController::class, 'contactSend'])->name('contact.send'); // Process contact form
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy'); // Privacy policy
Route::get('/terms', [PageController::class, 'terms'])->name('terms'); // Terms of service
Route::get('/delivery-areas', [PageController::class, 'deliveryAreas'])->name('delivery-areas'); // Delivery info
Route::get('/track-order', [PageController::class, 'trackOrder'])->name('track-order'); // Track order page
Route::post('/track-order', [PageController::class, 'trackOrder']); // Process order tracking

// Shop/Ecommerce Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop');      // Product listing - /shop
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.product'); // Product detail - /shop/product-slug

// User Authentication Routes
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register'); // Registration form
Route::post('/register', [AuthController::class, 'register']); // Process registration

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login'); // Login form
Route::post('/login', [AuthController::class, 'login']); // Process login

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth'); // Logout (requires login)

// Reviews - Allow logged-in users to leave reviews
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews')->middleware('auth');

// User Address Management
Route::get('/profile/addresses', [AddressController::class, 'index'])->name('profile.addresses')->middleware('auth');
Route::post('/profile/addresses', [AddressController::class, 'store'])->name('profile.addresses.store')->middleware('auth');
Route::put('/profile/addresses/{address}', [AddressController::class, 'update'])->name('profile.addresses.update')->middleware('auth');
Route::delete('/profile/addresses/{address}', [AddressController::class, 'destroy'])->name('profile.addresses.destroy')->middleware('auth');
Route::post('/profile/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('profile.addresses.setDefault')->middleware('auth');

// User Order Management
Route::get('/orders', [OrderController::class, 'index'])->name('orders')->middleware('auth');
Route::get('/test-orders', function() {
    return response()->json([
        ['id' => 1, 'order_number' => 'ORD-001', 'total_amount' => 1500, 'status' => 'pending', 'created_at' => '2024-01-01'],
        ['id' => 2, 'order_number' => 'ORD-002', 'total_amount' => 2500, 'status' => 'completed', 'created_at' => '2024-01-02'],
    ]);
});
Route::get('/orders/{orderId}', [OrderController::class, 'show'])->name('orders.show')->middleware('auth'); // Order details
Route::post('/orders/{orderId}/receipt', [OrderController::class, 'uploadReceipt'])->name('orders.receipt')->middleware('auth'); // Upload payment

// User Wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist')->middleware('auth');
Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store')->middleware('auth');
Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy')->middleware('auth');

// Shopping Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart')->middleware('auth'); // View cart
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count'); // Get cart item count (AJAX)
Route::post('/cart', [CartController::class, 'add'])->name('cart.add')->middleware('auth'); // Add to cart
Route::put('/cart/{cartItem}', [CartController::class, 'update'])->name('cart.update')->middleware('auth'); // Update quantity
Route::delete('/cart/{cartItem}', [CartController::class, 'remove'])->name('cart.remove')->middleware('auth'); // Remove item

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout')->middleware('auth'); // Checkout page
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store')->middleware('auth'); // Place order

// User Profile
Route::get('/profile', [AuthController::class, 'profile'])->name('profile')->middleware('auth'); // View profile
Route::put('/profile', [AuthController::class, 'profile'])->name('profile.update')->middleware('auth'); // Update profile
Route::get('/notifications', [AuthController::class, 'notifications'])->name('notifications')->middleware('auth'); // Notifications
Route::get('/notifications/data', [AuthController::class, 'notificationsData'])->name('notifications.data')->middleware('auth'); // Notifications data
Route::post('/notifications/read-all', [AuthController::class, 'markAllNotificationsRead'])->name('notifications.readAll')->middleware('auth'); // Mark all read