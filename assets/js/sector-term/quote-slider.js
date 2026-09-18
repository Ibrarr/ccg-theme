import Splide from '@splidejs/splide';

// The quotes band is editor content, and a sector or service with no quotes
// renders no carousel. Splide throws "null is invalid" on a selector that
// matches nothing, and that stopped every script after it in the bundle: 19 of
// the 37 published sector and service pages threw on load (Sept 2026).
if (document.querySelector('.quotes-slider .splide')) {
    const newQuotesSlider = new Splide('.quotes-slider .splide', {
        type: 'loop',
        pagination: false,
        arrows: false,
        updateOnMove: true,
        autoplay: true,
        pauseOnHover: true,
        perMove: 1,
        perPage: 1,
        speed: 1000,
        easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    });

    newQuotesSlider.mount();
}