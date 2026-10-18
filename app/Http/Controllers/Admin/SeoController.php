<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoController extends Controller
{
    public function index(Request $request): View
    {
        $groups = [
            $this->auditGroup(
                'products',
                'محصولات',
                Product::query()
                    ->where('is_active', true)
                    ->whereHas('category', fn (Builder $query) => $query->where('is_active', true)),
                fn (Product $product): string => route('products.show', $product),
                fn (Product $product): string => route('admin.products.edit', $product),
                fn (Product $product): string => $product->name,
            ),
            $this->auditGroup(
                'categories',
                'دسته‌بندی محصولات',
                Category::query()->where('is_active', true),
                fn (Category $category): string => route('categories.show', $category->slug),
                fn (Category $category): string => route('admin.categories.edit', $category),
                fn (Category $category): string => $category->name,
            ),
            $this->auditGroup(
                'blog_categories',
                'دسته‌بندی مقالات',
                BlogCategory::query()->where('is_active', true),
                fn (BlogCategory $category): string => route('blog.index', ['category' => $category->slug]),
                fn (BlogCategory $category): string => route('admin.blog-categories.edit', $category),
                fn (BlogCategory $category): string => $category->name,
            ),
            $this->auditGroup(
                'posts',
                'مقالات منتشرشده',
                Post::query()
                    ->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now())
                    ->whereHas('category', fn (Builder $query) => $query->where('is_active', true)),
                fn (Post $post): string => route('blog.show', $post->slug),
                fn (Post $post): string => route('admin.posts.edit', $post),
                fn (Post $post): string => $post->title,
            ),
        ];

        $totals = [
            'records' => array_sum(array_column($groups, 'total')),
            'indexable' => array_sum(array_column($groups, 'indexable')),
            'noindex' => array_sum(array_column($groups, 'noindex')),
            'missing_title' => array_sum(array_column($groups, 'missing_title')),
            'missing_description' => array_sum(array_column($groups, 'missing_description')),
            'missing_metadata' => array_sum(array_column($groups, 'missing_metadata')),
        ];

        $missingRecords = collect($groups)
            ->flatMap(fn (array $group): array => $group['missing_records'])
            ->sortBy('name')
            ->take(30)
            ->values();

        return view('admin.seo.index', [
            'groups' => $groups,
            'totals' => $totals,
            'missingRecords' => $missingRecords,
            'sitemapUrl' => route('sitemap'),
            'robotsUrl' => route('robots'),
        ]);
    }

    private function auditGroup(
        string $key,
        string $label,
        Builder $sourceQuery,
        callable $publicUrl,
        callable $editUrl,
        callable $recordName,
    ): array {
        $total = (clone $sourceQuery)->count();

        $noindex = (clone $sourceQuery)
            ->where('noindex', true)
            ->count();

        $indexableQuery = $this->indexableQuery(clone $sourceQuery);

        $missingTitle = (clone $indexableQuery)
            ->where(fn (Builder $query) => $query->whereNull('meta_title')->orWhere('meta_title', ''))
            ->count();

        $missingDescription = (clone $indexableQuery)
            ->where(fn (Builder $query) => $query->whereNull('meta_description')->orWhere('meta_description', ''))
            ->count();

        $missingQuery = (clone $indexableQuery)
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('meta_title')
                    ->orWhere('meta_title', '')
                    ->orWhereNull('meta_description')
                    ->orWhere('meta_description', '');
            });

        $missingRecords = $missingQuery
            ->orderByDesc('updated_at')
            ->limit(8)
            ->get()
            ->map(function ($record) use ($key, $label, $publicUrl, $editUrl, $recordName): array {
                $missingFields = [];

                if (! filled($record->meta_title)) {
                    $missingFields[] = 'عنوان SEO';
                }

                if (! filled($record->meta_description)) {
                    $missingFields[] = 'توضیحات SEO';
                }

                return [
                    'type' => $label,
                    'group' => $key,
                    'name' => $recordName($record),
                    'public_url' => $publicUrl($record),
                    'edit_url' => $editUrl($record),
                    'missing_fields' => $missingFields,
                    'updated_at' => $record->updated_at,
                ];
            })
            ->all();

        return [
            'key' => $key,
            'label' => $label,
            'total' => $total,
            'noindex' => $noindex,
            'indexable' => max(0, $total - $noindex),
            'missing_title' => $missingTitle,
            'missing_description' => $missingDescription,
            'missing_metadata' => count($missingRecords),
            'missing_records' => $missingRecords,
        ];
    }

    private function indexableQuery(Builder $query): Builder
    {
        return $query->where(function (Builder $builder): void {
            $builder
                ->whereNull('noindex')
                ->orWhere('noindex', false);
        });
    }
}
