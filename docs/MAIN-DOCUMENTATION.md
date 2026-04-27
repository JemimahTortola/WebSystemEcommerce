# Flourista - E-Commerce Florist Website Documentation

## Table of Contents
1. [Unlinked / Inaccessible Pages](#a-unlinked--inaccessible-pages)
2. [Updated Site Structure](#b-updated-site-structure)
3. [Functional Requirements](#c-functional-requirements-updated)
4. [Improvements Summary](#d-improvements-summary)

---

## A. Unlinked / Inaccessible Pages

### Pages That Were Fixed and Properly Integrated
- **Contact Form Submit** - Was missing route and handler. Now integrated:
  - Route: `POST /contact` → `contact.send`
  - Controller: `PageController::contactSend()`
  - Mail: `ContactMail` mailable created

### Pages/Views Removed or Not Created
- None - All existing views are integrated

### Views That Exist but Are Dynamically Rendered (Not Static Files)
- Cart page (`cart/index.blade.php`) - Uses JavaScript to fetch and render cart items via AJAX
- Profile page - Uses layouts but content dynamically loaded

---

## B. Updated Site Structure

### Final Sitemap

```
PUBLIC PAGES (No Auth Required)
├── / (home) - Landing page
├── /shop - Product listing
├── /shop/{slug} - Product detail
├── /about - About us
├── /contact - Contact form
├── /privacy - Privacy policy
├── /terms - Terms of service
├── /delivery-areas - Delivery areas info
├── /track-order - Order tracking
├── /login - User login
└── /register - User registration

AUTHENTICATED USER PAGES (Login Required)
├── /cart - Shopping cart
├── /checkout - Checkout
├── /orders - Order history
├── /orders/{orderId} - Order detail
├── /wishlist - Wishlist
├── /profile - User profile
├── /profile/addresses - Address management
└── /notifications - User notifications

ADMIN PAGES (Admin Role Required)
├── /admin/login - Admin login
├── /admin/dashboard - Dashboard
├── /admin/products - Product management
├── /admin/categories - Category management
├── /admin/orders - Order management
├── /admin/users - User management
├── /admin/reviews - Review management
└── /admin/coupons - Coupon management
```

### Navigation Flow

**Header Navigation:**
- Logo (Home) | Shop | About | Contact | Delivery Areas | Cart | Login/Profile Dropdown

**Footer Navigation:**
- Shop | About Us | Contact | Track Order | Delivery Areas | Privacy Policy | Terms of Service

---

## C. Functional Requirements (Updated)

### User Authentication
| Feature | Status | Implementation |
|---------|--------|----------------|
| User Registration | ✅ | `AuthController::register()` |
| User Login | ✅ | `AuthController::login()` |
| User Logout | ✅ | `POST /logout` |
| Password Reset | ⚠️ | Not implemented (uses Laravel defaults) |

### User Profile Management
| Feature | Status | Implementation |
|---------|--------|----------------|
| View Profile | ✅ | `GET /profile` |
| Update Profile | ✅ | `PUT /profile` |
| Manage Addresses | ✅ | `AddressController` CRUD |
| View Notifications | ✅ | `GET /notifications` |

### Product Browsing
| Feature | Status | Implementation |
|---------|--------|----------------|
| Product Listing | ✅ | `ShopController::index()` |
| Product Filtering | ✅ | By category, occasion (query params) |
| Product Search | ✅ | By name (query params) |
| Product Detail | ✅ | `ShopController::show()` |
| Product Reviews | ✅ | `ReviewController` |

### Shopping Cart
| Feature | Status | Implementation |
|---------|--------|----------------|
| Add to Cart | ✅ | `POST /cart` |
| View Cart | ✅ | `GET /cart` (AJAX-loaded) |
| Update Quantity | ✅ | `PUT /cart/{cartItem}` |
| Remove Item | ✅ | `DELETE /cart/{cartItem}` |
| Cart Count Badge | ✅ | Auto-updates on page load |

### Checkout
| Feature | Status | Implementation |
|---------|--------|----------------|
| View Checkout | ✅ | `CheckoutController::index()` |
| Apply Coupon | ✅ | Via checkout form |
| Place Order | ✅ | `POST /checkout` |

### Order Management (User)
| Feature | Status | Implementation |
|---------|--------|----------------|
| Order History | ✅ | `GET /orders` |
| Order Detail | ✅ | `GET /orders/{orderId}` |
| Upload Receipt | ✅ | `POST /orders/{orderId}/receipt` |

### Wishlist
| Feature | Status | Implementation |
|---------|--------|----------------|
| Add to Wishlist | ✅ | `POST /wishlist` |
| View Wishlist | ✅ | `GET /wishlist` |
| Remove from Wishlist | ✅ | `DELETE /wishlist/{wishlist}` |

### Admin Features
| Feature | Status | Implementation |
|---------|--------|----------------|
| Dashboard | ✅ | Sales stats, recent orders |
| Product Management | ✅ | CRUD products |
| Category Management | ✅ | CRUD categories |
| Order Management | ✅ | View, update status |
| User Management | ✅ | View, update users |
| Review Management | ✅ | Toggle visibility |
| Coupon Management | ✅ | CRUD coupons |

### Public Pages
| Feature | Status | Implementation |
|---------|--------|----------------|
| Contact Form | ✅ | Sends email via `ContactMail` |
| Order Tracking | ✅ | `POST /track-order` |
| Delivery Areas | ✅ | Static page |

---

## D. Improvements Summary

### Bug Fixes
1. **Cart - firstOrCreate error** - Fixed `DB::table()` usage to not use Eloquent method
2. **Cart - missing price field** - Added product price when adding to cart
3. **Cart - items not displayed** - Added AJAX cart loading and rendering in JavaScript
4. **Contact form not working** - Added missing route `contact.send` and handler

### Performance Improvements
1. **Cart count on every page** - Now fetches cart count on page load to reflect accurate count
2. **AJAX cart rendering** - Cart items loaded via fetch for better UX

### Maintainability
1. **Contact mail** - Created dedicated `ContactMail` mailable class
2. **Code organization** - Clean controller methods with proper JSON responses for API-style endpoints

### UI/UX Improvements
1. **Dynamic cart page** - JavaScript now loads and renders cart items dynamically
2. **Quantity controls** - +/- buttons with real-time updates
3. **Remove item** - One-click item removal

---

## Technical Details

### Database Tables
- `users` - User accounts
- `carts` - User shopping carts
- `cart_items` - Cart items with price snapshot
- `orders` - Customer orders
- `order_items` - Order line items
- `products` - Product catalog
- `categories` - Product categories
- `product_images` - Product images
- `product_occasions` - Product occasion tags
- `occasions` - Occasion types
- `addresses` - User addresses
- `wishlists` - User wishlists
- `reviews` - Product reviews
- `coupons` - Discount coupons
- `payments` - Payment records
- `notifications` - User notifications

### API Endpoints (AJAX)
- `GET /cart` - Get cart items (JSON)
- `POST /cart` - Add to cart
- `PUT /cart/{id}` - Update quantity
- `DELETE /cart/{id}` - Remove item
- `GET /cart/count` - Get cart item count
- `GET /notifications/data` - Get notifications

### Middleware
- `auth` - Requires authentication
- `admin` - Requires admin role
- `VerifyCsrfToken` - CSRF protection (excluded for admin API routes)

---

*Last Updated: April 26, 2026*