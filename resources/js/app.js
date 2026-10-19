import "../css/components.css";
import "./bootstrap";
import "./media-picker";

document.addEventListener("DOMContentLoaded", () => {
    initRevealObserver();
    initFlashMessages();
    initQuantityControls();
    initProductGallery();
    initSimpleProductThumbs();
    initHomeHero();
    initHomeProductTours();
    initScrollAwareStorefrontHeader();
    initCartActions();
    initGsapReveals();
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

function initScrollAwareStorefrontHeader() {
    const header = document.querySelector(".fz-header");
    if (!header) return;

    let lastY = Math.max(0, window.scrollY);
    let ticking = false;

    const update = () => {
        const currentY = Math.max(0, window.scrollY);
        const delta = currentY - lastY;

        if (currentY < 72) {
            header.classList.remove("is-scroll-hidden");
        } else if (delta > 7) {
            header.classList.add("is-scroll-hidden");
        } else if (delta < -5) {
            header.classList.remove("is-scroll-hidden");
        }

        lastY = currentY;
        ticking = false;
    };

    window.addEventListener("scroll", () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    }, { passive: true });

    header.addEventListener("focusin", () => header.classList.remove("is-scroll-hidden"));
}

function initHomeProductTours() {
    const tours = [...document.querySelectorAll("[data-product-tour]")];
    if (!tours.length) return;

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

    tours.forEach((tour) => {
        const stage = tour.querySelector("[data-tour-stage]");
        const frames = [...tour.querySelectorAll("[data-tour-frame]")];
        const prev = tour.querySelector("[data-tour-prev]");
        const next = tour.querySelector("[data-tour-next]");
        const counter = tour.querySelector("[data-tour-count]");
        const progress = tour.querySelector("[data-tour-progress]");
        if (!stage || !frames.length) return;

        let activeIndex = 0;
        let pointerStartX = null;
        let pointerStartIndex = 0;
        let pointerId = null;
        let isDragging = false;
        let latestPointerX = null;
        let scrollFrame = 0;
        const total = frames.length;
        const countText = (value) => String(value).padStart(2, "0");

        const render = (index) => {
            activeIndex = (index + total) % total;
            frames.forEach((frame, i) => {
                const active = i === activeIndex;
                frame.classList.toggle("is-active", active);
                frame.setAttribute("aria-hidden", active ? "false" : "true");
            });
            if (counter) counter.textContent = `${countText(activeIndex + 1)} / ${countText(total)}`;
            if (progress) progress.style.transform = `scaleX(${total > 1 ? activeIndex / (total - 1) : 1})`;
        };

        prev?.addEventListener("click", () => render(activeIndex - 1));
        next?.addEventListener("click", () => render(activeIndex + 1));

        // The position in the scroll runway maps to the ordered images.
        const syncToScroll = () => {
            scrollFrame = 0;
            if (isDragging || total < 2) return;
            const rect = tour.getBoundingClientRect();
            const scrollable = Math.max(1, tour.offsetHeight - window.innerHeight);
            const progressValue = clamp(-rect.top / scrollable, 0, 1);
            render(Math.round(progressValue * (total - 1)));
        };

        const requestScrollSync = () => {
            if (scrollFrame) return;
            scrollFrame = window.requestAnimationFrame(syncToScroll);
        };

        window.addEventListener("scroll", requestScrollSync, { passive: true });
        window.addEventListener("resize", requestScrollSync, { passive: true });

        // Mouse hover lets the pointer position select a viewing angle.
        stage.addEventListener("pointermove", (event) => {
            if (isDragging && pointerStartX !== null) {
                const dx = event.clientX - pointerStartX;
                const pixelsPerFrame = Math.max(8, stage.clientWidth / Math.max(8, total));
                render(pointerStartIndex - Math.trunc(dx / pixelsPerFrame));
                if (event.pointerType === "touch") latestPointerX = event.clientX;
                return;
            }
            if (event.pointerType === "touch") return;
            if (event.pointerType === "mouse" && total > 1) {
                const rect = stage.getBoundingClientRect();
                const ratio = clamp((event.clientX - rect.left) / Math.max(1, rect.width), 0, 1);
                render(Math.round(ratio * (total - 1)));
            }
        });

        stage.addEventListener("pointerdown", (event) => {
            if (event.pointerType === "mouse" && event.button !== 0) return;
            if (total < 2) return;
            isDragging = true;
            pointerStartX = event.clientX;
            pointerStartIndex = activeIndex;
            pointerId = event.pointerId;
            latestPointerX = event.clientX;
            if (stage.setPointerCapture) stage.setPointerCapture(event.pointerId);
            stage.classList.add("is-dragging");
        });

        const finishDrag = (event) => {
            if (!isDragging) return;
            if (pointerId !== null && stage.hasPointerCapture?.(pointerId)) {
                stage.releasePointerCapture(pointerId);
            }
            isDragging = false;
            pointerStartX = null;
            pointerId = null;
            stage.classList.remove("is-dragging");
        };

        stage.addEventListener("pointerup", finishDrag);
        stage.addEventListener("pointercancel", finishDrag);
        stage.addEventListener("lostpointercapture", finishDrag);

        // Touch swipes work even on browsers without pointer capture.
        stage.addEventListener("touchstart", (event) => {
            if (total < 2) return;
            latestPointerX = event.changedTouches[0]?.clientX ?? null;
        }, { passive: true });

        stage.addEventListener("touchend", (event) => {
            const endX = event.changedTouches[0]?.clientX ?? null;
            if (latestPointerX === null || endX === null) return;
            const distance = endX - latestPointerX;
            if (Math.abs(distance) > 18) {
                const steps = Math.max(1, Math.round(Math.abs(distance) / Math.max(24, stage.clientWidth / total)));
                render(activeIndex + (distance < 0 ? steps : -steps));
            }
            latestPointerX = null;
        }, { passive: true });

        stage.addEventListener("keydown", (event) => {
            if (event.key === "ArrowLeft") {
                event.preventDefault();
                render(activeIndex + 1);
            } else if (event.key === "ArrowRight") {
                event.preventDefault();
                render(activeIndex - 1);
            }
        });

        render(0);
    });
}


function initSimpleProductThumbs() {
    const mainImage = document.getElementById("fz-main-product-image");
    if (!mainImage) return;

    const thumbs = [...document.querySelectorAll("[data-product-thumb][data-image]")];
    if (!thumbs.length) return;

    thumbs.forEach((button) => {
        button.addEventListener("click", () => {
            const nextSrc = button.dataset.image;
            if (!nextSrc) return;

            mainImage.src = nextSrc;
            mainImage.alt = button.dataset.alt || "";
            thumbs.forEach((thumb) => {
                const active = thumb === button;
                thumb.classList.toggle("is-active", active);
                thumb.setAttribute("aria-pressed", active ? "true" : "false");
            });
        });
    });
}

let gsapModulePromise = null;

function loadGSAP() {
    if (!gsapModulePromise) {
        gsapModulePromise = import("gsap")
            .then((module) => module.gsap || module.default?.gsap || module.default)
            .catch(() => null);
    }
    return gsapModulePromise;
}

function appendStorefrontToast(message, isError = false) {
    const text = String(message || "").trim();
    if (!text) return;

    let region = document.querySelector("[data-toast-region]");
    if (!region) {
        region = document.createElement("div");
        region.className = "fz-toast-region";
        region.dataset.toastRegion = "";
        region.setAttribute("aria-live", "polite");
        region.setAttribute("aria-atomic", "true");
        document.body.append(region);
    }

    const toast = document.createElement("div");
    toast.className = "fz-toast" + (isError ? " fz-toast--error" : "");
    toast.setAttribute("role", isError ? "alert" : "status");
    toast.textContent = text;
    region.append(toast);

    window.setTimeout(() => {
        toast.style.opacity = "0";
        toast.style.transform = "translateY(6px)";
        window.setTimeout(() => toast.remove(), 180);
    }, 4200);
}

function syncCartCount(itemCount) {
    const badge = document.querySelector("[data-cart-count]");
    if (!badge) return;

    const count = Math.max(0, Number.parseInt(itemCount, 10) || 0);
    badge.textContent = String(count);
    badge.hidden = count === 0;
}

async function animateProductIntoCart(form) {
    const target = document.querySelector("[data-cart-link]");
    const source = form.closest("[data-product-card], .farzin-product-card")
        ?.querySelector("[data-product-image], .farzin-product-card__image-link img")
        || document.getElementById("fz-main-product-image");

    if (!target || !source || window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    const sourceRect = source.getBoundingClientRect();
    const targetRect = target.getBoundingClientRect();

    if (sourceRect.width < 1 || sourceRect.height < 1 || targetRect.width < 1 || targetRect.height < 1) return;

    const clone = source.cloneNode(false);
    clone.alt = "";
    clone.setAttribute("aria-hidden", "true");
    Object.assign(clone.style, {
        position: "fixed",
        zIndex: "9999",
        left: sourceRect.left + "px",
        top: sourceRect.top + "px",
        width: sourceRect.width + "px",
        height: sourceRect.height + "px",
        objectFit: "contain",
        pointerEvents: "none",
        borderRadius: "5px",
        boxShadow: "0 16px 40px rgb(17 19 24 / .22)",
        transformOrigin: "center center",
        willChange: "transform, opacity",
    });
    document.body.append(clone);

    try {
        const gsap = await loadGSAP();
        if (!gsap) {
            clone.remove();
            return;
        }

        const currentTarget = document.querySelector("[data-cart-link]") || target;
        const endRect = currentTarget.getBoundingClientRect();
        const deltaX = endRect.left + endRect.width / 2 - (sourceRect.left + sourceRect.width / 2);
        const deltaY = endRect.top + endRect.height / 2 - (sourceRect.top + sourceRect.height / 2);

        gsap.fromTo(clone,
            { x: 0, y: 0, scale: 1, opacity: 0.92, rotate: 0 },
            {
                x: deltaX,
                y: deltaY,
                scale: 0.16,
                opacity: 0.12,
                rotate: -7,
                duration: 0.62,
                ease: "power3.in",
                onComplete: () => clone.remove(),
            },
        );
    } catch {
        clone.remove();
    }
}

function initCartActions() {
    const forms = [...document.querySelectorAll("form[data-cart-add]")];
    if (!forms.length || !("fetch" in window)) return;

    forms.forEach((form) => {
        form.addEventListener("submit", async (event) => {
            if (form.dataset.cartBusy === "true") {
                event.preventDefault();
                return;
            }

            if (!form.reportValidity()) {
                event.preventDefault();
                return;
            }

            event.preventDefault();
            form.dataset.cartBusy = "true";

            const button = form.querySelector("[data-cart-add-button], button[type='submit']");
            const originalText = button?.textContent?.trim() || "افزودن به سبد خرید";
            if (button) {
                button.disabled = true;
                button.setAttribute("aria-busy", "true");
                button.textContent = "در حال افزودن…";
            }

            try {
                const response = await fetch(form.action, {
                    method: (form.method || "POST").toUpperCase(),
                    body: new FormData(form),
                    credentials: "same-origin",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                const contentType = response.headers.get("content-type") || "";
                const payload = contentType.includes("application/json")
                    ? await response.json()
                    : null;

                if (!response.ok || !payload?.success) {
                    const message = payload?.message
                        || (response.status === 419
                            ? "نشست شما منقضی شده است. صفحه را تازه‌سازی کن و دوباره تلاش کن."
                            : "افزودن محصول انجام نشد. اطلاعات را بررسی و دوباره امتحان کن.");
                    appendStorefrontToast(message, true);
                    return;
                }

                if (payload.item_count !== undefined) syncCartCount(payload.item_count);
                appendStorefrontToast(payload.message || "محصول به سبد خرید اضافه شد.");
                void animateProductIntoCart(form);
            } catch {
                appendStorefrontToast("ارتباط با سرور برقرار نشد. اتصال را بررسی و دوباره تلاش کن.", true);
            } finally {
                delete form.dataset.cartBusy;
                if (button) {
                    button.disabled = false;
                    button.removeAttribute("aria-busy");
                    button.textContent = originalText;
                }
            }
        });
    });
}

function initGsapReveals() {
    const elements = [...document.querySelectorAll("[data-gsap-reveal], [data-reveal-item]")];
    if (!elements.length || !("IntersectionObserver" in window)) return;
    if (window.matchMedia("(prefers-reduced-motion: reduce)").matches) return;

    const animated = new WeakSet();
    const observer = new IntersectionObserver((entries, currentObserver) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting || animated.has(entry.target)) return;

            animated.add(entry.target);
            currentObserver.unobserve(entry.target);

            void loadGSAP().then((gsap) => {
                if (!gsap || !entry.target.isConnected) return;

                gsap.fromTo(entry.target,
                    { y: 16, autoAlpha: 0 },
                    {
                        y: 0,
                        autoAlpha: 1,
                        duration: 0.62,
                        ease: "power3.out",
                        clearProps: "transform",
                    },
                );
            });
        });
    }, {
        threshold: 0.08,
        rootMargin: "0px 0px -28px 0px",
    });

    elements.forEach((element) => observer.observe(element));
}
