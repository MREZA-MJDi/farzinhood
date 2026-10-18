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

            const spread = Math.min(cardWidth * 0.76, stageWidth * 0.27);

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
   ADMIN SHELL — mobile navigation and profile menu
   Keep this shell dependency-free; the project does not ship Alpine.js.
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const sidebar = document.querySelector("[data-admin-sidebar]");
    const backdrop = document.querySelector("[data-admin-sidebar-backdrop]");
    const openButton = document.querySelector("[data-admin-sidebar-open]");
    const profile = document.querySelector("[data-admin-profile]");
    const profileToggle = document.querySelector("[data-admin-profile-toggle]");
    const profileMenu = document.querySelector("[data-admin-profile-menu]");
    const profileChevron = document.querySelector("[data-admin-profile-chevron]");

    const closeSidebar = () => {
        if (!sidebar || !backdrop) return;
        sidebar.classList.add("-translate-x-full");
        sidebar.classList.remove("translate-x-0");
        backdrop.hidden = true;
        backdrop.classList.add("hidden");
        document.body.classList.remove("overflow-hidden");
        openButton?.setAttribute("aria-expanded", "false");
    };

    const openSidebar = () => {
        if (!sidebar || !backdrop) return;
        sidebar.classList.remove("-translate-x-full");
        sidebar.classList.add("translate-x-0");
        backdrop.hidden = false;
        backdrop.classList.remove("hidden");
        document.body.classList.add("overflow-hidden");
        openButton?.setAttribute("aria-expanded", "true");
    };

    const closeProfile = () => {
        if (!profileMenu) return;
        profileMenu.hidden = true;
        profileMenu.classList.add("hidden");
        profileToggle?.setAttribute("aria-expanded", "false");
        profileChevron?.classList.remove("rotate-180");
    };

    const toggleProfile = () => {
        if (!profileMenu) return;
        const shouldOpen = profileMenu.hidden;
        profileMenu.hidden = !shouldOpen;
        profileMenu.classList.toggle("hidden", !shouldOpen);
        profileToggle?.setAttribute("aria-expanded", String(shouldOpen));
        profileChevron?.classList.toggle("rotate-180", shouldOpen);
    };

    openButton?.addEventListener("click", openSidebar);
    backdrop?.addEventListener("click", closeSidebar);
    sidebar?.querySelectorAll("[data-admin-sidebar-close]").forEach((button) => {
        button.addEventListener("click", closeSidebar);
    });
    sidebar?.querySelectorAll("a").forEach((link) => {
        link.addEventListener("click", () => {
            if (window.matchMedia("(max-width: 1023px)").matches) closeSidebar();
        });
    });

    profileToggle?.addEventListener("click", toggleProfile);
    profileMenu?.querySelectorAll("[data-admin-profile-close]").forEach((link) => {
        link.addEventListener("click", closeProfile);
    });

    document.addEventListener("click", (event) => {
        if (profile && !profile.contains(event.target)) closeProfile();
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            closeSidebar();
            closeProfile();
        }
    });
});



/* =========================================================
   SEO EDITOR — live snippet preview from the actual form fields
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll("[data-seo-preview]").forEach((preview) => {
        const form = preview.closest("form") ?? document;
        const titleField = form.querySelector('[name="meta_title"]');
        const descriptionField = form.querySelector('[name="meta_description"]');
        const canonicalField = form.querySelector('[name="canonical_url"]');
        const noindexField = form.querySelector('[name="noindex"]');

        const titleOutput = preview.querySelector("[data-seo-preview-title]");
        const descriptionOutput = preview.querySelector("[data-seo-preview-description]");
        const urlOutput = preview.querySelector("[data-seo-preview-url]");
        const robotsOutput = preview.querySelector("[data-seo-preview-robots]");
        const titleCount = preview.querySelector("[data-seo-title-count]");
        const descriptionCount = preview.querySelector("[data-seo-description-count]");
        const titleGuidance = preview.querySelector("[data-seo-title-guidance]");
        const descriptionGuidance = preview.querySelector("[data-seo-description-guidance]");

        const fallbackTitle = preview.dataset.fallbackTitle?.trim() ?? "";
        const fallbackDescription = preview.dataset.fallbackDescription?.trim() ?? "";
        const fallbackUrl = preview.dataset.fallbackUrl?.trim() ?? "";

        const characterCount = (value) => Array.from(value ?? "").length;

        const render = () => {
            const enteredTitle = titleField?.value.trim() ?? "";
            const enteredDescription = descriptionField?.value.trim() ?? "";
            const enteredCanonical = canonicalField?.value.trim() ?? "";
            const useNoindex = Boolean(noindexField?.checked);

            if (titleOutput) {
                titleOutput.textContent = enteredTitle || fallbackTitle || "عنوان صفحه در نتایج جستجو";
            }

            if (descriptionOutput) {
                descriptionOutput.textContent = enteredDescription || fallbackDescription
                    || "پس از وارد کردن توضیحات متا، پیش‌نمایش آن نمایش داده می‌شود.";
            }

            if (urlOutput) {
                urlOutput.textContent = enteredCanonical || fallbackUrl
                    || "URL صفحه پس از ثبت و ساخت نامک مشخص می‌شود";
            }

            if (robotsOutput) {
                robotsOutput.textContent = useNoindex ? "noindex, follow" : "index, follow";
                robotsOutput.classList.toggle("border-amber-200", useNoindex);
                robotsOutput.classList.toggle("bg-amber-50", useNoindex);
                robotsOutput.classList.toggle("text-amber-800", useNoindex);
                robotsOutput.classList.toggle("text-[var(--color-text-secondary)]", !useNoindex);
            }

            const titleLength = characterCount(enteredTitle);
            const descriptionLength = characterCount(enteredDescription);

            if (titleCount) titleCount.textContent = String(titleLength);
            if (descriptionCount) descriptionCount.textContent = String(descriptionLength);

            if (titleGuidance) {
                titleGuidance.textContent = !enteredTitle
                    ? "خالی است؛ عنوان پیش‌فرض محتوا در پیش‌نمایش استفاده می‌شود."
                    : titleLength < 30
                        ? "از راهنمای تقریبی ۳۰ تا ۶۰ نویسه کوتاه‌تر است."
                        : titleLength <= 60
                            ? "در محدوده راهنمای تقریبی قرار دارد."
                            : "از راهنمای تقریبی ۶۰ نویسه بیشتر است.";
            }

            if (descriptionGuidance) {
                descriptionGuidance.textContent = !enteredDescription
                    ? "خالی است؛ توضیح پیش‌فرض محتوا در پیش‌نمایش استفاده می‌شود."
                    : descriptionLength < 120
                        ? "از راهنمای تقریبی ۱۲۰ تا ۱۶۰ نویسه کوتاه‌تر است."
                        : descriptionLength <= 160
                            ? "در محدوده راهنمای تقریبی قرار دارد."
                            : "از راهنمای تقریبی ۱۶۰ نویسه بیشتر است.";
            }
        };

        [titleField, descriptionField, canonicalField, noindexField]
            .filter(Boolean)
            .forEach((field) => {
                field.addEventListener("input", render);
                field.addEventListener("change", render);
            });

        render();
    });
});
