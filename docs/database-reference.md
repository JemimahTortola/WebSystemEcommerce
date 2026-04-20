Table users {
  id bigint [pk, increment]
  name varchar(255)
  email varchar(255) [unique]
  password varchar(255)
  phone varchar(50) [null]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp [null]
}

Table roles {
  id bigint [pk, increment]
  name varchar(50)
}

Table user_roles {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  role_id bigint [ref: > roles.id]
}

Table addresses {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  full_name varchar(255)
  phone varchar(50)
  address text
  city varchar(100)
  postal_code varchar(20)
  country varchar(100)
  is_default boolean [default: false]
  created_at timestamp
}

Table categories {
  id bigint [pk, increment]
  name varchar(255)
  slug varchar(255) [unique]
  created_at timestamp
}

Table occasions {
  id bigint [pk, increment]
  name varchar(100)
}

Table products {
  id bigint [pk, increment]
  category_id bigint [ref: > categories.id]
  name varchar(255)
  slug varchar(255) [unique]
  description text [null]
  type varchar(50) // bouquet, single, bundle
  price decimal(10,2)
  stock integer
  is_active boolean [default: true]
  created_at timestamp
  updated_at timestamp
  deleted_at timestamp [null]
}

Table product_occasions {
  id bigint [pk, increment]
  product_id bigint [ref: > products.id]
  occasion_id bigint [ref: > occasions.id]
}

Table product_images {
  id bigint [pk, increment]
  product_id bigint [ref: > products.id]
  image varchar(255)
  is_primary boolean [default: false]
}

Table carts {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  created_at timestamp
  updated_at timestamp
}

Table cart_items {
  id bigint [pk, increment]
  cart_id bigint [ref: > carts.id]
  product_id bigint [ref: > products.id]
  quantity integer [default: 1]
  price decimal(10,2)
}

Table orders {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  order_number varchar(255) [unique]
  total_amount decimal(10,2)
  status varchar(50) // pending, confirmed, preparing, out_for_delivery, delivered, cancelled
  payment_status varchar(50) // pending, paid, failed
  shipping_name varchar(255)
  shipping_phone varchar(50)
  shipping_address text
  delivery_date date
  delivery_time varchar(50)
  delivery_notes text [null]
  gift_message text [null]
  coupon_id bigint [ref: > coupons.id, null]
  delivery_area_id bigint [ref: > delivery_areas.id, null]
  tracking_number varchar(100) [null]
  courier varchar(100) [null]
  created_at timestamp
  updated_at timestamp
}

Table order_items {
  id bigint [pk, increment]
  order_id bigint [ref: > orders.id]
  product_id bigint [ref: > products.id]
  product_name varchar(255)
  price decimal(10,2)
  quantity integer
  subtotal decimal(10,2)
}

Table payments {
  id bigint [pk, increment]
  order_id bigint [ref: > orders.id]
  payment_method varchar(50) // cod, gcash, card
  transaction_id varchar(255)
  amount decimal(10,2)
  status varchar(50)
  paid_at timestamp [null]
  created_at timestamp
}

Table reviews {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  product_id bigint [ref: > products.id]
  rating integer
  comment text [null]
  status varchar(20) [default: 'pending'] // pending, approved, rejected
  created_at timestamp
  Indexes {
    (user_id, product_id) [unique]
  }
}

Table notifications {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  type varchar(50) // order, promo, system
  title varchar(255)
  message text
  is_read boolean [default: false]
  created_at timestamp
}

Table delivery_areas {
  id bigint [pk, increment]
  city varchar(100)
  province varchar(100)
  delivery_fee decimal(10,2)
  same_day_cutoff time [null] // e.g. 18:00:00
  is_active boolean [default: true]
}

Table wishlists {
  id bigint [pk, increment]
  user_id bigint [ref: > users.id]
  product_id bigint [ref: > products.id]
  created_at timestamp
  Indexes {
    (user_id, product_id) [unique]
  }
}

Table coupons {
  id bigint [pk, increment]
  code varchar(50)
  discount decimal(10,2)
  type varchar(20) // fixed, percentage
  expires_at timestamp
}