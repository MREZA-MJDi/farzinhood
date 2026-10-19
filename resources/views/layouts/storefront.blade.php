<!doctype html>
<html lang="fa" dir="rtl"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}"><title>@yield('title','فرزین | انتخابی برای خانه شما')</title><meta name="description" content="@yield('meta_description','فروشگاه فرزین؛ انتخابی دقیق از محصولات مدرن آشپزخانه.')">@vite(['resources/css/app.css','resources/js/app.js'])
<style>
:root{--ink:#171715;--charcoal:#242421;--pearl:#f7f5f0;--muted:#77766f;--line:#e4e0d7;--accent:#b99a70}body{margin:0;background:var(--pearl);color:var(--ink);font-family:Vazirmatn,Tahoma,sans-serif}a{color:inherit;text-decoration:none}img{display:block;max-width:100%}.fz-container{width:min(1200px,calc(100% - 40px));margin:auto}.fz-top{background:var(--ink);color:#eee9df;text-align:center;padding:9px;font-size:12px}.fz-header{position:sticky;top:0;z-index:20;background:#f7f5f0ed;backdrop-filter:blur(16px);border-bottom:1px solid var(--line)}.fz-nav{min-height:76px;display:flex;align-items:center;justify-content:space-between;gap:22px}.fz-logo{font-size:24px;font-weight:900;letter-spacing:3px}.fz-links,.fz-actions{display:flex;align-items:center;gap:22px;font-size:13px}.fz-btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 20px;background:var(--ink);color:white;border:1px solid var(--ink);border-radius:3px;font:inherit;cursor:pointer}.fz-btn.light{background:transparent;color:var(--ink)}.fz-section{padding:64px 0}.fz-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:26px}.fz-kicker{font-size:12px;color:var(--muted);letter-spacing:1px}.fz-h2{font-size:clamp(24px,3vw,38px);margin:8px 0}.fz-desc{color:var(--muted);line-height:1.9}.fz-hero{background:var(--charcoal);color:#f8f5ef;overflow:hidden}.fz-hero-grid{display:grid;grid-template-columns:1fr 1fr;align-items:center;min-height:520px}.fz-hero-copy{padding:45px 0 45px 6%}.fz-hero h1{font-size:clamp(38px,5vw,66px);line-height:1.4;margin:16px 0}.fz-hero p{max-width:460px;color:#d2cfc7;line-height:2}.fz-hero-media{height:100%;min-height:420px;position:relative;background:linear-gradient(135deg,#3b3b36,#171715)}.fz-hero-media img{width:100%;height:100%;position:absolute;inset:0;object-fit:cover;opacity:.82}.fz-hero-media:after{content:"";position:absolute;inset:0;background:linear-gradient(90deg,var(--charcoal),transparent 45%)}.fz-pills{display:flex;gap:9px;flex-wrap:wrap;margin-top:24px}.fz-pill{border:1px solid #65645d;border-radius:99px;padding:8px 13px;font-size:12px;color:#dedbd3}.fz-category-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}.fz-category{min-height:145px;padding:24px;background:#eeebe4;border:1px solid var(--line);display:flex;flex-direction:column;justify-content:space-between;transition:.2s}.fz-category:hover{transform:translateY(-3px);background:#e9e4da}.fz-category strong{font-size:21px}.fz-category span{font-size:12px;color:var(--muted)}.fz-products{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}.fz-product{background:#fff;border:1px solid var(--line);min-width:0}.fz-product-img{aspect-ratio:1;background:#efede8;overflow:hidden}.fz-product-img img{width:100%;height:100%;object-fit:cover;transition:transform .35s}.fz-product:hover img{transform:scale(1.04)}.fz-product-body{padding:15px}.fz-brand{font-size:11px;color:var(--muted)}.fz-product h3{font-size:14px;line-height:1.8;margin:8px 0;min-height:48px}.fz-price{font-weight:800}.fz-old{font-size:12px;color:#888;text-decoration:line-through;margin-right:8px;font-weight:400}.fz-product-link{display:block;border-top:1px solid var(--line);padding-top:12px;margin-top:12px;font-size:12px}.fz-promo{background:#e9e4da;padding:38px;display:flex;align-items:center;justify-content:space-between;gap:24px}.fz-footer{background:var(--ink);color:#e8e4dc;padding:44px 0 20px}.fz-footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr;gap:30px}.fz-footer p,.fz-footer a{color:#bdb9b0;font-size:13px;line-height:2}.fz-footer h3{font-size:14px}.fz-copy{border-top:1px solid #45443e;margin-top:28px;padding-top:16px;color:#9e9a91;font-size:11px}.fz-breadcrumb{padding:22px 0;color:var(--muted);font-size:12px}.fz-catalog-head{padding:28px 0 12px}.fz-filterbar{display:flex;gap:10px;flex-wrap:wrap;margin:20px 0}.fz-input,.fz-select{min-height:42px;padding:0 12px;border:1px solid var(--line);background:white;font:inherit;max-width:100%}.fz-pagination{display:flex;justify-content:center;gap:8px;margin:28px 0}.fz-pagination a,.fz-pagination span{padding:8px 13px;border:1px solid var(--line);background:white}.fz-detail{display:grid;grid-template-columns:1fr 1fr;gap:42px;padding:24px 0 56px}.fz-detail-img{background:#eeebe4;aspect-ratio:1}.fz-detail-img img{width:100%;height:100%;object-fit:contain}.fz-detail h1{font-size:clamp(26px,3vw,42px);line-height:1.5}.fz-detail p{line-height:2;color:var(--muted)}.fz-stock{font-size:13px;color:#4d7653}.fz-empty{padding:40px;text-align:center;background:white;color:var(--muted)}@media(max-width:800px){.fz-links{gap:12px}.fz-hero-grid{grid-template-columns:1fr}.fz-hero-copy{padding:40px 0}.fz-hero-media{min-height:290px;grid-row:1}.fz-hero-media:after{background:linear-gradient(0deg,var(--charcoal),transparent 60%)}.fz-products{grid-template-columns:repeat(2,minmax(0,1fr));gap:11px}.fz-category-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.fz-footer-grid{grid-template-columns:1fr 1fr}.fz-promo{align-items:flex-start;flex-direction:column}.fz-detail{grid-template-columns:1fr;gap:20px}.fz-section{padding:44px 0}}@media(max-width:520px){.fz-container{width:calc(100% - 28px)}.fz-links{display:flex;gap:10px;overflow-x:auto;white-space:nowrap;max-width:42vw}.fz-links a{font-size:11px}.fz-actions>a:not(.fz-btn){font-size:11px}.fz-actions{gap:9px}.fz-actions .fz-btn{padding:0 10px;min-height:38px}.fz-logo{font-size:20px}.fz-head{align-items:flex-start;flex-direction:column}.fz-category{min-height:125px;padding:16px}.fz-category strong{font-size:17px}.fz-footer-grid{grid-template-columns:1fr}.fz-hero h1{font-size:38px}.fz-product-body{padding:11px}.fz-product h3{font-size:13px}}


/* Scroll-aware storefront navigation */
.fz-header{transition:transform .32s cubic-bezier(.22,1,.36,1),background-color .25s ease;will-change:transform}
.fz-header.is-scroll-hidden{transform:translateY(calc(-100% - 2px));pointer-events:none}
.fz-header:focus-within{transform:translateY(0);pointer-events:auto}
.fz-header{background:rgba(247,245,240,.82)!important;backdrop-filter:blur(18px) saturate(1.25);-webkit-backdrop-filter:blur(18px) saturate(1.25)}

/* Homepage 360-degree product tours */
.fz-tour{position:relative;padding:58px 0 66px;background:#22221f;color:#f7f3eb;isolation:isolate}
.fz-tour:before{content:"";position:absolute;z-index:-1;inset:0;background:radial-gradient(ellipse at 78% 20%,rgba(185,154,112,.12),transparent 42%),linear-gradient(130deg,#252521,#191917)}
.fz-tour__shell{min-height:calc(92svh + var(--tour-scroll-span,0svh));display:flex;flex-direction:column}
.fz-tour__intro{max-width:720px;margin:0 0 22px}
.fz-tour__eyebrow{color:#d0b28c;font-size:11px;font-weight:800;letter-spacing:.14em}
.fz-tour__intro h2{margin:9px 0 8px;font-size:clamp(25px,3.5vw,44px);line-height:1.45;letter-spacing:-.025em}
.fz-tour__intro p{max-width:650px;margin:0;color:#c2beb5;font-size:13px;line-height:2}
.fz-tour__stage{position:sticky;top:88px;z-index:1;height:min(68svh,660px);min-height:350px;overflow:hidden;border:1px solid rgba(238,226,207,.2);border-radius:5px;background:#34322d;box-shadow:0 24px 80px rgba(0,0,0,.25);cursor:ew-resize;touch-action:pan-y;user-select:none;-webkit-user-select:none}
.fz-tour__stage:focus-visible{outline:2px solid #d0b28c;outline-offset:4px}
.fz-tour__frames,.fz-tour__frame{position:absolute;inset:0}
.fz-tour__frame{opacity:0;visibility:hidden;transition:opacity .16s linear}
.fz-tour__frame.is-active{opacity:1;visibility:visible}
.fz-tour__frame img{width:100%;height:100%;object-fit:contain;object-position:center;background:linear-gradient(135deg,#33312c,#171715);pointer-events:none}
.fz-tour__shade{position:absolute;inset:0;pointer-events:none;background:linear-gradient(90deg,rgba(17,17,15,.48),transparent 40%,transparent 70%,rgba(17,17,15,.16)),linear-gradient(0deg,rgba(15,15,13,.72),transparent 30%,rgba(15,15,13,.12))}
.fz-tour__tag{position:absolute;top:20px;left:22px;color:#e1ceb2;font-size:10px;font-weight:800;letter-spacing:.14em;direction:ltr}
.fz-tour__caption{position:absolute;right:22px;bottom:62px;display:flex;align-items:center;gap:15px;flex-wrap:wrap;color:#f7f3eb;font-size:12px}
.fz-tour__caption strong{font-size:11px;letter-spacing:.12em;direction:ltr;color:#d0b28c}
.fz-tour__controls{position:absolute;right:20px;bottom:17px;left:20px;display:flex;align-items:center;gap:13px;max-width:360px}
.fz-tour__controls button{display:grid;width:36px;height:36px;flex:0 0 36px;place-items:center;border:1px solid rgba(255,255,255,.38);border-radius:50%;background:rgba(18,18,16,.28);color:white;font-size:17px;transition:background .2s,border-color .2s}
.fz-tour__controls button:hover{border-color:#d0b28c;background:rgba(185,154,112,.2)}
.fz-tour__track{height:2px;flex:1;overflow:hidden;background:rgba(255,255,255,.26)}
.fz-tour__track span{display:block;width:100%;height:100%;background:#d0b28c;transform:scaleX(0);transform-origin:left;transition:transform .12s linear}
.fz-tour__hint{position:absolute;left:22px;bottom:25px;color:#e7e0d4;font-size:10px;opacity:.8}
.fz-tour__hint span{margin-right:5px;font-size:16px}
.fz-tour__footer{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-top:22px;color:#c2beb5;font-size:12px}
.fz-tour__shop{gap:12px;background:#d0b28c!important;border-color:#d0b28c!important;color:#201f1c!important}
.fz-tour__shop:hover{background:#e1c8a5!important}
.fz-tour__stage.is-dragging{cursor:grabbing}
.fz-footer{position:relative}
@media(max-width:760px){
 .fz-tour{padding:38px 0 44px}
 .fz-tour__shell{min-height:calc(88svh + var(--tour-scroll-span,0svh))}
 .fz-tour__intro{margin-bottom:16px}
 .fz-tour__intro h2{font-size:26px}
 .fz-tour__intro p{font-size:12px}
 .fz-tour__stage{top:72px;height:62svh;min-height:300px;border-radius:4px}
 .fz-tour__tag{top:12px;left:12px;font-size:9px}
 .fz-tour__caption{right:13px;bottom:60px;gap:9px;font-size:10px}
 .fz-tour__controls{right:12px;bottom:12px;left:12px;gap:8px}
 .fz-tour__hint{left:13px;bottom:24px;font-size:9px}
 .fz-tour__footer{align-items:flex-start;flex-direction:column}
}
@media(prefers-reduced-motion:reduce){
 .fz-header,.fz-tour__frame,.fz-tour__controls button,.fz-tour__track span{transition:none!important}
}

</style></head><body><div class="fz-top">طراحی ماندگار برای خانه‌های امروزی · تجربه خریدی ساده و مطمئن</div><header class="fz-header"><div class="fz-container fz-nav"><a class="fz-logo" href="{{ route('home') }}">FARZIN<span style="color:var(--accent)">.</span></a><nav class="fz-links" aria-label="ناوبری اصلی"><a href="{{ route('home') }}">خانه</a><a href="{{ route('shop.index') }}">فروشگاه</a><a href="{{ route('blog.index') }}">مجله</a><a href="{{ route('contact.index') }}">تماس</a></nav><div class="fz-actions">
@auth
    @if(auth()->user()->isCustomer())
        <a href="{{ route('customer.cart.index') }}" aria-label="سبد خرید">سبد خرید</a>
        <a href="{{ route('customer.wishlist.index') }}">علاقه‌مندی‌ها</a>
        <a href="{{ route('customer.dashboard') }}">حساب من</a>
    @elseif(auth()->user()->isAdmin())
        <a href="{{ route('admin.dashboard') }}">پنل مدیریت</a>
    @else
        <a href="{{ route('home') }}">حساب کاربری</a>
    @endif
@else
    <a href="{{ route('login') }}">ورود</a>
    <a href="{{ route('register') }}">ثبت‌نام</a>
@endauth
<a class="fz-btn" href="{{ route('shop.index') }}">مشاهده محصولات</a>
</div></div></header>@if(session('success'))<div class="fz-container" style="padding-top:14px;color:#32613c">{{ session('success') }}</div>@endif@if($errors->any())<div class="fz-container" style="padding-top:14px;color:#9a3434">{{ $errors->first() }}</div>@endif<main>@yield('content')</main><footer class="fz-footer"><div class="fz-container"><div class="fz-footer-grid"><div><a class="fz-logo" href="{{ route('home') }}">FARZIN.</a><p>انتخابی دقیق برای آشپزخانه‌ای زیبا و کاربردی؛ طراحی ساده، اطلاعات روشن و خرید مطمئن.</p></div><div><h3>خرید</h3><a href="{{ route('shop.index') }}">همه محصولات</a><br><a href="{{ route('shop.index') }}">محصولات جدید</a></div><div><h3>راهنما</h3><a href="{{ route('blog.index') }}">مجله فرزین</a><br><a href="{{ route('contact.index') }}">تماس با ما</a></div></div><div class="fz-copy">© {{ date('Y') }} فرزین · تمامی حقوق محفوظ است.</div></div></footer></body></html>