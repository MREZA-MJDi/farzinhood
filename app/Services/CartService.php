<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartService
{
    public function getCart(?User $user = null): Cart
    {
        if ($user) {
            return Cart::query()
                ->firstOrCreate([
                    'user_id' => $user->id,
                ], [
                    'session_id' => null,
                ]);
        }

        $sessionId = session()->getId();

        return Cart::query()
            ->firstOrCreate([
                'session_id' => $sessionId,
                'user_id' => null,
            ]);
    }

    public function add(
        Product $product,
        int $quantity,
        ?User $user = null
    ): Cart {
        $cart = $this->getCart($user);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        $newQuantity = ($item?->quantity ?? 0) + $quantity;

        if ($newQuantity > $product->stock) {
            throw new \RuntimeException(
                'تعداد درخواستی بیشتر از موجودی محصول است.'
            );
        }

        if ($item) {
            $item->update([
                'quantity' => $newQuantity,
                'unit_price' => $product->price,
            ]);
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'unit_price' => $product->price,
            ]);
        }

        return $cart->load([
            'items.product.primaryImage',
        ]);
    }

    public function update(
        Product $product,
        int $quantity,
        ?User $user = null
    ): Cart {
        $cart = $this->getCart($user);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->firstOrFail();

        if ($quantity > $product->stock) {
            throw new \RuntimeException(
                'تعداد درخواستی بیشتر از موجودی محصول است.'
            );
        }

        $item->update([
            'quantity' => $quantity,
            'unit_price' => $product->price,
        ]);

        return $cart->load([
            'items.product.primaryImage',
        ]);
    }

    public function remove(
        Product $product,
        ?User $user = null
    ): Cart {
        $cart = $this->getCart($user);

        $cart->items()
            ->where('product_id', $product->id)
            ->delete();

        return $cart->load([
            'items.product.primaryImage',
        ]);
    }

    public function clear(?User $user = null): void
    {
        $cart = $this->getCart($user);

        $cart->items()->delete();
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
        $guestCart = Cart::query()
            ->whereNull('user_id')
            ->where('session_id', session()->getId())
            ->with('items')
            ->first();

        $userCart = $this->getCart($user);

        if (! $guestCart || $guestCart->id === $userCart->id) {
            return $userCart->load([
                'items.product.primaryImage',
            ]);
        }

        DB::transaction(function () use (
            $guestCart,
            $userCart
        ) {
            foreach ($guestCart->items as $guestItem) {

                $existingItem = $userCart->items()
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingItem) {
                    $product = Product::query()
                        ->find($guestItem->product_id);

                    if (! $product) {
                        continue;
                    }

                    $mergedQuantity = $existingItem->quantity
                        + $guestItem->quantity;

                    $existingItem->update([
                        'quantity' => min(
                            $mergedQuantity,
                            $product->stock
                        ),
                        'unit_price' => $product->price,
                    ]);

                    continue;
                }

                $userCart->items()->create([
                    'product_id' => $guestItem->product_id,
                    'quantity' => $guestItem->quantity,
                    'unit_price' => $guestItem->unit_price,
                ]);
            }

            $guestCart->items()->delete();
            $guestCart->delete();
        });

        return $userCart->fresh([
            'items.product.primaryImage',
        ]);
    }

    public function refreshPrices(?User $user = null): Cart
    {
        $cart = $this->getCart($user);

        $cart->load('items.product');

        foreach ($cart->items as $item) {
            if (! $item->product) {
                $item->delete();
                continue;
            }

            $item->update([
                'unit_price' => $item->product->price,
            ]);
        }

        return $cart->fresh([
            'items.product.primaryImage',
        ]);
    }
}
