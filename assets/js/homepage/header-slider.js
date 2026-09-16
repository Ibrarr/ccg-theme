import Splide from '@splidejs/splide';
import { gsap } from 'gsap';
import { build, settle } from '../shared/text-motion';

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
 * E3.1, built to match hoffman.com's own hero rather than an interpretation of
 * it. Their homepage H1 is animated by exactly this, from their all.min.js:
 *
 *   gsap.set(".home-intro-header", { opacity: 1 });
 *   gsap.timeline().from(".home-intro-header .main-header",
 *     { duration: .5, opacity: 0, x: -50, force3D: !0, stagger: .15 });
 *
 * Each `.main-header` is one line, so each line glides in from 50px to the
 * left, 0.15s after the one above, on GSAP's default ease, with no delay. That
 * is movement 2 in text-motion.js, and this file only decides when it runs.
 *
 * Two earlier builds got this wrong, and both passed their own checks.
 *
 * The first matched Hoffman's `.split-lines-fade` scroll reveal instead of
 * their hero, so it waited a tenth of a second their hero never waits, and it
 * split the statement into inline word spans. A transform does nothing to an
 * inline box: every line faded in on time and not one of them moved. The check
 * read getComputedStyle, which reports what the tween asked for rather than
 * what was drawn, so it counted 56 positions while the rendered left edge never
 * left 84px. Measure movement with getBoundingClientRect or pixels.
 *
 * The second made those words inline-block so they would move, and that cost
 * their kerning against the spaces either side, which moved where the sentence
 * broke at 9 of 483 widths. The lines are now cut where the browser already
 * broke them and put back afterwards; see text-motion.js.
 *
 * One divergence from E3.1's wording, flagged rather than hidden. E3.1 asks for
 * the acid lead-in to glide in on its own first. Hoffman get that free because
 * their accent phrase is the whole of their first line. Our approved hero runs
 * the lead-in inline with the remainder, per the Figma, so line one carries
 * both and the lead-in does not arrive alone.
 *
 * Client decision, Sept 2026: it replays on every slide change. E6.1's "once
 * per visit" governs a ScrollTrigger re-firing on scroll, not a carousel that
 * is already moving on its own.
 */

function statementOf( slide ) {
	return slide ? slide.querySelector( '.header-statement' ) : null;
}

// The design's separator between the two halves is drawn by CSS, as
// `.sub-heading:before`, so that it is there whether or not this script runs.
// Cutting the sentence into lines clones `.sub-heading` into every line it
// spans, and every clone would draw its own hyphen. So once the script runs the
// separator becomes one real element and `has-motion` stands the pseudo down.
// Same characters, same place, still inside the span, so it is shaped exactly
// as the pseudo was. aria-hidden keeps it out of the heading's accessible name,
// which reads the authored text.
function prepare( statement ) {
	if ( statement.classList.contains( 'has-motion' ) ) {
		return;
	}

	const sub = statement.querySelector( '.sub-heading' );

	statement.classList.add( 'has-motion' );

	if ( ! sub ) {
		return;
	}

	const separator = document.createElement( 'span' );

	separator.className = 'header-sep';
	separator.setAttribute( 'aria-hidden', 'true' );
	separator.textContent = '- ';
	sub.insertBefore( separator, sub.firstChild );
}

// Slides are found by Splide's own index, never by the `is-active` class.
//
// In loop mode a slide and its clones are ALL marked is-active, and the DOM
// order is [clone, clone, real, real, real, clone, clone], so querySelector
// returns whichever copy comes first. For slides two and three that is a
// leading clone, off-screen: the entrance played to nobody while the real slide
// slid in with its sentence sitting still. And at `move` this handler runs
// before Splide has moved the class at all, so "the active slide" was still the
// OUTGOING one, and hiding it blanked the old sentence the instant the carousel
// started to move. Only slide one ever animated, because its real slide happens
// to precede its clone. Splide hands both events the new index; use it.
function slideAt( index ) {
	const component = headerSlider.Components.Slides.getAt( index );

	return component ? component.slide : null;
}

// Every copy of a slide: the real one and any loop clone of it. A clone carries
// the index of the slide it copies as `slideIndex`.
function copiesOf( index ) {
	return headerSlider.Components.Slides.get()
		.filter( ( component ) => component.index === index || component.slideIndex === index )
		.map( ( component ) => component.slide );
}

// Hide the incoming statement so it arrives blank rather than showing its
// finished sentence for the second the transition takes and then snapping back.
// The build brings it back to full opacity in the frame its lines take their
// start positions.
function prime( slide ) {
	const statement = statementOf( slide );

	if ( statement ) {
		prepare( statement );
		gsap.set( statement, { opacity: 0 } );
	}
}

function playSlide( slide ) {
	const statement = statementOf( slide );

	if ( statement ) {
		prepare( statement );
		build( statement );
	}
}

// Nothing may be left invisible, whatever happens above. If the statement on
// screen, or any line of it, is still hidden once the sequence should long
// since have finished, put it straight into its finished state.
function failsafe() {
	const statement = statementOf( slideAt( headerSlider.index ) );

	if ( ! statement ) {
		return;
	}

	const pieces = [ statement, ...statement.querySelectorAll( '.tm-line' ) ];

	if ( pieces.some( ( piece ) => parseFloat( getComputedStyle( piece ).opacity ) < 0.9 ) ) {
		settle( statement );
	}
}

headerSlider.on( 'mounted', () => {
	// E6.5: the hero must not sit empty. This runs the moment the carousel
	// mounts rather than waiting on a scroll or a font.
	playSlide( slideAt( headerSlider.index ) );
	setTimeout( failsafe, 2500 );
} );

// Every copy of the incoming slide is hidden, because which copy the carousel
// actually brings on screen depends on whether it has to wrap. The outgoing
// slide is not touched, so its sentence stays put as it leaves.
headerSlider.on( 'move', ( index ) => {
	copiesOf( index ).forEach( prime );
} );

// Client decision, Sept 2026: every slide change replays it. Once the move has
// landed the real slide is the one on screen, so that is the one that builds,
// and its clones go straight back to visible so no copy is left hidden.
headerSlider.on( 'moved', ( index ) => {
	const real = slideAt( index );

	copiesOf( index ).forEach( ( slide ) => {
		if ( slide !== real ) {
			settle( statementOf( slide ) );
		}
	} );

	playSlide( real );
	setTimeout( failsafe, 2500 );
} );

headerSlider.mount();
