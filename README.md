# CNS Commerce — Small E-commerce Management System

A multi-shop e-commerce management system built for the **CSN Bangladesh Technical Assessment**.

Administrators manage shops, shop owners, and a shared product catalog. Shop owners can browse the catalog, assign products to their shop, and customize prices, descriptions, and thumbnails per shop. Customers browse the public storefront and discover products assigned to individual shops.

**Core design principle:** The `products` table is a shared global catalog. The `shop_products` pivot table links a product to a shop and stores shop-specific overrides (price, description, thumbnail). A change in one shop never affects another.

---

## Tech Stack

| Layer | Technology |
|---|---|
| Backend framework | Laravel 13 |
| Backend language | PHP 8.3+ |
| Frontend framework | Vue 3 + TypeScript |
| Frontend routing | Vue Router 4 |
| State management | Pinia |
| HTTP client | Axios |
| CSS | Tailwind CSS 4 |
| Database | MySQL 8 |
| Authentication | Laravel Sanctum (SPA cookie mode) |
| Authorization | Custom role middleware + Laravel Policies |
| Validation | Laravel Form Requests |
| Build tool | Vite 8 |
| Testing | PHPUnit 12 (SQLite `:memory:`) |

---

## Architecture

This is a **monolithic Laravel + Vue SPA**:

- Laravel serves a single Blade entry point (`resources/views/app.blade.php` → `<div id="app">`)
- Vue Router handles all client-side navigation (`/`, `/login`, `/admin/*`, `/owner/*`, `/shop/*`)
- All data flows through a REST API (`/api/*`) returning JSON
- Authentication uses **Sanctum SPA cookie mode** — no tokens, no localStorage, same-origin session cookies
- The API is split into three route groups: `Admin`, `Owner`, and `Storefront (public)`

```
Browser
  │
  └── GET /{any}  →  app.blade.php  →  Vue SPA (Vue Router)
                                            │
                                            └── axios  →  /api/*  →  Laravel JSON responses
```

---

## Requirements

- PHP 8.3 or higher
- Composer 2
- Node.js 22 or higher
- MySQL 8
- PHP extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `bcmath`, `curl`, `xml`, `ctype`, `tokenizer`

---

## Installation

```bash
# 1. Clone
git clone https://github.com/Shoaib3375/CNScommerce.git
cd CNScommerce

# 2. PHP dependencies
composer install

# 3. Node dependencies
npm install

# 4. Environment
cp .env.example .env
php artisan key:generate

# 5. Configure .env — set your database credentials
#    DB_CONNECTION=mysql
#    DB_DATABASE=cnscommerce
#    DB_USERNAME=root
#    DB_PASSWORD=yourpassword
#    SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8000,localhost:8000

# 6. Create the database
mysql -u root -p -e "CREATE DATABASE cnscommerce CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Run migrations and seed demo data
php artisan migrate:fresh --seed

# 8. Create storage symlink (for uploaded images)
php artisan storage:link

# 9. Build frontend
npm run build

# 10. Start the development server
php artisan serve
```

Visit: **http://127.0.0.1:8000**

---

## Demo Accounts

| Role | Email | Password |
|---|---|---|
| Admin | admin@example.com | password |
| Shop Owner 1 | owner1@example.com | password |
| Shop Owner 2 | owner2@example.com | password |

> These are local demo credentials only. Never use predictable passwords in production.

---

## Application URLs

| URL | Description |
|---|---|
| `http://127.0.0.1:8000/` | Public storefront — shop listing, featured products, live search |
| `http://127.0.0.1:8000/login` | Login page |
| `http://127.0.0.1:8000/admin/dashboard` | Admin panel (admin role required) |
| `http://127.0.0.1:8000/owner/dashboard` | Shop owner panel (shop_owner role required) |
| `http://127.0.0.1:8000/shop/{slug}` | Individual shop storefront |

---

## REST API Endpoints

All API routes are under `/api/`. Protected routes require an authenticated session (Sanctum SPA cookie).

