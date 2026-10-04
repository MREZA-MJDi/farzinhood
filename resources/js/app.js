import "../css/components.css";
import "./bootstrap";
import "./media-picker";

document.addEventListener("DOMContentLoaded", () => {
    initRevealObserver();
    initFlashMessages();
    initQuantityControls();
    initProductGallery();
    initHomeHero();
});

function initRevealObserver() {
    const items = document.querySelectorAll("[data-product-card], .reveal-up");

    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
        items.forEach((item) => item.classList.add("is-visible"));
        return;
    }

    const observer = new IntersectionObserver(
        (entries, current) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                entry.target.classList.add("is-visible");
                current.unobserve(entry.target);
            });
        },
        {
            threshold: 0.08,
            rootMargin: "0px 0px -32px 0px",
        },
    );

    items.forEach((item) => observer.observe(item));
}

function initFlashMessages() {
    document.querySelectorAll("[data-flash]").forEach((flash) => {
        const close = () => {
            flash.style.opacity = "0";
            flash.style.transform = "translateY(-6px)";
            window.setTimeout(() => flash.remove(), 180);
        };

        flash.querySelector("[data-flash-close]")?.addEventListener("click", close);
        window.setTimeout(close, 5200);
    });
}

function clampNumber(value, min, max) {
    const parsed = Number(value);

    if (!Number.isFinite(parsed)) {
        return min;
    }

    return Math.min(Math.max(parsed, min), max);
}

function syncQuantityInput(input, control) {
    const min = Number(input.min || 1);
    const max = Number(input.max || control.dataset.max || 99);
    input.value = String(clampNumber(input.value, min, max));
    input.dispatchEvent(new Event("quantity:changed", { bubbles: true }));
}

function initQuantityControls() {
    document.querySelectorAll("[data-quantity-control], .store-qty").forEach((control) => {
        const input = control.querySelector("[data-quantity-input]");
        if (!input) return;

        control.querySelectorAll("[data-quantity-action]").forEach((button) => {
            button.addEventListener("click", () => {
                const current = Number(input.value || 1);
                const min = Number(input.min || 1);
                const max = Number(input.max || control.dataset.max || 99);
                const delta = button.dataset.quantityAction === "increase" ? 1 : -1;

                input.value = String(clampNumber(current + delta, min, max));

                const form = control.closest("form[data-auto-submit-quantity]");
                if (form) {
                    window.clearTimeout(form._quantitySubmitTimer);
                    form._quantitySubmitTimer = window.setTimeout(() => {
                        form.requestSubmit();
                    }, 220);
                }
            });
        });

        input.addEventListener("change", () => {
            syncQuantityInput(input, control);
            const form = control.closest("form[data-auto-submit-quantity]");
            if (form) {
                form.requestSubmit();
            }
        });

        input.addEventListener("blur", () => {
            syncQuantityInput(input, control);
        });

        syncQuantityInput(input, control);
    });
}

