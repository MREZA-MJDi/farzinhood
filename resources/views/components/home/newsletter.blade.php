<section
    class="section section--sm home-section home-section--newsletter"
>
    <div class="container">

        <div class="section__inner">

            <div class="newsletter">

                <div class="newsletter__inner">

                    <div class="newsletter__content">

                        <span class="newsletter__eyebrow">
                            مجله فرزین
                        </span>

                        <h2 class="newsletter__title">
                            انتخاب بعدی شما شاید همین‌جا باشد.
                        </h2>

                        <p class="newsletter__description">
                            محصولات جدید و مطالب کاربردی فرزین را دنبال کنید.
                        </p>

                    </div>


                    <form
                        action="{{ route('newsletter.subscribe') }}"
                        method="POST"
                        class="newsletter__form"
                    >

                        @csrf

                        <div class="newsletter__input">

                            <label
                                for="newsletter-email"
                                class="sr-only"
                            >
                                ایمیل
                            </label>

                            <input
                                id="newsletter-email"
                                type="email"
                                name="email"
                                class="form-input"
                                placeholder="ایمیل شما"
                                autocomplete="email"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            class="btn btn--accent"
                        >
                            عضویت
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</section>
