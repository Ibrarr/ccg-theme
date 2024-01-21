import Splide from '@splidejs/splide';

const featuredSlider = new Splide('.featured-slider .splide', {
    type: 'loop',
    pagination: false,
    perPage: 1,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
});

featuredSlider.mount();