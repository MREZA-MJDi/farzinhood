@extends('layouts.app')

@section('title', 'سفارش‌های من | Farzin')
@section('meta_description', 'تاریخچه سفارش‌ها و وضعیت سفارش‌های حساب کاربری فرزین')

@section('content')
<section class="farzin-container py-8 sm:py-10 lg:py-12">
    <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="farzin-eyebrow">FARZIN / ORDERS</span>
            <h1 class="farzin-page-title mt-3">سفارش‌های من</h1>
            <p class="farzin-section-description mt-3 max-w-2xl">
                وضعیت سفارش‌ها، مبلغ و جزئیات هر خرید را از همین‌جا پیگیری کن.
            </p>
        </div>
        <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[var(--color-brand-900)] px-5 py-3.5 text-sm font-black text-white transition hover:-translate-y-0.5 hover:bg-[var(--color-brand-950)]">
            ادامه خرید
            <span aria-hidden="true">←</span>
        </a>
    </header>

    @if($orders->count())
        <div class="mt-8 overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
            <div class="border-b border-[var(--color-border)] bg-[var(--color-earth-50)] px-5 py-4 sm:px-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">ORDER HISTORY</span>
                        <p class="mt-1 text-xs text-[var(--color-text-muted)]">{{ number_format($orders->total()) }} سفارش ثبت شده</p>
                    </div>
                </div>
            </div>

            <div class="divide-y divide-[var(--color-border)]">
                @foreach($orders as $order)
                    @php
                        $statusLabel = match($order->status) {
                            'pending' => 'در انتظار پرداخت',
                            'processing' => 'در حال پردازش',
                            'shipped' => 'ارسال شده',
                            'delivered' => 'تحویل شده',
                            'cancelled' => 'لغو شده',
                            default => $order->status,
                        };

                        $statusClass = match($order->status) {
                            'delivered' => 'bg-[var(--color-success-50)] text-[var(--color-success-700)]',
                            'cancelled' => 'bg-[var(--color-danger-50)] text-[var(--color-danger-700)]',
                            'shipped' => 'bg-[var(--color-info-50)] text-[var(--color-info-700)]',
                            default => 'bg-[var(--color-earth-100)] text-[var(--color-earth-800)]',
                        };
                    @endphp

                    <a href="{{ route('customer.orders.show', $order) }}" class="group flex flex-col gap-5 px-5 py-5 transition hover:bg-[var(--color-surface-soft)] sm:px-6 lg:flex-row lg:items-center">
                        <div class="flex min-w-0 flex-1 items-center gap-4">
                            <span class="flex size-11 shrink-0 items-center justify-center rounded-xl bg-[var(--color-brand-50)] text-xs font-black text-[var(--color-brand-900)]">#</span>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <strong class="text-sm font-black text-[var(--color-text-primary)]">{{ $order->order_number }}</strong>
                                    <span class="rounded-full px-2.5 py-1 text-[9px] font-black {{ $statusClass }}">{{ $statusLabel }}</span>
                                </div>
                                <span class="mt-1 block text-[10px] text-[var(--color-text-muted)]">
                                    {{ $order->created_at?->format('Y/m/d H:i') }} · {{ number_format($order->items_count) }} قلم
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between gap-8 border-t border-[var(--color-border)] pt-4 sm:border-0 sm:pt-0">
                            <div>
                                <span class="block text-[9px] font-bold text-[var(--color-text-muted)]">مبلغ سفارش</span>
                                <strong class="mt-1 block text-sm font-black text-[var(--color-brand-950)]">
                                    {{ number_format($order->total) }}
                                    <small class="text-[9px] font-bold text-[var(--color-text-muted)]">تومان</small>
                                </strong>
                            </div>

                            <span class="text-xs font-black text-[var(--color-text-soft)] transition group-hover:-translate-x-1 group-hover:text-[var(--color-accent-600)]">
                                مشاهده جزئیات ←
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-8">
            {{ $orders->onEachSide(1)->links() }}
        </div>
    @else
        <div class="mt-8 rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-[var(--color-earth-50)] px-6 py-20 text-center">
            <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[var(--color-brand-900)] text-white">
                <svg class="size-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                    <path d="M3 5h2l2 10h10l3-7H6"/>
                    <circle cx="10" cy="19" r="1"/><circle cx="18" cy="19" r="1"/>
                </svg>
            </div>
            <h2 class="mt-5 text-xl font-black text-[var(--color-text-primary)]">هنوز سفارشی ثبت نکردی</h2>
            <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">
                وقتی انتخابت را نهایی کنی، همه سفارش‌ها و وضعیتشان اینجا قابل پیگیری هستند.
            </p>
            <a href="{{ route('shop.index') }}" class="mt-6 inline-flex rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-accent-700)]">
                رفتن به فروشگاه
            </a>
        </div>
    @endif
</section>
@endsection
