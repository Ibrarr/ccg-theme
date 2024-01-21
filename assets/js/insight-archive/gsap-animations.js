import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

const parallaxBarsConfig = {
    ease: "none",
    scrollTrigger: {
        trigger: "self",
        scrub: true
    }
};

gsap.to("#bar-one", {
    ...parallaxBarsConfig,
    yPercent: -400
});

gsap.to("#bar-two", {
    ...parallaxBarsConfig,
    yPercent: -400
});

gsap.to("#bar-three", {
    ...parallaxBarsConfig,
    yPercent: -500
});