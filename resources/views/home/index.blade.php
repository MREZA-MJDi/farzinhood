@extends('layouts.storefront')

@section('title', 'فرزین | انتخابی فراتر از روزمرگی')
@section('meta_description', 'در فروشگاه فرزین محصولات منتخب را با تجربه‌ای ساده، زیبا و مطمئن کشف کنید.')

@section('content')
<section class="farzin-home-hero" data-home-hero aria-label="محصولات منتخب">
    <div class="farzin-home-hero__slides">
        @forelse ($heroProducts as $index => $product)
            <article class="farzin-home-hero__slide {{ $index === 0 ? 'is-active' : '' }}" data-hero-slide aria-hidden="{{ $index === 0 ? 'false' : 'true' }}">
                <div class="farzin-home-hero__copy">
                    <span class="farzin-eyebrow">FARZIN / COLLECTION 0{{ $index + 1 }}</span>
                    <p class="farzin-home-hero__overline">برای انتخاب‌های آگاهانه</p>
                    <h1>{{ $index === 0 ? 'جزئیات، تفاوت را می‌سازند.' : $product->name }}</h1>
                    <p class="farzin-home-hero__description">{{ $product->short_description ?: 'محصولات منتخب با تمرکز بر کیفیت، زیبایی و کاربرد روزمره.' }}</p>
                    <div class="farzin-home-hero__actions">
                        <a class="farzin-button farzin-button--light" href="{{ route('products.show', ['product' => $product->slug]) }}">مشاهده محصول <span aria-hidden="true">↙</span></a>
                        <a class="farzin-button farzin-button--ghost" href="{{ route('shop.index') }}">رفتن به فروشگاه</a>
                    </div>
                </div>
                <a class="farzin-home-hero__visual" href="{{ route('products.show', ['product' => $product->slug]) }}" tabindex="{{ $index === 0 ? '0' : '-1' }}" aria-label="مشاهده {{ $product->name }}">
                    @if ($product->primaryImage?->image)
                        <img src="{{ asset('storage/' . $product->primaryImage->image) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}" @if ($index > 0) loading="lazy" @endif>
                    @else
                        <span class="farzin-home-hero__placeholder">FARZIN<br><small>SELECTED OBJECTS</small></span>
                    @endif
                    <span class="farzin-home-hero__product-label"><small>محصول منتخب</small><strong>{{ $product->name }}</strong><span>{{ number_format($product->price) }} تومان</span></span>
                </a>
            </article>
        @empty
            <article class="farzin-home-hero__slide is-active" data-hero-slide aria-hidden="false">
                <div class="farzin-home-hero__copy">
                    <span class="farzin-eyebrow">FARZIN / SELECTED OBJECTS</span>
                    <p class="farzin-home-hero__overline">به دنیای فرزین خوش آمدید</p>
                    <h1>جزئیات، تفاوت را می‌سازند.</h1>
                    <p class="farzin-home-hero__description">محصولات فروشگاه را ببینید و انتخاب بعدی خود را پیدا کنید.</p>
                    <div class="farzin-home-hero__actions"><a class="farzin-button farzin-button--light" href="{{ route('shop.index') }}">ورود به فروشگاه <span aria-hidden="true">↙</span></a></div>
                </div>
                <div class="farzin-home-hero__visual farzin-home-hero__visual--empty"><span class="farzin-home-hero__placeholder">FARZIN<br><small>SELECTED OBJECTS</small></span></div>
            </article>
        @endforelse
    </div>
    @if ($heroProducts->count() > 1)
        <div class="farzin-home-hero__controls">
            <div class="farzin-home-hero__counter"><span data-hero-current>01</span><span class="farzin-home-hero__counter-divider"></span><span>{{ str_pad((string) $heroProducts->count(), 2, '0', STR_PAD_LEFT) }}</span></div>
            <div class="farzin-home-hero__dots" role="tablist" aria-label="انتخاب اسلاید">
                @foreach ($heroProducts as $index => $product)
                    <button type="button" data-hero-dot class="{{ $index === 0 ? 'is-active' : '' }}" role="tab" aria-label="اسلاید {{ $index + 1 }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
            <div class="farzin-home-hero__arrows"><button type="button" data-hero-prev aria-label="اسلاید قبلی">→</button><button type="button" data-hero-next aria-label="اسلاید بعدی">←</button></div>
        </div>
        <div class="farzin-home-hero__progress"><span></span></div>
    @endif
