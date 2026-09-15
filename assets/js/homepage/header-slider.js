import Splide from '@splidejs/splide';
import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { reducedMotion, DISTANCE, DURATION, EASE, WORD_STAGGER } from '../shared/text-motion';

const headerSlider = new Splide('.header-slider .splide', {
    type: 'loop',
    pagination: false,
    arrows: false,
    pauseOnHover: false,
    drag: false,
    autoplay: true,
    updateOnMove: true,
    cover: true,
    height: '85vh',
    perPage: 1,
    interval: 5000,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)'
});

// Splide's loop mode clones slides, and a clone of the first slide carries a
// copy of its <h1>, so the page reported two. The clones are presentational
// duplicates, so their heading is demoted to a <p> with the same classes: the
// markup and the styling are identical, only the rank goes. Runs after mount
// because the clones do not exist before it.
headerSlider.on('mounted', () => {
    document.querySelectorAll('.header-slider .splide__slide--clone h1').forEach((h1) => {
        const p = document.createElement('p');
        p.className = h1.className;
        p.innerHTML = h1.innerHTML;
        h1.replaceWith(p);
    });
});

/**
 * E3.1, the reference implementation.
 *
 * "On each slide: the acid italic lead-in glides in on its own first. Half a
 * beat later the rest of the sentence follows, building line by line beneath
 * it." Client decision, Sept 2026: it replays on every slide change. E6.1's
 * "once per visit" governs a ScrollTrigger re-firing on scroll, not a carousel
 * that is already moving on its own.
 *
 * The remainder builds by WORD, not by line, which E2 allows for this hero
 * specifically. The lead-in and the remainder are one flowing sentence and
 * share a line box, so a line split would have to turn them into stacked
 * blocks and move the layout underneath them, against E6.4.
 *
 * The serif lead-in is never split: E6.3 is explicit that a serif phrase moves
 * as one whole piece.
 */
const HERO_LEAD_OFFSET = 0.18; // "half a beat" behind the lead-in.

function heroParts( slide ) {
	const statement = slide && slide.querySelector( '.header-statement' );

	if ( ! statement ) {
		return null;
	}

	return {
		lead: statement.querySelector( '.heading' ),
		rest: statement.querySelector( '.sub-heading' ),
	};
}

// Both halves are `display: inline`, and a transform does nothing on an inline
// box. Splitting them into words is what makes them transformable WITHOUT
// touching the layout: the word spans are inline-block, but words were already
// unbreakable units separated by spaces, so the sentence wraps exactly where it
// did. Making the lead-in itself inline-block would instead stop the phrase
// breaking across lines at all, which is a layout change (E6.4).
//
// The lead-in's words carry a stagger of 0, so they move together and it still
// reads as the single piece E6.3 requires of a serif phrase.
const splits = new WeakMap();

function wordsOf( element ) {
	if ( ! element ) {
		return null;
	}

	let split = splits.get( element );

	if ( ! split ) {
		split = new SplitText( element, { type: 'words', tag: 'span', aria: 'auto' } );
		splits.set( element, split );
	}

	return split.words;
}

function enter( targets, { delay = 0, stagger = 0 } = {} ) {
	if ( ! targets || ! targets.length ) {
		return;
	}

	if ( reducedMotion() ) {
		gsap.fromTo( targets, { opacity: 0 }, { opacity: 1, duration: 0.2, ease: 'none', delay } );

		return;
	}

	gsap.fromTo( targets,
		{ x: -DISTANCE, opacity: 0 },
		{ x: 0, opacity: 1, duration: DURATION, ease: EASE, force3D: true, stagger, delay }
	);
}

// Every slide's text waits in the from-state, so a slide arriving mid-carousel
// never shows its finished sentence for the second the transition takes and
// then snaps back to animate it.
function prime( slide ) {
	const parts = heroParts( slide );

	if ( ! parts ) {
		return;
	}

	[ wordsOf( parts.lead ), wordsOf( parts.rest ) ].forEach( ( words ) => {
		if ( words && words.length ) {
			gsap.set( words, { opacity: 0 } );
		}
	} );
}

function playSlide( slide ) {
	const parts = heroParts( slide );

	if ( ! parts ) {
		return;
	}

	// The acid italic lead-in glides in on its own first...
	enter( wordsOf( parts.lead ), { stagger: 0 } );

	// ...and half a beat later the rest of the sentence builds behind it.
	enter( wordsOf( parts.rest ), { delay: HERO_LEAD_OFFSET, stagger: WORD_STAGGER } );
}

headerSlider.on( 'mounted', () => {
	const slides = document.querySelectorAll( '.header-slider .splide__slide' );

	slides.forEach( prime );

	// E6.5: the hero must not sit empty. This runs the moment the carousel
	// mounts rather than waiting on a scroll or a font.
	const active = document.querySelector( '.header-slider .splide__slide.is-active' ) || slides[ 0 ];

	playSlide( active );
} );

// Client decision, Sept 2026: every slide change replays it.
headerSlider.on( 'moved', () => {
	const active = document.querySelector( '.header-slider .splide__slide.is-active' );

	if ( active ) {
		playSlide( active );
	}
} );

headerSlider.mount();