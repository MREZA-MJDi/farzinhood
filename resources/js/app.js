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
    const validationSummary = document.querySelector("[data-validation-summary]");
    const prefersReducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const scrollBehavior = prefersReducedMotion ? "auto" : "smooth";

    if (validationSummary) {
        window.requestAnimationFrame(() => {
            validationSummary.focus({ preventScroll: true });
            validationSummary.scrollIntoView({ behavior: scrollBehavior, block: "start" });
        });
    }

    document.querySelectorAll("[data-validation-error-link]").forEach((link) => {
        link.addEventListener("click", (event) => {
            const targetId = link.dataset.validationTarget;
            const target = targetId ? document.getElementById(targetId) : null;
            if (!target) return;

            event.preventDefault();
            target.focus({ preventScroll: true });
            target.scrollIntoView({ behavior: scrollBehavior, block: "center" });
        });
    });

    document.querySelectorAll("[data-alert-dismiss]").forEach((button) => {
        button.addEventListener("click", () => {
            button.closest("[data-dismissible-alert]")?.remove();
        });
    });


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
        const titleFieldName = preview.dataset.seoTitleField || "meta_title";
        const descriptionFieldName = preview.dataset.seoDescriptionField || "meta_description";
        const canonicalFieldName = preview.dataset.seoCanonicalField || "canonical_url";
        const noindexFieldName = preview.dataset.seoNoindexField || "noindex";
        const field = (name) => name ? form.querySelector('[name="' + name + '"]') : null;

        const titleField = field(titleFieldName);
        const descriptionField = field(descriptionFieldName);
        const canonicalField = field(canonicalFieldName);
        const noindexField = field(noindexFieldName);

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
