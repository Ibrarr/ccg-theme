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
    yPercent: -100,
    ease: "none",
    scrollTrigger: {
        trigger: ".bottom-cta",
        scrub: true
    }
});