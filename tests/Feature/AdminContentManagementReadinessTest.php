<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminContentManagementReadinessTest extends TestCase
{
    use RefreshDatabase;

    private function admin(string $email = 'admin-content@example.com'): User
    {
        return User::query()->create([
            'name' => 'Admin',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_content_create_forms_load_and_expose_image_previews(): void
    {
        $this->actingAs($this->admin());

        $this->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('data-media-picker', false)
            ->assertSee('data-media-preview', false);

        $this->get(route('admin.categories.create'))
            ->assertOk()
            ->assertSee('data-media-picker', false)
            ->assertSee('data-media-preview', false);

        $this->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('data-media-picker', false)
            ->assertSee('data-media-preview', false);

        $this->get(route('admin.blog-categories.create'))
            ->assertOk()
            ->assertSee('data-media-picker', false)
            ->assertSee('data-media-preview', false);
    }

    public function test_product_and_category_seo_and_image_uploads_are_persisted(): void
    {
        Storage::fake('public');
        $admin = $this->admin('admin-products@example.com');

        $this->actingAs($admin);

        $categoryImage = UploadedFile::fake()->image('kitchen-category.jpg', 640, 480);
        $categoryCanonical = 'https://example.com/catalog/kitchen';

        $this->post(route('admin.categories.store'), [
            'name' => 'تجهیزات آشپزخانه',
            'slug' => 'kitchen-equipment',
            'description' => 'تجهیزات آشپزخانه',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => $categoryImage,
            'meta_title' => 'عنوان سئوی دسته‌بندی',
            'meta_description' => 'توضیحات سئوی دسته‌بندی',
            'canonical_url' => $categoryCanonical,
            'noindex' => 1,
        ])->assertRedirect(route('admin.categories.index'));

        $category = Category::query()->where('slug', 'kitchen-equipment')->firstOrFail();

        $this->assertSame('عنوان سئوی دسته‌بندی', $category->meta_title);
        $this->assertSame('توضیحات سئوی دسته‌بندی', $category->meta_description);
        $this->assertSame($categoryCanonical, $category->canonical_url);
        $this->assertTrue($category->noindex);
        Storage::disk('public')->assertExists($category->image);

        $productImage = UploadedFile::fake()->image('hood.jpg', 800, 600);
        $productCanonical = 'https://example.com/products/test-hood';

        $this->post(route('admin.products.store'), [
            'category_id' => $category->id,
            'name' => 'هود تست',
            'slug' => 'test-hood',
            'sku' => 'HOOD-TEST-001',
            'short_description' => 'هود نمونه برای تست',
            'description' => 'شرح محصول آزمایشی',
            'price' => 1250000,
            'stock' => 8,
            'is_active' => 1,
            'is_featured' => 0,
            'images' => [$productImage],
            'meta_title' => 'عنوان سئوی محصول',
            'meta_description' => 'توضیحات سئوی محصول',
            'canonical_url' => $productCanonical,
            'noindex' => 1,
        ])->assertRedirect(route('admin.products.index'));

        $product = Product::query()->where('slug', 'test-hood')->firstOrFail();

        $this->assertSame('عنوان سئوی محصول', $product->meta_title);
        $this->assertSame('توضیحات سئوی محصول', $product->meta_description);
        $this->assertSame($productCanonical, $product->canonical_url);
        $this->assertTrue($product->noindex);

        $image = ProductImage::query()
            ->where('product_id', $product->id)
            ->firstOrFail();

        $this->assertTrue($image->is_primary);
        Storage::disk('public')->assertExists($image->image);

        $this->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('حذف تصویر');

        $secondImagePath = 'products/secondary-test-image.jpg';
        Storage::disk('public')->put($secondImagePath, 'fake-image-content');

        $secondImage = $product->images()->create([
            'image' => $secondImagePath,
            'alt' => $product->name . ' secondary',
            'sort_order' => 99,
            'is_primary' => false,
        ]);

        $this->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('تنظیم به‌عنوان تصویر اصلی')
            ->assertSee('حذف تصویر');

        $this->patch(route('admin.product-images.primary', $secondImage))
            ->assertRedirect();

        $this->assertDatabaseHas('product_images', [
            'id' => $secondImage->id,
            'is_primary' => true,
        ]);
        $this->assertDatabaseHas('product_images', [
            'id' => $image->id,
            'is_primary' => false,
        ]);

        $this->delete(route('admin.product-images.destroy', $secondImage))
            ->assertRedirect();

        Storage::disk('public')->assertMissing($secondImagePath);
        $this->assertDatabaseMissing('product_images', [
            'id' => $secondImage->id,
        ]);
        $this->assertDatabaseHas('product_images', [
            'id' => $image->id,
            'is_primary' => true,
        ]);

        $this->get(route('categories.show', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('عنوان سئوی دسته‌بندی')
            ->assertSee('noindex, follow');

        $this->get(route('products.show', ['product' => $product->slug]))
            ->assertOk()
            ->assertSee('عنوان سئوی محصول')
            ->assertSee('noindex, follow')
            ->assertSee($productCanonical);
    }

    public function test_blog_category_and_article_seo_and_image_uploads_are_persisted(): void
    {
        Storage::fake('public');
        $admin = $this->admin('admin-blog@example.com');

        $this->actingAs($admin);

        $categoryImage = UploadedFile::fake()->image('guides.jpg', 640, 480);
        $categoryCanonical = 'https://example.com/blog?category=guides';

        $this->post(route('admin.blog-categories.store'), [
            'name' => 'راهنماها',
            'slug' => 'guides',
            'description' => 'مقاله‌های راهنما',
            'sort_order' => 1,
            'is_active' => 1,
            'image' => $categoryImage,
            'meta_title' => 'عنوان سئوی راهنماها',
            'meta_description' => 'توضیحات سئوی راهنماها',
            'canonical_url' => $categoryCanonical,
            'noindex' => 1,
        ])->assertRedirect(route('admin.blog-categories.index'));

        $category = BlogCategory::query()->where('slug', 'guides')->firstOrFail();

        $this->assertSame('عنوان سئوی راهنماها', $category->meta_title);
        $this->assertSame('توضیحات سئوی راهنماها', $category->meta_description);
        $this->assertSame($categoryCanonical, $category->canonical_url);
        $this->assertTrue($category->noindex);
        Storage::disk('public')->assertExists($category->image);

        $featuredImage = UploadedFile::fake()->image('buying-guide.jpg', 800, 600);
        $postCanonical = 'https://example.com/blog/test-buying-guide';

        $this->post(route('admin.posts.store'), [
            'blog_category_id' => $category->id,
            'title' => 'راهنمای خرید آزمایشی',
            'slug' => 'test-buying-guide',
            'excerpt' => 'چکیده آزمایشی',
            'content' => 'متن آزمایشی مقاله',
            'featured_image' => $featuredImage,
            'status' => 'published',
            'meta_title' => 'عنوان سئوی مقاله',
            'meta_description' => 'توضیحات سئوی مقاله',
            'canonical_url' => $postCanonical,
            'noindex' => 1,
        ])->assertRedirect(route('admin.posts.index'));

        $post = Post::query()->where('slug', 'test-buying-guide')->firstOrFail();

        $this->assertSame('عنوان سئوی مقاله', $post->meta_title);
        $this->assertSame('توضیحات سئوی مقاله', $post->meta_description);
        $this->assertSame($postCanonical, $post->canonical_url);
        $this->assertTrue($post->noindex);
        Storage::disk('public')->assertExists($post->featured_image);

        $this->get(route('blog.index', ['category' => $category->slug]))
            ->assertOk()
            ->assertSee('عنوان سئوی راهنماها')
            ->assertSee('noindex, follow');

        $this->get(route('blog.show', ['post' => $post->slug]))
            ->assertOk()
            ->assertSee('عنوان سئوی مقاله')
            ->assertSee('noindex, follow')
            ->assertSee($postCanonical);
    }

    public function test_inactive_admin_cannot_access_the_admin_workspace(): void
    {
        $admin = $this->admin('inactive-admin@example.com');
        $admin->update(['is_active' => false]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_newsletter_admin_page_loads_with_its_validated_request(): void
    {
        $this->actingAs($this->admin('admin-newsletter@example.com'))
            ->get(route('admin.newsletter.index'))
            ->assertOk();
    }


    public function test_all_admin_management_index_routes_render_for_an_admin(): void
    {
        $this->actingAs($this->admin('admin-index-pages@example.com'));

        $routes = [
            'admin.dashboard',
            'admin.products.index',
            'admin.categories.index',
            'admin.inventory.index',
            'admin.orders.index',
            'admin.reviews.index',
            'admin.blog-categories.index',
            'admin.posts.index',
            'admin.customers.index',
            'admin.contact-messages.index',
            'admin.newsletter.index',
            'admin.settings.index',
        ];

        foreach ($routes as $routeName) {
            $this->get(route($routeName))
                ->assertOk();
        }

        $this->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('data-admin-sidebar', false)
            ->assertSee('data-admin-sidebar-open', false)
            ->assertSee('data-admin-profile-menu', false)
            ->assertDontSee('x-data=', false)
            ->assertDontSee('x-show=', false);
    }


    public function test_admin_edit_forms_render_for_existing_catalog_and_blog_records(): void
    {
        $admin = $this->admin('admin-edit-forms@example.com');

        $category = Category::query()->create([
            'name' => 'دسته برای ویرایش',
            'slug' => 'category-for-edit',
            'description' => 'دسته آزمایشی',
            'is_active' => true,
            'meta_title' => 'SEO دسته برای ویرایش',
            'meta_description' => 'توضیحات دسته',
            'canonical_url' => 'https://example.com/category-for-edit',
            'noindex' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'محصول برای ویرایش',
            'slug' => 'product-for-edit',
            'sku' => 'PRODUCT-FOR-EDIT-01',
            'price' => 100000,
            'stock' => 5,
            'is_active' => true,
            'meta_title' => 'SEO محصول برای ویرایش',
            'meta_description' => 'توضیحات محصول',
            'canonical_url' => 'https://example.com/product-for-edit',
            'noindex' => true,
        ]);

        $blogCategory = BlogCategory::query()->create([
            'name' => 'دسته وبلاگ برای ویرایش',
            'slug' => 'blog-category-for-edit',
            'description' => 'دسته وبلاگ آزمایشی',
            'is_active' => true,
            'sort_order' => 1,
            'meta_title' => 'SEO دسته وبلاگ',
            'meta_description' => 'توضیحات دسته وبلاگ',
            'canonical_url' => 'https://example.com/blog-category-for-edit',
            'noindex' => true,
        ]);

        $post = Post::query()->create([
            'blog_category_id' => $blogCategory->id,
            'author_id' => $admin->id,
            'title' => 'مقاله برای ویرایش',
            'slug' => 'post-for-edit',
            'excerpt' => 'چکیده مقاله',
            'content' => 'متن مقاله آزمایشی',
            'status' => 'draft',
            'published_at' => null,
            'meta_title' => 'SEO مقاله برای ویرایش',
            'meta_description' => 'توضیحات مقاله',
            'canonical_url' => 'https://example.com/post-for-edit',
            'noindex' => true,
        ]);

        $this->actingAs($admin);

        $this->get(route('admin.categories.edit', $category))
            ->assertOk()
            ->assertSee('SEO دسته برای ویرایش');

        $this->get(route('admin.products.edit', $product))
            ->assertOk()
            ->assertSee('SEO محصول برای ویرایش');

        $this->get(route('admin.blog-categories.edit', $blogCategory))
            ->assertOk()
            ->assertSee('SEO دسته وبلاگ');

        $this->get(route('admin.posts.edit', $post))
            ->assertOk()
            ->assertSee('SEO مقاله برای ویرایش');
    }

    public function test_admin_site_seo_settings_are_reflected_in_public_head_metadata(): void
    {
        $this->actingAs($this->admin('admin-seo-settings@example.com'))
            ->put(route('admin.settings.update'), [
                'site_name' => 'فروشگاه تنظیم‌شده',
                'site_description' => 'شرح پیش‌فرض فروشگاه',
                'default_meta_title' => 'عنوان متای ذخیره‌شده',
                'default_meta_description' => 'توضیحات متای ذخیره‌شده برای صفحه اصلی',
                'email' => 'hello@example.com',
            ])
            ->assertRedirect();

        // Shop intentionally defines a page title, but relies on the site-wide default description.
        $this->get(route('shop.index'))
            ->assertOk()
            ->assertSee('فروشگاه | Farzin')
            ->assertSee('توضیحات متای ذخیره‌شده برای صفحه اصلی')
            ->assertSee('فروشگاه تنظیم‌شده');
    }

    public function test_authentication_pages_are_noindexed_by_default(): void
    {
        $this->withoutVite();

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('noindex, follow');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('noindex, follow');
    }


    public function test_invalid_category_image_upload_is_rejected_without_creating_record(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin('admin-invalid-upload@example.com'))
            ->post(route('admin.categories.store'), [
                'name' => 'دسته تصویر نامعتبر',
                'slug' => 'invalid-category-image',
                'description' => 'تست اعتبارسنجی فایل',
                'is_active' => 1,
                'image' => UploadedFile::fake()->create('not-an-image.svg', 20, 'image/svg+xml'),
            ])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseMissing('categories', [
            'slug' => 'invalid-category-image',
        ]);
    }


    public function test_order_status_and_status_history_are_saved_together(): void
    {
        $admin = $this->admin('admin-order-status@example.com');

        $order = \App\Models\Order::query()->create([
            'user_id' => $admin->id,
            'order_number' => 'ORD-DEPLOY-TEST-001',
            'status' => 'pending',
            'payment_status' => 'paid',
            'subtotal' => 100000,
            'discount' => 0,
            'shipping_cost' => 0,
            'total' => 100000,
            'shipping_full_name' => 'Test Customer',
            'shipping_phone' => '09120000000',
            'shipping_country' => 'Iran',
            'shipping_province' => 'Tehran',
            'shipping_city' => 'Tehran',
            'shipping_postal_code' => '1234567890',
            'shipping_address' => 'Test address',
            'notes' => null,
        ]);

        $this->actingAs($admin)
            ->patch(route('admin.orders.status', $order), [
                'status' => 'processing',
                'note' => 'تست ثبت تاریخچه',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'processing',
        ]);

        $this->assertDatabaseHas('order_status_histories', [
            'order_id' => $order->id,
            'from_status' => 'pending',
            'to_status' => 'processing',
            'changed_by' => $admin->id,
            'note' => 'تست ثبت تاریخچه',
        ]);
    }


    public function test_unchecked_admin_form_booleans_are_saved_as_false(): void
    {
        $admin = $this->admin('admin-unchecked-booleans@example.com');

        $category = Category::query()->create([
            'name' => 'دسته وضعیت',
            'slug' => 'category-checkbox-state',
            'sort_order' => 1,
            'is_active' => true,
            'noindex' => true,
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'محصول وضعیت',
            'slug' => 'product-checkbox-state',
            'sku' => 'PRODUCT-CHECKBOX-001',
            'price' => 10000,
            'stock' => 2,
            'is_active' => true,
            'is_featured' => true,
            'noindex' => true,
        ]);

        $blogCategory = BlogCategory::query()->create([
            'name' => 'دسته وبلاگ وضعیت',
            'slug' => 'blog-checkbox-state',
            'sort_order' => 1,
            'is_active' => true,
            'noindex' => true,
        ]);

        $post = Post::query()->create([
            'blog_category_id' => $blogCategory->id,
            'author_id' => $admin->id,
            'title' => 'مقاله وضعیت',
            'slug' => 'post-checkbox-state',
            'content' => 'محتوای تست',
            'status' => 'draft',
            'noindex' => true,
        ]);

        $this->actingAs($admin);

        $this->put(route('admin.categories.update', $category), [
            'name' => $category->name,
            'slug' => $category->slug,
            'sort_order' => $category->sort_order,
        ])->assertRedirect(route('admin.categories.index'));

        $this->put(route('admin.products.update', $product), [
            'category_id' => $category->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'price' => $product->price,
            'stock' => $product->stock,
        ])->assertRedirect(route('admin.products.index'));

        $this->put(route('admin.blog-categories.update', $blogCategory), [
            'name' => $blogCategory->name,
            'slug' => $blogCategory->slug,
            'sort_order' => $blogCategory->sort_order,
        ])->assertRedirect(route('admin.blog-categories.index'));

        $this->put(route('admin.posts.update', $post), [
            'blog_category_id' => $blogCategory->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'content' => $post->content,
            'status' => 'draft',
        ])->assertRedirect(route('admin.posts.index'));

        $this->assertFalse($category->refresh()->is_active);
        $this->assertFalse($category->noindex);
        $this->assertFalse($product->refresh()->is_active);
        $this->assertFalse($product->is_featured);
        $this->assertFalse($product->noindex);
        $this->assertFalse($blogCategory->refresh()->is_active);
        $this->assertFalse($blogCategory->noindex);
        $this->assertFalse($post->refresh()->noindex);
    }


    public function test_saved_seo_settings_are_escaped_before_rendering_html_attributes(): void
    {
        $payload = '<script>alert("seo")</script>';

        $this->actingAs($this->admin('admin-seo-escaping@example.com'))
            ->put(route('admin.settings.update'), [
                'site_name' => $payload,
                'site_description' => 'Description',
                'default_meta_title' => $payload,
                'default_meta_description' => $payload,
            ])
            ->assertRedirect();

        $this->get(route('home'))
            ->assertOk()
            ->assertDontSee($payload, false)
            ->assertSee('&lt;script&gt;alert(&quot;seo&quot;)&lt;/script&gt;', false);
    }


    public function test_dashboard_chart_ranges_return_real_sales_series_and_reject_invalid_ranges(): void
    {
        $admin = $this->admin('admin-dashboard-chart@example.com');

        \App\Models\Order::query()->create([
            'user_id' => $admin->id,
            'order_number' => 'ORD-DASHBOARD-CHART-001',
            'status' => 'delivered',
            'payment_status' => 'paid',
            'subtotal' => 123456,
            'discount' => 0,
            'shipping_cost' => 0,
            'total' => 123456,
            'shipping_full_name' => 'Test Customer',
            'shipping_phone' => '09120000000',
            'shipping_country' => 'Iran',
            'shipping_province' => 'Tehran',
            'shipping_city' => 'Tehran',
            'shipping_postal_code' => '1234567890',
            'shipping_address' => 'Test address',
            'notes' => null,
        ]);

        $this->actingAs($admin);

        $daily = $this->getJson(route('admin.dashboard.chart', ['range' => 'daily']))
            ->assertOk()
            ->assertJsonStructure(['labels', 'data'])
            ->json();

        $this->assertCount(30, $daily['labels']);
        $this->assertCount(30, $daily['data']);
        $this->assertSame(123456, $daily['data'][29]);

        $monthly = $this->getJson(route('admin.dashboard.chart', ['range' => 'monthly']))
            ->assertOk()
            ->assertJsonStructure(['labels', 'data'])
            ->json();

        $this->assertCount(12, $monthly['labels']);
        $this->assertCount(12, $monthly['data']);
        $this->assertSame(123456, $monthly['data'][(int) now()->format('n') - 1]);

        $yearly = $this->getJson(route('admin.dashboard.chart', ['range' => 'yearly']))
            ->assertOk()
            ->assertJsonStructure(['labels', 'data'])
            ->json();

        $this->assertCount(5, $yearly['labels']);
        $this->assertCount(5, $yearly['data']);
        $this->assertSame(123456, $yearly['data'][4]);

        $this->getJson(route('admin.dashboard.chart', ['range' => 'weekly']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('range');
    }

}
