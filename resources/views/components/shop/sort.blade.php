@props([
'current' => '',
'options' => [
'newest' => 'جدیدترین',
'popular' => 'محبوب‌ترین',
'price_asc' => 'ارزان‌ترین',
'price_desc' => 'گران‌ترین',
],
])

<div class="form-field">

    <label
        for="sort"
        class="sr-only"
    >
        مرتب‌سازی
    </label>

    <select
        id="sort"
        name="sort"
        class="form-select"
        form="shop-filters-form"
    >
        @foreach($options as $value => $label)
            <option
                value="{{ $value }}"
                @selected($current === $value)
            >
                {{ $label }}
            </option>
        @endforeach
    </select>

</div>
