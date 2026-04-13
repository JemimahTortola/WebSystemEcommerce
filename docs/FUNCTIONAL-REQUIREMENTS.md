# Functional Requirements - TinyThreads E-Commerce

## Store: TinyThreads (Newborn to Toddler Clothing)

---

## 1. User Management

### 1.1 Customer Registration
- **Register**: `/register` - Fields: username, first_name, last_name, email, password
- Auto-login after registration
- Redirect to address creation

### 1.2 Customer Login
- **Login**: `/login` - Supports email OR username
- Rate limited (5 attempts/minute)

### 1.3 Customer Logout
- **Logout**: `/logout` - Clears session, redirects home

### 1.4 Profile Management
- **View Profile**: `/profile`
- **Update Profile**: PUT `/profile` - username, name, email, phone, address, city, postal_code, country
- **Upload Profile Image**: Supported

### 1.5 Address Management
- **Add/Edit Address**: `/address`

### 1.6 Notification Settings
- **Settings**: `/settings` - Email, SMS, Marketing toggles

### 1.7 Wishlist
- **View Wishlist**: `/wishlist`

---

## 2. Product Catalog

### 2.1 Shop/Product Listing
- **Route**: `/shop`
- Paginated (12 products/page)
- Filter by category
- Search by name
- Filter by price range
- Sort: newest, price_low, price_high

### 2.2 Product Details
- **Route**: `/shop/{slug}`
- Display: name, price, description, stock, category
- Related products (same category, max 4)
- Reviews with ratings
- Add to cart

### 2.3 Categories
- **List**: `/admin/categories`
- **Create/Edit/Delete**: Category management

---

## 3. Shopping Cart

### 3.1 Cart Operations
- **View Cart**: `/cart`
- **Add to Cart**: POST `/cart/add`
- **Update Quantity**: PUT `/cart/update/{id}`
- **Remove Item**: GET `/cart/remove/{id}`

### 3.2 Cart Features
- Persistent (database-backed)
- Stock validation
- Subtotal calculation

---

## 4. Checkout & Orders

### 4.1 Checkout
- **View Checkout**: `/checkout`
- **Process Order**: POST `/checkout/process`
- Creates order with unique order_number (ORD-{random})
- Deducts stock
- Clears cart

### 4.2 Order Management (Customer)
- **Order List**: `/orders` - 10 per page
- **Order Details**: `/orders/{id}`
- Shows items, shipping info, status, tracking

### 4.3 Order Statuses
- pending → processing → shipped → delivered
- cancelled

---

## 5. Reviews & Ratings

### 5.1 Customer Reviews
- **Submit Review**: POST `/reviews`
- Rating (1-5), comment
- One review per user per product

### 5.2 Admin Review Management
- **View/Delete Reviews**: `/admin/reviews`

---

## 6. Admin Dashboard

### 6.1 Dashboard Overview
- Revenue stats (all-time, monthly, change %)
- Orders count, customers count
- Average order value
- Visualizations: revenue chart, order status breakdown
- Recent orders, top products, activity timeline

### 6.2 Analytics
- **Route**: `/admin/analytics`
- Revenue, orders, customers, products totals
- Monthly sales breakdown
- Top 5 products

### 6.3 Product Management
- **List Products**: `/admin/products`
- Filter: active, archived; Search
- **Create/Edit Product**: name, category, price, stock, description, image, is_active
- Auto-generates slug
- **Delete/Archive/Restore**: Product operations

### 6.4 Order Management
- **List Orders**: `/admin/orders`
- Filter: status, archived; Search
- **View Order Details**: Customer, items, shipping, payment, tracking
- **Update Status**: pending, processing, shipped, delivered, cancelled
- **Update Payment**: pending, paid, failed, refunded
- **Add Tracking**: courier, tracking_number, estimated_delivery

### 6.5 Inventory
- **View Inventory**: `/admin/inventory`
- Filter: all, in_stock, low_stock, out_of_stock

### 6.6 Category Management
- **CRUD Categories**: `/admin/categories`

### 6.7 Customer Management
- **List Customers**: `/admin/customers`
- **View Customer**: `/admin/customers/{id}` - details, order history

### 6.8 Messages
- **Conversations**: `/admin/messages`
- **View/Send Messages**: `/admin/messages/{id}`

### 6.9 Settings
- **Store Settings**: `/admin/settings`
- Store name, email, phone, address, description, logo

### 6.10 Admin Profile
- **View Profile**: `/admin/profile`

---

## 7. Public Pages

| Route | Page |
|-------|------|
| `/` | Home |
| `/about` | About |
| `/contact` | Contact |
| `/privacy` | Privacy Policy |
| `/terms` | Terms of Service |
| `/preview` | Preview |
| `/login` | Login |
| `/register` | Register |
| `/address` | Address Form |

---

## 8. API Endpoints

- `GET /api/user` - Authenticated user (Sanctum)
- `GET /health` - Health check

---

## 9. Middleware

| Middleware | Purpose |
|------------|---------|
| `auth` | Protects user routes (cart, checkout, orders, profile, settings, wishlist) |
| `auth:admin` | Protects all admin routes |
| `AdminMiddleware` | Checks is_admin flag |

---

## 10. Database Models & Relationships

| Model | Relationships |
|-------|---------------|
| User | hasOne(Cart), hasMany(Order), hasMany(Conversation) |
| Admin | (standalone) |
| Product | belongsTo(Category), hasMany(OrderItem), hasMany(CartItem), hasMany(Review) |
| Category | hasMany(Product) |
| Order | belongsTo(User), hasMany(OrderItem), hasMany(ShippingTracking) |
| OrderItem | belongsTo(Order), belongsTo(Product) |
| Cart | belongsTo(User), hasMany(CartItem) |
| CartItem | belongsTo(Cart), belongsTo(Product) |
| Review | belongsTo(User), belongsTo(Product) |
| ShippingTracking | belongsTo(Order) |
| Conversation | belongsTo(User), hasMany(Message) |
| Message | belongsTo(Conversation), belongsTo(User) |