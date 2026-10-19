@extends('layouts.storefront')

@section('title', 'جزئیات سفارش ' . $order->order_number . ' | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span><a href="{{ route('customer.orders.index') }}">سفارش‌ها</a><span aria-hidden="true">/</span><span aria-current="page">{{ $order->order_number }}</span></nav>
            <header class="fz-page-heading"><span class="fz-kicker">FARZIN / ORDER DETAIL</span><h1 class="fz-h2">سفارش {{ $order->order_number }}</h1><p class="fz-desc">تاریخ ثبت: {{ $order->created_at->locale('fa')->translatedFormat('j F Y، H:i') }}</p></header>

            <div class="fz-order-detail-grid">
                <section class="fz-panel">
                    <h2 class="fz-panel-title">اقلام سفارش</h2>
                    <div class="fz-order-items">
                        @foreach ($order->items as $item)
                            <article class="fz-order-item">
                                <div><strong>{{ $item->product_name }}</strong><p class="fz-form-help">{{ number_format($item->unit_price) }} تومان × {{ number_format($item->quantity) }}</p></div>
                                <strong>{{ number_format($item->total) }} تومان</strong>
                            </article>
                        @endforeach
                    </div>
                </section>

                <aside class="fz-order-summary">
                    <span class="fz-kicker">وضعیت سفارش</span>
                    <h2>{{ ['pending' => 'در انتظار', 'processing' => 'در حال پردازش', 'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغوشده'][$order->status] ?? $order->status }}</h2>
                    <div class="fz-summary-row"><span>پرداخت</span><strong>{{ ['pending' => 'در انتظار', 'paid' => 'پرداخت‌شده', 'failed' => 'ناموفق', 'refunded' => 'مستردشده'][$order->payment_status] ?? $order->payment_status }}</strong></div>
                    <div class="fz-summary-row"><span>جمع کالاها</span><strong>{{ number_format($order->subtotal) }} تومان</strong></div>
                    @if ($order->discount > 0)<div class="fz-summary-row"><span>تخفیف</span><strong>{{ number_format($order->discount) }} تومان</strong></div>@endif
                    <div class="fz-summary-row"><span>هزینه ارسال</span><strong>{{ number_format($order->shipping_cost) }} تومان</strong></div>
                    <div class="fz-summary-row fz-summary-row--total"><span>مبلغ پرداختی</span><strong>{{ number_format($order->total) }} تومان</strong></div>
                </aside>
            </div>

            <section class="fz-panel fz-order-shipping">
                <h2 class="fz-panel-title">نشانی ارسال</h2>
                <p><strong>{{ $order->shipping_full_name }}</strong> · <span dir="ltr">{{ $order->shipping_phone }}</span></p>
                <p>{{ $order->shipping_country }}، {{ $order->shipping_province }}، {{ $order->shipping_city }}</p>
                <p>{{ $order->shipping_address }}</p>
                @if ($order->shipping_postal_code)<p>کد پستی: {{ $order->shipping_postal_code }}</p>@endif
                @if ($order->notes)<p class="fz-form-help">یادداشت: {{ $order->notes }}</p>@endif
            </section>

            @if ($order->statusHistories->isNotEmpty())
                <section class="fz-panel fz-order-history">
                    <h2 class="fz-panel-title">تاریخچه وضعیت</h2>
                    @foreach ($order->statusHistories as $history)
                        <div class="fz-order-history__row">
                            <strong>{{ $history->to_status }}</strong>
                            <time datetime="{{ $history->created_at->toIso8601String() }}">{{ $history->created_at->locale('fa')->translatedFormat('j F Y، H:i') }}</time>
                            @if ($history->changedBy)<span>{{ $history->changedBy->name }}</span>@endif
                        </div>
                    @endforeach
                </section>
            @endif

            <p class="mt-6"><a class="fz-btn light" href="{{ route('customer.orders.index') }}">بازگشت به سفارش‌ها</a></p>
        </div>
    </section>
@endsection
