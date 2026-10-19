@extends('layouts.storefront')
@section('title', $product->meta_title ?: $product->name.' | فرزین')
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('content')
<div class="fz-container">
    <div class="fz-breadcrumb"><a href="{{ route('home') }}">خانه</a> / <a href="{{ route('shop.index') }}">فروشگاه</a> / {{ $product->name }}</div>
    <section class="fz-detail">
        <div>
            <div class="fz-detail-img" style="overflow:hidden">
                @if($product->primaryImage)
                    <img id="fz-main-product-image" src="{{ asset('storage/'.$product->primaryImage->image) }}" alt="{{ $product->primaryImage->alt ?: $product->name }}">
                @elseif($product->images->isNotEmpty())
                    <img id="fz-main-product-image" src="{{ asset('storage/'.$product->images->first()->image) }}" alt="{{ $product->images->first()->alt ?: $product->name }}">
                @else
                    <div style="height:100%;display:grid;place-items:center;color:#aaa">FARZIN</div>
                @endif
            </div>
            @if($product->images->count() > 1)
                <div aria-label="تصاویر محصول" style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px">
                    @foreach($product->images as $image)
                        <button type="button" data-product-thumb data-image="{{ asset('storage/'.$image->image) }}" data-alt="{{ $image->alt ?: $product->name }}" aria-label="نمایش تصویر {{ $loop->iteration }}" style="width:72px;height:72px;padding:3px;border:1px solid var(--line);background:#fff;cursor:pointer">
                            <img src="{{ asset('storage/'.$image->image) }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
        <div>
            <span class="fz-kicker">{{ $product->brand ?: ($product->category->name ?? 'محصول فرزین') }}</span>
            <h1>{{ $product->name }}</h1>
            @if($product->sku)<p>کد محصول: {{ $product->sku }}</p>@endif
            @if($product->short_description)<p>{{ $product->short_description }}</p>@endif
            <p aria-label="امتیاز محصول" style="color:#946f36">★ {{ number_format((float)$reviewAverage, 1) }} <span style="color:var(--muted)">({{ $reviewCount }} دیدگاه تأییدشده)</span></p>
            <div class="fz-price" style="font-size:24px;margin:22px 0">
                {{ number_format($product->price) }} تومان
                @if($product->old_price && $product->old_price > $product->price)<span class="fz-old">{{ number_format($product->old_price) }}</span>@endif
            </div>
            <p class="fz-stock">{{ $product->stock > 0 ? 'موجود در انبار' : 'در حال حاضر ناموجود' }}</p>
            @if($product->stock > 0)
                @auth
                    @if(auth()->user()->isCustomer())
                        <form method="POST" action="{{ route('customer.cart.add') }}" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;margin-top:22px">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <label for="quantity">تعداد</label>
                            <input id="quantity" class="fz-input" style="width:80px" type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" required>
                            <button class="fz-btn" type="submit">افزودن به سبد خرید</button>
                        </form>
                    @else
                        <a class="fz-btn" href="{{ route('login') }}">ورود برای خرید</a>
                    @endif
                @else
                    <a class="fz-btn" href="{{ route('login') }}">ورود برای خرید</a>
                @endauth
            @endif
            @auth
                @if(auth()->user()->isCustomer())
                    <form method="POST" action="{{ route('customer.wishlist.toggle', $product->id) }}" style="margin-top:12px">
                        @csrf
                        <button type="submit" class="fz-btn light">{{ $isWishlisted ? 'حذف از علاقه‌مندی‌ها ♡' : 'افزودن به علاقه‌مندی‌ها ♡' }}</button>
                    </form>
                @endif
            @else
                <p style="font-size:12px"><a href="{{ route('login') }}">برای ذخیره در علاقه‌مندی‌ها وارد حساب شوید.</a></p>
            @endauth
            @if($product->description)
                <div style="border-top:1px solid var(--line);margin-top:28px;padding-top:20px;line-height:2">{!! nl2br(e($product->description)) !!}</div>
            @endif
        </div>
    </section>

    <section class="fz-section" style="border-top:1px solid var(--line)">
        <div class="fz-head"><div><span class="fz-kicker">نظر خریداران</span><h2 class="fz-h2">دیدگاه‌ها و امتیازها</h2></div></div>
        @if($product->reviews->isNotEmpty())
            <div style="display:grid;gap:12px">
                @foreach($product->reviews as $review)
                    <article style="padding:20px;background:#fff;border:1px solid var(--line)">
                        <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap">
                            <strong>{{ $review->user?->name ?: 'خریدار فرزین' }}</strong>
                            <span style="color:#946f36" aria-label="امتیاز {{ $review->rating }} از ۵">{{ str_repeat('★', (int)$review->rating) }}{{ str_repeat('☆', 5-(int)$review->rating) }}</span>
                        </div>
                        @if($review->title)<h3 style="font-size:15px">{{ $review->title }}</h3>@endif
                        <p class="fz-desc">{{ $review->body }}</p>
                    </article>
                @endforeach
            </div>
        @else
            <p class="fz-empty">هنوز دیدگاه تأییدشده‌ای برای این محصول ثبت نشده است.</p>
        @endif
        @if($canReview)
            <form method="POST" action="{{ route('customer.reviews.store') }}" style="margin-top:22px;padding:22px;background:#fff;border:1px solid var(--line);display:grid;gap:12px">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <h3 style="margin:0">تجربه‌ات را با دیگران به اشتراک بگذار</h3>
                <label for="review-rating">امتیاز از ۱ تا ۵</label>
                <select id="review-rating" class="fz-select" name="rating" required>
                    <option value="5">۵ - عالی</option><option value="4">۴ - خوب</option><option value="3">۳ - متوسط</option><option value="2">۲ - ضعیف</option><option value="1">۱ - خیلی ضعیف</option>
                </select>
                <label for="review-title">عنوان (اختیاری)</label>
                <input id="review-title" class="fz-input" name="title" maxlength="255">
                <label for="review-body">متن دیدگاه</label>
                <textarea id="review-body" class="fz-input" name="body" rows="4" minlength="5" maxlength="5000" required></textarea>
                <button class="fz-btn" type="submit">ارسال دیدگاه برای بررسی</button>
            </form>
        @endif
    </section>

    @if($relatedProducts->isNotEmpty())
        <section class="fz-section" style="border-top:1px solid var(--line)">
            <div class="fz-head"><h2 class="fz-h2">محصولات مرتبط</h2></div>
            <div class="fz-products">@foreach($relatedProducts as $relatedProduct)@include('storefront.partials.product-card',['product'=>$relatedProduct])@endforeach</div>
        </section>
    @endif
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const mainImage = document.getElementById('fz-main-product-image');
    if (!mainImage) return;
    document.querySelectorAll('[data-product-thumb]').forEach((button) => {
        button.addEventListener('click', () => {
            mainImage.src = button.dataset.image;
            mainImage.alt = button.dataset.alt || '';
            document.querySelectorAll('[data-product-thumb]').forEach((item) => item.style.borderColor = 'var(--line)');
            button.style.borderColor = 'var(--accent)';
        });
    });
});
</script>
@endsection