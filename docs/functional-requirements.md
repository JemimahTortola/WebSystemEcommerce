# Functional Requirements – FlouristaButuan.ph

## 1. User Management

### 1.1 Customer Registration
Route: /register (GET, POST)
Fields: name, email, password, phone (optional)
Auto-login after registration
Assign default role via user_roles

### 1.2 Customer Login
Route: /login (GET, POST)
Login using email
Rate limited (5 attempts/minute)

### 1.3 Customer Logout
Route: /logout (POST)

### 1.4 Profile Management
Route: /profile (GET, PUT)
Fields:
- name
- email
- phone
HTML: `resources/views/profile/index.blade.php`
CSS: `public/css/profile.css`
JS: `public/js/profile.js`

### 1.5 Address Management
Route: /addresses (GET, POST)
Route: /addresses/{id} (PUT, DELETE)
Multiple addresses per user
Default address supported (is_default)
HTML: `resources/views/addresses/index.blade.php`
CSS: `public/css/addresses.css`
JS: `public/js/addresses.js`

### 1.6 Notifications
Route: /notifications (GET)
Route: /notifications/{id}/read (POST)
Route: /notifications/read-all (POST)
Triggered by:
- Order status updates
- Payment updates
- Promotions / announcements
HTML: `resources/views/notifications/index.blade.php`
CSS: `public/css/notifications.css`
JS: `public/js/notifications.js`

### 1.7 Wishlist
Route: /wishlist (GET)
Route: /wishlist (POST)
Route: /wishlist/{productId} (DELETE)
HTML: `resources/views/wishlist/index.blade.php`
CSS: `public/css/wishlist.css`
JS: `public/js/wishlist.js`

### 1.8 Role-Based Access
Roles managed via:
- roles
- user_roles
Admin access based on role
CSS: `public/css/admin/base.css` (shared admin styles)

## 2. Product Catalog

### 2.1 Shop Listing
Route: /shop (GET)
Pagination (12 per page)
Filters:
- Category
- Occasion
- Price range
- Product type (bouquet, single, bundle)
Sorting:
- Recommended
- Price (low → high, high → low)
HTML: `resources/views/shop/index.blade.php`
CSS: `public/css/user/shop.css`
JS: `public/js/user/shop.js`

### 2.2 Product Details
Route: /shop/{slug} (GET)
Displays:
- Name, price, description
- Stock
- Category
- Product images (multiple, with primary)
- Related products
- Approved reviews only
HTML: `resources/views/shop/product.blade.php`
CSS: `public/css/user/product.css`
JS: `public/js/user/product.js`

### 2.3 Categories (Admin)
CRUD operations
HTML: `resources/views/admin/categories.blade.php`
CSS: `public/css/admin/categories.css`
JS: `public/js/admin/categories.js`

### 2.4 Products (Admin)
CRUD operations
Soft delete (deleted_at)
Stock managed directly in product
HTML: `resources/views/admin/products.blade.php`
CSS: `public/css/admin/products.css`
JS: `public/js/admin/products.js`

## 3. Shopping Cart

### 3.1 Cart
Route: /cart (GET)
Route: /cart/add (POST)
Route: /cart/update/{id} (PUT)
Route: /cart/remove/{id} (GET)
HTML: `resources/views/cart/index.blade.php`
CSS: `public/css/user/cart.css`
JS: `public/js/user/cart.js`

### 3.2 Cart Features
Persistent per user
Stock validation
Real-time updates

## 4. Checkout & Orders

### 4.1 Checkout
Route: /checkout (GET)
Route: /checkout/process (POST)
Process:
1. Create order
2. Create order items
3. Create payment record
4. Deduct stock
5. Clear cart
HTML: `resources/views/checkout/index.blade.php`
CSS: `public/css/user/checkout.css`
JS: `public/js/user/checkout.js`

### 4.2 Order Fields
- Shipping info (name, phone, address)
- Delivery date & time
- Delivery notes
- Gift message
- Coupon (optional)
- Delivery area

### 4.3 Order Status Flow
pending → confirmed → preparing → out_for_delivery → delivered
cancelled

### 4.4 Customer Orders
Route: /orders (GET)
Route: /orders/{id} (GET)
HTML: `resources/views/orders/index.blade.php`
HTML: `resources/views/orders/show.blade.php`
CSS: `public/css/user/orders.css`, `public/css/user/order-detail.css`
JS: `public/js/user/orders.js`, `public/js/user/order-detail.js`

