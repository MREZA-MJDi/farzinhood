<section
    class="hero"
    aria-labelledby="hero-title"
>
    <div class="hero__inner">

        <div class="hero__content">

            <span class="hero__eyebrow">
                طراحی برای آشپزخانه امروز
            </span>

            <h1
                id="hero-title"
                class="hero__title"
            >
                هود و سینک،
                <span>با نگاه فرزین</span>
            </h1>

            <p class="hero__description">
                ترکیبی از طراحی مدرن، کیفیت قابل اعتماد
                و جزئیاتی که آشپزخانه شما را کامل‌تر می‌کنند.
            </p>

            <div class="hero__actions">

                <a
                    href="{{ route('shop') }}"
                    class="btn btn--primary btn--lg"
                >
                    مشاهده محصولات
                </a>

                <a
                    href="{{ route('about') }}"
                    class="btn btn--outline btn--lg"
                >
                    درباره فرزین
                </a>

            </div>

            <div class="hero__meta">

                <div class="hero__meta-item">
                    <strong>کیفیت</strong>
                    <span>انتخاب دقیق محصولات</span>
                </div>

                <span
                    class="hero__meta-divider"
                    aria-hidden="true"
                ></span>

                <div class="hero__meta-item">
                    <strong>ارسال</strong>
                    <span>سراسر کشور</span>
                </div>

                <span
                    class="hero__meta-divider"
                    aria-hidden="true"
                ></span>

                <div class="hero__meta-item">
                    <strong>پشتیبانی</strong>
                    <span>در کنار شما</span>
                </div>

            </div>

        </div>


        <div class="hero__visual">

            <div class="hero__image-wrap">

                <img
                    src="{{ asset('images/home/hero.webp') }}"
                    alt="هود و سینک مدرن فرزین"
                    width="1200"
                    height="900"
                    fetchpriority="high"
                >

            </div>

            <div class="hero__floating-card">

                <span class="hero__floating-label">
                    انتخاب ویژه
                </span>

                <strong>
                    طراحی مینیمال
                </strong>

                <span>
                    ساخته شده برای فضای مدرن
                </span>

            </div>

        </div>

    </div>
</section>
