import Splide from '@splidejs/splide';

const sectorSlider = new Splide('.sectors-slider .splide', {
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

sectorSlider.mount();

jQuery(document).ready(function ($) {
    if (window.matchMedia('(min-width: 991px)').matches) {
        $(".recent-post").hover(
            function () {
                $(this).find(".post-intro").slideDown();
            },
            function () {
                $(this).find(".post-intro").slideUp();
            }
        );

        $(".sector-slide").hover(
            function () {
                $(this).find(".sub-heading").slideDown();
            },
            function () {
                $(this).find(".sub-heading").slideUp();
            }
        );
    }

    $('.next-slide').on('click', function () {
        $('.sectors-slider .splide__arrow.splide__arrow--next').trigger('click');
    });

    sectorSlider.on('move', function (newIndex) {
        if (newIndex === 0) {
            $('.sectors-slider').removeClass('first-slide-inactive');
            $('.first-slide').fadeIn(1500);
        } else {
            $('.sectors-slider').addClass('first-slide-inactive');
            $('.first-slide').fadeOut(1000);
        }
    });
});