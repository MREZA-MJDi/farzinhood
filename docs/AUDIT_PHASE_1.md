# Farzinhood — Phase 1 Audit

Date: 2026-10-19
Branch: `audit/fix-duplicate-home-route`

## Scope
Read-only inspection of the public `main` source tree and targeted source files through GitHub. The local Windows XAMPP working tree was not accessed, and PHP/Composer/npm commands were not executed in this environment.

## Confirmed finding and fix
- `routes/web.php` declared the root path twice.
- The first declaration returned `view('home2')`, but `resources/views/home2.blade.php` does not exist in the repository.
- The later root route correctly targets `App\Http\Controllers\HomeController@index`, which returns `view('home')`; `resources/views/home.blade.php` also does not exist, but `resources/views/welcome.blade.php` is present. This is a further potential blocking issue requiring tracing the committed view tree before changing the controller's intended view.
- Removed the first duplicate `/` route that pointed to the missing `home2` view from `routes/web.php`, leaving the HomeController route as the sole root route.
- Updated `HomeController@index` to render the existing `welcome` view instead of the missing `home` view, preventing a second missing-view error.
- Changes are committed on this audit branch only; `main` was not modified.

## Configuration observations
- `vite.config.js` lists only `resources/js/app.js` as a Laravel Vite input.
- `resources/js/app.js` imports `../css/components.css`, but `resources/css/app.css` is also present and contains Tailwind imports, design tokens, fonts, and store styles. Since CSS is not directly listed as an entry, verify whether it is imported by the active JS/layout path before changing Vite inputs.
- `package.json` and `package-lock.json` dependencies appear aligned at a glance. The lockfile resolves packages from `registry.npmmirror.com`; confirm this mirror is intentional and reachable in CI/deployment.
- CI workflow includes Laravel tests and a Vite production build, but no test/build was run from this connector session.

## Phases 2–5 status
Not implemented yet. Before storefront redesign, first resolve active homepage view/layout and verify all view names and route references. E-commerce and payment implementation needs targeted security review and tests. Production deployment must not be attempted without target-environment details and backup/approval.

## Verification still required in a shell-enabled workspace
```bat
php artisan route:list
php artisan test
npm ci
npm run build
```

Also validate all `view(...)` references against `resources/views`, all `route(...)` references in Blade/PHP, Vite directives/layouts, and payment gateway configuration. Do not run migrations against persistent data until reviewed and backed up.