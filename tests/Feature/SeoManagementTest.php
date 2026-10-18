<?php

namespace Tests\Feature;

use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SeoManagementTest extends TestCase
{
    use RefreshDatabase;

    private function admin(string $email = 'seo-admin@example.com'): User
    {
        return User::query()->create([
            'name' => 'SEO Admin',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    private function category(array $overrides = []): Category
    {
        static $sequence = 0;
        $sequence++;

        return Category::query()->create(array_merge([
            'name' => 'دسته ' . $sequence,
            'slug' => 'seo-category-' . $sequence,
            'description' => 'شرح دسته برای آزمایش SEO',
            'sort_order' => $sequence,
            'is_active' => true,
            'meta_title' => null,
            'meta_description' => null,
            'canonical_url' => null,
            'noindex' => false,
        ], $overrides));
    }

    private function product(Category $category, array $overrides = []): Product
    {
        static $sequence = 0;
        $sequence++;

        return Product::query()->create(array_merge([
            'category_id' => $category->id,
            'name' => 'محصول ' . $sequence,
            'slug' => 'seo-product-' . $sequence,
            'sku' => 'SEO-PRODUCT-' . $sequence,
            'short_description' => 'توضیح کوتاه محصول',
            'description' => 'توضیح کامل محصول',
            'price' => 250000,
            'stock' => 10,
            'is_active' => true,
            'is_featured' => false,
            'meta_title' => null,
            'meta_description' => null,
            'canonical_url' => null,
            'noindex' => false,
        ], $overrides));
    }

    private function blogCategory(array $overrides = []): BlogCategory
    {
        static $sequence = 0;
        $sequence++;

        return BlogCategory::query()->create(array_merge([
            'name' => 'دسته مقاله ' . $sequence,
            'slug' => 'seo-blog-category-' . $sequence,
            'description' => 'شرح دسته مقاله',
            'sort_order' => $sequence,
            'is_active' => true,
            'meta_title' => null,
            'meta_description' => null,
            'canonical_url' => null,
            'noindex' => false,
        ], $overrides));
    }

    private function post(BlogCategory $category, User $author, array $overrides = []): Post
    {
        static $sequence = 0;
        $sequence++;

        return Post::query()->create(array_merge([
            'blog_category_id' => $category->id,
            'author_id' => $author->id,
            'title' => 'مقاله ' . $sequence,
            'slug' => 'seo-post-' . $sequence,
            'excerpt' => 'چکیده مقاله',
            'content' => 'متن کامل مقاله',
            'status' => 'published',
            'published_at' => now()->subMinute(),
            'meta_title' => null,
            'meta_description' => null,
            'canonical_url' => null,
            'noindex' => false,
        ], $overrides));
    }

    public function test_seo_dashboard_metrics_are_calculated_from_current_database_records(): void
    {
        $admin = $this->admin();

        $this->category([
            'name' => 'دسته دارای متادیتای ناقص',
            'slug' => 'seo-missing-category',
        ]);

        $category = $this->category([
            'name' => 'دسته محصول',
            'slug' => 'seo-product-category',
            'meta_title' => 'عنوان دسته',
            'meta_description' => 'توضیحات دسته',
        ]);

        $this->product($category, [
            'name' => 'محصول فاقد توضیحات SEO',
            'slug' => 'seo-missing-product',
            'meta_title' => 'عنوان محصول',
            'meta_description' => null,
        ]);

        $this->category([
            'name' => 'دسته خارج از ایندکس',
            'slug' => 'seo-noindex-category',
            'noindex' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.seo.index'));

        $response->assertOk()
            ->assertSee('مدیریت SEO')
            ->assertSee('محصول فاقد توضیحات SEO')
            ->assertSee('تکمیل SEO')
            ->assertSee(route('admin.products.edit', Product::query()->where('slug', 'seo-missing-product')->firstOrFail()), false)
            ->assertSee('data-seo-metric="records"', false)
            ->assertSee('data-seo-metric="indexable"', false)
            ->assertSee('data-seo-metric="noindex"', false)
            ->assertSee('data-seo-metric="missing_metadata"', false);

        $html = $response->getContent();

        foreach ([
            'records' => '4',
            'indexable' => '3',
            'noindex' => '1',
            'missing_metadata' => '2',
        ] as $metric => $expected) {
            $pattern = '/data-seo-metric="' . preg_quote($metric, '/') . '"[^>]*>\s*([^<]+)\s*<\/p>/u';
            $this->assertSame(1, preg_match($pattern, $html, $matches), 'SEO metric "' . $metric . '" should exist.');
            $this->assertSame($expected, trim($matches[1]), 'SEO metric "' . $metric . '" should come from live records.');
        }
    }

    public function test_sitemap_contains_only_active_indexable_catalog_and_published_blog_content(): void
    {
        $admin = $this->admin('seo-sitemap-admin@example.com');

        $visibleCategory = $this->category([
            'name' => 'دسته قابل ایندکس',
            'slug' => 'sitemap-visible-category',
            'meta_title' => 'عنوان',
            'meta_description' => 'توضیحات',
        ]);

        $visibleProduct = $this->product($visibleCategory, [
            'name' => 'محصول قابل ایندکس',
            'slug' => 'sitemap-visible-product',
            'meta_title' => 'عنوان محصول',
            'meta_description' => 'توضیح محصول',
        ]);

        $noindexProduct = $this->product($visibleCategory, [
            'name' => 'محصول noindex',
            'slug' => 'sitemap-noindex-product',
            'noindex' => true,
        ]);

        $inactiveProduct = $this->product($visibleCategory, [
            'name' => 'محصول غیرفعال',
            'slug' => 'sitemap-inactive-product',
            'is_active' => false,
        ]);

        $noindexCategory = $this->category([
            'name' => 'دسته noindex',
            'slug' => 'sitemap-noindex-category',
            'noindex' => true,
        ]);

        $blogCategory = $this->blogCategory([
            'name' => 'دسته مقالات قابل ایندکس',
            'slug' => 'sitemap-visible-blog-category',
        ]);

        $publishedPost = $this->post($blogCategory, $admin, [
            'title' => 'مقاله منتشرشده',
            'slug' => 'sitemap-published-post',
        ]);

        $draftPost = $this->post($blogCategory, $admin, [
            'title' => 'مقاله پیش‌نویس',
            'slug' => 'sitemap-draft-post',
            'status' => 'draft',
            'published_at' => null,
        ]);

        $response = $this->get(route('sitemap'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', false)
            ->assertSee(route('home'), false)
            ->assertSee(route('products.show', $visibleProduct), false)
            ->assertSee(route('categories.show', $visibleCategory->slug), false)
            ->assertSee(route('blog.show', $publishedPost->slug), false)
            ->assertSee(route('blog.index', ['category' => $blogCategory->slug]), false)
            ->assertDontSee('sitemap-noindex-product', false)
            ->assertDontSee('sitemap-inactive-product', false)
            ->assertDontSee('sitemap-noindex-category', false)
            ->assertDontSee('sitemap-draft-post', false);
    }

    public function test_sitemap_excludes_content_that_declares_an_external_canonical_url(): void
    {
        $category = $this->category([
            'name' => 'دسته خارجی',
            'slug' => 'sitemap-external-category',
            'canonical_url' => 'https://other.example/category',
        ]);

        $this->product($category, [
            'name' => 'محصول با canonical خارجی',
            'slug' => 'sitemap-external-canonical-product',
            'canonical_url' => 'https://other.example/product',
        ]);

        $this->get(route('sitemap'))
            ->assertOk()
            ->assertDontSee('sitemap-external-category', false)
            ->assertDontSee('sitemap-external-canonical-product', false)
            ->assertDontSee('https://other.example/product', false);
    }

    public function test_robots_file_points_to_the_live_sitemap_and_does_not_block_meta_noindex_pages(): void
    {
        $response = $this->get(route('robots'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
            ->assertSee('User-agent: *')
            ->assertSee('Allow: /')
            ->assertSee('Disallow: /admin/')
            ->assertSee('Sitemap: ' . route('sitemap'))
            ->assertDontSee('Disallow: /login')
            ->assertDontSee('Disallow: /cart');
    }

    public function test_all_content_seo_forms_include_a_live_preview_component(): void
    {
        $this->actingAs($this->admin('seo-form-preview@example.com'));

        $this->get(route('admin.products.create'))
            ->assertOk()
            ->assertSee('data-seo-preview', false)
            ->assertSee('data-seo-preview-title', false)
            ->assertSee('data-seo-preview-description', false)
            ->assertSee('data-seo-preview-robots', false);

        $this->get(route('admin.categories.create'))
            ->assertOk()
            ->assertSee('data-seo-preview', false);

        $this->get(route('admin.posts.create'))
            ->assertOk()
            ->assertSee('data-seo-preview', false);

        $this->get(route('admin.blog-categories.create'))
            ->assertOk()
            ->assertSee('data-seo-preview', false);
    }
}
