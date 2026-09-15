import Splide from '@splidejs/splide';
import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';
import { reducedMotion, DISTANCE, DURATION, EASE, STAGGER, DELAY } from '../shared/text-motion';

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
 * E3.1, the reference implementation, built to match hoffman.com rather than to
 * interpret it. Their own source, from `all.min.js`:
 *
 *   document.querySelectorAll(".split-lines-fade").forEach(t => {
 *     gsap.set(t, { opacity: 1 });
 *     var e = new SplitText(t, { type: "lines" });
 *     gsap.from(e.lines, { duration: .5, opacity: 0, x: -50,
 *                          force3D: !0, stagger: .15, delay: .1 });
 *   });
 *
 * Every number above now lives on :root and is read through text-motion.js, so
 * this file holds none of its own. The ease is unspecified in their code, which
 * means GSAP's default, power1.out. An earlier build ran a 35ms word cascade on
 * a quartic ease, four times their pace on pieces a quarter the size: it read
 * as a ripple where theirs reads as a cascade, and that is what this corrects.
 *
 * The unit of movement is the LINE, as theirs is. It is assembled differently,
 * and for two reasons.
 *
 * SplitText's own line wrappers are wrong on this markup. Its line grouping
 * walks the statement's top-level children and starts a new line where one sits
 * both lower and further left than the last. That is exact when the children
 * are words, and this statement's children are two coloured spans: deepSlice
 * cuts `.sub-heading` at the right places, but the grouping then merges two of
 * the pieces into one wrapper, so a "line" holds two visual lines. Measured at
 * 1440: three rendered lines, two wrappers, the first of them 119.6px against a
 * 59.8px leading. A plain-text statement and a statement with ONE nested span
 * both split correctly; the second span is what tips it over.
 *
 * And a line wrapper is a block, so introducing one restructures a sentence
 * that is currently two inline spans. Splitting into WORDS restructures nothing
 * at all: a word was already an unbreakable unit between two spaces, so the
 * sentence wraps exactly where it did. Measured at 1068, 760 and 420px, the
 * statement's height is identical to the pixel before and after the split.
 *
 * So the words are grouped into lines by where they actually landed, and each
 * line's words move together on one beat. Visually that is Hoffman's movement
 * exactly; mechanically it is the same idea done against the rendered result
 * rather than against a guess at it. It also never goes stale: the word spans
 * do not depend on the width or on which face is loaded, so a resize or a late
 * font only changes which line a word is grouped into, and that is recomputed
 * on every play.
 *
 * One consequence to flag, and it is a real divergence from E3.1's wording
 * rather than an oversight. E3.1 asks for the acid italic lead-in to glide in
 * on its own first, with the remainder following half a beat later. Hoffman get
 * that for free because their accent phrase is its own line: their H1 is a stack
 * of `.main-header` divs and the yellow span is the whole of the first one. Our
 * approved hero runs the lead-in and the remainder as ONE flowing serif
 * sentence, per the Figma, so line 1 carries the lead-in and the opening words
 * of the remainder together and the lead-in cannot arrive alone. Matching their
 * move and keeping our layout are mutually exclusive.
 *
 * Client decision, Sept 2026: it replays on every slide change. E6.1's "once
 * per visit" governs a ScrollTrigger re-firing on scroll, not a carousel that
 * is already moving on its own.
 */

// Two words are on the same line if their tops are within this. Generous
// enough to absorb sub-pixel rounding, far inside the smallest leading the
// statement ever renders at (32.2px at 375).
const LINE_TOLERANCE = 6;

const splits = new WeakMap();

function statementOf( slide ) {
	return slide ? slide.querySelector( '.header-statement' ) : null;
}

// The design's separator between the two halves is drawn by CSS, as
// `.sub-heading:before`, so that it is there whether or not this script runs.
// A pseudo-element cannot be tweened, though, and a hyphen sitting at full
// opacity while the line glides in behind it is exactly the seam this is meant
// to avoid. Once the script is running it becomes a real element instead and
// travels with its line; `has-motion` stands the pseudo down so the glyph is
// never drawn twice. It is aria-hidden, and the split has already taken the
// statement's accessible name off the authored text, so the name never sees it.
function promoteSeparator( statement ) {
	const sub = statement.querySelector( '.sub-heading' );

	if ( ! sub ) {
		return null;
	}

	statement.classList.add( 'has-motion' );

	const separator = document.createElement( 'span' );

	separator.className = 'header-sep';
	separator.setAttribute( 'aria-hidden', 'true' );
	separator.textContent = '- ';
	sub.insertBefore( separator, sub.firstChild );

	return separator;
}

