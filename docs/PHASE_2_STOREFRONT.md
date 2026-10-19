# Farzinhood — Phase 2 Storefront

Date: 2026-10-19
Branch: `audit/fix-duplicate-home-route`

## Implemented in this branch

- Replaced the default Laravel welcome screen at the root route with a Persian, RTL storefront homepage driven by existing active products and categories.
- Added a shared storefront layout with responsive navigation, shop/blog/contact links, customer cart/account links, flash feedback, and footer.
- Added a reusable product-card Blade component with image fallback, category, brand, rating, price/discount, and stock availability.
- Added public shop catalog, category detail, and product detail views to match the view names already returned by the existing controllers.
- Added a hero carousel with previous/next controls, pagination dots, timed progression, and touch handling using the existing `initHomeHero()` script. Respects reduced-motion preferences.
- Added responsive catalog, category, product, and hero styling in `resources/css/shop-catalog.css` and `resources/css/home-hero-rail.css`, using the existing app stylesheet and design tokens.
- Registered `resources/css/app.css` as a Vite entry alongside `resources/js/app.js`.
- Used explicit slug route parameters for public product and category links to match the routes in `routes/web.php`.

## Important boundaries / not yet verified

- The code was committed to the GitHub branch only; the local XAMPP folder was not accessed or changed.
- PHP/Laravel tests and `npm run build` were not executed in this environment.
- The carousel uses available product images as slides. The user-referenced inspiration video has not been provided in this conversation, so the animation is a responsive product-led interpretation rather than a verified video match.
- Product image URLs assume the existing Laravel public storage disk and a correctly configured `public/storage` symlink.
- Cart add form uses the existing authenticated customer cart endpoint. End-to-end cart behavior still requires running the application and validating the form against the current `CartRequest`.
- Payment/checkout flows are intentionally outside Phase 2 and belong to Phase 3.

## Suggested verification when the branch is checked out

1. `composer install`
2. `npm ci`
3. Configure the local `.env` and database.
4. `php artisan storage:link`
5. `npm run build`
6. `php artisan test`
7. Visit `/`, `/shop`, a category slug URL, and a product slug URL at desktop and mobile widths.