### Auth
| Method | Endpoint | Description |
|---|---|---|
| `POST` | `/api/login` | Authenticate user, return user JSON |
| `POST` | `/api/logout` | Destroy session |
| `GET` | `/api/user` | Return authenticated user |

### Admin (`auth:sanctum + role:admin`)
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/admin/dashboard` | Stats: shops, owners, products, assignments |
| `GET/POST` | `/api/admin/shops` | List / create shops |
| `GET/PUT/DELETE` | `/api/admin/shops/{shop}` | Show / update / delete shop |
| `GET/POST` | `/api/admin/owners` | List / create shop-owner accounts |
| `GET/PUT/DELETE` | `/api/admin/owners/{owner}` | Show / update / delete owner |
| `GET/POST` | `/api/admin/products` | List / create global products |
| `GET/PUT/DELETE` | `/api/admin/products/{product}` | Show / update / delete product |

### Owner (`auth:sanctum + role:shop_owner`)
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/owner/dashboard` | Owner's shop stats |
| `GET` | `/api/owner/catalog` | Browse global catalog (`?search=`) |
| `POST` | `/api/owner/catalog/assign/{product:slug}` | Assign catalog product to own shop |
| `GET/PUT/DELETE` | `/api/owner/shop-products/{id}` | Manage assigned product listing |
| `GET/POST/PUT/DELETE` | `/api/owner/my-products` | Manage own created products |

### Storefront (public)
| Method | Endpoint | Description |
|---|---|---|
| `GET` | `/api/storefront/home` | Active shops + featured products |
| `GET` | `/api/storefront/shops/{slug}` | Shop detail + active assigned products |
| `GET` | `/api/storefront/shops/{slug}/products/{slug}` | Shop-specific product detail |
| `GET` | `/api/search?q=` | Live product search (min 2 chars) |

---

## Testing

Tests use SQLite `:memory:` — no MySQL connection required.

```bash
# Run all tests
php artisan test

# Or via composer
composer test
```

**91 tests, 225 assertions** across two suites:

### Original suite (`tests/Feature/`) — Inertia/web layer
- `Auth/` — role-based access control, registration disabled
- `Admin/` — shop, owner, product CRUD
- `Catalog/` — catalog browsing, product assignment, duplicate prevention
- `Isolation/` — per-shop price isolation, ownership enforcement
- `Storefront/` — public visibility rules, 404 for unassigned/inactive

### API suite (`tests/Feature/Api/`) — REST API layer
- `AuthApiTest` — login, logout, unauthenticated/unauthorized responses
- `Admin/AdminShopApiTest` — JSON CRUD for shops, dashboard counts
- `Admin/AdminProductApiTest` — JSON CRUD for products, search filtering
- `Owner/OwnerCatalogApiTest` — catalog browsing, product assignment, isolation
- `Storefront/StorefrontApiTest` — shop listing, product visibility, live search

---

## Key Features

- **Multi-shop catalog** — admins and shop owners both contribute to a single shared product catalog
- **Per-shop customization** — price, description, and thumbnail are overridden per shop in `shop_products` without affecting other shops or the global record
- **Role-based access** — `admin` and `shop_owner` roles enforced at the route level (middleware) and resource level (Laravel Policies)
- **Sanctum SPA auth** — cookie-based authentication, no tokens, no localStorage; CSRF protected on all mutating requests
- **Public registration disabled** — shop-owner accounts are created by admins only; `/register` returns 404
- **Shop data isolation** — the `UNIQUE(shop_id, product_id)` constraint prevents duplicate assignments; separate pivot rows guarantee pricing changes in one shop never leak to another
- **Live product search** — `GET /api/search?q=` queries active shop_products by name/description, returns matches with thumbnail, shop name, and shop-specific price
- **Vue Router SPA** — full client-side routing with Pinia auth store; protected routes redirect unauthenticated users to `/login`

---

## Development Commands

```bash
# Start all services (server + queue + Vite HMR)
composer run dev

# Build for production
npm run build

# Run tests
php artisan test

# Fresh database with seed data
php artisan migrate:fresh --seed

# Check registered routes
php artisan route:list --path=api
```
