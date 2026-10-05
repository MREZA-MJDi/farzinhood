@extends('layouts.app')

@section('title', 'آدرس‌های من | Farzin')
@section('meta_description', 'مدیریت آدرس‌های ارسال حساب کاربری فرزین')

@section('content')
<section class="farzin-container py-8 sm:py-10 lg:py-12">
    <header class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <span class="farzin-eyebrow">FARZIN / ADDRESSES</span>
            <h1 class="farzin-page-title mt-3">آدرس‌های من</h1>
            <p class="farzin-section-description mt-3 max-w-2xl">
                آدرس‌های ارسال را مدیریت کن تا هنگام ثبت سفارش مسیر کوتاه‌تری داشته باشی.
            </p>
        </div>
        <a href="#add-address" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-[var(--color-brand-900)] px-5 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)]">
            + افزودن آدرس
        </a>
    </header>

    @if(session('success'))
        <div class="mt-6 rounded-2xl border border-[var(--color-success-100)] bg-[var(--color-success-50)] px-4 py-3 text-sm font-bold text-[var(--color-success-700)]">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="mt-6 rounded-2xl border border-[var(--color-danger-100)] bg-[var(--color-danger-50)] px-4 py-3 text-sm font-bold text-[var(--color-danger-700)]">
            اطلاعات آدرس نیاز به بررسی دارد.
        </div>
    @endif

    <div class="mt-8 grid gap-5 md:grid-cols-2">
        @forelse($addresses as $address)
            <article class="overflow-hidden rounded-[1.75rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)] transition hover:-translate-y-0.5 hover:border-[var(--color-earth-300)] hover:shadow-[var(--shadow-md)]">
                <div class="h-1 {{ $address->is_default ? 'bg-[var(--color-accent-600)]' : 'bg-[var(--color-earth-200)]' }}"></div>
                <div class="p-5 sm:p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="flex size-11 items-center justify-center rounded-2xl {{ $address->is_default ? 'bg-[var(--color-accent-50)] text-[var(--color-accent-700)]' : 'bg-[var(--color-earth-100)] text-[var(--color-earth-800)]' }}">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true"><path d="M12 21s7-6 7-12A7 7 0 1 0 5 9c0 6 7 12 7 12Z"/><circle cx="12" cy="9" r="2.2"/></svg>
                            </div>
                            <div>
                                <h2 class="text-sm font-black">{{ $address->title ?: 'آدرس ارسال' }}</h2>
                                @if($address->is_default)
                                    <span class="mt-1 inline-flex rounded-full bg-[var(--color-success-50)] px-2.5 py-1 text-[9px] font-black text-[var(--color-success-700)]">آدرس پیش‌فرض</span>
                                @else
                                    <span class="mt-1 block text-[9px] text-[var(--color-text-muted)]">آدرس ذخیره‌شده</span>
                                @endif
                            </div>
                        </div>

                        <form action="{{ route('customer.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('آیا از حذف این آدرس مطمئنی؟');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex size-9 items-center justify-center rounded-xl text-[var(--color-text-muted)] transition hover:bg-[var(--color-danger-50)] hover:text-[var(--color-danger-700)]" aria-label="حذف آدرس">
                                <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M19 6l-1 15H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                            </button>
                        </form>
                    </div>

                    <div class="mt-5 space-y-4 border-t border-[var(--color-border)] pt-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <div><span class="text-[9px] font-black text-[var(--color-text-muted)]">گیرنده</span><p class="mt-1 text-sm font-bold">{{ $address->full_name }}</p></div>
                            <div><span class="text-[9px] font-black text-[var(--color-text-muted)]">تلفن</span><p class="mt-1 text-sm font-bold" dir="ltr">{{ $address->phone }}</p></div>
                            <div><span class="text-[9px] font-black text-[var(--color-text-muted)]">موقعیت</span><p class="mt-1 text-sm font-bold">{{ $address->province }}، {{ $address->city }}</p></div>
                            <div><span class="text-[9px] font-black text-[var(--color-text-muted)]">کد پستی</span><p class="mt-1 font-mono text-sm font-bold">{{ $address->postal_code ?: '—' }}</p></div>
                        </div>
                        <div><span class="text-[9px] font-black text-[var(--color-text-muted)]">آدرس کامل</span><p class="mt-1 text-sm leading-7 text-[var(--color-text-secondary)]">{{ $address->address }}</p></div>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2 border-t border-[var(--color-border)] pt-5">
                        @unless($address->is_default)
                            <form action="{{ route('customer.addresses.default', $address) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3 text-[10px] font-black text-[var(--color-text-secondary)] transition hover:border-[var(--color-earth-400)] hover:text-[var(--color-earth-800)]">
                                    انتخاب به‌عنوان پیش‌فرض
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center rounded-xl bg-[var(--color-success-50)] px-4 py-3 text-[10px] font-black text-[var(--color-success-700)]">برای Checkout پیش‌فرض است</span>
                        @endunless
                    </div>

                    <details class="mt-4 rounded-2xl border border-[var(--color-border)] bg-[var(--color-surface-soft)]">
                        <summary class="cursor-pointer px-4 py-3 text-xs font-black text-[var(--color-text-secondary)]">ویرایش آدرس</summary>
                        <form action="{{ route('customer.addresses.update', $address) }}" method="POST" class="grid gap-4 border-t border-[var(--color-border)] p-4 sm:grid-cols-2">
                            @csrf
                            @method('PUT')
                            <input name="title" value="{{ old('title', $address->title) }}" placeholder="عنوان آدرس" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="full_name" required value="{{ old('full_name', $address->full_name) }}" placeholder="نام گیرنده" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="phone" required value="{{ old('phone', $address->phone) }}" placeholder="شماره تماس" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="country" required value="{{ old('country', $address->country) }}" placeholder="کشور" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="province" required value="{{ old('province', $address->province) }}" placeholder="استان" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="city" required value="{{ old('city', $address->city) }}" placeholder="شهر" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm outline-none focus:border-[var(--color-earth-400)]">
                            <input name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" placeholder="کد پستی" class="rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm font-mono outline-none focus:border-[var(--color-earth-400)]">
                            <textarea name="address" required class="sm:col-span-2 min-h-28 rounded-xl border border-[var(--color-border)] bg-white px-3.5 py-3 text-sm leading-7 outline-none focus:border-[var(--color-earth-400)]">{{ old('address', $address->address) }}</textarea>
                            <label class="sm:col-span-2 flex items-center gap-2 text-xs font-bold text-[var(--color-text-secondary)]">
                                <input type="checkbox" name="is_default" value="1" @checked($address->is_default)>
                                این آدرس پیش‌فرض باشد
                            </label>
                            <button type="submit" class="sm:col-span-2 rounded-xl bg-[var(--color-brand-900)] px-5 py-3.5 text-xs font-black text-white transition hover:bg-[var(--color-brand-950)]">ذخیره ویرایش</button>
                        </form>
                    </details>
                </div>
            </article>
        @empty
            <div class="md:col-span-2 rounded-[2rem] border border-dashed border-[var(--color-border-strong)] bg-[var(--color-earth-50)] px-6 py-20 text-center">
                <div class="mx-auto flex size-16 items-center justify-center rounded-2xl bg-[var(--color-brand-900)] text-white">⌖</div>
                <h2 class="mt-5 text-xl font-black">هنوز آدرسی ثبت نکردی</h2>
                <p class="mx-auto mt-2 max-w-md text-sm leading-7 text-[var(--color-text-secondary)]">یک آدرس اضافه کن تا هنگام خرید دوباره اطلاعات ارسال را وارد نکنی.</p>
                <a href="#add-address" class="mt-6 inline-flex rounded-2xl bg-[var(--color-accent-600)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-accent-700)]">افزودن اولین آدرس</a>
            </div>
        @endforelse
    </div>

    <section id="add-address" class="mt-8 scroll-mt-28 overflow-hidden rounded-[2rem] border border-[var(--color-border)] bg-white shadow-[var(--shadow-xs)]">
        <header class="border-b border-[var(--color-border)] bg-[var(--color-earth-50)] px-6 py-6 sm:px-8">
            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-[var(--color-earth-700)]">NEW ADDRESS</span>
            <h2 class="mt-2 text-xl font-black">افزودن آدرس جدید</h2>
        </header>

        <form action="{{ route('customer.addresses.store') }}" method="POST" class="grid gap-5 p-6 sm:grid-cols-2 sm:p-8">
            @csrf
            <input name="title" value="{{ old('title') }}" placeholder="عنوان؛ مثلاً خانه یا محل کار" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="full_name" required value="{{ old('full_name') }}" placeholder="نام گیرنده" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="phone" required value="{{ old('phone') }}" placeholder="شماره تماس" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="country" required value="{{ old('country', 'ایران') }}" placeholder="کشور" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="province" required value="{{ old('province') }}" placeholder="استان" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="city" required value="{{ old('city') }}" placeholder="شهر" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm outline-none focus:border-[var(--color-earth-400)]">
            <input name="postal_code" value="{{ old('postal_code') }}" placeholder="کد پستی" class="rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm font-mono outline-none focus:border-[var(--color-earth-400)]">
            <label class="flex items-center gap-2 rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 text-xs font-bold text-[var(--color-text-secondary)]">
                <input type="checkbox" name="is_default" value="1" @checked(old('is_default'))>
                آدرس پیش‌فرض
            </label>
            <textarea name="address" required placeholder="خیابان، کوچه، پلاک، واحد..." class="min-h-32 resize-none rounded-xl border border-[var(--color-border)] bg-[var(--color-earth-50)] px-4 py-3.5 text-sm leading-7 outline-none focus:border-[var(--color-earth-400)] sm:col-span-2">{{ old('address') }}</textarea>
            <button type="submit" class="rounded-xl bg-[var(--color-brand-900)] px-6 py-3.5 text-sm font-black text-white transition hover:bg-[var(--color-brand-950)] sm:col-span-2">ذخیره آدرس</button>
        </form>
    </section>
</section>
@endsection
