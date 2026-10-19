@props([
'subtotal' => 0,
'shipping' => 0,
'discount' => 0,
'total' => 0,
])

<div class="cart-summary">

    <div class="cart-summary__row">
        <span>جمع محصولات</span>

        <strong>
            {{ \App\Support\JalaliCalendar::number($subtotal) }}
            تومان
        </strong>
    </div>

    <div class="cart-summary__row">
        <span>ارسال</span>

        <strong>
            @if($shipping > 0)
                {{ \App\Support\JalaliCalendar::number($shipping) }}
                تومان
            @else
                رایگان
            @endif
        </strong>
    </div>

    @if($discount > 0)

        <div class="cart-summary__row cart-summary__row--discount">

            <span>
                تخفیف
            </span>

            <strong>
                -{{ \App\Support\JalaliCalendar::number($discount) }}
                تومان
            </strong>

        </div>

    @endif

    <div class="cart-summary__row cart-summary__row--total">

        <span>
            مبلغ نهایی
        </span>

        <strong>
            {{ \App\Support\JalaliCalendar::number($total) }}
            تومان
        </strong>

    </div>

</div>
