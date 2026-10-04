@extends('layouts.admin')

@section('title', 'جزئیات مشتری')

@section('content')
    <div class="space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

            <div class="flex items-center gap-4">

                <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-[var(--color-brand-50)] text-2xl font-black text-[var(--color-brand-700)]">
                    {{ mb_substr($customer->name ?: '؟', 0, 1) }}
                </div>

                <div>

                    <h1 class="text-2xl font-black text-[var(--color-text-primary)]">
                        {{ $customer->name ?: 'بدون نام' }}
                    </h1>

                    <p class="mt-1 text-sm text-[var(--color-text-secondary)]"
                       dir="ltr">
                        {{ $customer->email }}
                    </p>

                </div>

            </div>


            <a href="{{ route('admin.customers.index') }}"
               class="inline-flex items-center justify-center rounded-xl border border-[var(--color-border)] bg-white px-5 py-3 text-sm font-bold text-[var(--color-text-primary)] hover:bg-[var(--color-neutral-50)]">
                بازگشت به مشتریان
            </a>

        </div>


        {{-- Customer Info + Status --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

            {{-- Information --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm lg:col-span-2">

                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    اطلاعات مشتری
                </h2>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <div class="text-xs text-[var(--color-text-muted)]">
                            نام
                        </div>

                        <div class="mt-1 font-bold text-[var(--color-text-primary)]">
                            {{ $customer->name ?: 'بدون نام' }}
                        </div>
                    </div>


                    <div>
                        <div class="text-xs text-[var(--color-text-muted)]">
                            ایمیل
                        </div>

                        <div class="mt-1 text-sm text-[var(--color-text-secondary)]"
                             dir="ltr">
                            {{ $customer->email }}
                        </div>
                    </div>


                    <div>
                        <div class="text-xs text-[var(--color-text-muted)]">
                            وضعیت ایمیل
                        </div>

                        <div class="mt-1">

                            @if($customer->email_verified_at)

                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">
                                    تایید شده
                                </span>

                            @else

                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">
                                    تایید نشده
                                </span>

                            @endif

                        </div>
                    </div>


                    <div>
                        <div class="text-xs text-[var(--color-text-muted)]">
                            تاریخ عضویت
                        </div>

                        <div class="mt-1 text-sm font-bold text-[var(--color-text-primary)]">
                            {{ $customer->created_at->locale('fa')->translatedFormat('l، j F Y') }}
                        </div>
                    </div>

                </div>

            </div>


            {{-- Status --}}
            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    وضعیت حساب
                </h2>

                <div class="mt-5">

                    @if($customer->is_active)

                        <div class="rounded-xl bg-emerald-50 p-4">
                            <div class="font-bold text-emerald-700">
                                حساب فعال است
                            </div>

                            <div class="mt-1 text-xs text-emerald-600">
                                مشتری می‌تواند از حساب خود استفاده کند.
                            </div>
                        </div>

                    @else

                        <div class="rounded-xl bg-red-50 p-4">
                            <div class="font-bold text-red-700">
                                حساب غیرفعال است
                            </div>

                            <div class="mt-1 text-xs text-red-600">
                                دسترسی مشتری غیرفعال شده است.
                            </div>
                        </div>

                    @endif

                </div>


                <form action="{{ route('admin.customers.status', $customer) }}"
                      method="POST"
                      class="mt-4">

                    @csrf
                    @method('PATCH')

                    <input type="hidden"
                           name="status"
                           value="{{ $customer->is_active ? 0 : 1 }}">

                    <button type="submit"
                            class="w-full rounded-xl px-5 py-3 text-sm font-bold
                            {{ $customer->is_active
                                ? 'bg-red-50 text-red-700 hover:bg-red-100'
                                : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'
                            }}">

                        {{ $customer->is_active ? 'غیرفعال کردن حساب' : 'فعال کردن حساب' }}

                    </button>

                </form>

            </div>

        </div>


        {{-- Statistics --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">

            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="text-sm text-[var(--color-text-muted)]">
                    تعداد سفارش‌ها
                </div>

                <div class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($customer->orders_count ?? $orders->count()) }}
                </div>

            </div>


            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="text-sm text-[var(--color-text-muted)]">
                    مجموع خرید
                </div>

                <div class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">
                    {{ number_format($customer->orders_sum_total ?? $orders->sum('total')) }}
                    <span class="text-sm font-medium">
                        تومان
                    </span>
                </div>

            </div>


            <div class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">

                <div class="text-sm text-[var(--color-text-muted)]">
                    آخرین فعالیت
                </div>

                <div class="mt-2 text-lg font-black text-[var(--color-text-primary)]">
                    {{ $customer->updated_at->locale('fa')->diffForHumans() }}
                </div>

            </div>

        </div>


        {{-- Orders --}}
        <div class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">

            <div class="border-b border-[var(--color-border)] p-5">
                <h2 class="text-lg font-black text-[var(--color-text-primary)]">
                    سفارش‌های اخیر
                </h2>
            </div>


            <div class="overflow-x-auto">

                <table class="min-w-full text-right">

                    <thead class="border-b border-[var(--color-border)] bg-[var(--color-neutral-50)]">

                    <tr>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            سفارش
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            مبلغ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            وضعیت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            پرداخت
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            تاریخ
                        </th>

                        <th class="px-5 py-4 text-xs font-black text-[var(--color-text-secondary)]">
                            عملیات
                        </th>

                    </tr>

                    </thead>


                    <tbody class="divide-y divide-[var(--color-border)]">

                    @php
                        $statusLabels = [
                            'pending' => 'در انتظار',
                            'processing' => 'در حال پردازش',
                            'shipped' => 'ارسال شده',
                            'delivered' => 'تحویل شده',
                            'cancelled' => 'لغو شده',
                        ];

                        $statusClasses = [
                            'pending' => 'bg-amber-50 text-amber-700',
                            'processing' => 'bg-blue-50 text-blue-700',
                            'shipped' => 'bg-indigo-50 text-indigo-700',
                            'delivered' => 'bg-emerald-50 text-emerald-700',
                            'cancelled' => 'bg-red-50 text-red-700',
                        ];

                        $paymentLabels = [
                            'pending' => 'در انتظار',
                            'paid' => 'پرداخت شده',
                            'failed' => 'ناموفق',
                            'refunded' => 'برگشت وجه',
                        ];

                        $paymentClasses = [
                            'pending' => 'bg-amber-50 text-amber-700',
                            'paid' => 'bg-emerald-50 text-emerald-700',
                            'failed' => 'bg-red-50 text-red-700',
                            'refunded' => 'bg-purple-50 text-purple-700',
                        ];
                    @endphp


                    @forelse($orders as $order)

                        <tr class="hover:bg-[var(--color-neutral-50)]">

                            <td class="px-5 py-4">
                                <span class="font-black text-[var(--color-text-primary)]">
                                    #{{ $order->order_number }}
                                </span>
                            </td>


                            <td class="px-5 py-4">
                                <span class="font-bold text-[var(--color-text-primary)]">
                                    {{ number_format($order->total) }}
                                    تومان
                                </span>
                            </td>


                            <td class="px-5 py-4">

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <span class="rounded-full px-3 py-1 text-xs font-bold {{ $paymentClasses[$order->payment_status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                                </span>

                            </td>


                            <td class="px-5 py-4">

                                <div class="text-sm font-bold text-[var(--color-text-primary)]">
                                    {{ $order->created_at->locale('fa')->translatedFormat('Y/m/d') }}
                                </div>

                                <div class="mt-1 text-xs text-[var(--color-text-muted)]">
                                    {{ $order->created_at->locale('fa')->translatedFormat('H:i') }}
                                </div>

                            </td>


                            <td class="px-5 py-4">

                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="rounded-lg bg-[var(--color-brand-50)] px-4 py-2 text-xs font-bold text-[var(--color-brand-700)] hover:bg-[var(--color-brand-100)]">
                                    مشاهده
                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-sm text-[var(--color-text-secondary)]">
                                هنوز سفارشی برای این مشتری ثبت نشده است.
                            </td>
                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
