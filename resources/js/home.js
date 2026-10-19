const HERO_SELECTOR = "[data-farzin-hero]";
const REDUCED_MOTION_QUERY = "(prefers-reduced-motion: reduce)";
const AUTOPLAY_DELAY = 6800;

document.addEventListener("DOMContentLoaded", async () => {
    const homePage = document.querySelector(".farzin-home");
    if (!homePage) return;

    const heroes = [...homePage.querySelectorAll(HERO_SELECTOR)];

    try {
        // Homepage-only entry: GSAP never loads on admin, account or catalog pages.
        const { gsap } = await import("gsap");
        let ScrollTrigger = null;

        try {
            ({ ScrollTrigger } = await import("gsap/ScrollTrigger"));
            gsap.registerPlugin(ScrollTrigger);
        } catch (error) {
            console.warn("ScrollTrigger unavailable; using IntersectionObserver.", error);
        }

        initHomeScrollAnimations(gsap, ScrollTrigger);\n        initHomeScrollMotion(gsap, ScrollTrigger);
        heroes.forEach((hero) => initFarzinHero(hero, gsap));
    } catch (error) {
        console.error("Farzin homepage animation could not be loaded.", error);

        // Fail open: animations must never be a prerequisite for readable content.
        document.querySelectorAll("[data-farzin-motion]").forEach((element) => {
            element.style.removeProperty("opacity");
            element.style.removeProperty("visibility");
            element.style.removeProperty("transform");
            element.removeAttribute("data-farzin-motion");
        });

        heroes.forEach((hero) => {
            hero.classList.add("farzin-hero--static");
            hero.querySelectorAll("[data-hero-slide]").forEach((slide, index) => {
                const active = index === 0;
                slide.classList.toggle("is-active", active);
                slide.setAttribute("aria-hidden", String(!active));
                slide.inert = !active;
                ["opacity", "visibility", "clip-path", "z-index", "transform"].forEach((property) => {
                    slide.style.removeProperty(property);
                });
            });
            hero.querySelector(".farzin-hero__controls")?.remove();
            hero.querySelector(".farzin-hero__progress")?.remove();
        });
    }
});

function initHomeScrollAnimations(gsap, ScrollTrigger) {
    if (window.matchMedia(REDUCED_MOTION_QUERY).matches) return;

    const mark = (elements) => elements.forEach((element) => element.setAttribute("data-farzin-motion", ""));

    const nav = document.querySelector(".store-nav");
    if (nav) {
        mark([nav]);
        gsap.fromTo(nav, { autoAlpha: 0, y: -12 }, {
            autoAlpha: 1,
            y: 0,
            duration: 0.68,
            ease: "power3.out",
            clearProps: "opacity,visibility,transform",
            onComplete: () => nav.removeAttribute("data-farzin-motion"),
        });
    }

    const hero = document.querySelector(HERO_SELECTOR);
    const heroCopy = hero?.querySelector("[data-hero-slide].is-active .farzin-hero__content");
    if (heroCopy) {
        mark([heroCopy]);
        gsap.fromTo(heroCopy, { autoAlpha: 0, x: 24 }, {
            autoAlpha: 1,
            x: 0,
            delay: 0.12,
            duration: 0.9,
            ease: "power3.out",
            clearProps: "opacity,visibility,transform",
            onComplete: () => heroCopy.removeAttribute("data-farzin-motion"),
        });
    }

    // Subtle scrubbed image drift: animate transforms, never layout or dimensions.
    if (hero && ScrollTrigger && window.matchMedia("(min-width: 761px)").matches) {
        hero.querySelectorAll(".farzin-hero__media img").forEach((image) => {
            gsap.to(image, {
                yPercent: 4,
                scale: 1.08,
                ease: "none",
                scrollTrigger: {
                    trigger: hero,
                    start: "top top",
                    end: "bottom top",
                    scrub: 0.6,
                    invalidateOnRefresh: true,
                },
            });
        });
    }

    const reveal = (targets, trigger, options = {}) => {
        if (!targets.length || !trigger) return;

        const { stagger = 0, distance = 22, duration = 0.74 } = options;
        mark(targets);
        const fromVars = { autoAlpha: 0, y: distance };
        const commonVars = {
            autoAlpha: 1,
            y: 0,
            duration,
            stagger,
            ease: "power3.out",
            clearProps: "opacity,visibility,transform",
            onComplete: () => targets.forEach((element) => element.removeAttribute("data-farzin-motion")),
        };

        if (ScrollTrigger) {
            gsap.fromTo(targets, fromVars, {
                ...commonVars,
                scrollTrigger: {
                    trigger,
                    start: "top 86%",
                    once: true,
                    invalidateOnRefresh: true,
                },
            });
            return;
        }

        if (!("IntersectionObserver" in window)) {
            targets.forEach((element) => {
                element.style.removeProperty("opacity");
                element.style.removeProperty("visibility");
                element.style.removeProperty("transform");
                element.removeAttribute("data-farzin-motion");
            });
            return;
        }

        gsap.set(targets, fromVars);
        const observer = new IntersectionObserver((entries, currentObserver) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                gsap.to(entry.target, {
                    autoAlpha: 1,
                    y: 0,
                    duration,
                    ease: "power3.out",
                    clearProps: "opacity,visibility,transform",
                    onComplete: () => entry.target.removeAttribute("data-farzin-motion"),
                });
                currentObserver.unobserve(entry.target);
            });
        }, { threshold: 0.1, rootMargin: "0px 0px -5% 0px" });

        targets.forEach((target) => observer.observe(target));
    };

    document.querySelectorAll(".farzin-section").forEach((section) => {
        reveal([...section.querySelectorAll(".farzin-section__head")], section, { distance: 18, duration: 0.68 });
    });

    document.querySelectorAll(".farzin-category-grid").forEach((grid) => {
        reveal([...grid.querySelectorAll(".farzin-category")], grid, { stagger: 0.055, distance: 24 });
    });

    document.querySelectorAll(".farzin-product-grid").forEach((grid) => {
        reveal([...grid.querySelectorAll(".farzin-product-card")], grid, { stagger: 0.06, distance: 22 });
    });

    document.querySelectorAll(".farzin-features-grid").forEach((grid) => {
        reveal([...grid.querySelectorAll(".farzin-feature")], grid, { stagger: 0.07, distance: 20 });
    });

    document.querySelectorAll(".farzin-blog-grid").forEach((grid) => {
        reveal([...grid.querySelectorAll(".farzin-blog-card")], grid, { stagger: 0.065, distance: 22 });
    });

    document.querySelectorAll(".farzin-manifesto, .farzin-newsletter").forEach((section) => {
        const inner = section.querySelector(":scope > .farzin-container, :scope > .farzin-newsletter__container");
        reveal(inner ? [inner] : [], section, { distance: 26, duration: 0.82 });
    });

    const footer = document.querySelector("body > footer");
    if (footer) reveal([footer], footer, { distance: 16, duration: 0.7 });

    if (ScrollTrigger) {
        window.addEventListener("load", () => ScrollTrigger.refresh(), { once: true });
        document.fonts?.ready?.then(() => ScrollTrigger.refresh()).catch(() => {});
    }
}

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