</section>

<section class="store-trust-strip" data-home-trust-strip aria-label="مزیت‌های خرید از فرزین">
    <div><span aria-hidden="true">✳</span><strong>انتخاب‌های گزیده</strong><small>تمرکز بر کیفیت و کاربرد</small></div>
    <div><span aria-hidden="true">◇</span><strong>اطلاعات شفاف</strong><small>قیمت و موجودی مشخص</small></div>
    <div><span aria-hidden="true">↗</span><strong>خرید قابل پیگیری</strong><small>از طریق حساب کاربری</small></div>
    <div><span aria-hidden="true">◌</span><strong>پشتیبانی فرزین</strong><small>راه ارتباطی روشن</small></div>
</section>

<section class="store-section store-section--categories">
    <div class="store-section__heading"><div><span class="farzin-eyebrow">EXPLORE BY CATEGORY</span><h2>از کجا شروع کنیم؟</h2><p>دسته‌بندی‌ها را مرور کنید و سریع‌تر به انتخاب خود برسید.</p></div><a class="store-text-link" href="{{ route('shop.index') }}">همه محصولات <span aria-hidden="true">←</span></a></div>
    <div class="store-category-grid">
        @forelse ($categories as $category)
            <a class="store-category-tile" href="{{ route('categories.show', ['category' => $category->slug]) }}">
                @if ($category->image)<img src="{{ asset('storage/' . $category->image) }}" alt="" loading="lazy">@endif
                <span class="store-category-tile__shade"></span><span class="store-category-tile__content"><small>دسته‌بندی</small><strong>{{ $category->name }}</strong><span>مشاهده محصولات ↙</span></span>
            </a>
        @empty
            <div class="store-empty"><strong>به‌زودی دسته‌بندی‌ها اینجا نمایش داده می‌شوند.</strong><a href="{{ route('shop.index') }}">مشاهده فروشگاه</a></div>
        @endforelse
    </div>
</section>

