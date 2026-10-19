@extends('layouts.storefront')
@section('title','فرزین | طراحی ماندگار برای خانه‌های امروزی')
@section('meta_description','خرید هود، سینک و محصولات مدرن آشپزخانه از فروشگاه فرزین.')
@section('content')
<section class="fz-hero"><div class="fz-container fz-hero-grid"><div class="fz-hero-copy"><span class="fz-kicker" style="color:#cbb08b">طراحی · کیفیت · جزئیات</span><h1>فضای آشپزخانه‌ات،<br><span style="color:#cbb08b">امضای انتخاب توست.</span></h1><p>محصولات کاربردی و خوش‌طراحی برای خانه‌ای که به جزئیاتش اهمیت می‌دهی. مجموعه فرزین را کشف کن.</p><div class="fz-pills"><span class="fz-pill">طراحی مدرن</span><span class="fz-pill">انتخاب دقیق</span><span class="fz-pill">خرید مطمئن</span></div><div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:26px"><a class="fz-btn" href="{{ route('shop.index') }}">ورود به فروشگاه</a><a class="fz-btn" style="background:transparent;border-color:#777" href="#featured">محصولات منتخب</a></div></div><div class="fz-hero-media">@forelse($heroProducts as $index=>$product)@if($product->primaryImage)<img data-hero-slide src="{{ asset('storage/'.$product->primaryImage->image) }}" alt="{{ $product->name }}" style="{{ $index===0?'':'display:none' }}" loading="{{ $index===0?'eager':'lazy' }}">@endif @empty<div style="position:absolute;inset:0;display:grid;place-items:center;color:#d5d0c5;font-size:44px;letter-spacing:8px">FARZIN</div>@endforelse<div style="position:absolute;z-index:2;bottom:22px;right:22px;background:#f7f5f0;color:#171715;padding:13px 16px;font-size:12px">مجموعه فرزین · انتخابی برای زندگی روزمره</div></div></div></section>
<section class="fz-section"><div class="fz-container"><div class="fz-head"><div><span class="fz-kicker">انتخاب بر اساس نیاز</span><h2 class="fz-h2">دسته‌بندی محصولات</h2><p class="fz-desc">سریع‌تر به محصول موردنظرت برس.</p></div><a href="{{ route('shop.index') }}">مشاهده فروشگاه ←</a></div><div class="fz-category-grid">@forelse($categories as $category)<a class="fz-category" href="{{ route('categories.show',$category->slug) }}"><span>{{ str_pad((string)$loop->iteration,2,'0',STR_PAD_LEFT) }} / دسته‌بندی</span><strong>{{ $category->name }}</strong><span>{{ $category->active_products_count }} محصول · مشاهده مجموعه ↗</span></a>@empty<div class="fz-empty">دسته‌بندی‌ها پس از ثبت در پنل مدیریت نمایش داده می‌شوند.</div>@endforelse</div></div></section>
@foreach ([
    'hood' => ['eyebrow' => 'PRODUCT TOUR · 360°', 'title' => 'تور ۳۶۰ درجه هودهای فرزین', 'description' => 'جزئیات طراحی هود را از زاویه‌های مختلف ببین؛ با اسکرول، حرکت ماوس یا لمس تصویر را بچرخان.'],
    'sink' => ['eyebrow' => 'PRODUCT TOUR · 360°', 'title' => 'تور ۳۶۰ درجه سینک‌های فرزین', 'description' => 'نمای محصول را قدم‌به‌قدم بررسی کن. برای بهترین نتیجه، فریم‌ها را به ترتیب زاویه آپلود کرده‌ایم.'],
] as $scene => $tourCopy)
    @php $sceneFrames = $tourFrames->get($scene, collect()); @endphp
    @if ($sceneFrames->isNotEmpty())
        <section class="fz-tour" data-product-tour data-tour-scene="{{ $scene }}"
                 style="--tour-scroll-span: {{ max(0, $sceneFrames->count() - 1) * 42 }}svh">
            <div class="fz-container fz-tour__shell">
                <div class="fz-tour__intro">
                    <span class="fz-tour__eyebrow">{{ $tourCopy['eyebrow'] }}</span>
                    <h2>{{ $tourCopy['title'] }}</h2>
                    <p>{{ $tourCopy['description'] }}</p>
                </div>

                <div class="fz-tour__stage" data-tour-stage tabindex="0"
                     role="group" aria-label="{{ $tourCopy['title'] }}. برای تغییر زاویه از اسکرول، درگ ماوس یا لمس استفاده کن.">
                    <div class="fz-tour__frames">
                        @foreach ($sceneFrames as $frameIndex => $frame)
                            <div class="fz-tour__frame {{ $loop->first ? 'is-active' : '' }}"
                                 data-tour-frame data-frame-index="{{ $frameIndex }}"
                                 aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                                <img src="{{ asset('storage/' . $frame->image) }}"
                                     alt="{{ $frame->alt ?: $tourCopy['title'] }}"
                                     draggable="false"
                                     loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                            </div>
                        @endforeach
                    </div>

                    <div class="fz-tour__shade" aria-hidden="true"></div>
                    <span class="fz-tour__tag">FARZIN · 360° VIEW</span>
                    <div class="fz-tour__caption">
                        <span>حرکت کن و جزئیات را ببین</span>
                        <strong data-tour-count>01 / {{ str_pad((string) $sceneFrames->count(), 2, '0', STR_PAD_LEFT) }}</strong>
                    </div>
                    <div class="fz-tour__controls" aria-label="کنترل زاویه محصول">
                        <button type="button" data-tour-prev aria-label="زاویه قبلی">←</button>
                        <div class="fz-tour__track" aria-hidden="true"><span data-tour-progress></span></div>
                        <button type="button" data-tour-next aria-label="زاویه بعدی">→</button>
                    </div>
                    <span class="fz-tour__hint"><span aria-hidden="true">↔</span> اسکرول · درگ · لمس</span>
                </div>

                <div class="fz-tour__footer">
                    <span>نمایش محصول از چند زاویه</span>
                    <a class="fz-btn fz-tour__shop" href="{{ route('shop.index') }}">مشاهده محصولات فرزین <span aria-hidden="true">←</span></a>
                </div>
            </div>
        </section>
    @endif
