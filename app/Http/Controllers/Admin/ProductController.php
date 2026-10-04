<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->when(
                $request->filled('search'),
                fn (Builder $query) =>
                $query->where(function (Builder $query) use ($request) {
                    $search = $request->string('search')->toString();

                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                })
            )
            ->when(
                $request->filled('category_id'),
                fn (Builder $query) =>
                $query->where('category_id', $request->integer('category_id'))
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.products.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }
    public function create(): View
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('admin.products.create', compact('categories'));
    }

    public function store(
        ProductRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $imageFiles = $request->file('images', []);

        unset($validated['images']);

        $product = DB::transaction(function () use (
            $validated,
            $imageFiles,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $productData = $validated;

            unset(
                $productData['meta_title'],
                $productData['meta_description'],
                $productData['canonical_url'],
                $productData['noindex']
            );

            $product = Product::create($productData);

            $seoService->apply(
                $product,
                $seoData
            );

            $this->storeImages(
                $product,
                $imageFiles
            );

            return $product;
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product): View
    {
        $product->load([
            'images' => fn ($query) => $query
                ->orderBy('sort_order')
                ->orderBy('id'),
            'category',
        ]);

        $categories = Category::query()
            ->where('is_active', true)
            ->when(
                $product->category_id,
                fn ($query) => $query->orWhereKey(
                    $product->category_id
                )
            )
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    public function update(
        ProductRequest $request,
        Product $product,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $imageFiles = $request->file('images', []);

        unset($validated['images']);

        DB::transaction(function () use (
            $validated,
            $imageFiles,
            $product,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $productData = $validated;

            unset(
                $productData['meta_title'],
                $productData['meta_description'],
                $productData['canonical_url'],
                $productData['noindex']
            );

            $product->update($productData);

            $seoService->apply(
                $product,
                $seoData
            );

            $this->storeImages(
                $product,
                $imageFiles
            );
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->load('images');

        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                $this->deleteImageFile($image);
            }

            $product->delete();
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function destroyImage(ProductImage $productImage): RedirectResponse
    {
        $product = $productImage->product;

        $wasPrimary = $productImage->is_primary;

        $this->deleteImageFile($productImage);

        $productImage->delete();

        if ($wasPrimary) {
            $nextImage = $product
                ->images()
                ->orderBy('sort_order')
                ->orderBy('id')
                ->first();

            if ($nextImage) {
                $nextImage->update([
                    'is_primary' => true,
                ]);
            }
        }

        return back()->with(
            'success',
            'Product image deleted successfully.'
        );
    }

    public function setPrimaryImage(
        ProductImage $productImage
    ): RedirectResponse {
        $product = $productImage->product;

        DB::transaction(function () use (
            $product,
            $productImage
        ) {
            $product->images()->update([
                'is_primary' => false,
            ]);

            $productImage->update([
                'is_primary' => true,
            ]);
        });

        return back()->with(
            'success',
            'Primary image updated successfully.'
        );
    }

    private function storeImages(
        Product $product,
        array $files
    ): void {
        if (empty($files)) {
            return;
        }

        $hasPrimaryImage = $product
            ->images()
            ->where('is_primary', true)
            ->exists();

        $nextSortOrder = (int) (
                $product->images()->max('sort_order') ?? -1
            ) + 1;

        foreach ($files as $index => $file) {
            $path = $file->store(
                'products',
                'public'
            );

            $isPrimary = ! $hasPrimaryImage && $index === 0;

            $image = ProductImage::create([
                'product_id' => $product->id,
                'image' => $path,
                'alt' => $product->name,
                'sort_order' => $nextSortOrder + $index,
                'is_primary' => $isPrimary,
            ]);

            if ($isPrimary) {
                $hasPrimaryImage = true;
            }
        }
    }

    private function deleteImageFile(
        ProductImage $image
    ): void {
        if (
            $image->image &&
            Storage::disk('public')->exists($image->image)
        ) {
            Storage::disk('public')->delete(
                $image->image
            );
        }
    }
}
