<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Cart;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class CheckoutService
{
    /**
     * Return the current checkout summary for the authenticated user.
     *
     * This method is read-only and does not modify stock or create an order.
     */
    public function summary(User $user): array
    {
        $cart = $this->getUserCart($user);

        if (! $cart || $cart->items->isEmpty()) {
            return [
                'subtotal' => 0,
                'discount' => 0,
                'shipping' => 0,
                'total' => 0,
                'item_count' => 0,
                'items' => collect(),
            ];
        }

        $productIds = $cart->items
            ->pluck('product_id')
            ->filter()
            ->unique()
            ->values();

        $products = Product::query()
            ->whereIn('id', $productIds)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        $items = collect();
        $subtotal = 0;
        $itemCount = 0;

        foreach ($cart->items as $cartItem) {
            $product = $products->get($cartItem->product_id);

            // Do not expose unavailable items as valid checkout totals.
            if (! $product) {
                continue;
            }

            $quantity = (int) $cartItem->quantity;
            $unitPrice = (int) $product->price;
            $lineTotal = $unitPrice * $quantity;

            $subtotal += $lineTotal;
            $itemCount += $quantity;

            $items->push([
                'cart_item' => $cartItem,
                'product' => $product,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total' => $lineTotal,
            ]);
        }

        $discount = $this->calculateDiscount(
            $subtotal,
            $items->all()
        );

        $shipping = $this->calculateShipping($subtotal);

        $total = max(
            0,
            $subtotal - $discount + $shipping
        );

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
            'item_count' => $itemCount,
            'items' => $items,
        ];
    }

    /**
     * Create an order from the user's current cart.
     *
     * Stock is reserved immediately by decrementing the available stock.
     * If payment later fails or expires, the reserved stock must be released
     * by releaseReservedStock().
     */
    public function createOrder(
        User $user,
        Address $address,
        ?string $notes = null
    ): Order {
        return DB::transaction(function () use (
            $user,
            $address,
            $notes
        ) {

            /*
            |--------------------------------------------------------------------------
            | Address Ownership
            |--------------------------------------------------------------------------
            */

            if ((int) $address->user_id !== (int) $user->id) {
                throw new RuntimeException(
                    'آدرس انتخاب‌شده متعلق به شما نیست.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Cart
            |--------------------------------------------------------------------------
            */

            $cart = $this->getUserCart($user);

            if (! $cart || $cart->items->isEmpty()) {
                throw new RuntimeException(
                    'سبد خرید شما خالی است.'
                );
            }


            /*
            |--------------------------------------------------------------------------
            | Products - Lock For Update
            |--------------------------------------------------------------------------
            */

            $productIds = $cart->items
                ->pluck('product_id')
                ->filter()
                ->unique()
                ->values();

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');


            /*
            |--------------------------------------------------------------------------
            | Validate Cart + Calculate Totals
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            $orderItems = [];

            foreach ($cart->items as $cartItem) {

                $product = $products->get(
                    $cartItem->product_id
                );

                if (! $product) {
                    throw new RuntimeException(
                        'یکی از محصولات سبد دیگر وجود ندارد.'
                    );
                }

                if (! $product->is_active) {
                    throw new RuntimeException(
                        "محصول «{$product->name}» دیگر فعال نیست."
                    );
                }

                $quantity = (int) $cartItem->quantity;

                if ($quantity < 1) {
                    throw new RuntimeException(
                        "تعداد محصول «{$product->name}» نامعتبر است."
                    );
                }

                if ($quantity > $product->stock) {
                    throw new RuntimeException(
                        "موجودی محصول «{$product->name}» کافی نیست."
                    );
                }

                /*
                 * Never trust the price stored in cart_items.
                 * The current Product price is the source of truth.
                 */
                $unitPrice = (int) $product->price;

                $lineTotal = $unitPrice * $quantity;

                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $lineTotal,
                ];
            }


            /*
            |--------------------------------------------------------------------------
            | Totals
            |--------------------------------------------------------------------------
            */

            $discount = $this->calculateDiscount(
                $subtotal,
                $orderItems
            );

            $shippingCost = $this->calculateShipping(
                $subtotal
            );

            $total = max(
                0,
                $subtotal
                - $discount
                + $shippingCost
            );


            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'user_id' => $user->id,

                'order_number' => $this->generateOrderNumber(),

                'status' => 'pending',
                'payment_status' => 'pending',

                'subtotal' => $subtotal,
                'discount' => $discount,
                'shipping_cost' => $shippingCost,
                'total' => $total,

                /*
                 * Shipping Snapshot
                 */
                'shipping_full_name' => $address->full_name,
                'shipping_phone' => $address->phone,
                'shipping_country' => $address->country,
                'shipping_province' => $address->province,
                'shipping_city' => $address->city,
                'shipping_postal_code' => $address->postal_code,
                'shipping_address' => $address->address,

                'notes' => $notes,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Order Items + Stock Reservation
            |--------------------------------------------------------------------------
            */

            foreach ($orderItems as $item) {

                /** @var Product $product */
                $product = $item['product'];

                $quantity = (int) $item['quantity'];

                $order->items()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'unit_price' => $item['unit_price'],
                    'quantity' => $quantity,
                    'total' => $item['total'],
                ]);


                /*
                 * Product is already locked by lockForUpdate().
                 */
                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore - $quantity;

                if ($stockAfter < 0) {
                    throw new RuntimeException(
                        "موجودی محصول «{$product->name}» کافی نیست."
                    );
                }

                $product->update([
                    'stock' => $stockAfter,
                ]);


                /*
                 * The stock reduction acts as a reservation.
                 */
                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $user->id,

                    'type' => 'sale',
                    'quantity' => -$quantity,

                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,

                    'reference_type' => Order::class,
                    'reference_id' => $order->id,

                    'note' => "Order #{$order->order_number}",
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Initial Order Status History
            |--------------------------------------------------------------------------
            */

            $order->statusHistories()->create([
                'from_status' => null,
                'to_status' => 'pending',
                'changed_by' => $user->id,
                'note' => 'سفارش ایجاد شد و موجودی محصولات رزرو شد.',
            ]);


            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            $cart->items()->delete();


            /*
            |--------------------------------------------------------------------------
            | Return Fresh Order
            |--------------------------------------------------------------------------
            */

            return $order->load([
                'items',
            ]);
        });
    }

    /**
     * Release stock reserved for an unpaid order.
     *
     * This should be called when payment fails, expires, or the order is
     * cancelled before payment completion.
     */
    public function releaseReservedStock(Order $order): void
    {
        DB::transaction(function () use ($order) {

            $order->load([
                'items',
            ]);

            foreach ($order->items as $orderItem) {

                if (! $orderItem->product_id) {
                    continue;
                }

                $product = Product::query()
                    ->whereKey($orderItem->product_id)
                    ->lockForUpdate()
                    ->first();

                if (! $product) {
                    continue;
                }

                $quantity = (int) $orderItem->quantity;

                if ($quantity < 1) {
                    continue;
                }

                $stockBefore = (int) $product->stock;
                $stockAfter = $stockBefore + $quantity;

                $product->update([
                    'stock' => $stockAfter,
                ]);

                InventoryMovement::create([
                    'product_id' => $product->id,
                    'user_id' => $order->user_id,

                    'type' => 'return',
                    'quantity' => $quantity,

                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,

                    'reference_type' => Order::class,
                    'reference_id' => $order->id,

                    'note' => "Stock released for Order #{$order->order_number}",
                ]);
            }
        });
    }

    /**
     * Determine checkout discount.
     */
    private function calculateDiscount(
        int $subtotal,
        array $items
    ): int {
        /*
         * Global discounts are not implemented yet.
         *
         * Product-level discount is already represented by the current
         * Product::price value.
         */
        return 0;
    }

    /**
     * Determine shipping cost.
     */
    private function calculateShipping(int $subtotal): int
    {
        /*
         * Current rule:
         *
         * >= 5,000,000 تومان => free shipping
         * < 5,000,000 تومان => 150,000 تومان
         */

        if ($subtotal >= 5_000_000) {
            return 0;
        }

        return 150_000;
    }

    /**
     * Get the current user's cart.
     */
    private function getUserCart(User $user): ?Cart
    {
        return Cart::query()
            ->where('user_id', $user->id)
            ->with('items')
            ->first();
    }

    /**
     * Generate a collision-safe order number.
     */
    private function generateOrderNumber(): string
    {
        do {
            $number =
                now()->format('YmdHis')
                . '-'
                . strtoupper(Str::random(6));

        } while (
            Order::query()
                ->where('order_number', $number)
                ->exists()
        );

        return $number;
    }
}
