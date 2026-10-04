<?php

use App\Http\Controllers\Admin\BlogCategoryController as AdminBlogCategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\NewsletterController as AdminNewsletterController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

use App\Http\Controllers\Auth\AuthController;

use App\Http\Controllers\Customer\AddressController;
use App\Http\Controllers\Customer\BlogController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CategoryController as CustomerCategoryController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\ContactController;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Customer\NewsletterController as CustomerNewsletterController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Customer\SettingsController;
use App\Http\Controllers\Customer\ShopController;
use App\Http\Controllers\Customer\WishlistController;

use App\Http\Controllers\HomeController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
|
| Pages in this section are accessible without authentication.
|
*/


/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');


/*
|--------------------------------------------------------------------------
| Shop
|--------------------------------------------------------------------------
*/

Route::get('/shop', [ShopController::class, 'index'])
    ->name('shop.index');


/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

Route::get('/categories/{category:slug}', [CustomerCategoryController::class, 'show'])
    ->name('categories.show');


/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
*/

Route::get('/products/{product:slug}', [CustomerProductController::class, 'show'])
    ->name('products.show');


/*
|--------------------------------------------------------------------------
| Blog
|--------------------------------------------------------------------------
*/

Route::get('/blog', [BlogController::class, 'index'])
    ->name('blog.index');

Route::get('/blog/{post:slug}', [BlogController::class, 'show'])
    ->name('blog.show');


/*
|--------------------------------------------------------------------------
| Contact
|--------------------------------------------------------------------------
*/

Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact.index');

Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');


/*
|--------------------------------------------------------------------------
| Newsletter
|--------------------------------------------------------------------------
*/