function piecesOf( statement ) {
	if ( ! statement ) {
		return null;
	}

	let record = splits.get( statement );

	if ( ! record ) {
		const split = new SplitText( statement, {
			type: 'words',
			// Spans, not SplitText's default div: the statement is an <h1> on
			// the first slide and a <p> on the rest, and the words sit inside a
			// sentence that is still flowing inline.
			tag: 'span',
			// E6.7: "auto" puts the whole sentence on the element as an
			// aria-label and hides the pieces, so the split is visual only and
			// a screen reader still reads one heading. Taken here, before the
			// separator is promoted, so the name stays exactly as authored.
			aria: 'auto',
		} );

		const separator = promoteSeparator( statement );

		record = {
			split,
			targets: separator ? [ separator, ...split.words ] : split.words.slice(),
		};

		splits.set( statement, record );
	}

	return record.targets;
}

// Which line each piece ended up on, read off the rendered boxes. Recomputed
// every time rather than cached, because the answer changes with the width and
// with which face is loaded, and neither is worth tracking when reading it
// costs one pass.
function lineOf( targets ) {
	const tops = targets.map( ( t ) => t.getBoundingClientRect().top );
	const bands = [];

	[ ...tops ].sort( ( a, b ) => a - b ).forEach( ( top ) => {
		if ( ! bands.length || ( top - bands[ bands.length - 1 ] > LINE_TOLERANCE ) ) {
			bands.push( top );
		}
	} );

	return tops.map( ( top ) => {
		let line = 0;

		bands.forEach( ( band, index ) => {
			if ( top >= ( band - LINE_TOLERANCE / 2 ) ) {
				line = index;
			}
		} );

		return line;
	} );
}

function enter( targets ) {
	if ( ! targets || ! targets.length ) {
		return;
	}

	// E6.6: reduced motion keeps the fade, which still says something arrived,
	// and drops the travel, which is the part that causes sickness.
	if ( reducedMotion() ) {
		gsap.fromTo( targets, { opacity: 0 },
			{ opacity: 1, duration: 0.2, ease: 'none', delay: DELAY } );

		return;
	}

	const lines = lineOf( targets );

	gsap.fromTo( targets,
		{ x: -DISTANCE, opacity: 0 },
		{
			x: 0,
			opacity: 1,
			duration: DURATION,
			ease: EASE,
			force3D: true,
			delay: DELAY,
			// A whole line moves on one beat, and the beat is Hoffman's 0.15s.
			stagger: ( index ) => lines[ index ] * STAGGER,
		}
	);
}

// The slide the reader is actually looking at. With `updateOnMove: true` this
// class lands on the DESTINATION at the start of a move, which is what lets the
// incoming slide be hidden before it arrives and animated once it lands.
//
// Never fall back to slides[0]: in loop mode the DOM order is
// [clone, clone, real, real, real, clone, clone], so slides[0] is a CLONE. An
// earlier version fell back to it when `is-active` had not landed yet at mount,
// animated the clone, and left the real H1 sitting at opacity 0 for the visit.
function activeSlide() {
	return document.querySelector( '.header-slider .splide__slide.is-active' );
}

// Hide the incoming slide's words so it arrives blank rather than showing its
// finished sentence for the second the transition takes and then snapping back.
function prime( slide ) {
	if ( reducedMotion() ) {
		return;
	}

	const targets = piecesOf( statementOf( slide ) );

	if ( targets && targets.length ) {
		gsap.set( targets, { opacity: 0 } );
	}
}

function playSlide( slide ) {
	enter( piecesOf( statementOf( slide ) ) );
}

// Nothing may be left invisible, whatever happens above. If the slide on screen
// still has hidden words once the sequence should long since have finished,
// show them. A missed animation is a small loss; a hero with no heading is not.
function failsafe() {
	const targets = piecesOf( statementOf( activeSlide() ) );

	if ( ! targets || ! targets.length ) {
		return;
	}

	const hidden = targets.some( ( t ) => parseFloat( getComputedStyle( t ).opacity ) < 0.9 );

	if ( hidden ) {
		gsap.set( targets, { opacity: 1, x: 0 } );
	}
}

headerSlider.on( 'mounted', () => {
	// Splide sets is-active during mount. If it has not landed on this tick,
	// wait a frame rather than guessing at a slide.
	const start = () => {
		const active = activeSlide();

		if ( ! active ) {
			requestAnimationFrame( start );

			return;
		}

		// E6.5: the hero must not sit empty. This runs the moment the carousel
		// mounts rather than waiting on a scroll or a font.
		playSlide( active );
		setTimeout( failsafe, 2500 );
	};

	start();
} );

// updateOnMove puts is-active on the destination as the move BEGINS, so this
// hides the incoming slide before the reader sees it.
headerSlider.on( 'move', () => {
	prime( activeSlide() );
} );

// Client decision, Sept 2026: every slide change replays it.
headerSlider.on( 'moved', () => {
	playSlide( activeSlide() );
	setTimeout( failsafe, 2500 );
} );

headerSlider.mount();
