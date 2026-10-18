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

        $imagePath = $image?->store('categories', 'public');

        if ($imagePath) {
            $validated['image'] = $imagePath;
        }

        try {
            DB::transaction(function () use ($validated, $seoService): void {
                $seoData = $seoService->data($validated);
                $categoryData = $validated;

                unset(
                    $categoryData['meta_title'],
                    $categoryData['meta_description'],
                    $categoryData['canonical_url'],
                    $categoryData['noindex']
                );

                $category = Category::create($categoryData);
                $seoService->apply($category, $seoData);
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

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

        $newImagePath = $image?->store('categories', 'public');
        $oldImagePath = $category->image;

        if ($newImagePath) {
            $validated['image'] = $newImagePath;
        }

        try {
            DB::transaction(function () use ($validated, $category, $seoService): void {
                $lockedCategory = Category::query()
                    ->whereKey($category->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $seoData = $seoService->data($validated);
                $categoryData = $validated;

                unset(
                    $categoryData['meta_title'],
                    $categoryData['meta_description'],
                    $categoryData['canonical_url'],
                    $categoryData['noindex']
                );

                $lockedCategory->update($categoryData);
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
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        [$deleted, $imagePath] = DB::transaction(function () use ($category): array {
            $lockedCategory = Category::query()
                ->whereKey($category->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedCategory->products()->exists()) {
                return [false, null];
            }

            $imagePath = $lockedCategory->image;
            $lockedCategory->delete();

            return [true, $imagePath];
        });

        if (! $deleted) {
            return back()->with(
                'error',
                'This category cannot be deleted because it contains products.'
            );
        }

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
