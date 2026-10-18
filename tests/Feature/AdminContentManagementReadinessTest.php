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

}
