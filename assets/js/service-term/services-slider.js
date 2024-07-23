import Splide from '@splidejs/splide';

const servicesSlider = new Splide('.services-slider .splide', {
    type: 'slide',
    pagination: false,
    updateOnMove: true,
    cover: true,
    omitEnd: true,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    fixedWidth: '400px',
    gap: '2em',
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        1400: {
            perPage: 2,
        },
        991: {
            perPage: 1,
        },
        800: {
            fixedWidth: '275px',
        },
    }
});

servicesSlider.mount();

jQuery(document).ready(function ($) {
    $('.next-slide').on('click', function () {
        $('.sectors-slider .splide__arrow.splide__arrow--next').trigger('click');
    });

    // servicesSlider.on('move', function (newIndex) {
    //     if (newIndex === 0) {
    //         $('.first-slide').fadeIn(1500);
    //     } else {
    //         $('.first-slide').fadeOut(1000);
    //     }
    // });
});