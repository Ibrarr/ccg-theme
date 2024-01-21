import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

setTimeout(function () {
    gsap.to("#bar-four", {
        yPercent: -400,
        ease: "none",
        scrollTrigger: {
            trigger: "#footer",
            scrub: true
        }
    });
}, 1000);

ScrollTrigger.batch(".about-content img", {
    batchMax: 1,
    onEnter: (batch) => {
        gsap.to(batch, {autoAlpha: 1, y: 0, stagger: 0.15, overwrite: true});
    },
});