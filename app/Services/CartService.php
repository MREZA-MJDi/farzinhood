<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CartService
{
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            $existing = Cart::query()
                ->where('user_id', $user->getKey())
                ->first();

            if ($existing) {
                return $existing;
            }

            // Serialize first-cart creation on the owning user row. The user_id
            // index is not unique in older installations, so firstOrCreate alone
            // is not sufficient under concurrent requests.
            return DB::transaction(function () use ($user): Cart {
                $lockedUser = User::query()
                    ->whereKey($user->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                return Cart::query()->firstOrCreate(
                    ['user_id' => $lockedUser->getKey()],
                    ['session_id' => null],
                );
            }, 3);
        }

        $sessionId = session()->getId();

        $existing = Cart::query()
            ->whereNull('user_id')
            ->where('session_id', $sessionId)
            ->first();

        if ($existing) {
            return $existing;
        }

        try {
            return Cart::query()->create([
                'session_id' => $sessionId,
                'user_id' => null,
            ]);
        } catch (QueryException $exception) {
            // Guest carts have a unique session_id. If another request created
            // it first, reuse that row instead of surfacing a duplicate-key 500.
            $cart = Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $sessionId)
                ->first();

            if ($cart) {
                return $cart;
            }

            throw $exception;
        }
    }

    public function add(
        Product $product,
        int $quantity,
        ?User $user = null
    ): Cart {
        return DB::transaction(function () use ($product, $quantity, $user): Cart {
            $cart = $this->lockCart($user);
            $currentProduct = $this->lockActiveProduct($product);

            if ($quantity < 1 || $quantity > 99) {
                throw new RuntimeException('تعداد درخواستی نامعتبر است.');
            }

            $item = $cart->items()
                ->where('product_id', $currentProduct->id)
                ->lockForUpdate()
                ->first();

            $newQuantity = ($item?->quantity ?? 0) + $quantity;
            $maxQuantity = min(99, (int) $currentProduct->stock);

            if ($newQuantity > $maxQuantity) {
                throw new RuntimeException(
                    'تعداد درخواستی بیشتر از موجودی محصول است.'
                );
            }

            if ($item) {
                $item->update([
                    'quantity' => $newQuantity,
                    'unit_price' => $currentProduct->price,
                ]);
            } else {
                $cart->items()->create([
                    'product_id' => $currentProduct->id,
                    'quantity' => $newQuantity,
                    'unit_price' => $currentProduct->price,
                ]);
            }

            return $cart->load([
                'items.product.primaryImage',
            ]);
        }, 3);
    }

    public function update(
        Product $product,
        int $quantity,
        ?User $user = null
    ): Cart {
        return DB::transaction(function () use ($product, $quantity, $user): Cart {
            $cart = $this->lockCart($user);
            $currentProduct = $this->lockActiveProduct($product);

            if ($quantity < 1 || $quantity > 99) {
                throw new RuntimeException('تعداد درخواستی نامعتبر است.');
            }

            $item = $cart->items()
                ->where('product_id', $currentProduct->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($quantity > $currentProduct->stock) {
                throw new RuntimeException(
                    'تعداد درخواستی بیشتر از موجودی محصول است.'
                );
            }

            $item->update([
                'quantity' => $quantity,
                'unit_price' => $currentProduct->price,
            ]);

            return $cart->load([
                'items.product.primaryImage',
            ]);
        }, 3);
    }

    public function remove(
        Product $product,
        ?User $user = null
    ): Cart {
        return DB::transaction(function () use ($product, $user): Cart {
            $cart = $this->lockCart($user);

            $cart->items()
                ->where('product_id', $product->id)
                ->delete();

            return $cart->load([
                'items.product.primaryImage',
            ]);
        }, 3);
    }

    public function clear(?User $user = null): void
    {
        DB::transaction(function () use ($user): void {
            $cart = $this->lockCart($user);
            $cart->items()->delete();
        }, 3);
    }

    public function subtotal(?User $user = null): int
    {
        $cart = $this->getCart($user);

        return (int) $cart->items()
            ->selectRaw('COALESCE(SUM(quantity * unit_price), 0) as subtotal')
            ->value('subtotal');
    }

    public function itemCount(?User $user = null): int
    {
        $cart = $this->getCart($user);

        return (int) $cart->items()->sum('quantity');
    }

    public function contents(?User $user = null): Cart
    {
        return $this->getCart($user)
            ->load([
                'items.product.category',
                'items.product.primaryImage',
            ]);
    }

    public function mergeGuestCart(User $user): Cart
    {
        $sessionId = session()->getId();

        return DB::transaction(function () use ($user, $sessionId): Cart {
            $lockedUser = User::query()
                ->whereKey($user->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $userCart = Cart::query()
                ->where('user_id', $lockedUser->getKey())
                ->lockForUpdate()
                ->first();

            if (! $userCart) {
                $userCart = Cart::query()->create([
                    'user_id' => $lockedUser->getKey(),
                    'session_id' => null,
                ]);
            }

            $guestCart = Cart::query()
                ->whereNull('user_id')
                ->where('session_id', $sessionId)
                ->lockForUpdate()
                ->first();

            if (! $guestCart) {
                return $userCart->load(['items.product.primaryImage']);
            }

            $guestItems = $guestCart->items()
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get();

            if ($guestItems->isEmpty()) {
                $guestCart->delete();

                return $userCart->load(['items.product.primaryImage']);
            }

            $productIds = $guestItems->pluck('product_id')->unique()->values();

            // Lock all product rows in a deterministic order to reduce deadlocks.
            $products = Product::query()
                ->with('category')
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $existingItems = $userCart->items()
                ->whereIn('product_id', $productIds)
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get()
                ->keyBy('product_id');

            foreach ($guestItems as $guestItem) {
                $product = $products->get($guestItem->product_id);
                $existingItem = $existingItems->get($guestItem->product_id);

                if (! $product || ! $product->is_active || ! $product->category?->is_active || $product->stock < 1) {
                    $existingItem?->delete();
                    $guestItem->delete();
                    continue;
                }

                $maxQuantity = min(99, (int) $product->stock);
                $requestedQuantity = (int) $guestItem->quantity;

                if ($existingItem) {
                    $existingItem->update([
                        'quantity' => min(
                            (int) $existingItem->quantity + $requestedQuantity,
                            $maxQuantity,
                        ),
                        'unit_price' => $product->price,
                    ]);

                    continue;
                }

                $quantity = min($requestedQuantity, $maxQuantity);

                if ($quantity < 1) {
                    $guestItem->delete();
                    continue;
                }

                $newItem = $userCart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $product->price,
                ]);

                $existingItems->put($product->id, $newItem);
            }

            $guestCart->items()->delete();
            $guestCart->delete();

            return $userCart->fresh(['items.product.primaryImage']);
        }, 3);
    }

    public function refreshPrices(?User $user = null): Cart
    {
        return DB::transaction(function () use ($user): Cart {
            $cart = $this->lockCart($user);
            $items = $cart->items()
                ->orderBy('product_id')
                ->lockForUpdate()
                ->get();

            if ($items->isEmpty()) {
                return $cart->load(['items.product.primaryImage']);
            }

            $products = Product::query()
                ->whereIn('id', $items->pluck('product_id')->unique())
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($items as $item) {
                $product = $products->get($item->product_id);

                if (! $product) {
                    $item->delete();
                    continue;
                }

                $item->update(['unit_price' => $product->price]);
            }

            return $cart->fresh(['items.product.primaryImage']);
        }, 3);
    }

    private function lockCart(?User $user): Cart
    {
        $cart = $this->getCart($user);

        return Cart::query()
            ->whereKey($cart->getKey())
            ->lockForUpdate()
            ->firstOrFail();
    }

    private function lockActiveProduct(Product $product): Product
    {
        $currentProduct = Product::query()
            ->with('category')
            ->whereKey($product->getKey())
            ->lockForUpdate()
            ->firstOrFail();

        if (! $currentProduct->is_active || ! $currentProduct->category?->is_active) {
            throw new RuntimeException('این محصول دیگر برای خرید در دسترس نیست.');
        }

        return $currentProduct;
    }
}
