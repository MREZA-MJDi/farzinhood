@if($product->images->isNotEmpty())
    <section class="rounded-2xl border border-[var(--color-border)] bg-white p-5 shadow-sm">
        <div class="mb-6">
            <h2 class="text-lg font-black text-[var(--color-text-primary)]">تصاویر فعلی محصول</h2>
            <p class="mt-1 text-sm text-[var(--color-text-secondary)]">
                تصویر اصلی در فروشگاه به‌عنوان عکس پیش‌فرض محصول نمایش داده می‌شود. هر تصویر را می‌توانید اصلی کنید یا حذف کنید.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5">
            @foreach($product->images->sortBy('sort_order') as $image)
                <article class="overflow-hidden rounded-2xl border border-[var(--color-border)] bg-[var(--color-neutral-50)]">
                    <img
                        src="{{ asset('storage/' . $image->image) }}"
                        alt="{{ $image->alt ?: $product->name }}"
                        loading="lazy"
                        class="aspect-square w-full object-cover"
                    >

                    <div class="space-y-3 p-3">
                        @if($image->is_primary)
                            <span class="inline-flex rounded-full bg-[var(--color-brand-50)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-brand-700)]">
                                تصویر اصلی
                            </span>
                        @else
                            <span class="inline-flex rounded-full bg-[var(--color-neutral-100)] px-2.5 py-1 text-[11px] font-bold text-[var(--color-text-secondary)]">
                                تصویر محصول
                            </span>

                            <form action="{{ route('admin.product-images.primary', $image) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button
                                    type="submit"
                                    class="w-full rounded-lg border border-[var(--color-border)] bg-white px-3 py-2 text-xs font-bold text-[var(--color-text-primary)] transition hover:border-[var(--color-brand-300)] hover:bg-[var(--color-brand-50)]"
                                >
                                    تنظیم به‌عنوان تصویر اصلی
                                </button>
                            </form>
                        @endif

                        <form
                            action="{{ route('admin.product-images.destroy', $image) }}"
                            method="POST"
                            onsubmit="return confirm('این تصویر محصول حذف شود؟');"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="w-full rounded-lg border border-red-200 bg-white px-3 py-2 text-xs font-bold text-red-700 transition hover:bg-red-50"
                            >
                                حذف تصویر
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endif
