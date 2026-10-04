@props([
'categories' => [],
'brands' => [],
'availability' => [],
])

@php
    /*
    |--------------------------------------------------------------------------
    | Normalize inputs
    |--------------------------------------------------------------------------
    */

    $categories = collect($categories);
    $brands = collect($brands);
    $availability = collect($availability);


    /*
    |--------------------------------------------------------------------------
    | Current query state
    |--------------------------------------------------------------------------
    */

    $selectedCategories = collect(
        request()->query('category', [])
    );

    $selectedBrands = collect(
        request()->query('brand', [])
    );

    $selectedAvailability = collect(
        request()->query('availability', [])
    );


    /*
    |--------------------------------------------------------------------------
    | Normalize single query values
    |--------------------------------------------------------------------------
    */

    if (
        $selectedCategories->isEmpty()
        && request()->filled('category')
    ) {
        $selectedCategories = collect([
            request()->query('category'),
        ]);
    }

    if (
        $selectedBrands->isEmpty()
        && request()->filled('brand')
    ) {
        $selectedBrands = collect([
            request()->query('brand'),
        ]);
    }

    if (
        $selectedAvailability->isEmpty()
        && request()->filled('availability')
    ) {
        $selectedAvailability = collect([
            request()->query('availability'),
        ]);
    }
@endphp


<aside
    {{ $attributes->merge([
        'class' => 'filters',
    ]) }}
    aria-label="فیلتر محصولات"
>

    {{-- =====================================================
         HEADER
    ====================================================== --}}

    <div class="filters__header">

        <h2 class="filters__title">
            فیلترها
        </h2>

        <a
            href="{{ url()->current() }}"
            class="btn btn--ghost btn--sm"
        >
            حذف فیلترها
        </a>

    </div>


    {{-- =====================================================
         CATEGORY
    ====================================================== --}}

    @if($categories->isNotEmpty())

        <section class="filters__group">

            <h3 class="filters__group-title">
                دسته‌بندی
            </h3>

            <div class="filters__options">

                @foreach($categories as $category)

                    @php
                        if (is_object($category)) {
                            $categoryValue = $category->slug
                                ?? $category->value
                                ?? null;

                            $categoryLabel = $category->name
                                ?? $category->label
                                ?? $categoryValue;
                        } elseif (is_array($category)) {
                            $categoryValue = $category['value']
                                ?? $category['slug']
                                ?? null;

                            $categoryLabel = $category['label']
                                ?? $category['name']
                                ?? $categoryValue;
                        } else {
                            $categoryValue = $category;
                            $categoryLabel = $category;
                        }
                    @endphp

                    @if(filled($categoryValue))

                        <label class="choice">

                            <input
                                type="checkbox"
                                class="choice__input"
                                name="category[]"
                                value="{{ $categoryValue }}"
                                form="shop-filters-form"

                                @checked(
                                $selectedCategories->contains(
                            (string) $categoryValue
                            )
                            )
                            >

                            <span class="choice__control"></span>

                            <span class="choice__label">
                                {{ $categoryLabel }}
                            </span>

                        </label>

                    @endif

                @endforeach

            </div>

        </section>

    @endif


    {{-- =====================================================
         BRAND
    ====================================================== --}}

    @if($brands->isNotEmpty())

        <section class="filters__group">

            <h3 class="filters__group-title">
                برند
            </h3>

            <div class="filters__options">

                @foreach($brands as $brand)

                    @php
                        if (is_object($brand)) {
                            $brandValue = $brand->slug
                                ?? $brand->value
                                ?? $brand->name
                                ?? null;

                            $brandLabel = $brand->name
                                ?? $brand->label
                                ?? $brandValue;
                        } elseif (is_array($brand)) {
                            $brandValue = $brand['value']
                                ?? $brand['slug']
                                ?? $brand['name']
                                ?? null;

                            $brandLabel = $brand['label']
                                ?? $brand['name']
                                ?? $brandValue;
                        } else {
                            $brandValue = $brand;
                            $brandLabel = $brand;
                        }
                    @endphp

                    @if(filled($brandValue))

                        <label class="choice">

                            <input
                                type="checkbox"
                                class="choice__input"
                                name="brand[]"
                                value="{{ $brandValue }}"
                                form="shop-filters-form"

                                @checked(
                                $selectedBrands->contains(
                            (string) $brandValue
                            )
                            )
                            >

                            <span class="choice__control"></span>

                            <span class="choice__label">
                                {{ $brandLabel }}
                            </span>

                        </label>

                    @endif

                @endforeach

            </div>

        </section>

    @endif


    {{-- =====================================================
         AVAILABILITY
    ====================================================== --}}

    @if($availability->isNotEmpty())

        <section class="filters__group">

            <h3 class="filters__group-title">
                وضعیت موجودی
            </h3>

            <div class="filters__options">

                @foreach($availability as $item)

                    @php
                        if (is_object($item)) {
                            $availabilityValue = $item->value
                                ?? $item->slug
                                ?? $item->id
                                ?? null;

                            $availabilityLabel = $item->label
                                ?? $item->name
                                ?? $availabilityValue;
                        } elseif (is_array($item)) {
                            $availabilityValue = $item['value']
                                ?? $item['slug']
                                ?? null;

                            $availabilityLabel = $item['label']
                                ?? $item['name']
                                ?? $availabilityValue;
                        } else {
                            $availabilityValue = $item;
                            $availabilityLabel = $item;
                        }
                    @endphp

                    @if(filled($availabilityValue))

                        <label class="choice">

                            <input
                                type="checkbox"
                                class="choice__input"
                                name="availability[]"
                                value="{{ $availabilityValue }}"
                                form="shop-filters-form"

                                @checked(
                                $selectedAvailability->contains(
                            (string) $availabilityValue
                            )
                            )
                            >

                            <span class="choice__control"></span>

                            <span class="choice__label">
                                {{ $availabilityLabel }}
                            </span>

                        </label>

                    @endif

                @endforeach

            </div>

        </section>

    @endif

</aside>