function initProductGallery() {
    document.querySelectorAll("[data-product-gallery]").forEach((gallery) => {
        const main = gallery.querySelector("[data-gallery-main]");
        const thumbs = [...gallery.querySelectorAll("[data-gallery-thumb]")];
        const openButton = gallery.querySelector("[data-gallery-open]");
        const dialog = document.querySelector("[data-gallery-dialog]");

        if (!main || !openButton || !dialog) return;

        const images = thumbs.length
            ? thumbs.map((thumb) => ({
                  src: thumb.dataset.gallerySrc,
                  alt: thumb.dataset.galleryAlt || main.alt,
                  index: thumb.dataset.galleryIndex || "1",
              }))
            : [
                  {
                      src: main.currentSrc || main.src,
                      alt: main.alt,
                      index: "1",
                  },
              ];

        let activeIndex = Math.max(
            0,
            thumbs.findIndex((thumb) => thumb.classList.contains("is-active")),
        );

        if (activeIndex < 0) activeIndex = 0;

        const lightboxImage = dialog.querySelector("[data-gallery-lightbox-image]");
        const counter = dialog.querySelector("[data-gallery-counter]");

        const render = (index) => {
            activeIndex = (index + images.length) % images.length;
            const image = images[activeIndex];

            main.src = image.src;
            main.alt = image.alt;

            thumbs.forEach((thumb, thumbIndex) => {
                const active = thumbIndex === activeIndex;
                thumb.classList.toggle("is-active", active);
                thumb.setAttribute("aria-pressed", active ? "true" : "false");
            });

            gallery
                .querySelector("[data-gallery-current]")
                ?.replaceChildren(document.createTextNode(String(activeIndex + 1).padStart(2, "0")));

            if (lightboxImage) {
                lightboxImage.src = image.src;
                lightboxImage.alt = image.alt;
            }

            if (counter) {
                counter.textContent = `${activeIndex + 1} / ${images.length}`;
            }
        };

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener("click", () => render(index));
        });

        openButton.addEventListener("click", () => {
            render(activeIndex);

            if (typeof dialog.showModal === "function") {
                dialog.showModal();
            } else {
                dialog.setAttribute("open", "");
            }

            document.documentElement.classList.add("overflow-hidden");
        });

        dialog.querySelector("[data-gallery-close]")?.addEventListener("click", () => {
            if (typeof dialog.close === "function") {
                dialog.close();
            } else {
                dialog.removeAttribute("open");
            }
        });

        dialog.querySelector("[data-gallery-prev]")?.addEventListener("click", () => {
            render(activeIndex - 1);
        });

        dialog.querySelector("[data-gallery-next]")?.addEventListener("click", () => {
            render(activeIndex + 1);
        });

        dialog.addEventListener("click", (event) => {
            if (event.target === dialog) {
                dialog.close?.();
            }
        });

        dialog.addEventListener("close", () => {
            document.documentElement.classList.remove("overflow-hidden");
        });

        dialog.addEventListener("keydown", (event) => {
            if (event.key === "ArrowLeft") render(activeIndex + 1);
            if (event.key === "ArrowRight") render(activeIndex - 1);
        });

        render(activeIndex);
    });
}

function initHomeHero() {
    const hero = document.querySelector("[data-home-hero]");
    if (!hero) return;

    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    const prev = hero.querySelector("[data-hero-prev]");
    const next = hero.querySelector("[data-hero-next]");
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const current = hero.querySelector("[data-hero-current]");
    if (slides.length < 2) return;

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    let index = slides.findIndex((slide) => slide.classList.contains("is-active"));
    if (index < 0) index = 0;
    let timer = null;
    let progressAnimation = null;
    let touchStartX = null;


    const stop = () => {
        if (timer) {
            window.clearInterval(timer);
            timer = null;
        }
        window.clearTimeout(progressAnimation);
    };

    const render = (nextIndex, restart = true) => {
        index = (nextIndex + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            const active = i === index;
            slide.classList.toggle("is-active", active);
            slide.setAttribute("aria-hidden", active ? "false" : "true");

            const link = slide.querySelector("a");
            if (link) link.tabIndex = active ? 0 : -1;
        });

        dots.forEach((dot, i) => {
            const active = i === index;
            dot.classList.toggle("is-active", active);
            dot.setAttribute("aria-selected", active ? "true" : "false");
        });

        if (current) {
            current.textContent = String(index + 1).padStart(2, "0");
        }

        const imageProgress = hero.querySelector(".farzin-home-hero__progress span");
        if (imageProgress) {
            imageProgress.style.transition = "none";
            imageProgress.style.transform = "scaleX(0)";
            requestAnimationFrame(() => {
                imageProgress.style.transition = "transform 5.2s linear";
                imageProgress.style.transform = "scaleX(1)";
            });
        }

        if (restart) start();
    };

    const start = () => {
        stop();
        if (reduceMotion.matches) return;

        timer = window.setInterval(() => {
            render(index + 1, false);
        }, 5200);
    };

    prev?.addEventListener("click", () => render(index - 1));
    next?.addEventListener("click", () => render(index + 1));

    dots.forEach((dot, dotIndex) => {
        dot.addEventListener("click", () => render(dotIndex));
    });

    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", start);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) start();
    });

    hero.addEventListener("touchstart", (event) => {
        touchStartX = event.changedTouches[0]?.clientX ?? null;
        stop();
    }, { passive: true });

    hero.addEventListener("touchend", (event) => {
        if (touchStartX === null) return;
        const endX = event.changedTouches[0]?.clientX ?? touchStartX;
        const distance = endX - touchStartX;
        touchStartX = null;

        if (Math.abs(distance) >= 45) {
            render(distance < 0 ? index + 1 : index - 1);
        } else {
            start();
        }
    }, { passive: true });

    hero.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") {
            event.preventDefault();
            render(index + 1);
        }
        if (event.key === "ArrowRight") {
            event.preventDefault();
            render(index - 1);
        }
    });

    reduceMotion.addEventListener?.("change", start);
    render(index, false);
    start();
}