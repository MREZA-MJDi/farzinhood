import "../css/app.css";
import "../css/components.css";
import "../css/design-system.css";
import "./bootstrap";
import "./media-picker";

document.addEventListener("DOMContentLoaded", () => {
    initMoneyInputs();
    initImageFallbacks();
    initRevealObserver();
    initFlashMessages();
    initQuantityControls();
    initProductGallery();
});

function initRevealObserver() {
    const items = [...document.querySelectorAll("[data-product-card], .reveal-up")]
        .filter((item) => !item.closest(".farzin-home"));

    if (!items.length) return;

    if (!("IntersectionObserver" in window)) {
        items.forEach((item) => item.classList.add("is-visible"));
        return;
    }

    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
        items.forEach((item) => item.classList.add("is-visible"));
        return;
    }

    // Only opt into hide/reveal animations when the observer is available.
    // If JavaScript fails, the stylesheet keeps all meaningful content visible.
    document.documentElement.classList.add("has-reveal-observer");

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



/**
 * Persian/Arabic digit aware money input used by admin product prices.
 * Inputs stay readable while editing; forms submit plain integer toman values.
 */
function initMoneyInputs() {
    const inputs = [...document.querySelectorAll("[data-money-input]")];
    if (!inputs.length) return;

    const toAsciiDigits = (value) => String(value ?? "")
        .replace(/[۰-۹٠-٩]/g, (digit) => {
            const fa = "۰۱۲۳۴۵۶۷۸۹".indexOf(digit);
            if (fa >= 0) return String(fa);
            const ar = "٠١٢٣٤٥٦٧٨٩".indexOf(digit);
            return ar >= 0 ? String(ar) : digit;
        })
        .replace(/[^0-9]/g, "");

    const formatToman = (value) => {
        const digits = toAsciiDigits(value);
        if (!digits) return "";
        const amount = Number(digits);
        if (!Number.isSafeInteger(amount)) return digits;
        return new Intl.NumberFormat("fa-IR", {
            maximumFractionDigits: 0,
            useGrouping: true,
        }).format(amount);
    };

    const refreshPreview = (input) => {
        const previewId = input.getAttribute("aria-describedby");
        const preview = previewId ? document.getElementById(previewId) : null;
        if (!preview) return;

        const digits = toAsciiDigits(input.value);
        const caption = input.dataset.moneyCaption || "مبلغ واردشده";
        preview.textContent = digits
            ? `${caption}: ${formatToman(digits)} تومان`
            : input.name === "old_price"
                ? "اگر محصول تخفیف ندارد، این فیلد را خالی بگذار."
                : "قیمت را به تومان وارد کن تا مبلغ نهایی را ببینی.";
    };

    inputs.forEach((input) => {
        input.value = formatToman(input.value);
        refreshPreview(input);

        input.addEventListener("input", () => {
            const cursor = input.selectionStart ?? input.value.length;
            const digitsBeforeCursor = toAsciiDigits(input.value.slice(0, cursor)).length;
            const formatted = formatToman(input.value);
            input.value = formatted;

            // Preserve caret location while the grouping separators are added.
            let caret = 0;
            let seenDigits = 0;
            for (let index = 0; index < formatted.length; index += 1) {
                if (/[۰-۹]/.test(formatted[index])) seenDigits += 1;
                caret = index + 1;
                if (seenDigits >= digitsBeforeCursor) break;
            }
            if (digitsBeforeCursor === 0) caret = 0;
            input.setSelectionRange?.(caret, caret);
            refreshPreview(input);
        });

        input.addEventListener("blur", () => {
            input.value = formatToman(input.value);
            refreshPreview(input);
        });
    });

    const forms = new Set(inputs.map((input) => input.form).filter(Boolean));
    forms.forEach((form) => {
        form.addEventListener("submit", () => {
            inputs.filter((input) => input.form === form).forEach((input) => {
                input.value = toAsciiDigits(input.value);
            });
        });
    });
}


/**
 * Keep catalog layouts visually stable when a stored image URL is stale or
 * an upload was moved. Intrinsic dimensions remain in markup to avoid shifts.
 */
function initImageFallbacks() {
    const images = [...document.querySelectorAll("[data-image-fallback-image]")];

    const showFallback = (image) => {
        if (image.dataset.fallbackHandled === "true") return;
        image.dataset.fallbackHandled = "true";
        image.style.display = "none";

        const container = image.closest("[data-image-fallback-container]");
        const fallback = container?.querySelector("[data-image-fallback-placeholder]");
        if (!fallback) return;

        fallback.hidden = false;
        fallback.setAttribute("aria-hidden", "false");
        fallback.setAttribute("role", "img");
        fallback.setAttribute("aria-label", image.alt || "تصویر در دسترس نیست");
    };

    images.forEach((image) => {
        image.addEventListener("error", () => showFallback(image), { once: true });

        // Handles cached failures that happened before DOMContentLoaded.
        if (image.complete && image.naturalWidth === 0) showFallback(image);
    });
}
