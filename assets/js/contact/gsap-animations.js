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
    yPercent: -300,
    ease: "none",
    scrollTrigger: {
        trigger: "#footer",
        scrub: true
    }
});

gsap.to("#bar-four", {
    yPercent: -400,
    ease: "none",
    scrollTrigger: {
        trigger: "#footer",
        scrub: true
    }
});