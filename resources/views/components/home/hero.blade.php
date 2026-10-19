@props(['heroProducts' => collect()])
@php
    $heroSlides = $heroProducts->take(4);
    if ($heroSlides->isEmpty()) $heroSlides = collect([null]);
@endphp
<section class="farzin-hero" data-farzin-hero aria-label="معرفی محصولات فرزین">
<div class="farzin-hero__slides">
@foreach($heroSlides as $product)
@php
    $image = $product?->primaryImage?->image;
    $imageUrl = filled($image) ? (str_starts_with($image, 'http://') || str_starts_with($image, 'https://') || str_starts_with($image, '/') ? $image : asset('storage/' . ltrim($image, '/'))) : asset('images/home/hero.webp');
@endphp
<article class="farzin-hero__slide {{ $loop->first ? 'is-active' : '' }}" data-hero-slide aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
<div class="farzin-hero__media"><img src="{{ $imageUrl }}" alt="{{ $product?->primaryImage?->alt ?? $product?->name ?? 'فضای مدرن فرزین' }}" width="1800" height="1200" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif decoding="async"></div>
<div class="farzin-hero__shade"></div><div class="farzin-hero__frame" aria-hidden="true"><span></span><span></span><span></span><span></span></div>
<div class="farzin-hero__content">
<p class="farzin-hero__eyebrow"><span class="farzin-hero__eyebrow-line"></span> FARZIN / HOME ESSENTIALS</p>
<h1 class="farzin-hero__title">خانه‌ی امروز،<br><span>انتخابی ماندگار.</span></h1>
<p class="farzin-hero__description">{{ $product?->short_description ?: 'طراحی سنجیده، کیفیت قابل اتکا و جزئیاتی که تجربه‌ی خانه را بهتر می‌کنند.' }}</p>
<div class="farzin-hero__actions"><a class="farzin-hero__button farzin-hero__button--light" href="{{ $product ? route('products.show', ['product' => $product->slug]) : route('shop.index') }}">کشف محصول <span aria-hidden="true">↗</span></a><a class="farzin-hero__button farzin-hero__button--ghost" href="{{ route('shop.index') }}">مشاهده فروشگاه</a></div>
<div class="farzin-hero__caption"><span>منتخب فرزین</span><strong>{{ $product?->name ?? 'سادگی در جزئیات' }}</strong></div>
</div>
<span class="farzin-hero__index" aria-hidden="true">{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }} <i></i> {{ str_pad((string)$heroSlides->count(),2,'0',STR_PAD_LEFT) }}</span>
</article>
@endforeach
</div>
<div class="farzin-hero__rail"><span class="farzin-hero__rail-label">FORM / FUNCTION / FEELING</span><div class="farzin-hero__progress" aria-hidden="true"><span data-hero-progress></span></div>
@if($heroSlides->count() > 1)
<div class="farzin-hero__controls" aria-label="کنترل اسلایدها"><button type="button" data-hero-prev aria-label="اسلاید قبلی">←</button><div class="farzin-hero__dots" role="tablist" aria-label="انتخاب اسلاید">@foreach($heroSlides as $product)<button type="button" data-hero-dot role="tab" aria-label="اسلاید {{ $loop->iteration }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}" class="{{ $loop->first ? 'is-active' : '' }}"></button>@endforeach</div><button type="button" data-hero-next aria-label="اسلاید بعدی">→</button></div>
@endif
<a class="farzin-hero__scroll" href="#home-categories"><span>اسکرول برای کشف</span><i aria-hidden="true"></i></a>
</div>
</section>
