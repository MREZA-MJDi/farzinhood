<section
    class="section home-section home-section--blog"
    aria-labelledby="home-blog-title"
>
    <div class="container">

        <div class="section__inner">

            <x-ui.section-header
                eyebrow="مجله فرزین"
                title="راهنمای انتخاب بهتر"
                description="نکات کاربردی برای انتخاب هود، سینک و طراحی آشپزخانه."
                title-id="home-blog-title"
            >
                <x-slot:action>
                    <a
                        href="{{ route('blog.index') }}"
                        class="btn btn--outline"
                    >
                        مشاهده مجله
                    </a>
                </x-slot:action>
            </x-ui.section-header>


            <div class="section-content">

                <div class="blog-grid">

                    <x-content.blog-card
                        title="قبل از خرید هود به چه نکاتی توجه کنیم؟"
                        image="{{ asset('images/blog/hood-guide.webp') }}"
                        href="{{ route('blog.show', ['slug' => 'guide-to-choosing-hood']) }}"
                        category="راهنمای خرید"
                        excerpt="چند نکته کاربردی برای انتخاب هودی که با فضای آشپزخانه شما هماهنگ باشد."
                        meta="۵ دقیقه مطالعه"
                    />

                    <x-content.blog-card
                        title="سینک توکار یا روکار؛ کدام مناسب شماست؟"
                        image="{{ asset('images/blog/sink-guide.webp') }}"
                        href="{{ route('blog.show', ['slug' => 'kitchen-sink-guide']) }}"
                        category="راهنمای خرید"
                        excerpt="تفاوت‌ها، مزایا و نکاتی که قبل از انتخاب سینک بهتر است بدانید."
                        meta="۴ دقیقه مطالعه"
                    />

                    <x-content.blog-card
                        title="چطور آشپزخانه‌ای مدرن و یکدست داشته باشیم؟"
                        image="{{ asset('images/blog/kitchen-design.webp') }}"
                        href="{{ route('blog.show', ['slug' => 'modern-kitchen-design']) }}"
                        category="طراحی"
                        excerpt="از رنگ و متریال تا انتخاب تجهیزات؛ یک نگاه ساده به طراحی آشپزخانه مدرن."
                        meta="۶ دقیقه مطالعه"
                    />

                </div>

            </div>

        </div>

    </div>
</section>
