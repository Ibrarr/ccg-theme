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

const bottomCta = document.querySelector(".bottom-cta");
const triggerThree = bottomCta ? ".bottom-cta" : "#footer";
gsap.to("#bar-three", {
    yPercent: -200,
    ease: "none",
    scrollTrigger: {
        trigger: triggerThree,
        scrub: true
    }
});

ScrollTrigger.batch(".person-container", {
    batchMax: 12,
    onEnter: (batch) => {
        gsap.to(batch, {autoAlpha: 1, stagger: 0.15, overwrite: true});
    },
});