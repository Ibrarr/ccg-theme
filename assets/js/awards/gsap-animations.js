import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

gsap.to("#bar-one", {
    yPercent: -200,
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
        trigger: "self",
        scrub: true
    }
});