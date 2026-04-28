# Functional Requirements

## 1. User Authentication & Authorization

### 1.1 User Registration
- **FR-001**: Users must be able to register with name, email, password, phone number
- **FR-002**: Email must be unique across the system
- **FR-003**: Password must be at least 8 characters
- **FR-004**: Users can upload a profile photo during registration or later

### 1.2 User Login
- **FR-005**: Users must be able to login using email and password
- **FR-006**: Admin users must have a separate login portal at `/admin/login`
- **FR-007**: System must validate user credentials before granting access
- **FR-008**: Failed login attempts should show appropriate error messages

### 1.3 Password Recovery
- **FR-009**: Users must be able to request password reset via email
- **FR-010**: System must send password reset link to registered email
- **FR-011**: Password reset link must expire after a set time period
- **FR-012**: Users must be able to set a new password using the reset link

### 1.4 User Profile Management
- **FR-013**: Users must be able to view and edit their profile information
- **FR-014**: Users must be able to update name, email, phone, and profile photo
- **FR-015**: Users must be able to change their password
- **FR-016**: Users must be able to view their order history
- **FR-017**: Users must be able to view their wishlist

---

## 2. Product Management

### 2.1 Product Browsing
- **FR-018**: Users must be able to browse all available products
- **FR-019**: Products must be displayed with name, price, image, and stock status
- **FR-020**: Users must be able to filter products by category
- **FR-021**: Users must be able to search products by name
- **FR-022**: Products must display "Out of Stock" badge when stock is 0
- **FR-023**: Products must display "Low Stock" badge when stock is between 1-10

### 2.2 Product Details
- **FR-024**: Users must be able to view detailed product information
- **FR-025**: Product details must include: name, description, price, stock, images
- **FR-026**: Users must be able to select quantity when adding to cart
- **FR-027**: Users must be able to view product reviews/ratings
- **FR-028**: Users must be able to add a review with rating (1-5 stars)

### 2.3 Product Categories
- **FR-029**: Products must be organized into categories
- **FR-030**: Users must be able to browse products by category
- **FR-031**: Categories must display with name and image

---

## 3. Shopping Cart

### 3.1 Cart Management
- **FR-032**: Users must be able to add products to cart
- **FR-033**: Users must be able to view their cart contents
- **FR-034**: Users must be able to update item quantity in cart
- **FR-035**: Users must be able to remove items from cart
- **FR-036**: Cart must display: product name, price, quantity, subtotal, total
- **FR-037**: Cart must persist across sessions for logged-in users

### 3.2 Delivery Date Selection
- **FR-038**: Users must be able to select a delivery date for each cart item
- **FR-039**: Delivery date must be validated to ensure it's a future date
- **FR-040**: Users must be able to set delivery time preference

---

## 4. Checkout & Orders

### 4.1 Checkout Process
- **FR-041**: Users must be able to proceed to checkout from cart
- **FR-042**: Users must provide shipping information: name, phone, address, city, postal code
- **FR-043**: Users must select a delivery area
- **FR-044**: Users must select a payment method (COD, GCash, Bank Transfer)
- **FR-045**: Users must agree to terms and conditions before placing order
- **FR-046**: System must calculate total amount including delivery fees

### 4.2 Order Placement
- **FR-047**: System must generate unique order number for each order
- **FR-048**: System must reduce product stock when order is placed
- **FR-049**: System must create order record with all items
- **FR-050**: System must create shipping record with delivery information
- **FR-051**: Users must receive order confirmation after successful placement

### 4.3 Payment Processing
- **FR-052**: For GCash/Bank payments, users must upload payment receipt
- **FR-053**: System must set payment status to "pending" for receipt uploads
- **FR-054**: System must set payment status to "verified" for COD orders
- **FR-055**: Admin must verify GCash/Bank payment receipts

---

## 5. Order Management (User Side)

### 5.1 Order History
- **FR-056**: Users must be able to view their order history
- **FR-057**: Orders must display: order number, date, status, total amount
- **FR-058**: Users must be able to view order details
- **FR-059**: Order details must show: items, shipping address, payment method, status

### 5.2 Order Tracking
- **FR-060**: Users must be able to track order status
- **FR-061**: Order status must be one of: pending, processing, completed, cancelled
- **FR-062**: Users must be able to cancel pending orders

---

## 6. Wishlist

