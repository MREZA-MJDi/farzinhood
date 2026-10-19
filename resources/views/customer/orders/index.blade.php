@extends('layouts.storefront')

@section('title', 'سفارش‌های من | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <nav class="fz-breadcrumb" aria-label="مسیر صفحه"><a href="{{ route('home') }}">خانه</a><span aria-hidden="true">/</span><a href="{{ route('customer.dashboard') }}">حساب من</a><span aria-hidden="true">/</span><span aria-current="page">سفارش‌ها</span></nav>
            <header class="fz-page-heading"><span class="fz-kicker">FARZIN / ORDERS</span><h1 class="fz-h2">سفارش‌های من</h1><p class="fz-desc">وضعیت سفارش‌ها و جزئیات هر خرید را پیگیری کن.</p></header>

            @if ($orders->isEmpty())
                <div class="fz-empty"><p>هنوز سفارشی ثبت نکرده‌ای.</p><a class="fz-btn" href="{{ route('shop.index') }}">مشاهده محصولات</a></div>
            @else
                <div class="fz-table-wrap">
                    <table class="fz-table">
                        <thead><tr><th>شماره سفارش</th><th>تاریخ</th><th>تعداد کالا</th><th>وضعیت سفارش</th><th>وضعیت پرداخت</th><th>مبلغ</th><th></th></tr></thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td><strong>{{ $order->order_number }}</strong></td>
                                    <td>{{ $order->created_at->locale('fa')->translatedFormat('j F Y') }}</td>
                                    <td>{{ number_format($order->items_count) }}</td>
                                    <td>{{ ['pending' => 'در انتظار', 'processing' => 'در حال پردازش', 'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغوشده'][$order->status] ?? $order->status }}</td>
                                    <td>{{ ['pending' => 'در انتظار پرداخت', 'paid' => 'پرداخت‌شده', 'failed' => 'ناموفق', 'refunded' => 'مستردشده'][$order->payment_status] ?? $order->payment_status }}</td>
                                    <td>{{ number_format($order->total) }} تومان</td>
                                    <td><a href="{{ route('customer.orders.show', $order) }}">جزئیات ←</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="fz-pagination">{{ $orders->links() }}</div>
            @endif
        </div>
    </section>
@endsection
