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
   HOME HERO — reference-led light product rail
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const hero = document.querySelector("[data-home-hero]");
    if (!hero) return;

    const visual = hero.querySelector(".farzin-home-hero__visual");
    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const prev = hero.querySelector("[data-hero-prev]");
    const next = hero.querySelector("[data-hero-next]");

    if (!visual || slides.length < 2) return;

    const reduceMotion = window.matchMedia("(prefers-reduced-motion: reduce)");
    if (reduceMotion.matches) hero.setAttribute("data-reduced-motion", "true");
    let index = 0;
    let timer = null;
    let startX = null;
    let resizeFrame = null;

    const wrap = (value) => (value + slides.length) % slides.length;

    const layout = () => {
        const stageWidth = visual.clientWidth;
        const cardWidth = Math.min(390, Math.max(220, stageWidth * 0.58));
        const spread = Math.min(cardWidth * 0.67, stageWidth * 0.28);

        slides.forEach((slide, slideIndex) => {
            let relative = slideIndex - index;
            if (relative > slides.length / 2) relative -= slides.length;
            if (relative < -slides.length / 2) relative += slides.length;

            const active = relative === 0;
            const visible = Math.abs(relative) <= 1;
            slide.style.setProperty("--home-hero-x", `${relative * spread}px`);
            slide.style.setProperty("--home-hero-scale", active ? "1" : ".79");
            slide.style.setProperty("--home-hero-opacity", active ? "1" : visible ? ".42" : "0");
            slide.style.setProperty("--home-hero-blur", active ? "0px" : "1px");
            slide.style.setProperty("--home-hero-z", active ? "5" : "1");
            slide.classList.toggle("is-active", active);
            slide.setAttribute("aria-hidden", active ? "false" : "true");
            slide.querySelector("a")?.setAttribute("tabindex", active ? "0" : "-1");
            slide.style.pointerEvents = visible ? "auto" : "none";
        });
    };

    const render = (nextIndex) => {
        index = wrap(nextIndex);
        layout();
        dots.forEach((dot, dotIndex) => {
            const active = dotIndex === index;
            dot.classList.toggle("is-active", active);
            dot.setAttribute("aria-pressed", active ? "true" : "false");
        });
    };

    const stop = () => {
        if (timer !== null) {
            window.clearInterval(timer);
            timer = null;
        }
    };
    const start = () => {
        stop();
        if (!reduceMotion.matches && !hero.matches(":hover") && !hero.contains(document.activeElement)) {
            timer = window.setInterval(() => render(index + 1), 5500);
        }
    };

    prev?.addEventListener("click", () => { render(index - 1); start(); });
    next?.addEventListener("click", () => { render(index + 1); start(); });
    dots.forEach((dot, dotIndex) => dot.addEventListener("click", () => {
        render(dotIndex);
        start();
    }));

    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", start);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) start();
    });
    visual.addEventListener("touchstart", (event) => {
        startX = event.changedTouches[0]?.clientX ?? null;
        stop();
    }, { passive: true });
    visual.addEventListener("touchend", (event) => {
        if (startX === null) return;
        const endX = event.changedTouches[0]?.clientX ?? startX;
        const distance = endX - startX;
        startX = null;
        if (Math.abs(distance) >= 45) render(distance < 0 ? index + 1 : index - 1);
        start();
    }, { passive: true });
    hero.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") {
            event.preventDefault();
            render(index + 1);
            start();
        } else if (event.key === "ArrowRight") {
            event.preventDefault();
            render(index - 1);
            start();
        }
    });

    const resizeObserver = new ResizeObserver(() => {
        if (resizeFrame !== null) cancelAnimationFrame(resizeFrame);
        resizeFrame = requestAnimationFrame(layout);
    });
    resizeObserver.observe(visual);
    reduceMotion.addEventListener?.("change", start);
    render(0);
    start();
});

