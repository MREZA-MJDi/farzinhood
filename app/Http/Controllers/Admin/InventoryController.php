<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\InventoryAdjustmentRequest;
use App\Http\Requests\Admin\InventoryIndexRequest;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(InventoryIndexRequest $request): View
    {
        $query = Product::query()
            ->with('primaryImage')
            ->select([
                'id',
                'name',
                'slug',
                'sku',
                'category_id',
                'stock',
                'is_active',
            ]);

        $query->when(
            $request->filled('search'),
            function ($query) use ($request) {
                $search = $request->validated('search');

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }
        );

        $query->when(
            $request->filled('stock_status'),
            function ($query) use ($request) {
                $status = $request->validated('stock_status');

                match ($status) {
                    'low' => $query->whereBetween('stock', [1, 5]),

                    'out' => $query->where('stock', 0),

                    'available' => $query->where('stock', '>', 5),

                    default => null,
                };
            }
        );

        $products = $query
            ->orderBy('stock')
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view(
            'admin.inventory.index',
            compact('products')
        );
    }

    public function movements(
        Product $product
    ): View {
        $movements = $product
            ->inventoryMovements()
            ->with('user:id,name')
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view(
            'admin.inventory.movements',
            compact('product', 'movements')
        );
    }

    public function adjust(
        InventoryAdjustmentRequest $request,
        Product $product
    ): RedirectResponse {
        $validated = $request->validated();

        DB::transaction(function () use (
            $validated,
            $product,
            $request
        ) {
            $product = Product::query()
                ->whereKey($product->id)
                ->lockForUpdate()
                ->firstOrFail();

            $stockBefore = $product->stock;

            /*
             * type:
             * restock  => increase
             * return   => increase
             * adjustment => signed quantity
             */

            $quantity = match ($validated['type']) {
                'restock' => abs($validated['quantity']),
                'return' => abs($validated['quantity']),
                'adjustment' => $validated['quantity'],
            };

            $stockAfter = $stockBefore + $quantity;

            if ($stockAfter < 0) {
                abort(
                    422,
                    'موجودی نمی‌تواند منفی باشد.'
                );
            }

            $product->update([
                'stock' => $stockAfter,
            ]);

            InventoryMovement::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,

                'type' => $validated['type'],

                'quantity' => $quantity,

                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,

                'reference_type' => null,
                'reference_id' => null,

                'note' => $validated['note'] ?? null,
            ]);
        });

        return back()->with(
            'success',
            'موجودی با موفقیت بروزرسانی شد.'
        );
    }
}
