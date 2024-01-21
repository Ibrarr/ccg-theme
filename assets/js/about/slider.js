import Splide from '@splidejs/splide';

jQuery(document).ready(function ($) {
    const aboutSlider = new Splide('.about-slider .splide', {
        type: 'slide',
        direction: 'ttb',
        pagination: false,
        arrows: false,
        updateOnMove: true,
        wheel: true,
        releaseWheel: true,
        height: 'var(--app-height)',
        perPage: 1,
        wheelSleep: 1000,
        speed: 1000,
        easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
        loader: false,
    });

    aboutSlider.on('mounted', () => {
        $('body').css('display', 'block');
    });

    aboutSlider.mount();

    aboutSlider.on('moved', () => {
        if (aboutSlider.index === aboutSlider.length - 1) {
            aboutSlider.Components.Drag.disable(true);
        } else {
            aboutSlider.Components.Drag.enable();
        }
    });

    aboutSlider.on('move', (newIndex) => {
        const $textElement = $('.about-slide');
        const aboutSliderClass = $('.about-slider');

        $textElement.fadeOut(500, () => {
            $textElement.fadeIn(500);
        });

        aboutSliderClass.removeClass(function (index, className) {
            return (className.match(/\bslide-\d+\b/g) || []).join(' ');
        });

        aboutSliderClass.addClass(`slide-${newIndex + 1}`);
    });
});

