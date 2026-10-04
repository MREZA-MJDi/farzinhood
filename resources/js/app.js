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

    const stage = hero.querySelector(".farzin-hero-rail__stage");
    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const prev = hero.querySelector("[data-hero-prev]");
    const next = hero.querySelector("[data-hero-next]");

    if (!stage || slides.length < 2) return;

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    const visibleRadius = 2;
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
        const cardWidth = Math.min(330, Math.max(205, stageWidth * 0.24));

        slides.forEach((slide, slideIndex) => {
            const relative = getRelativeIndex(slideIndex);
            const visible = Math.abs(relative) <= visibleRadius;
            const isActive = relative === 0;

            if (!visible) {
                slide.style.setProperty(
                    "--hero-x",
                    relative > 0 ? stageWidth + "px" : -stageWidth + "px",
                );
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

            const spread = Math.min(cardWidth * 0.76, stageWidth * 0.27);

            slide.style.setProperty("--hero-x", relative * spread + "px");
            slide.style.setProperty(
                "--hero-scale",
                isActive ? "1" : Math.abs(relative) === 1 ? "0.82" : "0.67",
            );
            slide.style.setProperty(
                "--hero-opacity",
                isActive ? "1" : Math.abs(relative) === 1 ? "0.68" : "0.34",
            );
            slide.style.setProperty(
                "--hero-blur",
                isActive ? "0px" : Math.abs(relative) === 1 ? "0.5px" : "1.5px",
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

    prev?.addEventListener("click", () => {
        render(index - 1);
        start();
    });

    next?.addEventListener("click", () => {
        render(index + 1);
        start();
    });

    dots.forEach((dot, dotIndex) => {
        dot.addEventListener("click", () => {
            render(dotIndex);
            start();
        });
    });

    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", start);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) start();
    });

    stage.addEventListener(
        "touchstart",
        (event) => {
            startX = event.changedTouches[0]?.clientX ?? null;
            stop();
        },
        { passive: true },
    );

    stage.addEventListener(
        "touchend",
        (event) => {
            if (startX === null) return;

            const endX = event.changedTouches[0]?.clientX ?? startX;
            const distance = endX - startX;
            startX = null;

            if (Math.abs(distance) >= 45) {
                render(distance < 0 ? index + 1 : index - 1);
            }

            start();
        },
        { passive: true },
    );

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
}
