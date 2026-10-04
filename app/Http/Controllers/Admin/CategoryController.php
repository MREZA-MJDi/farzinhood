<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::query()
            ->withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(20);

        return view(
            'admin.categories.index',
            compact('categories')
        );
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(
        CategoryRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $image = $request->file('image');

        unset($validated['image']);

        $category = DB::transaction(function () use (
            $validated,
            $image,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $categoryData = $validated;

            unset(
                $categoryData['meta_title'],
                $categoryData['meta_description'],
                $categoryData['canonical_url'],
                $categoryData['noindex']
            );

            if ($image) {
                $categoryData['image'] = $image->store(
                    'categories',
                    'public'
                );
            }

            $category = Category::create($categoryData);

            $seoService->apply(
                $category,
                $seoData
            );

            return $category;
        });

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function edit(Category $category): View
    {
        return view(
            'admin.categories.edit',
            compact('category')
        );
    }

    public function update(
        CategoryRequest $request,
        Category $category,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $image = $request->file('image');

        unset($validated['image']);

        DB::transaction(function () use (
            $validated,
            $image,
            $category,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $categoryData = $validated;

            unset(
                $categoryData['meta_title'],
                $categoryData['meta_description'],
                $categoryData['canonical_url'],
                $categoryData['noindex']
            );

            if ($image) {
                if ($category->image) {
                    Storage::disk('public')
                        ->delete($category->image);
                }

                $categoryData['image'] = $image->store(
                    'categories',
                    'public'
                );
            }

            $category->update($categoryData);

            $seoService->apply(
                $category,
                $seoData
            );
        });

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return back()->with(
                'error',
                'This category cannot be deleted because it contains products.'
            );
        }

        if ($category->image) {
            Storage::disk('public')
                ->delete($category->image);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
