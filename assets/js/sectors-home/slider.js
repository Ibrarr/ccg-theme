import Splide from '@splidejs/splide';

const sectorCardSlider = new Splide('.sectors-slider .splide', {
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

const sectorNameSlider = new Splide('.sector-name-slide .splide', {
    type: 'loop',
    direction: 'ttb',
    focus: 'center',
    pagination: false,
    drag: false,
    arrows: false,
    updateOnMove: true,
    omitEnd: true,
    perMove: 1,
    perPage: 5,
    height: '100%',
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        700: {
            perPage: 7,
        },
    }
});

sectorCardSlider.mount();
sectorNameSlider.mount();

jQuery(document).ready(function ($) {
    $(".recent-post").hover(
        function () {
            $(this).find(".post-intro").slideDown();
        },
        function () {
            $(this).find(".post-intro").slideUp();
        }
    );

    if (window.matchMedia('(min-width: 991px)').matches) {
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

    sectorCardSlider.on('move', function (newIndex) {
        if (newIndex === 0) {
            $('.first-slide').fadeIn(1500);
            sectorNameSlider.go(0);
        } else {
            $('.first-slide').fadeOut(1000);
            sectorNameSlider.go(newIndex - 1);
        }
    });
});