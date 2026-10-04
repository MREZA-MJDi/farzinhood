<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategoryIndexRequest;
use App\Http\Requests\Admin\BlogCategoryRequest;
use App\Models\BlogCategory;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
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
            'admin.blog.categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view(
            'admin.blog.categories.create'
        );
    }

    public function store(
        BlogCategoryRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $data = $request->validated();

        $image = $request->file('image');

        unset($data['image']);

        if ($image) {
            $data['image'] = $image->store(
                'blog/categories',
                'public'
            );
        }

        $category = BlogCategory::create($data);

        $seoService->apply(
            $category,
            $data
        );

        return redirect()
            ->route('admin.blog-categories.index')
            ->with(
                'success',
                'Blog category created successfully.'
            );
    }

    public function edit(
        BlogCategory $blogCategory
    ): View {
        return view(
            'admin.blog.categories.edit',
            compact('blogCategory')
        );
    }

    public function update(
        BlogCategoryRequest $request,
        BlogCategory $blogCategory,
        SeoService $seoService
    ): RedirectResponse {
        $data = $request->validated();

        $image = $request->file('image');

        unset($data['image']);

        if ($image) {
            if ($blogCategory->image) {
                Storage::disk('public')
                    ->delete($blogCategory->image);
            }

            $data['image'] = $image->store(
                'blog/categories',
                'public'
            );
        }

        $blogCategory->update($data);

        $seoService->apply(
            $blogCategory,
            $data
        );

        return redirect()
            ->route('admin.blog-categories.index')
            ->with(
                'success',
                'Blog category updated successfully.'
            );
    }

    public function destroy(
        BlogCategory $blogCategory
    ): RedirectResponse {
        if ($blogCategory->posts()->exists()) {
            return back()->with(
                'error',
                'This category contains posts and cannot be deleted.'
            );
        }

        if ($blogCategory->image) {
            Storage::disk('public')
                ->delete($blogCategory->image);
        }

        $blogCategory->delete();

        return redirect()
            ->route('admin.blog-categories.index')
            ->with(
                'success',
                'Blog category deleted successfully.'
            );
    }
}
