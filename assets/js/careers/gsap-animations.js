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
        trigger: ".values",
        scrub: true
    }
});

gsap.to("#bar-three", {
    yPercent: -200,
    ease: "none",
    scrollTrigger: {
        trigger: ".other-info",
        scrub: true
    }
});

ScrollTrigger.batch(".slide-in-image", {
    batchMax: 4,
    onEnter: (batch) => {
        gsap.to(batch, {autoAlpha: 1, y: 0, stagger: 0.15, overwrite: true});
    },
});