Route::post('/newsletter', [CustomerNewsletterController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('newsletter.store');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');


    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */

    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->middleware('throttle:5,1')
        ->name('register.store');
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Customer
|--------------------------------------------------------------------------
|
| Everything inside this group requires an authenticated customer.
|
*/

Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'customer'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [CustomerHomeController::class, 'index'])
            ->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Cart
        |--------------------------------------------------------------------------
        */

        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/cart/add', [CartController::class, 'add'])
            ->name('cart.add');

        Route::patch('/cart/{product}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/cart/{product}', [CartController::class, 'remove'])
            ->name('cart.remove');

        Route::delete('/cart', [CartController::class, 'clear'])
            ->name('cart.clear');


        /*
        |--------------------------------------------------------------------------
        | Wishlist
        |--------------------------------------------------------------------------
        */

        Route::get('/wishlist', [WishlistController::class, 'index'])
            ->name('wishlist.index');

        Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])
            ->name('wishlist.toggle');


        /*
        |--------------------------------------------------------------------------
        | Checkout
        |--------------------------------------------------------------------------
        */

        Route::get('/checkout', [CheckoutController::class, 'index'])
            ->name('checkout.index');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [CustomerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
            ->name('orders.show');


        /*
        |--------------------------------------------------------------------------
        | Payment
        |--------------------------------------------------------------------------
        */

        Route::get('/orders/{order}/payment', [PaymentController::class, 'start'])
            ->name('payment.start');

        Route::get('/orders/{order}/payment/callback', [PaymentController::class, 'callback'])
            ->name('payment.callback');


        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        */

        Route::post('/reviews', [ReviewController::class, 'store'])
            ->name('reviews.store');


        /*
        |--------------------------------------------------------------------------
        | Addresses
        |--------------------------------------------------------------------------
        */

        Route::get('/addresses', [AddressController::class, 'index'])
            ->name('addresses.index');

        Route::post('/addresses', [AddressController::class, 'store'])
            ->name('addresses.store');

        Route::put('/addresses/{address}', [AddressController::class, 'update'])
            ->name('addresses.update');

        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])
            ->name('addresses.destroy');

        Route::patch('/addresses/{address}/default', [AddressController::class, 'makeDefault'])
            ->name('addresses.default');


        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', [SettingsController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [SettingsController::class, 'update'])
            ->name('settings.update');
    });


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
|
| Everything inside this group requires an authenticated admin.
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/dashboard/chart', [AdminDashboardController::class, 'chart'])
            ->name('dashboard.chart');


        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Route::resource('products', AdminProductController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Product Images
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/product-images/{productImage}',
            [AdminProductController::class, 'destroyImage']
        )->name('product-images.destroy');

        Route::patch(
            '/product-images/{productImage}/primary',
            [AdminProductController::class, 'setPrimaryImage']
        )->name('product-images.primary');


        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::resource('categories', AdminCategoryController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Inventory
        |--------------------------------------------------------------------------
        */

        Route::get('/inventory', [InventoryController::class, 'index'])
            ->name('inventory.index');

        Route::get(
            '/inventory/products/{product}/movements',
            [InventoryController::class, 'movements']
        )->name('inventory.movements');

        Route::post(
            '/inventory/products/{product}/adjust',
            [InventoryController::class, 'adjust']
        )->name('inventory.adjust');


        /*
        |--------------------------------------------------------------------------
        | Orders
        |--------------------------------------------------------------------------
        */

        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch(
            '/orders/{order}/status',
            [AdminOrderController::class, 'updateStatus']
        )->name('orders.status');


        /*
        |--------------------------------------------------------------------------
        | Reviews
        |--------------------------------------------------------------------------
        */

        Route::get('/reviews', [AdminReviewController::class, 'index'])
            ->name('reviews.index');

        Route::patch(
            '/reviews/{review}/status',
            [AdminReviewController::class, 'updateStatus']
        )->name('reviews.status');

        Route::delete(
            '/reviews/{review}',
            [AdminReviewController::class, 'destroy']
        )->name('reviews.destroy');


        /*
        |--------------------------------------------------------------------------
        | Blog Categories
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'blog-categories',
            AdminBlogCategoryController::class
        )
            ->parameters([
                'blog-categories' => 'blogCategory',
            ])
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Posts
        |--------------------------------------------------------------------------
        */

        Route::resource('posts', AdminPostController::class)
            ->except(['show']);


        /*
        |--------------------------------------------------------------------------
        | Customers
        |--------------------------------------------------------------------------
        */

        Route::get('/customers', [CustomerController::class, 'index'])
            ->name('customers.index');

        Route::get('/customers/{customer}', [CustomerController::class, 'show'])
            ->name('customers.show');

        Route::patch(
            '/customers/{customer}/status',
            [CustomerController::class, 'toggleStatus']
        )->name('customers.status');


        /*
        |--------------------------------------------------------------------------
        | Contact Messages
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/contact-messages',
            [ContactMessageController::class, 'index']
        )->name('contact-messages.index');

        Route::get(
            '/contact-messages/{contactMessage}',
            [ContactMessageController::class, 'show']
        )->name('contact-messages.show');

        Route::patch(
            '/contact-messages/{contactMessage}/read',
            [ContactMessageController::class, 'markAsRead']
        )->name('contact-messages.read');

        Route::patch(
            '/contact-messages/{contactMessage}/replied',
            [ContactMessageController::class, 'markAsReplied']
        )->name('contact-messages.replied');

        Route::patch(
            '/contact-messages/{contactMessage}/close',
            [ContactMessageController::class, 'close']
        )->name('contact-messages.close');

        Route::delete(
            '/contact-messages/{contactMessage}',
            [ContactMessageController::class, 'destroy']
        )->name('contact-messages.destroy');


        /*
        |--------------------------------------------------------------------------
        | Newsletter
        |--------------------------------------------------------------------------
        */

        Route::get('/newsletter', [AdminNewsletterController::class, 'index'])
            ->name('newsletter.index');

        Route::patch(
            '/newsletter/{newsletterSubscriber}/activate',
            [AdminNewsletterController::class, 'activate']
        )->name('newsletter.activate');

        Route::patch(
            '/newsletter/{newsletterSubscriber}/deactivate',
            [AdminNewsletterController::class, 'deactivate']
        )->name('newsletter.deactivate');

        Route::delete(
            '/newsletter/{newsletterSubscriber}',
            [AdminNewsletterController::class, 'destroy']
        )->name('newsletter.destroy');


        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */

        Route::get('/settings', [AdminSettingsController::class, 'index'])
            ->name('settings.index');

        Route::put('/settings', [AdminSettingsController::class, 'update'])
            ->name('settings.update');
    });
