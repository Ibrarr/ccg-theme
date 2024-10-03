import Splide from '@splidejs/splide';

const sectorCardSlider = new Splide('.services-slider .splide', {
    type: 'slide',
    pagination: false,
    updateOnMove: true,
    cover: true,
    omitEnd: true,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    height: '530px',
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
            height: '360px',
            fixedWidth: '275px',
        },
    }
});

sectorCardSlider.mount();

jQuery(document).ready(function ($) {
    $('.next-slide').on('click', function () {
        $('.sectors-slider .splide__arrow.splide__arrow--next').trigger('click');
    });

    sectorCardSlider.on('move', function (newIndex) {
        if (newIndex === 0) {
            $('.first-slide').fadeIn(1500);
        } else {
            $('.first-slide').fadeOut(1000);
        }
    });
});