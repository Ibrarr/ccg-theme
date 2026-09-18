import Splide from '@splidejs/splide';
import { cascade, reducedMotion, sequence } from '../shared/text-motion';

/*
 * E3.3: the pinned masthead keeps its scroll behaviour exactly, and when each
 * new statement arrives its lines appear one after another instead of the
 * whole passage landing at once. A statement, its support paragraph and a
 * display word build line by line in one cascade. An ethos passage's serif lead
 * sentence moves whole and first (E6.3), with its Poppins remainder building
 * beneath it. Each beat builds the first time it arrives only (E6.1); a beat
 * scrolled back to, and every beat under reduced motion, takes the existing
 * fade.
 */
const played = new Set();

function playBeat(slider, index) {
    const component = slider.Components.Slides.getAt(index);
    const beat = component && component.slide.querySelector('.about-slide');

    if (!beat) {
        return;
    }

    played.add(index);

    const parts = [...beat.children].filter((el) => el.matches('.heading, .sub-heading, .support, .body'));

    cascade(parts.map((element) => ({
        element,
        run: (at) => (element.matches('.body')
            ? sequence(element, { phrase: '.statement-lead', order: 'first', rest: 'build', delay: at })
            : sequence(element, { rest: 'build', delay: at })),
    })));
}

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

        // The page is hidden until the slider mounts, so the first beat can
        // build from here with no finished frame showing first (E6.5).
        if (!reducedMotion()) {
            playBeat(aboutSlider, aboutSlider.index);
        }
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

        // The existing swap: the statements fade out as the slide moves. The
        // callback used to run once per beat element and fade every one of them
        // in each time; the promise settles once.
        $textElement.fadeOut(500).promise().done(() => {
            if (reducedMotion() || played.has(newIndex)) {
                $textElement.fadeIn(500);
                return;
            }

            // Shown and cut into lines in the same tick, so nothing is painted
            // in between.
            $textElement.show();
            playBeat(aboutSlider, newIndex);
        });

        aboutSliderClass.removeClass(function (index, className) {
            return (className.match(/\bslide-\d+\b/g) || []).join(' ');
        });

        aboutSliderClass.addClass(`slide-${newIndex + 1}`);
    });
});

