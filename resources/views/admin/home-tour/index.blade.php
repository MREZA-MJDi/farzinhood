@extends('layouts.admin')

@section('title', 'مدیریت تور ۳۶۰ درجه')
@section('page_title', 'تور ۳۶۰ درجه')

@section('content')
    @php
        $scenes = [
            'hood' => [
                'title' => 'تور هود',
                'description' => 'فریم‌های هود را به ترتیب زاویه یا حرکت آپلود کن؛ اولین فریم در شروع نمایش داده می‌شود.',
            ],
            'sink' => [
                'title' => 'تور سینک',
                'description' => 'فریم‌های سینک را به ترتیب زاویه یا حرکت آپلود کن؛ شماره ترتیب، مسیر حرکت را مشخص می‌کند.',
            ],
        ];
    @endphp

    <div class="space-y-7">
        <header class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm sm:p-7">
            <p class="text-xs font-extrabold tracking-wide text-[var(--color-brand-700)]">HOMEPAGE EXPERIENCE</p>
            <h1 class="mt-2 text-2xl font-black text-[var(--color-text-primary)]">مدیریت فریم‌های تور ۳۶۰ درجه</h1>
            <p class="mt-3 max-w-3xl text-sm leading-8 text-[var(--color-text-secondary)]">
                عکس‌ها را به ترتیب زاویه انتخاب کن. هرچه فریم‌ها از زاویه‌های نزدیک‌تری گرفته شده باشند، چرخش با ماوس و لمس نرم‌تر می‌شود.
                فرمت‌های JPG، PNG، WEBP و AVIF تا حجم ۱۰ مگابایت برای هر تصویر پشتیبانی می‌شوند.
            </p>
            <ol class="mt-4 list-inside list-decimal space-y-1 text-sm leading-7 text-[var(--color-text-secondary)]">
                <li>فریم‌ها را به ترتیب حرکت انتخاب کن؛ ترتیب انتخاب، ترتیب اولیه را تعیین می‌کند.</li>
                <li>در بخش فریم‌ها می‌توانی شماره ترتیب، متن جایگزین، فعال‌بودن یا خود تصویر را تغییر بدهی.</li>
                <li>فقط فریم‌های فعال در صفحه اصلی نمایش داده می‌شوند. برای چرخش پیوسته، دست‌کم ۸ تا ۲۴ فریم توصیه می‌شود.</li>
            </ol>
        </header>

        @foreach ($scenes as $scene => $copy)
            @php $sceneFrames = $frames->get($scene, collect()); @endphp

            <section class="space-y-4">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h2 class="text-xl font-black text-[var(--color-text-primary)]">{{ $copy['title'] }}</h2>
                        <p class="mt-1 text-sm leading-7 text-[var(--color-text-secondary)]">{{ $copy['description'] }}</p>
                    </div>
                    <span class="w-fit rounded-full bg-[var(--color-brand-50)] px-3 py-1.5 text-xs font-extrabold text-[var(--color-brand-800)]">
                        {{ $sceneFrames->count() }} فریم ثبت‌شده
                    </span>
                </div>

                <form method="POST" action="{{ route('admin.home-tour.store') }}" enctype="multipart/form-data"
                      class="rounded-2xl border border-dashed border-[var(--color-border-strong)] bg-white p-4 shadow-sm sm:p-6">
                    @csrf
                    <input type="hidden" name="scene" value="{{ $scene }}">
                    <label class="block text-sm font-extrabold text-[var(--color-text-primary)]" for="tour-images-{{ $scene }}">افزودن فریم‌های جدید</label>
                    <input id="tour-images-{{ $scene }}" type="file" name="images[]" accept="image/jpeg,image/png,image/webp,image/avif"
                           multiple required
                           class="mt-3 block w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-3 text-sm file:ml-3 file:rounded-lg file:border-0 file:bg-[var(--color-brand-900)] file:px-4 file:py-2 file:font-bold file:text-white">
                    @error('images') <p class="mt-2 text-sm font-bold text-red-700">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-2 text-sm font-bold text-red-700">{{ $message }}</p> @enderror
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        <p class="text-xs leading-6 text-[var(--color-text-muted)]">می‌توانی چند تصویر را با هم انتخاب کنی. هر تصویر حداکثر ۱۰ مگابایت.</p>
                        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl bg-[var(--color-brand-900)] px-5 text-sm font-extrabold text-white transition hover:bg-[var(--color-brand-800)]">
                            آپلود فریم‌ها
                        </button>
                    </div>
                </form>

                @if ($sceneFrames->isEmpty())
                    <div class="rounded-2xl border border-[var(--color-border)] bg-white px-5 py-10 text-center">
                        <p class="font-extrabold text-[var(--color-text-primary)]">هنوز فریمی برای این بخش آپلود نشده.</p>
                        <p class="mt-2 text-sm text-[var(--color-text-secondary)]">بعد از اضافه‌کردن فریم‌ها، بخش تور به‌صورت خودکار در صفحه اصلی ظاهر می‌شود.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 2xl:grid-cols-3">
                        @foreach ($sceneFrames as $frame)
                            <article class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-white shadow-sm">
                                <div class="relative aspect-[16/10] bg-[var(--color-neutral-100)]">
                                    <img src="{{ asset('storage/' . $frame->image) }}" alt="{{ $frame->alt ?? $copy['title'] }}"
                                         class="h-full w-full object-contain" loading="lazy">
                                    <span class="absolute right-3 top-3 rounded-lg bg-black/75 px-3 py-1.5 text-xs font-black text-white">
                                        ترتیب {{ $frame->sort_order }}
                                    </span>
                                    @unless ($frame->is_active)
                                        <span class="absolute bottom-3 right-3 rounded-lg bg-red-700 px-3 py-1.5 text-xs font-bold text-white">غیرفعال</span>
                                    @endunless
                                </div>

                                <form method="POST" action="{{ route('admin.home-tour.update', $frame) }}" enctype="multipart/form-data" class="space-y-4 p-4">
                                    @csrf
                                    @method('PATCH')
                                    <div class="grid grid-cols-[100px_minmax(0,1fr)] gap-3">
                                        <label class="block text-xs font-bold text-[var(--color-text-secondary)]">
                                            شماره ترتیب
                                            <input type="number" name="sort_order" min="0" max="100000" required value="{{ $frame->sort_order }}"
                                                   class="mt-2 block min-h-11 w-full rounded-lg border border-[var(--color-border)] bg-white px-3 text-sm text-[var(--color-text-primary)]">
                                        </label>
                                        <label class="block min-w-0 text-xs font-bold text-[var(--color-text-secondary)]">
                                            متن جایگزین تصویر
                                            <input type="text" name="alt" maxlength="180" value="{{ $frame->alt }}"
                                                   class="mt-2 block min-h-11 w-full rounded-lg border border-[var(--color-border)] bg-white px-3 text-sm text-[var(--color-text-primary)]">
                                        </label>
                                    </div>

                                    <label class="block text-xs font-bold text-[var(--color-text-secondary)]">
                                        جایگزینی تصویر (اختیاری)
                                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp,image/avif"
                                               class="mt-2 block w-full rounded-lg border border-[var(--color-border)] bg-[var(--color-neutral-50)] p-2 text-xs">
                                    </label>

                                    <label class="flex items-center gap-3 text-sm font-bold text-[var(--color-text-secondary)]">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" value="1" @checked($frame->is_active)
                                               class="h-4 w-4 rounded border-[var(--color-border)]">
                                        نمایش این فریم در صفحه اصلی
                                    </label>

                                    <button type="submit" class="w-full rounded-xl border border-[var(--color-border)] bg-[var(--color-neutral-50)] px-4 py-3 text-sm font-extrabold text-[var(--color-text-primary)] transition hover:bg-[var(--color-brand-50)]">
                                        ذخیره تغییرات فریم
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('admin.home-tour.destroy', $frame) }}" class="border-t border-[var(--color-border)] p-4"
                                      onsubmit="return confirm('این فریم برای همیشه حذف شود؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full rounded-xl px-4 py-2.5 text-sm font-bold text-red-700 transition hover:bg-red-50">حذف فریم</button>
                                </form>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach
    </div>
@endsection