function initHomeScrollMotion(gsap, ScrollTrigger) {
    const home = document.querySelector(".farzin-home");
    if (!home) return;

    const reducedMotion = window.matchMedia("(prefers-reduced-motion: reduce)").matches;
    const desktopMotion = window.matchMedia("(min-width: 768px)").matches;

    // Scroll-scrubbed image depth gives the page a single visual rhythm.
    // Only transforms are animated so image dimensions and document flow stay stable.
    if (!reducedMotion && ScrollTrigger && desktopMotion) {
        const mediaImages = [
            ...home.querySelectorAll(".farzin-category__media img"),
            ...home.querySelectorAll(".farzin-product-card .product-card__image img"),
            ...home.querySelectorAll(".farzin-blog-card__media img"),
        ];

        mediaImages.forEach((image) => {
            const card = image.closest(".farzin-category, .farzin-product-card, .farzin-blog-card");
            if (!card) return;

            gsap.fromTo(image,
                { scale: 1.12, yPercent: 4 },
                {
                    scale: 1,
                    yPercent: -2,
                    ease: "none",
                    scrollTrigger: {
                        trigger: card,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: 0.45,
                        invalidateOnRefresh: true,
                    },
                },
            );
        });

        home.querySelectorAll(".farzin-tour__frame.is-active img").forEach((image) => {
            gsap.fromTo(image,
                { scale: 1.11 },
                {
                    scale: 1.02,
                    ease: "none",
                    scrollTrigger: {
                        trigger: image.closest(".farzin-tour"),
                        start: "top bottom",
                        end: "bottom top",
                        scrub: 0.7,
                        invalidateOnRefresh: true,
                    },
                },
            );
        });
    }

    initHomeProductTours(gsap, ScrollTrigger, reducedMotion);
}

