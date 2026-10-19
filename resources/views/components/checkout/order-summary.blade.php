@props([
'items' => [],
'subtotal' => 0,
'shipping' => 0,
'discount' => 0,
'total' => 0,
])

<aside class="order-summary">

    <h2 class="order-summary__title">
        خلاصه سفارش
    </h2>


    <div class="order-summary__items">

        @foreach($items as $item)

            <div class="order-summary__item">

                <div class="order-summary__item-info">

                    <div class="order-summary__item-title">
                        {{ $item['name'] ?? '' }}
                    </div>

                    <div class="order-summary__item-meta">
                        تعداد:
                        {{ $item['quantity'] ?? 1 }}
                    </div>

                </div>

                <div class="order-summary__item-price">
                    {{
                        \\App\\Support\\JalaliCalendar::number(
                            ($item['price'] ?? 0) *
                            ($item['quantity'] ?? 1)
                        )
                    }}
                    تومان
                </div>

            </div>

        @endforeach

    </div>


    <div class="order-summary__totals">

        <div class="cart-summary">

            <div class="cart-summary__row">
                <span>جمع محصولات</span>

                <strong>
                    {{ \\App\\Support\\JalaliCalendar::number($subtotal) }}
                    تومان
                </strong>
            </div>

            <div class="cart-summary__row">
                <span>ارسال</span>

                <strong>
                    {{
                        $shipping > 0
                            ? \\App\\Support\\JalaliCalendar::number($shipping) . ' تومان'
                            : 'رایگان'
                    }}
                </strong>
            </div>

            @if($discount > 0)

                <div class="cart-summary__row cart-summary__row--discount">

                    <span>تخفیف</span>

                    <strong>
                        -{{ \\App\\Support\\JalaliCalendar::number($discount) }}
                        تومان
                    </strong>

                </div>

            @endif

            <div class="cart-summary__row cart-summary__row--total">

                <span>
                    مبلغ قابل پرداخت
                </span>

                <strong>
                    {{ \\App\\Support\\JalaliCalendar::number($total) }}
                    تومان
                </strong>

            </div>

        </div>

    </div>

</aside>
