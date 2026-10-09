# Farzinhood — Hood & Sink Store

Farzinhood is a Laravel e-commerce project for a kitchen hood and sink store. Its routes cover a customer-facing storefront and an admin workspace with products, categories, inventory, orders, customers, reviews, blog content, contact messages, newsletter subscriptions, and site settings. Confirm the current state of checkout and payment integrations in source/tests before using them for live transactions.

## Dedicated admin dashboard
Farzinhood has its own dedicated administration dashboard for the store's product, inventory, order, customer, and content-management workflows. Verify the exact permissions and production readiness of individual operations in the current code and tests.

## Stack
- PHP `^8.2`, Laravel `^12.0`
- Blade, Vite and Laravel's Eloquent ORM
- Database migrations and seeders
- Frontend dependencies managed through npm

## Requirements
PHP 8.2+, Composer, Node.js/npm, and a supported database such as MySQL/MariaDB.

## Local setup
```bash
git clone https://github.com/MREZA-MJDi/farzinhood.git
cd farzinhood
composer install
```

Copy `.env.example` to `.env` (`copy .env.example .env` in Windows CMD; `cp .env.example .env` on macOS/Linux). Create a local database and set `DB_*` values.

```bash
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan storage:link
php artisan serve
```

Open `http://127.0.0.1:8000`. Run `npm run dev` in a second terminal for Vite hot reload.

## Tests
```bash
php artisan test
```

## Production checklist
Configure mail, storage, and payment credentials only through environment variables. Test inventory, order lifecycle, authorization, image uploads, and payment callbacks before launch. Review migrations and seeders before using them on any persistent database.

## Links
- Repository: https://github.com/MREZA-MJDi/farzinhood
- Laravel documentation: https://laravel.com/docs/12.x