function initHomeProductTours(gsap, ScrollTrigger, reducedMotion) {
    const tours = [...document.querySelectorAll("[data-product-tour]")];
    if (!tours.length) return;

    const clamp = (value, min, max) => Math.min(Math.max(value, min), max);

    tours.forEach((tour) => {
        const stage = tour.querySelector("[data-tour-stage]");
        const frames = [...tour.querySelectorAll("[data-tour-frame]")];
        const previousButton = tour.querySelector("[data-tour-prev]");
        const nextButton = tour.querySelector("[data-tour-next]");
        const title = tour.querySelector("[data-tour-active-title]");
        const description = tour.querySelector("[data-tour-active-description]");
        const counter = tour.querySelector("[data-tour-counter]");
        const progress = tour.querySelector("[data-tour-progress]");
        if (!stage || !frames.length) return;

        let activeIndex = Math.max(0, frames.findIndex((frame) => frame.classList.contains("is-active")));
        let isDragging = false;
        let pointerStartX = 0;
        let pointerStartIndex = 0;
        let pointerId = null;
        let rafId = 0;
        let scrollTriggerInstance = null;
        const total = frames.length;
        const count = (number) => String(number).padStart(2, "0");

        const warmFrame = (index) => {
            const image = frames[(index + total) % total]?.querySelector("img");
            if (!image) return;
            image.loading = "eager";
            if (typeof image.decode === "function" && !image.complete) image.decode().catch(() => {});
        };

        const render = (index) => {
            activeIndex = (index + total) % total;
            frames.forEach((frame, frameIndex) => {
                const active = frameIndex === activeIndex;
                frame.classList.toggle("is-active", active);
                frame.setAttribute("aria-hidden", active ? "false" : "true");
                frame.inert = !active;
            });

            const frame = frames[activeIndex];
            if (title) title.textContent = frame.dataset.frameTitle || "نمای محصول";
            if (description) {
                description.textContent = frame.dataset.frameDescription
                    || tour.dataset.tourDefaultDescription
                    || "برای دیدن زاویه‌های دیگر، اسکرول یا کنترل‌های تصویر را استفاده کن.";
            }
            if (counter) counter.textContent = count(activeIndex + 1) + " / " + count(total);
            if (progress) progress.style.transform = "scaleX(" + (total > 1 ? activeIndex / (total - 1) : 1) + ")";
            warmFrame(activeIndex + 1);
        };

        previousButton?.addEventListener("click", () => {
            render(activeIndex - 1);
        });
        nextButton?.addEventListener("click", () => {
            render(activeIndex + 1);
        });

        stage.addEventListener("keydown", (event) => {
            if (event.key === "ArrowLeft") {
                event.preventDefault();
                render(activeIndex + 1);
            } else if (event.key === "ArrowRight") {
                event.preventDefault();
                render(activeIndex - 1);
            }
        });

        if (total > 1) {
            stage.addEventListener("pointerdown", (event) => {
                if (event.pointerType === "mouse" && event.button !== 0) return;
                isDragging = true;
                pointerStartX = event.clientX;
                pointerStartIndex = activeIndex;
                pointerId = event.pointerId;
                stage.classList.add("is-dragging");
                stage.setPointerCapture?.(event.pointerId);
            });

            stage.addEventListener("pointermove", (event) => {
                if (!isDragging || pointerId !== event.pointerId) return;
                const threshold = Math.max(12, stage.clientWidth / Math.max(total, 12));
                const delta = event.clientX - pointerStartX;
                render(pointerStartIndex - Math.trunc(delta / threshold));
            });

            const finishDrag = (event) => {
                if (!isDragging || (pointerId !== null && event.pointerId !== pointerId)) return;
                isDragging = false;
                stage.classList.remove("is-dragging");
                if (pointerId !== null && stage.hasPointerCapture?.(pointerId)) {
                    stage.releasePointerCapture(pointerId);
                }
                pointerId = null;
            };
            stage.addEventListener("pointerup", finishDrag);
            stage.addEventListener("pointercancel", finishDrag);
            stage.addEventListener("lostpointercapture", finishDrag);
        }

        if (!reducedMotion && ScrollTrigger && total > 1) {
            scrollTriggerInstance = ScrollTrigger.create({
                trigger: tour,
                start: "top top",
                end: "bottom bottom",
                invalidateOnRefresh: true,
                onUpdate: (self) => {
                    if (isDragging) return;
                    render(Math.round(self.progress * (total - 1)));
                },
            });
        } else if (!reducedMotion && total > 1) {
            // Graceful fallback if ScrollTrigger is unavailable.
            const syncOnScroll = () => {
                if (rafId || isDragging) return;
                rafId = window.requestAnimationFrame(() => {
                    rafId = 0;
                    const rect = tour.getBoundingClientRect();
                    const scrollable = Math.max(1, tour.offsetHeight - window.innerHeight);
                    const progressValue = clamp(-rect.top / scrollable, 0, 1);
                    render(Math.round(progressValue * (total - 1)));
                });
            };
            window.addEventListener("scroll", syncOnScroll, { passive: true });
            window.addEventListener("resize", syncOnScroll, { passive: true });
            syncOnScroll();
        }

        render(activeIndex);
        warmFrame(activeIndex + 1);
    });
}
