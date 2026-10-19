@extends('layouts.app')
@section('title', 'فروشگاه | Farzin')
@section('meta_description','محصولات فعال فرزین را جستجو و بر اساس دسته‌بندی، قیمت و ترتیب دلخواه فیلتر کنید.')
@section('content')
<section class="farzin-catalog"><div class="farzin-container">
<header class="farzin-page-head"><p class="farzin-kicker">FARZIN / CATALOG</p><h1>فروشگاه <em>فرزین</em></h1><p>ساده انتخاب کن؛ با اطمینان خرید کن.</p></header>
<form class="farzin-filters" action="{{ route('shop.index') }}" method="GET">
<label class="farzin-field farzin-field--search"><span>جستجوی محصول</span><input type="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="نام محصول، برند یا کد کالا..." maxlength="255"></label>
<label class="farzin-field"><span>دسته‌بندی</span><select name="category"><option value="">همه دسته‌ها</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>@endforeach</select></label>
<label class="farzin-field"><span>مرتب‌سازی</span><select name="sort"><option value="latest" @selected(($filters['sort'] ?? 'latest') === 'latest')>جدیدترین</option><option value="popular" @selected(($filters['sort'] ?? '') === 'popular')>محبوب‌ترین</option><option value="rating" @selected(($filters['sort'] ?? '') === 'rating')>بالاترین امتیاز</option><option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>ارزان‌ترین</option><option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>گران‌ترین</option></select></label>
<label class="farzin-field"><span>حداقل قیمت (تومان)</span><input type="number" name="min_price" min="0" inputmode="numeric" value="{{ $filters['min_price'] ?? '' }}" placeholder="{{ $priceMin }}"></label><label class="farzin-field"><span>حداکثر قیمت (تومان)</span><input type="number" name="max_price" min="0" inputmode="numeric" value="{{ $filters['max_price'] ?? '' }}" placeholder="{{ $priceMax }}"></label>
<input type="hidden" name="per_page" value="{{ $filters['per_page'] ?? 12 }}"><div class="farzin-filters__actions"><button type="submit" class="farzin-button">اعمال فیلتر <span aria-hidden="true">↗</span></button><a href="{{ route('shop.index') }}" class="farzin-reset">حذف فیلترها</a></div>
</form>
<div class="farzin-catalog__result"><span>{{ \App\Support\JalaliCalendar::number($products->total()) }} محصول</span>@if(request()->filled('search'))<span>نتیجه برای «{{ request('search') }}»</span>@endif</div>
@if($products->isNotEmpty())<div class="farzin-product-grid">@foreach($products as $product)
<x-product.product-card :id="$product->id" :name="$product->name" :image="$product->primaryImage?->image" :href="route('products.show',$product)" :price="$product->price" :old-price="$product->old_price" :discount="$product->discount" :brand="$product->brand" :meta="$product->short_description" :rating="$product->rating" :review-count="$product->review_count" :alt="$product->primaryImage?->alt" :is-wishlisted="$product->is_wishlisted ?? false" />
@endforeach</div><nav class="farzin-pagination" aria-label="صفحه‌بندی">{{ $products->links() }}</nav>
@else<div class="farzin-empty farzin-empty--large"><span class="farzin-empty__mark">F.</span><h2>محصولی پیدا نشد</h2><p>فیلترها را تغییر بده یا دوباره همه‌ی محصولات را ببین.</p><a href="{{ route('shop.index') }}" class="farzin-button">نمایش همه محصولات</a></div>@endif
</div></section>
@endsection