### 4.5 Admin Orders
Route: /admin/orders
Update:
- order status
- payment status
- tracking info
HTML: `resources/views/admin/orders.blade.php`
CSS: `public/css/admin/orders.css` (imports base.css)
JS: `public/js/admin/orders.js`

### 4.6 Payments
Stored in payments
Methods:
- COD
- GCash
- Card

### 4.7 Order Tracking
Route: /track-order (GET, POST)
Uses:
- tracking_number
- courier
HTML: `resources/views/track-order.blade.php`
CSS: `public/css/user/track-order.css`
JS: `public/js/user/track-order.js`

## 5. Reviews & Ratings

### 5.1 Customer Reviews
Route: /reviews (POST)
One review per user per product
Default status: pending

### 5.2 Admin Review Management
Route: /admin/reviews (GET)
Route: /admin/reviews/{id}/approve (POST)
Route: /admin/reviews/{id}/reject (POST)
Route: /admin/reviews/{id} (DELETE)
HTML: `resources/views/admin/reviews.blade.php`
CSS: `public/css/admin/reviews.css` (imports base.css)
JS: `public/js/admin/reviews.js`

## 6. Delivery & Locations

### 6.1 Delivery Areas
Route: /delivery-areas (GET)
Shows available delivery locations
Features:
- Delivery fee per area
- Same-day delivery cutoff time
- Active/inactive areas
HTML: `resources/views/delivery-areas.blade.php`
CSS: `public/css/user/delivery-areas.css`
JS: `public/js/user/delivery-areas.js`

### 6.2 Checkout Integration
User selects delivery area
Fee added to order total
Stored in order

## 7. Admin Dashboard

### 7.1 Dashboard
Route: /admin/dashboard
Displays:
- Total revenue
- Orders count
- Customers count
- Products count
- Recent orders
HTML: `resources/views/admin/dashboard.blade.php`
CSS: `public/css/admin/dashboard.css` (imports base.css)
JS: `public/js/admin/dashboard.js`

### 7.2 Analytics
- Monthly sales
- Top products

### 7.3 Inventory
Based on product stock:
- in stock
- low stock
- out of stock

### 7.4 Customer Management
- View all users
- View user details
HTML: `resources/views/admin/users.blade.php`
CSS: `public/css/admin/users.css` (imports base.css)
JS: `public/js/admin/users.js`

### 7.5 Coupons
CRUD operations
Types:
- fixed
- percentage
HTML: `resources/views/admin/coupons.blade.php`
CSS: `public/css/admin/coupons.css` (imports base.css)
JS: `public/js/admin/coupons.js`

### 7.6 Admin Profile
View profile

## 8. Public Pages

| Route | Page | HTML | CSS | JS |
|-------|------|------|-----|-----|
| / | Home | `resources/views/home.blade.php` | `public/css/user/home.css` | `public/js/user/home.js` |
| /about | About | `resources/views/about.blade.php` | `public/css/user/page.css` | - |
| /contact | Contact | `resources/views/contact.blade.php` | `public/css/user/contact.css` | `public/js/user/contact.js` |
| /privacy | Privacy Policy | `resources/views/privacy.blade.php` | `public/css/user/page.css` | - |
| /terms | Terms | `resources/views/terms.blade.php` | `public/css/user/page.css` | - |
| /login | Login | `resources/views/auth/login.blade.php` | `public/css/main.css` | `public/js/auth.js` |
| /register | Register | `resources/views/auth/register.blade.php` | `public/css/main.css` | `public/js/auth.js` |
| /track-order | Track Order | `resources/views/track-order.blade.php` | `public/css/user/track-order.css` | `public/js/user/track-order.js` |
| /delivery-areas | Delivery Areas | `resources/views/delivery-areas.blade.php` | `public/css/user/delivery-areas.css` | `public/js/user/delivery-areas.js` |

## 9. API Endpoints

| Route | Method | Description |
|-------|--------|-------------|
| /api/user | GET | Authenticated user |
| /health | GET | System health check |
| /shop/search/suggestions | GET | Search autocomplete |

## 10. Middleware

| Middleware | Purpose |
|-----------|---------|
| auth | Protect user routes |
| admin | Protect admin routes |
| throttle | Rate limiting |
| https | Secure connection |
| csp | Security headers |

## 11. Key System Features

**Security**
- Password hashing
- CSRF protection
- XSS protection
- SQL injection prevention
- Rate limiting

**Performance**
- Pagination
- Eager loading
- Indexed queries

**Data Integrity**
- Unique constraints (wishlist, reviews)
- Foreign key relationships
- Soft deletes for products/users