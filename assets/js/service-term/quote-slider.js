import Splide from '@splidejs/splide';

const servicesSlider = new Splide('.quotes-slider .splide', {
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

servicesSlider.mount();