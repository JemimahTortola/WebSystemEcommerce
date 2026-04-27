# Flourista - E-Commerce Florist Website

A Laravel-based e-commerce website for a florist shop in Butuan City.

## Documentation

All documentation files are in the `docs/` folder:

| File | Description |
|------|-------------|
| `DOCUMENTATION.md` | Main documentation with site structure |
| `functional-requirements.md` | Feature requirements |
| `database-reference.md` | Database schema reference |
| `CSS-GUIDE.md` | CSS learning guide for beginners |
| `JS-GUIDE.md` | JavaScript learning guide for beginners |

## Quick Start

```bash
# Install dependencies
composer install
npm install

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database (optional)
php artisan db:seed

# Start development server
php artisan serve
```

## Tech Stack

- **Backend**: Laravel 10.x
- **Frontend**: HTML, CSS, JavaScript
- **Database**: MySQL
- **Server**: Built-in PHP server

## Features

- Product catalog with categories
- Shopping cart
- User authentication
- Order management
- Admin panel
- Wishlist
- Address management

## License

MIT License