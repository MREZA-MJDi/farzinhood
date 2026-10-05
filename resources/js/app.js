import "../css/app.css";
import "../css/components.css";
import "./bootstrap";
import "./media-picker";


/* =========================================================
   PRODUCT CARD REVEAL
========================================================= */

document.addEventListener("DOMContentLoaded", () => {
    const productCards = document.querySelectorAll(
        "[data-product-card]"
    );

    if (!productCards.length) {
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add("is-visible");

                obs.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: "0px 0px -40px 0px",
        }
    );

    productCards.forEach((card) => {
        observer.observe(card);
    });
});



/* =========================================================
   HOME HERO — premium product rail
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const hero = document.querySelector("[data-home-hero]");
    if (!hero) return;

    const stage = hero.querySelector(".farzin-hero-rail__stage");
    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const prev = hero.querySelector("[data-hero-prev]");
    const next = hero.querySelector("[data-hero-next]");

    if (!stage || slides.length < 2) return;

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    let visibleRadius = 2;
    let index = 0;
    let timer = null;
    let startX = null;
    let resizeFrame = null;

    const wrap = (value) => (value + slides.length) % slides.length;

    const getRelativeIndex = (slideIndex) => {
        let relative = slideIndex - index;
        if (relative > slides.length / 2) relative -= slides.length;
        if (relative < -slides.length / 2) relative += slides.length;
        return relative;
    };

    const layout = () => {
        const stageWidth = stage.clientWidth;
        visibleRadius = stageWidth < 640 ? 1 : 2;
        const cardWidth = Math.min(330, Math.max(205, stageWidth * 0.22));

        slides.forEach((slide, slideIndex) => {
            const relative = getRelativeIndex(slideIndex);
            const visible = Math.abs(relative) <= visibleRadius;
            const isActive = relative === 0;

            if (!visible) {
                slide.style.setProperty("--hero-x", relative > 0 ? stageWidth + "px" : -stageWidth + "px");
                slide.style.setProperty("--hero-scale", "0.42");
                slide.style.setProperty("--hero-opacity", "0");
                slide.style.setProperty("--hero-blur", "4px");
                slide.style.setProperty("--hero-z-index", "0");
                slide.style.setProperty("--hero-z", "0px");
                slide.classList.remove("is-active");
                slide.setAttribute("aria-hidden", "true");
                slide.querySelector("a")?.setAttribute("tabindex", "-1");
                return;
            }

            const maxSpread = Math.max(0, (stageWidth - cardWidth) / (visibleRadius === 1 ? 2 : 4));
            const spread = Math.min(cardWidth * 0.58, maxSpread);

            slide.style.setProperty("--hero-x", (relative * spread) + "px");
            slide.style.setProperty(
                "--hero-scale",
                isActive ? "1" : relative === -1 || relative === 1 ? "0.82" : "0.67"
            );
            slide.style.setProperty(
                "--hero-opacity",
                isActive ? "1" : relative === -1 || relative === 1 ? "0.68" : "0.34"
            );
            slide.style.setProperty(
                "--hero-blur",
                isActive ? "0px" : relative === -1 || relative === 1 ? "0.5px" : "1.5px"
            );
            slide.style.setProperty("--hero-z", isActive ? "60px" : "0px");
            slide.style.setProperty("--hero-z-index", String(5 - Math.abs(relative)));

            slide.classList.toggle("is-active", isActive);
            slide.setAttribute("aria-hidden", isActive ? "false" : "true");
            slide.querySelector("a")?.setAttribute("tabindex", isActive ? "0" : "-1");
        });
    };

    const render = (nextIndex) => {
        index = wrap(nextIndex);
        layout();

        dots.forEach((dot, dotIndex) => {
            const active = dotIndex === index;
            dot.classList.toggle("is-active", active);
            dot.setAttribute("aria-selected", active ? "true" : "false");
        });
    };

    const stop = () => {
        if (timer) {
            window.clearInterval(timer);
            timer = null;
        }
    };

    const start = () => {
        stop();
        if (!reduceMotion.matches) {
            timer = window.setInterval(() => render(index + 1), 5200);
        }
    };

    prev?.addEventListener("click", () => { render(index - 1); start(); });
    next?.addEventListener("click", () => { render(index + 1); start(); });

    dots.forEach((dot, dotIndex) => {
        dot.addEventListener("click", () => { render(dotIndex); start(); });
    });

    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", start);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) start();
    });

    stage.addEventListener("touchstart", (event) => {
        startX = event.changedTouches[0]?.clientX ?? null;
        stop();
    }, { passive: true });

    stage.addEventListener("touchend", (event) => {
        if (startX === null) return;
        const endX = event.changedTouches[0]?.clientX ?? startX;
        const distance = endX - startX;
        startX = null;

        if (Math.abs(distance) >= 45) {
            render(distance < 0 ? index + 1 : index - 1);
        }

        start();
    }, { passive: true });

    hero.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") {
            event.preventDefault();
            render(index + 1);
            start();
        }

        if (event.key === "ArrowRight") {
            event.preventDefault();
            render(index - 1);
            start();
        }
    });

    const resizeObserver = new ResizeObserver(() => {
        if (resizeFrame) cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(layout);
    });

    resizeObserver.observe(stage);
    reduceMotion.addEventListener?.("change", start);

    render(0);
    start();
});


/* =========================================================
   HOME SECTION REVEALS
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const revealItems = document.querySelectorAll("[data-home-reveal]");

    if (!revealItems.length) {
        return;
    }

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");

    if (reduceMotion.matches || !("IntersectionObserver" in window)) {
        revealItems.forEach((item) => item.classList.add("is-revealed"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add("is-revealed");
                obs.unobserve(entry.target);
            });
        },
        {
            threshold: 0.12,
            rootMargin: "0px 0px -50px 0px",
        },
    );

    revealItems.forEach((item) => observer.observe(item));
});


/* =========================================================
   SHOP FILTER URL CLEANUP
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const filterForms = document.querySelectorAll("[data-shop-filter]");

    filterForms.forEach((form) => {
        form.addEventListener("submit", () => {
            form.querySelectorAll("input, select").forEach((field) => {
                if (!field.name || field.disabled) return;
                if (field.value === "") field.disabled = true;
            });
        });
    });
});


/* =========================================================
   SERVER-DRIVEN LIVE SEARCH
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const forms = [...document.querySelectorAll("[data-live-search]")];

    forms.forEach((form) => {
        const input = form.querySelector("[data-live-search-input]");
        const results = form.querySelector("[data-live-search-results]");
        const endpoint = form.dataset.suggestionsUrl;

        if (!input || !results || !endpoint) {
            return;
        }

        let timer = null;
        let controller = null;

        const close = () => {
            results.hidden = true;
            results.innerHTML = "";
        };

        const loading = () => {
            results.hidden = false;
            results.innerHTML = `
                <div class="farzin-live-search__state">
                    <span class="farzin-live-search__pulse"></span>
                    <strong>در حال جستجو…</strong>
                </div>
            `;
        };

        const render = (items, query) => {
            if (!items.length) {
                results.hidden = false;
                results.innerHTML = `
                    <div class="farzin-live-search__state">
                        <strong>نتیجه‌ای برای «${query}» پیدا نشد.</strong>
                        <span>Enter را بزن تا جستجوی کامل فروشگاه اجرا شود.</span>
                    </div>
                `;
                return;
            }

            results.innerHTML = "";

            items.forEach((item) => {
                const link = document.createElement("a");
                link.href = item.url;
                link.className = "farzin-live-search__item";

                const media = document.createElement("span");
                media.className = "farzin-live-search__media";

                if (item.image) {
                    const image = document.createElement("img");
                    image.src = item.image;
                    image.alt = "";
                    image.loading = "lazy";
                    image.decoding = "async";
                    media.appendChild(image);
                } else {
                    media.textContent = "F";
                }

                const copy = document.createElement("span");
                copy.className = "farzin-live-search__copy";

                const title = document.createElement("strong");
                title.textContent = item.name;

                const meta = document.createElement("span");
                meta.textContent = [item.brand, item.category]
                    .filter(Boolean)
                    .join(" · ") || "فرزین";

                copy.append(title, meta);

                const price = document.createElement("span");
                price.className = "farzin-live-search__price";
                price.textContent = `${new Intl.NumberFormat("fa-IR").format(Number(item.price || 0))} تومان`;

                link.append(media, copy, price);
                results.appendChild(link);
            });

            results.hidden = false;
        };

        const search = async () => {
            const query = input.value.trim();

            if (query.length < 2) {
                close();
                return;
            }

            controller?.abort();
            controller = new AbortController();

            loading();

            try {
                const url = new URL(endpoint, window.location.origin);
                url.searchParams.set("search", query);

                const response = await fetch(url, {
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    signal: controller.signal,
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.message || "جستجو ناموفق بود.");
                }

                render(
                    Array.isArray(payload.items) ? payload.items : [],
                    query
                );
            } catch (error) {
                if (error.name === "AbortError") {
                    return;
                }

                results.hidden = false;
                results.innerHTML = `
                    <div class="farzin-live-search__state">
                        <strong>جستجوی سریع در دسترس نیست.</strong>
                        <span>Enter را بزن تا جستجوی کامل اجرا شود.</span>
                    </div>
                `;
            }
        };

        input.addEventListener("input", () => {
            window.clearTimeout(timer);
            timer = window.setTimeout(search, 220);
        });

        input.addEventListener("keydown", (event) => {
            if (event.key === "Escape") {
                close();
            }
        });

        form.addEventListener("submit", () => {
            window.clearTimeout(timer);
        });

        document.addEventListener("click", (event) => {
            if (!form.contains(event.target)) {
                close();
            }
        });
    });
});
