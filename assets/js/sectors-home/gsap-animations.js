import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);


gsap.to("#bar-one", {
    yPercent: -150,
    ease: "none",
    scrollTrigger: {
        trigger: ".bottom-cta",
        scrub: true
    }
});
