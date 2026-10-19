const HERO_SELECTOR = "[data-farzin-hero]";
const REDUCED_MOTION_QUERY = "(prefers-reduced-motion: reduce)";
const AUTOPLAY_DELAY = 6800;

document.addEventListener("DOMContentLoaded", async () => {
    const heroes = [...document.querySelectorAll(HERO_SELECTOR)];
    if (heroes.length === 0) return;

    // Keep GSAP out of the main bundle for the shop, account and admin pages.
    try {
        const { gsap } = await import("gsap");
        heroes.forEach((hero) => initFarzinHero(hero, gsap));
    } catch (error) {
        console.error("Farzin hero animation could not be loaded.", error);
        // The first product slide remains visible as a static, usable fallback.
        heroes.forEach((hero) => {
            hero.classList.add("farzin-hero--static");
            hero.querySelector(".farzin-hero__controls")?.remove();
            hero.querySelector(".farzin-hero__progress")?.remove();
        });
    }
});

function initFarzinHero(hero, gsap) {
    const slides = [...hero.querySelectorAll("[data-hero-slide]")];
    if (slides.length < 2) return;

    const previousButton = hero.querySelector("[data-hero-prev]");
    const nextButton = hero.querySelector("[data-hero-next]");
    const dots = [...hero.querySelectorAll("[data-hero-dot]")];
    const progress = hero.querySelector("[data-hero-progress]");
    const reducedMotion = window.matchMedia(REDUCED_MOTION_QUERY);

    let activeIndex = Math.max(
        0,
        slides.findIndex((slide) => slide.classList.contains("is-active")),
    );
    let autoplayTimer = 0;
    let progressTween = null;
    let isTransitioning = false;
    let touchStartX = null;

    const warmSlideImage = (targetIndex) => {
        const image = slides[(targetIndex + slides.length) % slides.length]
            ?.querySelector(".farzin-hero__media img");
        if (!image || image.complete) return;
        image.loading = "eager";
        if (typeof image.decode === "function") image.decode().catch(() => {});
    };

    const stopAutoplay = () => {
        window.clearTimeout(autoplayTimer);
        autoplayTimer = 0;
        progressTween?.kill();
        progressTween = null;
    };

    const startAutoplay = () => {
        stopAutoplay();
        warmSlideImage(activeIndex + 1);

        if (progress) {
            gsap.set(progress, { scaleX: 0, transformOrigin: "right center" });

            if (!reducedMotion.matches) {
                progressTween = gsap.to(progress, {
                    scaleX: 1,
                    duration: AUTOPLAY_DELAY / 1000,
                    ease: "none",
                });
            }
        }

        if (!reducedMotion.matches && !document.hidden) {
            autoplayTimer = window.setTimeout(
                () => showSlide(activeIndex + 1),
                AUTOPLAY_DELAY,
            );
        }
    };

    const syncAccessibleState = () => {
        slides.forEach((slide, index) => {
            const isActive = index === activeIndex;
            slide.classList.toggle("is-active", isActive);
            slide.setAttribute("aria-hidden", String(!isActive));
            slide.inert = !isActive;
        });

        dots.forEach((dot, index) => {
            const isActive = index === activeIndex;
            dot.classList.toggle("is-active", isActive);
            dot.setAttribute("aria-selected", String(isActive));
            dot.tabIndex = isActive ? 0 : -1;
        });
    };

    function showSlide(targetIndex) {
        if (isTransitioning) return;

        const nextIndex = (targetIndex + slides.length) % slides.length;
        if (nextIndex === activeIndex) {
            startAutoplay();
            return;
        }

        stopAutoplay();

        const currentSlide = slides[activeIndex];
        const nextSlide = slides[nextIndex];
        warmSlideImage(nextIndex);
        warmSlideImage(nextIndex + 1);
        const nextImage = nextSlide.querySelector(".farzin-hero__media img");
        const nextFrame = nextSlide.querySelector(".farzin-hero__frame");
        const nextCopy = nextSlide.querySelector(".farzin-hero__content");
        const currentCopy = currentSlide.querySelector(".farzin-hero__content");

        isTransitioning = true;
        nextSlide.inert = false;
        nextSlide.classList.add("is-active");

        gsap.set(nextSlide, {
            zIndex: 2,
            visibility: "visible",
            opacity: 1,
            clipPath: reducedMotion.matches
                ? "inset(0)"
                : "inset(43% 15% 43% 15%)",
        });
        gsap.set(nextFrame, {
            scaleX: reducedMotion.matches ? 1 : 0.3,
            scaleY: reducedMotion.matches ? 1 : 0.18,
            transformOrigin: "center",
            opacity: reducedMotion.matches ? 1 : 0,
        });
        gsap.set(nextCopy, {
            y: reducedMotion.matches ? 0 : 24,
            autoAlpha: reducedMotion.matches ? 1 : 0,
        });
        gsap.set(nextImage, { scale: reducedMotion.matches ? 1 : 1.09 });

        const finishTransition = () => {
            currentSlide.classList.remove("is-active");
            currentSlide.setAttribute("aria-hidden", "true");
            currentSlide.inert = true;

            gsap.set(currentSlide, {
                zIndex: 0,
                visibility: "hidden",
                opacity: 0,
                clipPath: "inset(0)",
            });
            gsap.set(nextSlide, { zIndex: 1, clipPath: "inset(0)" });

            activeIndex = nextIndex;
            syncAccessibleState();
            isTransitioning = false;
            startAutoplay();
        };

        if (reducedMotion.matches) {
            gsap.set(currentSlide, { opacity: 0, visibility: "hidden" });
            gsap.set(nextSlide, {
                opacity: 1,
                visibility: "visible",
                clipPath: "inset(0)",
            });
            finishTransition();
            return;
        }

        gsap.timeline({
            onComplete: finishTransition,
            defaults: { ease: "power3.inOut" },
        })
            .to(currentSlide, { opacity: 0.18, duration: 0.72 }, 0)
            .to(
                nextSlide,
                {
                    clipPath: "inset(0% 0% 0% 0%)",
                    duration: 1.15,
                    ease: "power4.inOut",
                },
                0,
            )
            .to(
                nextFrame,
                {
                    scaleX: 1,
                    scaleY: 1,
                    opacity: 1,
                    duration: 1.12,
                    ease: "power3.out",
                },
                0.08,
            )
            .to(nextImage, { scale: 1, duration: 1.45, ease: "power3.out" }, 0)
            .to(
                currentCopy,
                { y: -12, autoAlpha: 0, duration: 0.32, ease: "power2.in" },
                0,
            )
            .to(
                nextCopy,
                { y: 0, autoAlpha: 1, duration: 0.62, ease: "power3.out" },
                0.44,
            );
    }

    previousButton?.addEventListener("click", () => showSlide(activeIndex - 1));
    nextButton?.addEventListener("click", () => showSlide(activeIndex + 1));
    dots.forEach((dot, index) => {
        dot.addEventListener("click", () => showSlide(index));
    });

    hero.addEventListener("mouseenter", stopAutoplay);
    hero.addEventListener("mouseleave", startAutoplay);
    hero.addEventListener("focusin", stopAutoplay);
    hero.addEventListener("focusout", (event) => {
        if (!hero.contains(event.relatedTarget)) startAutoplay();
    });

    hero.addEventListener(
        "touchstart",
        (event) => {
            touchStartX = event.changedTouches[0]?.clientX ?? null;
            stopAutoplay();
        },
        { passive: true },
    );
    hero.addEventListener(
        "touchend",
        (event) => {
            if (touchStartX === null) return;

            const touchEndX = event.changedTouches[0]?.clientX ?? touchStartX;
            const distance = touchEndX - touchStartX;
            touchStartX = null;

            if (Math.abs(distance) > 45) {
                showSlide(distance < 0 ? activeIndex + 1 : activeIndex - 1);
            } else {
                startAutoplay();
            }
        },
        { passive: true },
    );

    hero.addEventListener("keydown", (event) => {
        if (event.key === "ArrowLeft") showSlide(activeIndex + 1);
        if (event.key === "ArrowRight") showSlide(activeIndex - 1);
    });

    document.addEventListener("visibilitychange", () => {
        if (document.hidden) stopAutoplay();
        else startAutoplay();
    });

    reducedMotion.addEventListener?.("change", startAutoplay);

    syncAccessibleState();
    slides.forEach((slide, index) => {
        if (index !== activeIndex) {
            gsap.set(slide, {
                visibility: "hidden",
                opacity: 0,
                zIndex: 0,
                clipPath: "inset(0)",
            });
        }
    });

    startAutoplay();
}
