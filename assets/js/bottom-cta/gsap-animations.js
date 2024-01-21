import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

gsap.registerPlugin(ScrollTrigger);

function triggerAnimations() {
    const bottomBarOne = document.getElementById("bottom-bar-one");
    if (bottomBarOne) {
        gsap.to(bottomBarOne, {
            yPercent: -100,
            ease: "none",
            scrollTrigger: {
                trigger: "#footer",
                scrub: true,
            },
        });
    }

    const bottomBarTwo = document.getElementById("bottom-bar-two");
    if (bottomBarTwo) {
        gsap.to(bottomBarTwo, {
            yPercent: -400,
            ease: "none",
            scrollTrigger: {
                trigger: "#footer",
                scrub: true,
            },
        });
    }

    const bottomBarThree = document.getElementById("bottom-bar-three");
    if (bottomBarThree) {
        gsap.to(bottomBarThree, {
            yPercent: -200,
            ease: "none",
            scrollTrigger: {
                trigger: "#footer",
                scrub: true,
            },
        });
    }
}

setTimeout(triggerAnimations, 1000);
