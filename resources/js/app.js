import "../css/app.css";
import "../css/components.css";
import "./bootstrap";


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
