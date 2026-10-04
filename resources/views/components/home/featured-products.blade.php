@props([
'products',
])

<section class="section home-section--featured">

    <div class="container">

        <div class="section__inner">

            <x-ui.section-header
                eyebrow="محصولات منتخب"
                title="انتخاب‌های ویژه فرزین"
                description="محصولات منتخب و پرطرفدار فرزین را با مشخصات و قیمت به‌روز مشاهده کنید."
            >
                <x-slot:action>
                    <a
                        href="{{ route('shop') }}"
                        class="btn btn--outline"
                    >
                        مشاهده همه محصولات
                    </a>
                </x-slot:action>
            </x-ui.section-header>


            <div class="section-content">

                <x-product.product-grid :columns="4">

                    @forelse($products as $product)

                        <x-product.product-card
                            :id="$product->id"
                            :name="$product->name"

                            :image="$product->primaryImage
                                ? asset('storage/' . $product->primaryImage->image)
                                : null"

                            :alt="$product->primaryImage?->alt"

                            :href="route('product.show', $product)"

                            :price="$product->price"

                            :old-price="$product->old_price"

                            :discount="$product->discount
                                ? $product->discount . '%'
                                : null"

                            :brand="$product->brand"

                            :meta="$product->short_description"

                            :rating="$product->rating"

                            :review-count="$product->review_count"

                            :badge="$product->is_featured
                                ? 'منتخب'
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
                                    <path d="M6 4h12l2 16H4L6 4Z" />
                                    <path d="M9 9h6" />
                                </svg>
                            </div>

                            <div class="empty-state__content">

                                <h2 class="empty-state__title">
                                    محصولی برای نمایش وجود ندارد
                                </h2>

                                <p class="empty-state__description">
                                    محصولات منتخب پس از ثبت در سیستم در این بخش نمایش داده می‌شوند.
                                </p>

                            </div>

                        </div>

                    @endforelse

                </x-product.product-grid>

            </div>

        </div>

    </div>

</section>
