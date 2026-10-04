@extends('layouts.app')

@section('title', 'آدرس‌های من | فرزین')
@section('meta_description', 'مدیریت آدرس‌های ارسال در فرزین')

@section('content')
<div class="store-transaction">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="store-account-shell">
            <main class="store-account-content lg:order-2">
                <div class="store-transaction__head">
                    <div>
                        <span class="product-v2__eyebrow">FARZIN / ADDRESSES</span>
                        <h1>آدرس‌های من</h1>
                        <p>آدرس‌های ارسال را یک‌بار ثبت کن تا checkout سریع و مطمئن‌تر باشد.</p>
                    </div>
                    @include('partials.back-link', ['href' => route('customer.dashboard'), 'label' => 'بازگشت به حساب'])
                </div>

                <section class="store-panel mt-3">
                    <div class="store-panel__head">
                        <strong>آدرس‌های ذخیره‌شده</strong>
                        <a class="text-[10px] font-black text-[var(--color-accent-600)]" href="#add-address">افزودن آدرس</a>
                    </div>
                    <div class="store-panel__body">
                        @if($addresses->isEmpty())
                            <div class="store-empty !border-0 !p-8">
                                <div class="store-empty__icon">⌖</div>
                                <h2>هنوز آدرسی نداری</h2>
                                <p>اولین آدرس را ثبت کن تا در checkout قابل انتخاب باشد.</p>
                                <a href="#add-address">افزودن آدرس ←</a>
                            </div>
                        @else
                            <div class="grid gap-3 md:grid-cols-2">
                                @foreach($addresses as $address)
                                    <article class="rounded-[1.15rem] border border-[var(--color-border)] bg-[var(--color-surface)] p-4 {{ $address->is_default ? 'ring-2 ring-[var(--color-brand-200)]' : '' }}">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <strong class="text-xs font-black text-[var(--color-text-primary)]">{{ $address->title ?: 'آدرس ارسال' }}</strong>
                                                @if($address->is_default)
                                                    <span class="mt-1 inline-flex rounded-full bg-[var(--color-success-surface)] px-2 py-1 text-[9px] font-black text-[var(--color-success-ink)]">پیش‌فرض</span>
                                                @endif
                                            </div>
                                            <div class="flex gap-1">
                                                <a href="#edit-address-{{ $address->id }}" class="grid h-8 w-8 place-items-center rounded-lg border border-[var(--color-border)] text-[10px] text-[var(--color-text-muted)] transition hover:border-[var(--color-brand-300)] hover:text-[var(--color-brand-900)]" aria-label="ویرایش">✎</a>
                                                <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('این آدرس حذف شود؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="grid h-8 w-8 place-items-center rounded-lg border border-[var(--color-border)] text-[10px] text-[var(--color-danger-ink)] transition hover:bg-[var(--color-danger-surface)]" aria-label="حذف">×</button>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="mt-3 space-y-1.5 text-[10px] leading-6 text-[var(--color-text-secondary)]">
                                            <p><b>گیرنده:</b> {{ $address->full_name }}</p>
                                            <p><b>تلفن:</b> {{ $address->phone }}</p>
                                            <p><b>محدوده:</b> {{ $address->province }}، {{ $address->city }}</p>
                                            <p><b>آدرس:</b> {{ $address->address }}</p>
                                            @if($address->postal_code)
                                                <p><b>کد پستی:</b> <span dir="ltr">{{ $address->postal_code }}</span></p>
                                            @endif
                                        </div>

                                        @unless($address->is_default)
                                            <form action="{{ route('customer.addresses.default', $address) }}" method="POST" class="mt-3">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="w-full min-h-9 rounded-lg border border-[var(--color-border)] bg-[var(--color-surface-soft)] text-[9px] font-black text-[var(--color-brand-900)]">انتخاب به‌عنوان پیش‌فرض</button>
                                            </form>
                                        @endunless
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>

                <section id="add-address" class="store-panel mt-3 scroll-mt-28">
                    <div class="store-panel__head"><strong>افزودن آدرس جدید</strong><span class="text-[10px] text-[var(--color-text-muted)]">اطلاعات ارسال</span></div>
                    <form action="{{ route('customer.addresses.store') }}" method="POST" class="store-panel__body">
                        @csrf
                        <div class="grid gap-3 sm:grid-cols-2">
                            <input class="store-checkout__field" name="title" value="{{ old('title') }}" placeholder="عنوان، مثل خانه یا محل کار" maxlength="100">
                            <input class="store-checkout__field" name="full_name" value="{{ old('full_name', auth()->user()->name) }}" placeholder="نام گیرنده" required>
                            <input class="store-checkout__field" name="phone" value="{{ old('phone') }}" placeholder="شماره تماس" required>
                            <input class="store-checkout__field" name="country" value="{{ old('country', 'ایران') }}" placeholder="کشور" required>
                            <input class="store-checkout__field" name="province" value="{{ old('province') }}" placeholder="استان" required>
                            <input class="store-checkout__field" name="city" value="{{ old('city') }}" placeholder="شهر" required>
                            <input class="store-checkout__field" name="postal_code" value="{{ old('postal_code') }}" placeholder="کد پستی">
                            <div class="sm:col-span-2">
                                <label class="text-[10px] font-black text-[var(--color-text-secondary)]" for="new-address-body">آدرس کامل</label>
                                <textarea id="new-address-body" name="address" rows="4" maxlength="2000" required placeholder="خیابان، کوچه، پلاک، واحد...">{{ old('address') }}</textarea>
                            </div>
                            <label class="flex items-center gap-2 text-xs font-bold text-[var(--color-text-secondary)] sm:col-span-2">
                                <input type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                                این آدرس پیش‌فرض باشد.
                            </label>
                        </div>
                        <button type="submit" class="mt-3 min-h-11 w-full rounded-xl bg-[var(--color-accent-600)] px-4 text-xs font-black text-white transition hover:bg-[var(--color-accent-700)] sm:w-auto">ذخیره آدرس</button>
                    </form>
                </section>

                @foreach($addresses as $address)
                    <details id="edit-address-{{ $address->id }}" class="store-panel mt-3 scroll-mt-28">
                        <summary class="store-panel__head cursor-pointer"><strong>ویرایش {{ $address->title ?: 'آدرس ارسال' }}</strong><span>+</span></summary>
                        <form action="{{ route('customer.addresses.update', $address) }}" method="POST" class="store-panel__body">
                            @csrf
                            @method('PUT')
                            <div class="grid gap-3 sm:grid-cols-2">
                                <input class="store-checkout__field" name="title" value="{{ old('title', $address->title) }}" placeholder="عنوان">
                                <input class="store-checkout__field" name="full_name" value="{{ old('full_name', $address->full_name) }}" placeholder="نام گیرنده" required>
                                <input class="store-checkout__field" name="phone" value="{{ old('phone', $address->phone) }}" placeholder="شماره تماس" required>
                                <input class="store-checkout__field" name="country" value="{{ old('country', $address->country) }}" placeholder="کشور" required>
                                <input class="store-checkout__field" name="province" value="{{ old('province', $address->province) }}" placeholder="استان" required>
                                <input class="store-checkout__field" name="city" value="{{ old('city', $address->city) }}" placeholder="شهر" required>
                                <input class="store-checkout__field" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" placeholder="کد پستی">
                                <div class="sm:col-span-2">
                                    <label class="text-[10px] font-black text-[var(--color-text-secondary)]" for="address-{{ $address->id }}">آدرس کامل</label>
                                    <textarea id="address-{{ $address->id }}" name="address" rows="4" maxlength="2000" required>{{ old('address', $address->address) }}</textarea>
                                </div>
                                <label class="flex items-center gap-2 text-xs font-bold text-[var(--color-text-secondary)] sm:col-span-2">
                                    <input type="checkbox" name="is_default" value="1" @checked(old('is_default', $address->is_default))>
                                    این آدرس پیش‌فرض باشد.
                                </label>
                            </div>
                            <button type="submit" class="mt-3 min-h-11 rounded-xl bg-[var(--color-brand-900)] px-5 text-xs font-black text-white">ذخیره تغییرات</button>
                        </form>
                    </details>
                @endforeach
            </main>

            <aside class="lg:order-1">@include('partials.account-nav')</aside>
        </div>
    </div>
</div>
@endsection
