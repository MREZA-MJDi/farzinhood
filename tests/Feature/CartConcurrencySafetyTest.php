<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\CartService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use RuntimeException;
use Tests\TestCase;

class CartConcurrencySafetyTest extends TestCase
{
    use RefreshDatabase;

    private function customer(string $email): User
    {
        return User::query()->create([
            'name' => 'Cart Test Customer',
            'email' => $email,
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true,
        ]);
    }

    private function category(): Category
    {
        return Category::query()->create([
            'name' => 'Cart Test Category',
            'slug' => 'cart-test-category',
            'is_active' => true,
        ]);
    }

    private function product(Category $category, string $slug, int $stock, int $price): Product
    {
        return Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Cart Product '.$slug,
            'slug' => $slug,
            'sku' => strtoupper($slug),
            'price' => $price,
            'stock' => $stock,
            'is_active' => true,
        ]);
    }

    public function test_repeated_adds_use_live_price_and_never_exceed_stock(): void
    {
        $user = $this->customer('cart-live-price@example.test');
        $category = $this->category();
        $product = $this->product($category, 'cart-stock-product', 3, 1_000_000);
        $service = app(CartService::class);

        $service->add($product, 2, $user);
        $product->update(['price' => 1_200_000]);

        // The caller still holds a stale model with the original price and stock.
        $service->add($product, 1, $user);

        try {
            $service->add($product, 1, $user);
            $this->fail('Cart quantity must not exceed the latest available stock.');
        } catch (RuntimeException $exception) {
            $this->assertSame(
                'تعداد درخواستی بیشتر از موجودی محصول است.',
                $exception->getMessage()
            );
        }

        $cart = $service->contents($user);
        $this->assertSame(1, $cart->items->count());
        $this->assertSame(3, (int) $cart->items->first()->quantity);
        $this->assertSame(1_200_000, (int) $cart->items->first()->unit_price);
        $this->assertSame(3_600_000, $service->subtotal($user));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock' => 3,
        ]);
    }

    public function test_guest_cart_merge_uses_live_prices_caps_stock_and_discards_sold_out_products(): void
    {
        $user = $this->customer('cart-merge@example.test');
        $category = $this->category();
        $available = $this->product($category, 'merge-available-product', 3, 1_500_000);
        $soldOut = $this->product($category, 'merge-sold-out-product', 0, 700_000);

        $userCart = Cart::query()->create([
            'user_id' => $user->id,
            'session_id' => null,
        ]);
        $userCart->items()->create([
            'product_id' => $available->id,
            'quantity' => 2,
            'unit_price' => 1,
        ]);
        $userCart->items()->create([
            'product_id' => $soldOut->id,
            'quantity' => 1,
            'unit_price' => $soldOut->price,
        ]);

        $sessionId = session()->getId();
        $guestCart = Cart::query()->create([
            'user_id' => null,
            'session_id' => $sessionId,
        ]);
        $guestCart->items()->create([
            'product_id' => $available->id,
            'quantity' => 2,
            'unit_price' => 2,
        ]);
        $guestCart->items()->create([
            'product_id' => $soldOut->id,
            'quantity' => 1,
            'unit_price' => $soldOut->price,
        ]);

        $merged = app(CartService::class)->mergeGuestCart($user);

        $this->assertSame(1, $merged->items->count());
        $item = $merged->items->first();
        $this->assertSame($available->id, (int) $item->product_id);
        $this->assertSame(3, (int) $item->quantity);
        $this->assertSame(1_500_000, (int) $item->unit_price);
        $this->assertDatabaseMissing('carts', ['id' => $guestCart->id]);
        $this->assertDatabaseMissing('cart_items', ['product_id' => $soldOut->id]);
        $this->assertDatabaseHas('products', [
            'id' => $available->id,
            'stock' => 3,
        ]);
    }
}
