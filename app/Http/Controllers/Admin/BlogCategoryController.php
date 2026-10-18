<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryIndexRequest;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogCategoryController extends Controller
{
    public function index(BlogCategoryIndexRequest  $request): View
    {
        $categories = BlogCategory::query()
            ->withCount('posts')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->input('status') === 'active');
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(15);

        return view(
            'admin.blog-categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view(
            'admin.blog-categories.create'
        );
    }

    public function store(
        BlogCategoryRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $data = $request->validated();
        $seoData = $seoService->data($data);
        unset(
            $data['meta_title'],
            $data['meta_description'],
            $data['canonical_url'],
            $data['noindex'],
            $data['image']
        );

        $image = $request->file('image');
        $imagePath = $image?->store('blog/categories', 'public');

        if ($imagePath) {
            $data['image'] = $imagePath;
        }

        try {
            DB::transaction(function () use ($data, $seoData, $seoService): void {
                $category = BlogCategory::create($data);
                $seoService->apply($category, $seoData);
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Blog category created successfully.');
    }

    public function edit(
        BlogCategory $blogCategory
    ): View {
        return view(
            'admin.blog-categories.edit',
            ['category' => $blogCategory]
        );
    }

    public function update(
        BlogCategoryRequest $request,
        BlogCategory $blogCategory,
        SeoService $seoService
    ): RedirectResponse {
        $data = $request->validated();
        $seoData = $seoService->data($data);
        unset(
            $data['meta_title'],
            $data['meta_description'],
            $data['canonical_url'],
            $data['noindex'],
            $data['image']
        );

        $image = $request->file('image');
        $newImagePath = $image?->store('blog/categories', 'public');
        $oldImagePath = $blogCategory->image;

        if ($newImagePath) {
            $data['image'] = $newImagePath;
        }

        try {
            DB::transaction(function () use ($data, $seoData, $blogCategory, $seoService): void {
                $lockedCategory = BlogCategory::query()
                    ->whereKey($blogCategory->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $lockedCategory->update($data);
                $seoService->apply($lockedCategory, $seoData);
            });
        } catch (\Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Blog category updated successfully.');
    }

    public function destroy(
        BlogCategory $blogCategory
    ): RedirectResponse {
        [$deleted, $imagePath] = DB::transaction(function () use ($blogCategory): array {
            $lockedCategory = BlogCategory::query()
                ->whereKey($blogCategory->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedCategory->posts()->exists()) {
                return [false, null];
            }

            $imagePath = $lockedCategory->image;
            $lockedCategory->delete();

            return [true, $imagePath];
        });

        if (! $deleted) {
            return back()->with(
                'error',
                'This category contains posts and cannot be deleted.'
            );
        }

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.blog-categories.index')
            ->with('success', 'Blog category deleted successfully.');
    }
}
