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
   HOME HERO SLIDER
========================================================= */
document.addEventListener("DOMContentLoaded", () => {
    const hero = document.querySelector("[data-home-hero]");
    if (!hero) return;

    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const prev = hero.querySelector("[data-hero-prev]");
    const next = hero.querySelector("[data-hero-next]");

    if (slides.length < 2) return;

    let index = 0;
    let timer = null;
    let startX = 0;

    const render = (nextIndex) => {
        index = (nextIndex + slides.length) % slides.length;

        slides.forEach((slide, i) => {
            const active = i === index;
            slide.classList.toggle("opacity-100", active);
            slide.classList.toggle("opacity-0", !active);
            slide.classList.toggle("translate-x-0", active);
            slide.classList.toggle("translate-x-3", !active);
            slide.classList.toggle("pointer-events-none", !active);
            slide.setAttribute("aria-hidden", active ? "false" : "true");
        });

        dots.forEach((dot, i) => {
            const active = i === index;
            dot.classList.toggle("bg-white", active);
            dot.classList.toggle("bg-white/15", !active);
            dot.setAttribute("aria-selected", active ? "true" : "false");
        });
    };

    const stop = () => {
        if (timer) window.clearInterval(timer);
        timer = null;
    };

    const start = () => {
        stop();
        if (!window.matchMedia("(prefers-reduced-motion: reduce)").matches) {
            timer = window.setInterval(() => render(index + 1), 5000);
        }
    };

    prev?.addEventListener("click", () => { render(index - 1); start(); });
    next?.addEventListener("click", () => { render(index + 1); start(); });
    dots.forEach((dot, i) => dot.addEventListener("click", () => { render(i); start(); }));

    hero.addEventListener("mouseenter", stop);
    hero.addEventListener("mouseleave", start);
    hero.addEventListener("focusin", stop);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) start();
    });

    hero.addEventListener("touchstart", (event) => {
        startX = event.changedTouches[0]?.clientX ?? 0;
    }, { passive: true });

    hero.addEventListener("touchend", (event) => {
        const endX = event.changedTouches[0]?.clientX ?? startX;
        const distance = endX - startX;
        if (Math.abs(distance) > 45) {
            render(distance < 0 ? index + 1 : index - 1);
            start();
        }
    }, { passive: true });

    hero.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") { render(index + 1); start(); }
        if (event.key === "ArrowRight") { render(index - 1); start(); }
    });

    start();
});
