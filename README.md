# CNS Commerce — Small E-commerce Management System

## Overview

A multi-shop e-commerce management system built for the **CSN Bangladesh Technical Assessment**.

Administrators manage shops, shop owners, and a shared product catalog. Shop owners can browse the catalog, assign products to their shop, and customize prices, descriptions, and thumbnails per shop. Customers browse products assigned to individual storefronts.

**Core principle:** The `products` table is a shared catalog. The `shop_products` table links a product to a shop and holds shop-specific customizations (price, description, thumbnail). Changes in one shop never affect another.

---

## Tech Stack

| Layer              | Technology                  |
|--------------------|-----------------------------|
| Backend            | Laravel 13                  |
| Language (backend) | PHP 8.3+                    |
| Frontend           | Vue 3 + TypeScript          |
| Integration        | Inertia.js                  |
| CSS                | Tailwind CSS 4              |
| Database           | MySQL 8                     |
| Authentication     | Laravel Breeze (Vue + SSR)  |
| Authorization      | Middleware + Policies       |
| Validation         | Laravel Form Requests       |
| Build tool         | Vite                        |
| Testing            | PHPUnit 12 (SQLite :memory:)|

---

## Requirements

- **PHP** 8.3 or higher
- **Composer** 2
- **Node.js** 22 or higher
- **MySQL** 8
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `bcmath`, `curl`, `xml`, `ctype`, `tokenizer`

---

## Installation

```bash
# 1. Clone the repository
git clone <repository-url> cns-commerce
cd cns-commerce

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Copy environment file and configure
cp .env.example .env
php artisan key:generate

# 5. Edit .env — set your database credentials
#    DB_DATABASE=cnscommerce
#    DB_USERNAME=root
#    DB_PASSWORD=yourpassword

# 6. Create the database (MySQL)
mysql -u root -p -e "CREATE DATABASE cnscommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Run migrations and seed demo data
php artisan migrate:fresh --seed

# 8. Build frontend assets
npm run build

# 9. Start the development server
php artisan serve
```

---

## Demo Accounts

| Role        | Email                   | Password |
|-------------|-------------------------|----------|
| Admin       | admin@example.com       | password |
| Shop Owner  | owner1@example.com      | password |
| Shop Owner  | owner2@example.com      | password |

---

## URLs

| URL                     | Description                              |
|-------------------------|------------------------------------------|
| `/`                     | Storefront home — lists all active shops |
| `/login`                | Login page                               |
| `/admin/dashboard`      | Admin panel (admin only)                 |
| `/owner/dashboard`      | Shop owner panel (shop owners only)      |
| `/shop/{slug}`          | Public storefront for a specific shop    |

---

## Testing

Tests use SQLite `:memory:` — no MySQL required for the test suite.

```bash
# Run all tests
composer test

# Or run directly
php artisan test
```

All tests live in `tests/Feature/` organized by domain:

- `Auth/` — authorization and access control
- `Admin/` — admin CRUD for shops, owners, products
- `Catalog/` — catalog browsing and product assignment
- `Isolation/` — per-shop price isolation and ownership enforcement
- `Storefront/` — public storefront visibility rules

---

## Key Features

- **Multi-shop architecture** — each shop has its own product listing, pricing, and appearance
- **Shared product catalog** — admins and shop owners both contribute products to a single catalog
- **Per-shop customization** — price, description, and thumbnail can be overridden per shop without affecting other shops
- **Role-based access control** — admins and shop owners see entirely different dashboards with middleware-enforced separation
- **Registration disabled** — shop-owner accounts are created by admins only; `/register` returns 404
- **Shop isolation guaranteed** — the `shop_products` unique constraint (`shop_id`, `product_id`) prevents duplicate assignments; separate pivot rows ensure pricing changes in one shop never leak to another
- **Customer storefront** — customers see only active products assigned to the requested shop; inactive shops and unassigned products return 404
