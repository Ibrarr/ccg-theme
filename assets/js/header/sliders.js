import Splide from "@splidejs/splide";

/**
 * The three "Recent News & Views" carousels in the header's search panel.
 *
 * Splide's constructor throws "null is invalid" when its selector matches
 * nothing, and the constructor runs at module scope, so one missing carousel
 * takes the rest of the bundle down with it. The 404 template renders a header
 * without them and threw on every load. Mount only what is actually on the page.
 */
const OPTIONS = {
    type: 'rewind',
    pagination: false,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        640: {
            perPage: 2,
        },
    }
};

['.recent-insights .splide', '.recent-insights-search .splide', '.latest-updates .splide']
    .forEach(function (selector) {
        if (!document.querySelector(selector)) {
            return;
        }

        new Splide(selector, OPTIONS).mount();
    });
