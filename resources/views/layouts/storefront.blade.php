<!doctype html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#ffffff">
    <title>@yield('title', $defaultMetaTitle ?? 'فرزین | تجهیزات مدرن آشپزخانه')</title>
    <meta name="description" content="@yield('meta_description', 'خرید محصولات مدرن آشپزخانه از فروشگاه فرزین؛ مشخصات روشن، انتخاب مطمئن و پشتیبانی واقعی.')">
    @if (!app()->environment('testing'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    @stack('head')
    <style>
        :root {
            color-scheme: light;
            --fz-ink: #111318;
            --fz-dark: #191c22;
            --fz-pearl: #ffffff;
            --fz-surface: #f7f8fa;
            --fz-surface-2: #eef1f4;
            --fz-muted: #68707c;
            --fz-line: #e3e6ea;
            --fz-accent: #64748b;
            --fz-radius: 1.15rem;
            --fz-shadow: 0 18px 48px rgb(17 19 24 / .08);
        }
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; text-rendering: optimizeLegibility; -webkit-font-smoothing: antialiased; }
        body { min-height: 100vh; margin: 0; background: var(--fz-pearl); color: var(--fz-ink); font-family: Vazirmatn, Tahoma, Arial, sans-serif; line-height: 1.75; }
        body, button, input, textarea, select { font-family: Vazirmatn, Tahoma, Arial, sans-serif; }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button, input, select, textarea { font: inherit; }
        button, a { -webkit-tap-highlight-color: transparent; }
        button { cursor: pointer; }
        :focus-visible { outline: 2px solid var(--fz-accent); outline-offset: 3px; }
        .fz-container { width: min(1240px, calc(100% - 40px)); margin-inline: auto; }
        .fz-top { padding: 8px 14px; background: var(--fz-ink); color: #dce0e5; text-align: center; font-size: 11px; letter-spacing: .02em; }
        .fz-header { position: sticky; top: 0; z-index: 50; border-bottom: 1px solid rgb(227 230 234 / .9); background: rgb(255 255 255 / .88); -webkit-backdrop-filter: blur(18px) saturate(1.15); backdrop-filter: blur(18px) saturate(1.15); transition: transform .3s cubic-bezier(.22,1,.36,1), background-color .2s ease; will-change: transform; }
        .fz-header.is-scroll-hidden { transform: translateY(calc(-100% - 2px)); pointer-events: none; }
        .fz-header:focus-within { transform: translateY(0); pointer-events: auto; }
        .fz-nav { display: flex; min-height: 74px; align-items: center; justify-content: space-between; gap: 24px; }
        .fz-logo { flex: 0 0 auto; font-size: 23px; font-weight: 950; letter-spacing: .12em; line-height: 1; direction: ltr; }
        .fz-logo span { color: var(--fz-accent); }
        .fz-links, .fz-actions { display: flex; align-items: center; gap: clamp(12px, 2vw, 24px); font-size: 12px; }
        .fz-links a, .fz-actions > a:not(.fz-btn) { color: #454b55; transition: color .18s ease; white-space: nowrap; }
        .fz-links a:hover, .fz-actions > a:not(.fz-btn):hover { color: var(--fz-accent); }
        .fz-actions { gap: 12px; }
        .fz-cart-link { position: relative; display: inline-flex; align-items: center; gap: 7px; }
        .fz-cart-count { display: inline-grid; min-width: 18px; height: 18px; place-items: center; padding-inline: 4px; border-radius: 99px; background: var(--fz-ink); color: white; font-size: 10px; line-height: 1; }
        .fz-btn { display: inline-flex; min-height: 44px; align-items: center; justify-content: center; gap: 9px; padding: 0 18px; border: 1px solid var(--fz-ink); border-radius: 4px; background: var(--fz-ink); color: #fff; font-size: 12px; font-weight: 800; cursor: pointer; transition: background .2s ease, border-color .2s ease, color .2s ease, transform .2s ease; }
        .fz-btn:hover { transform: translateY(-1px); background: #303540; border-color: #303540; }
        .fz-btn.light { border-color: var(--fz-line); background: white; color: var(--fz-ink); }
        .fz-btn.light:hover { background: var(--fz-surface); border-color: #b7bec8; }
        .fz-btn:disabled, .fz-btn[aria-disabled="true"] { opacity: .55; cursor: not-allowed; transform: none; }
        .fz-section { padding: clamp(44px, 6vw, 76px) 0; }
        .fz-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 22px; margin-bottom: 26px; }
        .fz-kicker { display: inline-block; color: var(--fz-muted); font-size: 11px; font-weight: 800; letter-spacing: .08em; }
        .fz-h2 { margin: 8px 0; font-size: clamp(25px, 3vw, 39px); font-weight: 850; letter-spacing: -.035em; line-height: 1.45; }
        .fz-desc { color: var(--fz-muted); font-size: 13px; line-height: 2; }
        .fz-head > a { color: #424a56; font-size: 12px; white-space: nowrap; }
        .fz-head > a:hover { color: var(--fz-accent); }
        .fz-hero { overflow: hidden; background: var(--fz-dark); color: #f8fafc; }
        .fz-hero-grid { display: grid; min-height: min(650px, 78svh); grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); align-items: stretch; }
        .fz-hero-copy { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; padding: 56px 0 56px 7%; }
        .fz-hero h1 { margin: 14px 0; font-size: clamp(38px, 5vw, 66px); font-weight: 900; letter-spacing: -.04em; line-height: 1.4; }
        .fz-hero p { max-width: 500px; color: #cbd1da; font-size: 14px; line-height: 2.1; }
        .fz-hero-media { position: relative; min-height: 420px; overflow: hidden; background: radial-gradient(ellipse at 50% 38%, #464c56 0, #272b33 47%, #171a20 78%); }
        .fz-hero-media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .88; }
        .fz-hero-media:after { position: absolute; inset: 0; content: ""; pointer-events: none; background: linear-gradient(90deg, var(--fz-dark), transparent 48%); }
        .fz-pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 20px; }
        .fz-pill { padding: 7px 12px; border: 1px solid #515761; border-radius: 999px; color: #e3e7ec; font-size: 11px; }
        .fz-category-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; }
        .fz-category { display: flex; min-height: 150px; flex-direction: column; justify-content: space-between; gap: 12px; padding: 22px; border: 1px solid var(--fz-line); border-radius: 5px; background: var(--fz-surface); transition: border-color .2s ease, background .2s ease, transform .2s ease; }
        .fz-category:hover { transform: translateY(-3px); border-color: #c4cbd4; background: #f0f2f5; }
        .fz-category strong { font-size: 19px; font-weight: 850; }
        .fz-category span { color: var(--fz-muted); font-size: 11px; }
        .fz-products { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; }
        .fz-product { display: flex; min-width: 0; flex-direction: column; overflow: hidden; border: 1px solid var(--fz-line); border-radius: 5px; background: #fff; }
        .fz-product-img { position: relative; display: block; aspect-ratio: 4 / 4.5; overflow: hidden; background: var(--fz-surface); }
        .fz-product-img img { width: 100%; height: 100%; object-fit: cover; transition: transform .55s cubic-bezier(.22,1,.36,1); }
        .fz-product:hover .fz-product-img img { transform: scale(1.045); }
        .fz-product-body { display: flex; flex: 1; flex-direction: column; padding: 15px; }
        .fz-brand { color: var(--fz-muted); font-size: 10px; }
        .fz-product h3 { min-height: 46px; margin: 7px 0 12px; font-size: 13px; font-weight: 800; line-height: 1.9; }
        .fz-price { color: var(--fz-ink); font-size: 14px; font-weight: 900; }
        .fz-price small { color: var(--fz-muted); font-size: 10px; font-weight: 500; }
        .fz-old { margin-inline-start: 8px; color: #8b929d; font-size: 11px; font-weight: 500; text-decoration: line-through; }
        .fz-product-buy { margin-top: auto; padding-top: 13px; }
        .fz-product-buy .fz-btn { width: 100%; min-height: 40px; font-size: 11px; }
        .fz-product-link { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 10px; padding-top: 10px; border-top: 1px solid var(--fz-line); color: #424a56; font-size: 11px; }
        .fz-product-link:hover { color: var(--fz-accent); }
        .fz-promo { display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: clamp(24px, 5vw, 48px); border: 1px solid var(--fz-line); background: var(--fz-surface); }
        .fz-footer { margin-top: 0; padding: 48px 0 18px; background: var(--fz-ink); color: #f8fafc; }
        .fz-footer-grid { display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 28px; }
        .fz-footer p, .fz-footer a { color: #c0c6d0; font-size: 12px; line-height: 2.2; }
        .fz-footer a:hover { color: white; }
        .fz-footer h3 { margin: 0 0 12px; font-size: 13px; font-weight: 850; }
        .fz-copy { margin-top: 28px; padding-top: 15px; border-top: 1px solid #353943; color: #9ba3b0; font-size: 10px; }
        .fz-breadcrumb { padding: 20px 0; color: var(--fz-muted); font-size: 11px; }
        .fz-breadcrumb a:hover { color: var(--fz-ink); }
        .fz-catalog-head { padding: 22px 0 8px; }
        .fz-filterbar { display: grid; grid-template-columns: minmax(180px, 1.5fr) repeat(4, minmax(130px, 1fr)) auto auto; gap: 9px; margin: 20px 0; }
        .fz-input, .fz-select, .fz-textarea { width: 100%; min-height: 44px; max-width: 100%; padding: 10px 12px; border: 1px solid #dce1e7; border-radius: 4px; background: white; color: var(--fz-ink); font-size: 12px; }
        .fz-textarea { min-height: 140px; resize: vertical; }
        .fz-input:focus, .fz-select:focus, .fz-textarea:focus { outline: 2px solid rgb(100 116 139 / .22); border-color: #64748b; }
        .fz-pagination { display: flex; justify-content: center; gap: 6px; margin: 30px 0; }
        .fz-pagination nav { display: flex; flex-wrap: wrap; justify-content: center; gap: 6px; }
        .fz-pagination a, .fz-pagination span { display: inline-flex; min-width: 36px; min-height: 36px; align-items: center; justify-content: center; padding: 6px 11px; border: 1px solid var(--fz-line); border-radius: 4px; background: white; color: #3f4652; font-size: 11px; }
        .fz-pagination [aria-current="page"] span, .fz-pagination span[aria-current="page"] { border-color: var(--fz-ink); background: var(--fz-ink); color: white; }
        .fz-pagination a:hover { border-color: #aeb6c1; background: var(--fz-surface); }
        .fz-detail { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: clamp(24px, 5vw, 54px); padding: 12px 0 52px; }
        .fz-detail-img { position: relative; aspect-ratio: 1; overflow: hidden; border: 1px solid var(--fz-line); background: var(--fz-surface); }
        .fz-detail-img img { width: 100%; height: 100%; object-fit: contain; }
        .fz-detail h1 { margin: 8px 0 14px; font-size: clamp(26px, 3.1vw, 42px); font-weight: 900; line-height: 1.5; letter-spacing: -.025em; }
        .fz-detail p { font-size: 13px; line-height: 2; }
        .fz-stock { color: #4b5563; font-size: 12px; }
        .fz-empty { padding: 36px 22px; border: 1px solid var(--fz-line); background: var(--fz-surface); color: var(--fz-muted); text-align: center; font-size: 13px; }
        .fz-alert { margin: 14px auto; padding: 12px 16px; border: 1px solid var(--fz-line); background: var(--fz-surface); font-size: 12px; }
        .fz-alert--success { border-color: #d1d5db; color: #1f2937; }
        .fz-alert--error { border-color: #cbd5e1; color: #334155; }
        .fz-toast-region { position: fixed; z-index: 200; right: 16px; bottom: 18px; display: grid; width: min(380px, calc(100vw - 32px)); gap: 8px; pointer-events: none; }
        .fz-toast { padding: 13px 16px; border: 1px solid #353943; border-radius: 6px; background: var(--fz-ink); color: white; box-shadow: var(--fz-shadow); font-size: 12px; line-height: 1.8; }
        .fz-toast--error { border-color: #cbd5e1; background: #f8fafc; color: #1f2937; }
        .fz-blog-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; }
        .fz-blog-card { display: flex; min-width: 0; flex-direction: column; overflow: hidden; border: 1px solid var(--fz-line); background: #fff; }
        .fz-blog-card__image { display: block; aspect-ratio: 16 / 10; overflow: hidden; background: var(--fz-surface); }
        .fz-blog-card__image img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s cubic-bezier(.22,1,.36,1); }
        .fz-blog-card:hover .fz-blog-card__image img { transform: scale(1.035); }
        .fz-blog-card__body { display: flex; flex: 1; flex-direction: column; padding: 18px; }
        .fz-blog-card__meta { display: flex; flex-wrap: wrap; gap: 8px 12px; color: var(--fz-muted); font-size: 10px; }
        .fz-blog-card h2 { margin: 10px 0; font-size: 17px; line-height: 1.7; }
        .fz-blog-card p { margin: 0 0 16px; color: var(--fz-muted); font-size: 12px; line-height: 2; }
        .fz-blog-card__link { display: inline-flex; align-items: center; gap: 8px; margin-top: auto; font-size: 11px; font-weight: 850; }
        .fz-blog-article { max-width: 860px; margin: 0 auto; }
        .fz-blog-article__cover { width: 100%; max-height: 540px; margin: 24px 0; object-fit: cover; background: var(--fz-surface); }
        .fz-blog-article__content { color: #333944; font-size: 14px; line-height: 2.25; overflow-wrap: anywhere; }
        .fz-blog-article__content p { margin: 0 0 1.2em; }
        .fz-contact-grid { display: grid; grid-template-columns: minmax(0, .8fr) minmax(0, 1.2fr); gap: 24px; align-items: start; }
        .fz-panel { border: 1px solid var(--fz-line); background: #fff; padding: clamp(20px, 3vw, 30px); }
        .fz-form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
        .fz-form-field { display: grid; gap: 7px; min-width: 0; }
        .fz-form-field > span { font-size: 11px; font-weight: 800; color: #3f4652; }
        .fz-form-field--full { grid-column: 1 / -1; }
        .fz-field-error { color: #475569; font-size: 11px; }
        .fz-tour { position: relative; padding: 58px 0 66px; background: #171a20; color: #f8fafc; isolation: isolate; }
        .fz-tour:before { position: absolute; z-index: -1; inset: 0; content: ""; background: radial-gradient(ellipse at 78% 20%, rgb(148 163 184 / .10), transparent 42%), linear-gradient(130deg, #20242c, #15171c); }
        .fz-tour__shell { display: flex; min-height: calc(92svh + var(--tour-scroll-span, 0svh)); flex-direction: column; }
        .fz-tour__intro { max-width: 720px; margin: 0 0 22px; }
        .fz-tour__eyebrow { color: #cbd5e1; font-size: 11px; font-weight: 850; letter-spacing: .14em; }
        .fz-tour__intro h2 { margin: 9px 0 8px; font-size: clamp(25px, 3.5vw, 44px); line-height: 1.45; letter-spacing: -.025em; }
        .fz-tour__intro p { max-width: 650px; margin: 0; color: #c3c9d2; font-size: 13px; line-height: 2; }
        .fz-tour__stage { position: sticky; top: 88px; z-index: 1; height: min(68svh, 660px); min-height: 350px; overflow: hidden; border: 1px solid rgb(226 232 240 / .2); border-radius: 5px; background: #252a33; box-shadow: 0 24px 80px rgb(0 0 0 / .25); cursor: ew-resize; touch-action: pan-y; user-select: none; -webkit-user-select: none; }
        .fz-tour__stage:focus-visible { outline: 2px solid #cbd5e1; outline-offset: 4px; }
        .fz-tour__frames, .fz-tour__frame { position: absolute; inset: 0; }
        .fz-tour__frame { visibility: hidden; opacity: 0; transition: opacity .16s linear; }
        .fz-tour__frame.is-active { visibility: visible; opacity: 1; }
        .fz-tour__frame img { width: 100%; height: 100%; object-fit: contain; object-position: center; background: linear-gradient(135deg, #333943, #171a20); pointer-events: none; }
        .fz-tour__shade { position: absolute; inset: 0; pointer-events: none; background: linear-gradient(90deg, rgb(17 19 24 / .4), transparent 40%, transparent 70%, rgb(17 19 24 / .16)), linear-gradient(0deg, rgb(17 19 24 / .72), transparent 30%, rgb(17 19 24 / .12)); }
        .fz-tour__tag { position: absolute; top: 20px; left: 22px; color: #e2e8f0; font-size: 10px; font-weight: 850; letter-spacing: .14em; direction: ltr; }
        .fz-tour__caption { position: absolute; right: 22px; bottom: 62px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; color: #f8fafc; font-size: 12px; }
        .fz-tour__caption strong { color: #cbd5e1; font-size: 11px; letter-spacing: .12em; direction: ltr; }
        .fz-tour__controls { position: absolute; right: 20px; bottom: 17px; left: 20px; display: flex; max-width: 360px; align-items: center; gap: 13px; }
        .fz-tour__controls button { display: grid; width: 36px; height: 36px; flex: 0 0 36px; place-items: center; border: 1px solid rgb(255 255 255 / .38); border-radius: 50%; background: rgb(18 18 16 / .28); color: white; font-size: 17px; transition: background .2s, border-color .2s; }
        .fz-tour__controls button:hover { border-color: #cbd5e1; background: rgb(148 163 184 / .18); }
        .fz-tour__track { height: 2px; flex: 1; overflow: hidden; background: rgb(255 255 255 / .26); }
        .fz-tour__track span { display: block; width: 100%; height: 100%; background: #cbd5e1; transform: scaleX(0); transform-origin: left; transition: transform .12s linear; }
        .fz-tour__hint { position: absolute; bottom: 25px; left: 22px; color: #e2e8f0; font-size: 10px; opacity: .8; }
        .fz-tour__hint span { margin-right: 5px; font-size: 16px; }
        .fz-tour__footer { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-top: 22px; color: #c3c9d2; font-size: 12px; }
        .fz-tour__shop { border-color: #e2e8f0; background: #f8fafc; color: #111318; }
        .fz-tour__shop:hover { border-color: #cbd5e1; background: #e2e8f0; color: #111318; }
        .fz-tour__stage.is-dragging { cursor: grabbing; }
        @media (max-width: 1050px) {
            .fz-filterbar { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .fz-products { grid-template-columns: repeat(3, minmax(0, 1fr)); }
            .fz-blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }
        @media (max-width: 800px) {
            .fz-nav { min-height: 66px; gap: 14px; }
            .fz-links { max-width: 42vw; overflow-x: auto; gap: 13px; white-space: nowrap; scrollbar-width: none; }
            .fz-links::-webkit-scrollbar { display: none; }
            .fz-links a, .fz-actions > a:not(.fz-btn) { font-size: 11px; }
            .fz-actions { gap: 8px; }
            .fz-actions .fz-btn { min-height: 38px; padding-inline: 11px; font-size: 10px; }
            .fz-logo { font-size: 20px; }
            .fz-hero-grid { grid-template-columns: 1fr; min-height: unset; }
            .fz-hero-copy { padding: 34px 0 42px; }
            .fz-hero-media { min-height: min(54vw, 380px); grid-row: 1; }
            .fz-hero-media:after { background: linear-gradient(0deg, var(--fz-dark), transparent 58%); }
            .fz-category-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fz-products { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
            .fz-detail, .fz-contact-grid { grid-template-columns: 1fr; gap: 22px; }
            .fz-footer-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fz-filterbar { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fz-promo { align-items: flex-start; flex-direction: column; }
            .fz-blog-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .fz-tour { padding: 38px 0 44px; }
            .fz-tour__shell { min-height: calc(88svh + var(--tour-scroll-span, 0svh)); }
            .fz-tour__intro { margin-bottom: 16px; }
            .fz-tour__intro h2 { font-size: 26px; }
            .fz-tour__intro p { font-size: 12px; }
            .fz-tour__stage { top: 72px; height: 62svh; min-height: 300px; }
            .fz-tour__tag { top: 12px; left: 12px; font-size: 9px; }
            .fz-tour__caption { right: 13px; bottom: 60px; gap: 9px; font-size: 10px; }
            .fz-tour__controls { right: 12px; bottom: 12px; left: 12px; gap: 8px; }
            .fz-tour__hint { bottom: 24px; left: 13px; font-size: 9px; }
            .fz-tour__footer { align-items: flex-start; flex-direction: column; }
        }
        @media (max-width: 520px) {
            .fz-container { width: calc(100% - 28px); }
            .fz-top { font-size: 10px; }
            .fz-hero h1 { font-size: 38px; }
            .fz-category { min-height: 125px; padding: 16px; }
            .fz-category strong { font-size: 17px; }
            .fz-product-body { padding: 11px; }
            .fz-product h3 { font-size: 12px; }
            .fz-product-buy .fz-btn { padding-inline: 8px; font-size: 10px; }
            .fz-head { align-items: flex-start; flex-direction: column; }
            .fz-blog-grid { grid-template-columns: 1fr; }
            .fz-filterbar { grid-template-columns: 1fr 1fr; }
            .fz-form-grid { grid-template-columns: 1fr; }
            .fz-form-field--full { grid-column: auto; }
            .fz-footer-grid { grid-template-columns: 1fr; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { scroll-behavior: auto !important; animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }
    </style>
</head>
<body>
    <div class="fz-top">{{ $siteName ?? 'فرزین' }}؛ انتخاب دقیق برای آشپزخانه‌های امروزی</div>

    <header class="fz-header">
        <div class="fz-container fz-nav">
            <a class="fz-logo" href="{{ route('home') }}" aria-label="فرزین، صفحه اصلی">FARZIN<span>.</span></a>

            <nav class="fz-links" aria-label="ناوبری اصلی">
                <a href="{{ route('home') }}">خانه</a>
                <a href="{{ route('shop.index') }}">فروشگاه</a>
                <a href="{{ route('blog.index') }}">مجله</a>
                <a href="{{ route('contact.index') }}">تماس با ما</a>
            </nav>

            <div class="fz-actions">
                @auth
                    @if (auth()->user()->isCustomer())
                        <a class="fz-cart-link" data-cart-link href="{{ route('customer.cart.index') }}" aria-label="سبد خرید">
                            سبد خرید
                            <span class="fz-cart-count" data-cart-count @if (($headerCartCount ?? 0) < 1) hidden @endif>{{ $headerCartCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('customer.wishlist.index') }}">علاقه‌مندی‌ها</a>
                        <a href="{{ route('customer.dashboard') }}">حساب من</a>
                    @elseif (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}">پنل مدیریت</a>
                    @endif
                @else
                    <a href="{{ route('login') }}">ورود</a>
                    <a href="{{ route('register') }}">ثبت‌نام</a>
                @endauth
                <a class="fz-btn" href="{{ route('shop.index') }}">مشاهده محصولات</a>
            </div>
        </div>
    </header>

    @if (session('success'))
        <div class="fz-container fz-alert fz-alert--success" role="status">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="fz-container fz-alert fz-alert--error" role="alert">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="fz-container fz-alert fz-alert--error" role="alert">
            <strong>لطفاً موارد زیر را بررسی کن:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="fz-toast-region" data-toast-region aria-live="polite" aria-atomic="true"></div>

    <main id="main-content">
        @yield('content')
    </main>

    <footer class="fz-footer">
        <div class="fz-container">
            <div class="fz-footer-grid">
                <div>
                    <a class="fz-logo" href="{{ route('home') }}">FARZIN<span>.</span></a>
                    <p>{{ $siteName ?? 'فرزین' }}؛ محصولات منتخب آشپزخانه، اطلاعات شفاف و مسیر خرید ساده برای انتخابی مطمئن‌تر.</p>
                </div>
                <div>
                    <h3>خرید و کشف</h3>
                    <p><a href="{{ route('shop.index') }}">همه محصولات</a></p>
                    <p><a href="{{ route('shop.index') }}">محصولات جدید</a></p>
                    <p><a href="{{ route('blog.index') }}">مجله فرزین</a></p>
                </div>
                <div>
                    <h3>پشتیبانی</h3>
                    <p><a href="{{ route('contact.index') }}">تماس با ما</a></p>
                    @auth
                        @if (auth()->user()->isCustomer())
                            <p><a href="{{ route('customer.orders.index') }}">پیگیری سفارش‌ها</a></p>
                            <p><a href="{{ route('customer.addresses.index') }}">نشانی‌های من</a></p>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="fz-copy">© {{ now()->year }} {{ $siteName ?? 'فرزین' }} · تمامی حقوق محفوظ است.</div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
