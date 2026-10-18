# Farzinhood — Persian Storefront Redesign Brief

## Scope
This redesign branch is based on `main` and limited to the public storefront and customer-facing account journeys. The admin workspace, database schema, order/payment logic, and backend integrations are intentionally out of scope.

## Product
Farzinhood is a Persian-first RTL e-commerce application for kitchen hoods and sinks, built on Laravel 12, Blade, Vite, Tailwind CSS 4, and local Vazirmatn fonts.

## Primary journeys
- Discover products through the home page, categories, search, catalog filters and sorting.
- Inspect product image galleries, pricing, availability, discounts and reviews.
- Sign in or register, manage the cart, continue to checkout, and view orders.
- Maintain customer profile, saved addresses, wishlist and contact requests.
- Read the store's buying guides and product journal.

## Reference direction
- Warm off-white backgrounds, clean white surfaces, quiet borders and soft shadows.
- Charcoal typography with a muted olive accent for primary interactions.
- Generous whitespace and product photography as the visual focus.
- Persian copy, right-to-left layout, Vazirmatn type, and deliberate LTR handling for email, SKU and telephone values.
- Responsive touch targets, strong keyboard focus, reduced-motion support and no reliance on hover-only actions.

## Engineering constraints
- Keep current routes, Blade data bindings, validation, form methods, CSRF protection and controller/service responsibilities.
- No added frontend dependencies.
- Do not claim payment/security/stock guarantees in marketing copy unless those claims are proven by backend behavior.
- No production deploy, production data changes, destructive migrations or merge to `main` without explicit approval.

## Verification gates
Inspect route/view coverage, use the repository's feature tests and Pint, build Vite assets, validate narrow and wide viewports, review keyboard/screen-reader states, and audit environment configuration and transaction flows before release.
