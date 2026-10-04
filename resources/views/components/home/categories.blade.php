@props([
'categories',
])

<section class="section home-section--categories">

    <div class="container">

        <div class="section__inner">

            <x-ui.section-header
                eyebrow="دسته‌بندی"
                title="انتخاب مناسب برای آشپزخانه شما"
                description="محصولات فرزین را بر اساس دسته‌بندی موردنظر خود مشاهده کنید."
            >
                <x-slot:action>
                    <a
                        href="{{ route('categories.index') }}"
                        class="btn btn--outline"
                    >
                        مشاهده دسته‌بندی‌ها
                    </a>
                </x-slot:action>
            </x-ui.section-header>


            <div class="section-content">

                <div class="category-grid">

                    @forelse($categories as $category)

                        <x-product.category-card
                            :title="$category->name"
                            :eyebrow="$category->active_products_count . ' محصول'"
                            :description="$category->description"
                            :href="route('category.show', $category->slug)"
                            :image="$category->image
                                ? asset('storage/' . $category->image)
                                : null"
                        />

                    @empty

                        <div class="empty-state">

                            <div class="empty-state__icon">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.7"
                                    aria-hidden="true"
                                >
                                    <path d="M4 5h16v14H4z" />
                                    <path d="M4 9h16" />
                                </svg>
                            </div>

                            <div class="empty-state__content">

                                <h2 class="empty-state__title">
                                    هنوز دسته‌بندی‌ای ثبت نشده است
                                </h2>

                                <p class="empty-state__description">
                                    دسته‌بندی‌های محصولات به‌زودی در این بخش نمایش داده می‌شوند.
                                </p>

                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</section>

