import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

gsap.to("#bar-one", {
    yPercent: -400,
    ease: "none",
    scrollTrigger: {
        trigger: "self",
        scrub: true
    }
});

gsap.to("#bar-two", {
    yPercent: -300,
    ease: "none",
    scrollTrigger: {
        trigger: ".related-content",
        scrub: true
    }
});

gsap.to("#bar-three", {
    yPercent: -200,
    ease: "none",
    scrollTrigger: {
        trigger: ".bottom-cta",
        scrub: true
    }
});

ScrollTrigger.batch(".post-content .image img", {
    batchMax: 1,
    onEnter: (batch) => {
        gsap.to(batch, {autoAlpha: 1, y: 0, stagger: 0.15, overwrite: true});
    },
});

ScrollTrigger.batch(".related-content .article-card", {
    batchMax: 4,
    onEnter: (batch) => {
        gsap.to(batch, {autoAlpha: 1, y: -20, stagger: 0.15, overwrite: true});
    },
});