### 6.1 Wishlist Management
- **FR-063**: Users must be able to add products to wishlist
- **FR-064**: Users must be able to view their wishlist
- **FR-065**: Users must be able to remove products from wishlist
- **FR-066**: Users must be able to move wishlist items to cart

---

## 7. Reviews & Ratings

### 7.1 Review Submission
- **FR-067**: Users must be able to review products they purchased
- **FR-068**: Reviews must include rating (1-5 stars) and comment
- **FR-069**: Reviews must be approved by admin before becoming visible
- **FR-070**: Users must be able to edit/delete their own reviews

### 7.2 Review Display
- **FR-071**: Product pages must display approved reviews
- **FR-072**: Reviews must show: user name, rating, comment, date
- **FR-073**: Average rating must be displayed on product page

---

## 8. Admin Panel

### 8.1 Dashboard
- **FR-074**: Admin must see overview statistics: total revenue, orders, customers, products
- **FR-075**: Admin must see recent orders list
- **FR-076**: Admin must see top-selling products
- **FR-077**: Admin must see inventory status (in stock, low stock, out of stock)

### 8.2 Product Management (Admin)
- **FR-078**: Admin must be able to add new products
- **FR-079**: Admin must be able to edit existing products
- **FR-080**: Admin must be able to delete products
- **FR-081**: Product form must include: name, category, description, price, stock, image
- **FR-082**: System must auto-generate slug from product name
- **FR-083**: Admin must be able to upload multiple product images

### 8.3 Category Management (Admin)
- **FR-084**: Admin must be able to add new categories
- **FR-085**: Admin must be able to edit existing categories
- **FR-086**: Admin must be able to delete categories
- **FR-087**: System must auto-generate slug from category name
- **FR-088**: Admin must be able to upload category image

### 8.4 Order Management (Admin)
- **FR-089**: Admin must be able to view all orders
- **FR-090**: Admin must be able to filter orders by status
- **FR-091**: Admin must be able to view order details
- **FR-092**: Admin must be able to update order status
- **FR-093**: Admin must be able to verify payment receipts
- **FR-094**: Admin must have calendar view for orders with delivery dates
- **FR-095**: Admin must be able to print order details
- **FR-096**: Cancelled orders must return items to stock

### 8.5 User Management (Admin)
- **FR-097**: Admin must be able to view all registered users
- **FR-098**: Admin must be able to see user details: name, email, phone, order count
- **FR-099**: Admin must be able to see online/offline status
- **FR-100**: Admin must be able to ban users for specified days
- **FR-101**: Admin must be able to unban users

### 8.6 Review Management (Admin)
- **FR-102**: Admin must be able to view all reviews
- **FR-103**: Admin must be able to approve/reject reviews
- **FR-104**: Admin must be able to add admin comments to reviews
- **FR-105**: Admin must be able to delete reviews

### 8.7 Delivery Area Management (Admin)
- **FR-106**: Admin must be able to add delivery areas with fees
- **FR-107**: Admin must be able to set same-day delivery cutoff time
- **FR-108**: Admin must be able to activate/deactivate delivery areas

---

## 9. Notifications

### 9.1 User Notifications
- **FR-109**: Users must receive notification when payment is verified
- **FR-110**: Users must receive notification when order status changes
- **FR-111**: Users must be able to mark notifications as read
- **FR-112**: Users must be able to view notification history

### 9.2 Admin Notifications
- **FR-113**: Admin must receive notification for new orders
- **FR-114**: Admin must receive notification for payment receipts uploaded

---

## 10. Additional Features

### 10.1 Address Book
- **FR-115**: Users must be able to save multiple shipping addresses
- **FR-116**: Users must be able to set a default address
- **FR-117**: Users must be able to edit/delete saved addresses

### 10.2 Online Status Tracking
- **FR-118**: System must track user online status
- **FR-119**: User is considered online if active in last 5 minutes
- **FR-120**: System must update `last_activity_at` on user actions

---

## 11. Database Requirements

### 11.1 Data Integrity
- **FR-121**: Foreign key constraints must be enforced
- **FR-122**: Unique constraints must be applied (email, order_number, slug)
- **FR-123**: Required fields must not be null
- **FR-124**: Dates must be stored in correct format

### 11.2 Relationships
- **FR-125**: Users can have multiple orders, addresses, reviews, wishlists
- **FR-126**: Orders belong to one user and have one shipping record
- **FR-127**: Products belong to one category and have multiple images
- **FR-128**: Orders have multiple order items linking to products
