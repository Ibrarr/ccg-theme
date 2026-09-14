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

    // Both scoped to this slider. The homepage has two sliders and each has a
    // .first-slide: the sectors one holds the intro panel, the services one
    // holds the services accordion. Unscoped, moving the sectors slider faded
    // the services slider's first slide out with it, which is the two sliders
    // appearing to move together.
    const $sectors = $('.sectors-slider');

    $sectors.find('.next-slide').on('click', function () {
        $sectors.find('.splide__arrow.splide__arrow--next').trigger('click');
    });

    sectorSlider.on('move', function (newIndex) {
        const $firstSlide = $sectors.find('.first-slide');

        if (newIndex === 0) {
            $sectors.removeClass('first-slide-inactive');
            $firstSlide.fadeIn(1500);
        } else {
            $sectors.addClass('first-slide-inactive');
            $firstSlide.fadeOut(1000);
        }
    });
});