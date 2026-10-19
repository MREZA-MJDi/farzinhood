# Farzinhood — Phase 5: Configurable Homepage Product Tours

Date: 2026-10-19
Branch: `audit/fix-duplicate-home-route`

## Implemented

- Added the `home_tour_frames` table and `HomeTourFrame` model to store ordered, active frames for the `hood` and `sink` scenes.
- Added an admin screen at `/admin/home-tour`, accessible through the administration sidebar.
- Added multi-image uploads (up to 30 per request; JPG, PNG, WEBP, AVIF; 10 MB per image), image replacement, frame deletion, active/inactive toggles, alt text, and editable sort order.
- Connected active frames to the public homepage. The hood and sink tours are hidden until their respective scenes have active frames.
- Added frame changes controlled by page scroll, mouse position, drag, touch swipe, keyboard arrows, and previous/next buttons.
- Added scroll-aware storefront header behavior: hide while scrolling down and reveal on upward scrolling, with a translucent blurred background.
- Kept existing catalog, featured/latest product sections, storefront routes, and shared footer; the footer remains after all homepage sections, including the tour sections.

## Adding frames

1. Run `php artisan migrate` so the new `home_tour_frames` table exists.
2. Sign in as an administrator and open **Admin → تور ۳۶۰ درجه هود و سینک**.
3. Upload the hood or sink image frames in approximate viewing order.
4. Adjust each frame's order number and ensure the desired frames are active.
5. Ensure Laravel's public storage link exists (`php artisan storage:link`) so uploaded images under `storage/app/public` can be served.

For a more convincing 360-degree effect, use consecutive frames from the same product and camera position, rotate the camera/product in small consistent increments, keep the crop and lighting as consistent as possible, and use roughly 8–24 frames per tour. This is a frame-based image tour, not a generated 3D model.

## Verification still required

- Run `php artisan migrate`, `php artisan route:list --path=admin/home-tour`, `php artisan test`, and `npm run build` in the local Laravel/XAMPP project.
- Verify uploads, replacement and deletion with real images on the public storage disk.
- Check scroll / pointer / touch behavior across desktop and mobile browsers, including reduced-motion preferences and frame sequences with only one image.
- Confirm that the product imagery and order number are correct for the hood and sink scenes.

These commands and browser tests have not been run by the GitHub connector. The local Windows XAMPP directory has not been accessed or changed.