@endforeach

<section class="fz-section" id="featured" style="background:#f0ede6"><div class="fz-container"><div class="fz-head"><div><span class="fz-kicker">انتخاب سردبیر</span><h2 class="fz-h2">محصولات منتخب</h2><p class="fz-desc">چند انتخاب ویژه برای شروع.</p></div><a href="{{ route('shop.index') }}">همه محصولات ←</a></div><div class="fz-products">@forelse($featuredProducts as $product)@include('storefront.partials.product-card',['product'=>$product])@empty<div class="fz-empty" style="grid-column:1/-1">هنوز محصول ویژه‌ای ثبت نشده است.</div>@endforelse</div></div></section>
<section class="fz-section"><div class="fz-container"><div class="fz-promo"><div><span class="fz-kicker">انتخابی فراتر از ظاهر</span><h2 class="fz-h2">سادگی در طراحی، دقت در جزئیات.</h2><p class="fz-desc">محصولات را بررسی کن و با اطلاعات روشن‌تر، انتخاب بهتری داشته باش.</p></div><a class="fz-btn" href="{{ route('shop.index') }}">کشف محصولات</a></div></div></section>
<section class="fz-section"><div class="fz-container"><div class="fz-head"><div><span class="fz-kicker">تازه‌های فرزین</span><h2 class="fz-h2">تازه اضافه‌شده‌ها</h2></div><a href="{{ route('shop.index') }}">مشاهده همه ←</a></div><div class="fz-products">@forelse($latestProducts as $product)@include('storefront.partials.product-card',['product'=>$product])@empty<div class="fz-empty" style="grid-column:1/-1">محصولی برای نمایش وجود ندارد.</div>@endforelse</div></div></section>
<script>document.addEventListener('DOMContentLoaded',()=>{const s=[...document.querySelectorAll('[data-hero-slide]')];if(s.length<2)return;let i=0;setInterval(()=>{s[i].style.display='none';i=(i+1)%s.length;s[i].style.display='block'},5000)});</script>
@endsection