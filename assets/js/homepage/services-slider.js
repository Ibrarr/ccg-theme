import Splide from '@splidejs/splide';

const sectorCardSlider = new Splide('.services-slider .splide', {
    type: 'slide',
    pagination: false,
    updateOnMove: true,
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
            fixedWidth: '275px',
        },
    }
});

sectorCardSlider.mount();

jQuery(document).ready(function ($) {
    $('.service-item').first().addClass('active').find('.service-description').show();
    moveSlidesToSplide('.first-set');

    $('.service-item').click(function (event) {
        event.stopPropagation();

        let $currentItem = $(this).closest('.service-item');
        let $currentDescription = $currentItem.find('.service-description');

        if ($currentItem.hasClass('active')) {
            return;
        }

        $('.service-item').removeClass('active').find('.service-description').slideUp().addClass('inactive');

        $('.service-item').find('.open-close-accordion .minus').fadeOut();
        $('.service-item').find('.open-close-accordion .plus').fadeIn();

        $currentItem.addClass('active');
        $currentDescription.slideDown().removeClass('inactive');

        $currentItem.find('.open-close-accordion .plus').fadeOut();
        $currentItem.find('.open-close-accordion .minus').fadeIn();

        if ($currentItem.hasClass('first')) {
            moveSlidesToSplide('.first-set');
        } else if ($currentItem.hasClass('second')) {
            moveSlidesToSplide('.second-set');
        } else if ($currentItem.hasClass('third')) {
            moveSlidesToSplide('.third-set');
        }
    });

    function moveSlidesToSplide(slideClass) {
        const $servicesSlider = $('.services-slider');
        const $splideList = $servicesSlider.find('.splide__list');

        const $currentSlides = $splideList.children(':not(:first)');
        if ($currentSlides.length) {
            $currentSlides.appendTo($servicesSlider);
        }

        $servicesSlider.find(slideClass).each(function () {
            $splideList.append($(this));
        });

        sectorCardSlider.refresh();
    }
});
