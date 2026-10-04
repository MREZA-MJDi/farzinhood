@props([
'items' => [],
'subtotal' => 0,
'shipping' => 0,
'discount' => 0,
'total' => 0,
])

<div
    class="cart-drawer"
    data-cart-drawer
    hidden
>
    <div
        class="cart-drawer__panel"
        role="dialog"
        aria-modal="true"
        aria-labelledby="cart-drawer-title"
    >

        <header class="cart-drawer__header">

            <h2
                id="cart-drawer-title"
                class="cart-drawer__title"
            >
                سبد خرید
            </h2>

            <button
                type="button"
                class="icon-btn icon-btn--border"
                data-cart-close
                aria-label="بستن سبد خرید"
            >
                ×
            </button>

        </header>


        <div
            class="cart-drawer__body"
            data-cart-items
        >

            @forelse($items as $item)

                <x-cart.cart-item
                    :item="$item"
                />

            @empty

                <x-ui.empty-state
                    title="سبد خرید خالی است"
                    description="هنوز محصولی به سبد خرید اضافه نکرده‌اید."
                >
                    <x-slot:icon>
                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path d="M4 5H6L8.2 15.5H18L20 8H7" />
                            <circle cx="9.5" cy="19" r="1.2" />
                            <circle cx="17" cy="19" r="1.2" />
                        </svg>
                    </x-slot:icon>

                    <x-slot:action>
                        <a
                            href="{{ route('shop') }}"
                            class="btn btn--primary"
                        >
                            مشاهده محصولات
                        </a>
                    </x-slot:action>
                </x-ui.empty-state>

            @endforelse

        </div>


        @if(count($items))

            <footer class="cart-drawer__footer">

                <x-cart.cart-summary
                    :subtotal="$subtotal"
                    :shipping="$shipping"
                    :discount="$discount"
                    :total="$total"
                />

                <div class="stack stack--sm">

                    <a
                        href="{{ route('cart') }}"
                        class="btn btn--outline btn--full"
                    >
                        مشاهده سبد خرید
                    </a>

                    <a
                        href="{{ route('checkout') }}"
                        class="btn btn--primary btn--full"
                    >
                        ادامه و پرداخت
                    </a>

                </div>

            </footer>

        @endif

    </div>
</div>