<section class="store-section">
    <div class="store-section__heading"><div><span class="farzin-eyebrow">CURATED FOR YOU</span><h2>انتخاب‌های ویژه</h2><p>محصولاتی که برای دیده‌شدن انتخاب شده‌اند.</p></div><a class="store-text-link" href="{{ route('shop.index', ['sort' => 'popular']) }}">دیدن همه <span aria-hidden="true">←</span></a></div>
    <div class="store-product-grid">
        @forelse ($featuredProducts as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="store-empty"><strong>هنوز محصول ویژه‌ای انتخاب نشده است.</strong><a href="{{ route('shop.index') }}">دیدن همه محصولات</a></div>
        @endforelse
    </div>
</section>

<section class="store-editorial">
    <div><span class="farzin-eyebrow">THE FARZIN POINT OF VIEW</span><h2>کمتر، اما<br>دقیق‌تر انتخاب کن.</h2><p>در فرزین، تمرکز روی محصولاتی است که به‌خاطر کیفیت و کارایی‌شان ارزش انتخاب دارند.</p><a class="farzin-button farzin-button--dark" href="{{ route('shop.index') }}">کشف مجموعه <span aria-hidden="true">↙</span></a></div>
    <div class="store-editorial__mark" aria-hidden="true"><span>ف</span><small>QUALITY OVER QUANTITY</small></div>
</section>

<section class="store-section store-section--latest">
    <div class="store-section__heading"><div><span class="farzin-eyebrow">JUST ARRIVED</span><h2>تازه‌های فروشگاه</h2><p>جدیدترین محصولات اضافه‌شده به فرزین.</p></div><a class="store-text-link" href="{{ route('shop.index', ['sort' => 'latest']) }}">همه تازه‌ها <span aria-hidden="true">←</span></a></div>
    <div class="store-product-grid">
        @forelse ($latestProducts as $product)
            <x-storefront.product-card :product="$product" />
        @empty
            <div class="store-empty"><strong>هنوز محصولی برای نمایش وجود ندارد.</strong><a href="{{ route('shop.index') }}">رفتن به فروشگاه</a></div>
        @endforelse
    </div>
</section>

<section class="store-section store-section--blog" aria-labelledby="home-blog-heading">
    <div class="store-section__heading">
        <div>
            <span class="farzin-eyebrow">FROM THE FARZIN JOURNAL</span>
            <h2 id="home-blog-heading">راهنمای انتخاب بهتر</h2>
            <p>محتوا و راهنمایی‌های کاربردی از مجله فرزین.</p>
        </div>
        <a class="store-text-link" href="{{ route('blog.index') }}">مشاهده همه مطالب <span aria-hidden="true">←</span></a>
    </div>
    <div class="store-blog-grid">
        @forelse ($blogPosts as $post)
            <article class="store-blog-card" data-gsap-reveal>
                <a class="store-blog-card__media" href="{{ route('blog.show', ['post' => $post->slug]) }}" aria-label="مطالعه {{ $post->title }}">
                    @if ($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" decoding="async">
                    @else
                        <span class="store-blog-card__placeholder" aria-hidden="true">F / JOURNAL</span>
                    @endif
                </a>
                <div class="store-blog-card__body">
                    <div class="store-blog-card__meta">
                        <span>{{ $post->category?->name ?: 'مجله فرزین' }}</span>
                        @if ($post->published_at)<time datetime="{{ $post->published_at->toIso8601String() }}">{{ $post->published_at->locale('fa')->translatedFormat('j F Y') }}</time>@endif
                    </div>
                    <h3><a href="{{ route('blog.show', ['post' => $post->slug]) }}">{{ $post->title }}</a></h3>
                    @if ($post->excerpt)<p>{{ $post->excerpt }}</p>@endif
                    <a class="store-blog-card__read" href="{{ route('blog.show', ['post' => $post->slug]) }}">مطالعه مطلب <span aria-hidden="true">←</span></a>
                </div>
            </article>
        @empty
            <div class="store-empty">
                <strong>مطالب تازه به‌زودی در مجله فرزین منتشر می‌شوند.</strong>
                <span>برای دیدن محصولات، به فروشگاه سر بزن.</span>
                <a href="{{ route('shop.index') }}">رفتن به فروشگاه</a>
            </div>
        @endforelse
    </div>
</section>

@foreach ([
    'hood' => ['eyebrow' => 'PRODUCT TOUR · 360°', 'title' => 'تور ۳۶۰ درجه هودهای فرزین', 'description' => 'جزئیات طراحی هود را از زاویه‌های مختلف ببین؛ با اسکرول، حرکت ماوس یا لمس تصویر را بچرخان.'],
    'sink' => ['eyebrow' => 'PRODUCT TOUR · 360°', 'title' => 'تور ۳۶۰ درجه سینک‌های فرزین', 'description' => 'نمای محصول را قدم‌به‌قدم بررسی کن. پس از آپلود فریم‌ها به ترتیب زاویه، حرکت تصویر با اسکرول و لمس فعال می‌شود.'],
] as $scene => $tourCopy)
    @php $sceneFrames = $tourFrames->get($scene, collect()); @endphp
    @if ($sceneFrames->isNotEmpty())
        <section class="fz-tour" data-product-tour data-tour-scene="{{ $scene }}"
                 style="--tour-scroll-span: {{ max(0, $sceneFrames->count() - 1) * 15 }}svh">
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
@endsection