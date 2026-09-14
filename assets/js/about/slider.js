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

    // Hold the pin until the last beat, then hand the scroll back to the page.
    //
    // Splide 4's Drag component has disable(boolean) and no enable(), so the
    // else branch here threw a TypeError on every move: four errors across the
    // sequence, and drag never came back once it had been turned off, so
    // scrolling up from the last beat left the pin dead.
    aboutSlider.on('moved', () => {
        const onLastBeat = aboutSlider.index === aboutSlider.length - 1;

        aboutSlider.Components.Drag.disable(onLastBeat);
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

