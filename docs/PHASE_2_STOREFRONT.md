# Farzinhood — Phase 2 Storefront

Date: 2026-10-19
Branch: `audit/fix-duplicate-home-route`

## Implemented in this branch

- Replaced the default Laravel welcome screen at the root route with a Persian RTL storefront homepage driven by active products and categories.
- Added a shared storefront layout with responsive navigation, shop/blog/contact links, account entry, feedback messages, and footer.
- Added a reusable product-card partial with image fallback, category/brand, pricing, and product detail links.
- Added public shop catalog filters (search, category, price range, sorting), category listing, and product detail views using the existing controller view names.
- Added a timed product-image hero slider, category tiles, featured products, latest products, and a promotional section.
- Restored the existing hero/catalog stylesheet contents and filled the formerly empty catalog stylesheet only where needed; existing global stylesheet imports are preserved.
- Confirmed `vite.config.js` includes both `resources/css/app.css` and `resources/js/app.js` as Vite inputs.
- Used explicit slug route parameters for public product/category links and the existing customer cart endpoint for signed-in customer add-to-cart forms.

## Boundaries / verification

- Changes are committed to this GitHub branch and remain unmerged in draft PR #11. The local XAMPP folder was not accessed or changed.
- PHP/Laravel tests and `npm run build` have not been executed in this environment; a green build is not claimed.
- The carousel uses existing product images. The inspiration video was not available in this conversation, so matching it exactly is not verified.
- Product images assume the existing public storage disk and a working `public/storage` symlink.
- End-to-end cart behavior and storefront rendering still need validation against the local database and current environment.

## Suggested local verification

1. Check out `audit/fix-duplicate-home-route`.
2. Run `composer install` and `npm ci`.
3. Configure `.env` and the database.
4. Run `php artisan storage:link`.
5. Run `npm run build` and `php artisan test`.
6. Visit `/`, `/shop`, a category slug URL, and a product slug URL at desktop and mobile widths.
