@extends('layouts.storefront')

@section('title', 'حساب من | فرزین')

@section('content')
    <section class="fz-section">
        <div class="fz-container">
            <header class="fz-account-heading">
                <div>
                    <span class="fz-kicker">FARZIN / MY ACCOUNT</span>
                    <h1 class="fz-h2">سلام، {{ auth()->user()->name }}</h1>
                    <p class="fz-desc">وضعیت خریدها و اطلاعات حساب را از یکجا مدیریت کن.</p>
                </div>
                <a class="fz-btn light" href="{{ route('shop.index') }}">بازگشت به فروشگاه</a>
            </header>

            <div class="fz-account-stats">
                <a href="{{ route('customer.orders.index') }}" class="fz-account-stat">
                    <span>سفارش‌ها</span><strong>{{ number_format($orderCount) }}</strong><small>مشاهده وضعیت سفارش‌ها ←</small>
                </a>
                <a href="{{ route('customer.wishlist.index') }}" class="fz-account-stat">
                    <span>علاقه‌مندی‌ها</span><strong>{{ number_format($wishlistCount) }}</strong><small>محصول‌های ذخیره‌شده ←</small>
                </a>
                <a href="{{ route('customer.addresses.index') }}" class="fz-account-stat">
                    <span>نشانی‌ها</span><strong>{{ number_format($addressCount) }}</strong><small>مدیریت نشانی ارسال ←</small>
                </a>
            </div>

            <div class="fz-account-links">
                <a href="{{ route('customer.cart.index') }}">سبد خرید <span aria-hidden="true">←</span></a>
                <a href="{{ route('customer.orders.index') }}">سفارش‌های من <span aria-hidden="true">←</span></a>
                <a href="{{ route('customer.addresses.index') }}">نشانی‌های من <span aria-hidden="true">←</span></a>
                <a href="{{ route('customer.settings.index') }}">اطلاعات حساب <span aria-hidden="true">←</span></a>
            </div>

            <section class="fz-section fz-section--compact" aria-labelledby="recent-orders-title">
                <div class="fz-head">
                    <div><span class="fz-kicker">پیگیری خرید</span><h2 class="fz-h2" id="recent-orders-title">آخرین سفارش‌ها</h2></div>
                    <a href="{{ route('customer.orders.index') }}">همه سفارش‌ها ←</a>
                </div>

                @if ($recentOrders->isEmpty())
                    <div class="fz-empty">
                        <p>هنوز سفارشی ثبت نکرده‌ای.</p>
                        <a class="fz-btn" href="{{ route('shop.index') }}">کشف محصولات</a>
                    </div>
                @else
                    <div class="fz-table-wrap">
                        <table class="fz-table">
                            <thead><tr><th>شماره سفارش</th><th>تاریخ ثبت</th><th>وضعیت</th><th>مبلغ</th><th></th></tr></thead>
                            <tbody>
                                @foreach ($recentOrders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_number }}</strong></td>
                                        <td>{{ $order->created_at->locale('fa')->translatedFormat('j F Y') }}</td>
                                        <td>{{ ['pending' => 'در انتظار', 'processing' => 'در حال پردازش', 'shipped' => 'ارسال‌شده', 'delivered' => 'تحویل‌شده', 'cancelled' => 'لغوشده'][$order->status] ?? $order->status }}</td>
                                        <td>{{ number_format($order->total) }} تومان</td>
                                        <td><a href="{{ route('customer.orders.show', $order) }}">جزئیات ←</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>
    </section>
@endsection
