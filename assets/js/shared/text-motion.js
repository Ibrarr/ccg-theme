/**
 * Section E, movements 1 and 2. Defined here once and reused; nothing else in
 * the theme should write its own text entrance.
 *
 * E2 gives two movements and no others:
 *
 *   glide  - the whole phrase slides in 50px from the left while fading up.
 *            One piece, one motion. Every Libre Baskerville italic phrase.
 *   build  - the text is broken into its rendered lines and each line glides in
 *            turn, top first, so the statement assembles rather than appearing.
 *
 * Both are Hoffman's own move, from their all.min.js. Their homepage hero is
 *
 *   gsap.timeline().from(".home-intro-header .main-header",
 *     { duration: .5, opacity: 0, x: -50, force3D: !0, stagger: .15 });
 *
 * and every number in it lives on :root, read below, so the GSAP tweens and the
 * CSS transitions that use the same tokens cannot drift apart.
 *
 * E6 sets the craft rules these implement:
 *   1. once per visit        - callers decide when to call, and only once
 *   2. quick and confident   - a movement is 0.5s, lines 0.15s apart
 *   4. no layout shift       - transform and opacity only, and the lines are
 *                              cut exactly where the browser already broke them
 *   6. reduced motion        - the travel is dropped, a short fade remains
 *   7. whole sentences       - the text stays in the element, in order, and the
 *                              markup is restored untouched once the lines land
 */
import { gsap } from 'gsap';

// The numbers come off :root, not out of this file, so the CSS implementation
// of the glide and this one read the SAME source.
function token( name, fallback ) {
	if ( typeof window === 'undefined' ) {
		return fallback;
	}

	const raw = getComputedStyle( document.documentElement ).getPropertyValue( name ).trim();
	const value = parseFloat( raw );

	return Number.isFinite( value ) ? value : fallback;
}

// E2: "about 50px to the left of its final position". Hoffman's x: -50.
export const DISTANCE = token( '--text-glide-distance', 50 );

// Hoffman's duration: .5. Milliseconds in the token, seconds here.
export const DURATION = token( '--text-glide-duration', 500 ) / 1000;

// Hoffman's stagger: .15, the gap between one line starting and the next.
export const STAGGER = token( '--text-build-stagger', 150 ) / 1000;

// Hoffman leave the ease unspecified, so their reveal runs on GSAP's default.
// Named here to keep it explicit, and in step with the bezier on :root, which
// is the same quadratic curve.
export const EASE = 'power1.out';

// The tenth of a second Hoffman's SCROLL reveals wait (`.split-lines-fade`, on
// their section headings). Their homepage hero does not wait at all.
export const DELAY = token( '--text-glide-delay', 100 ) / 1000;

export function reducedMotion() {
	return typeof window !== 'undefined'
		&& window.matchMedia
		&& window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
}

/**
 * Movement 1. Returns the tween so a caller can place it on a timeline.
 *
 * The target must not be display: inline. A CSS transform does nothing to a
 * non-replaced inline box, so an inline target fades and never travels while
 * getComputedStyle reports the travel as if it happened. The CTA glide works
 * because its halves are inline-block.
 */
export function glide( targets, vars = {} ) {
	if ( ! targets || ( targets.length === 0 ) ) {
		return null;
	}

	// E6.6: reduced motion keeps the fade, which still explains that something
	// arrived, and drops the travel, which is the part that causes sickness.
	if ( reducedMotion() ) {
		return gsap.fromTo( targets, { opacity: 0 }, {
			opacity: 1,
			duration: 0.2,
			ease: 'none',
			delay: vars.delay || 0,
		} );
	}

	return gsap.fromTo( targets,
		{ x: -DISTANCE, opacity: 0 },
		{
			x: 0,
			opacity: 1,
			duration: DURATION,
			ease: EASE,
			// force3D keeps the whole movement on the compositor.
			force3D: true,
			...vars,
		}
	);
}

/*
 * Movement 2 needs the element's rendered lines, and it cannot get them from
 * SplitText, for two reasons measured on this theme's markup (GSAP 3.13.0).
 *
 * SplitText's line grouping walks the split element's top-level children and
 * starts a line where one sits both lower and further left than the last. That
 * is exact when those children are words. Statements here are coloured spans,
 * and with two of them it merges pieces of the second: the homepage hero came
 * out as two line wrappers for three rendered lines.
 *
 * And splitting into words instead does not rescue it. A transform does nothing
 * to an inline box and 3.13 creates inline spans, so the words have to become
 * inline-block to move, and an inline-block word loses its kerning against the
 * spaces either side. The drift builds along the line, about 0.6px a word and
 * more at glyphs like "A" and "&", and across a 320-1920px sweep in 10px steps
 * it changed where 9 of 483 statement-widths broke, adding a whole line at 750,
 * 890 and 960px.
 *
 * So the lines are cut where the browser already broke them. Each word's range
 * reports where it landed; a word lower than the line before starts a new line;
 * and Range.extractContents() lifts each line out whole. A coloured span that
 * straddles a line boundary is cloned into both halves by the DOM itself, which
 * is the slicing SplitText calls deepSlice, and a line that sits wholly inside
 * one span gets that span rebuilt around it. Each line goes into a block
 * wrapper, `.tm-line`, so it can move. The text inside a line is still ordinary
 * inline text, shaped and kerned as it was, which is why nothing shifts.
 *
 * A block line cannot reflow, so the lines only exist while they are moving.
 * When the last line lands, the element's original markup goes back, byte for
 * byte, and the sentence is free to wrap again on a resize or a late font.
 */

// Original markup, per element, captured before the first cut.
const pristine = new WeakMap();

// The tween currently moving an element's lines, so a second build can stop it
// cleanly rather than have its onComplete tear down the new lines mid-flight.
const running = new WeakMap();

function lineStarts( element ) {
	const leading = parseFloat( getComputedStyle( element ).lineHeight );
	// Two words share a line if their tops are within half a line of each
	// other. Generous against sub-pixel noise and against an italic face and a
	// roman one sitting side by side, and nowhere near a whole line.
	const tolerance = Number.isFinite( leading ) ? leading / 2 : 8;
	const walker = document.createTreeWalker( element, NodeFilter.SHOW_TEXT );
	const range = document.createRange();
	const starts = [];
	let lastTop = null;
	let node;

	while ( ( node = walker.nextNode() ) ) {
		const word = /\S+/g;
		let match;

		while ( ( match = word.exec( node.textContent ) ) ) {
			range.setStart( node, match.index );
			range.setEnd( node, match.index + match[ 0 ].length );

			// A word's first box is where it starts, even in the rare case a
			// very long one has been broken across two lines.
			const box = range.getClientRects()[ 0 ];

			if ( ! box ) {
				continue;
			}

			if ( lastTop === null || ( box.top - lastTop ) > tolerance ) {
				starts.push( { node, offset: match.index } );
				lastTop = box.top;
			}
		}
	}

	return starts;
}

function restore( element ) {
	const tween = running.get( element );

	if ( tween ) {
		tween.kill();
		running.delete( element );
	}

	if ( pristine.has( element ) && element.querySelector( ':scope > .tm-line' ) ) {
		element.innerHTML = pristine.get( element );
	}
}

// Cut `element` into its rendered lines. Returns the line wrappers.
function cutLines( element ) {
	restore( element );

	if ( ! pristine.has( element ) ) {
		pristine.set( element, element.innerHTML );
	}

	const starts = lineStarts( element );

	if ( ! starts.length ) {
		return [];
	}

	// Lift the lines out last first, so each boundary still names the same
	// place in the text when its turn comes. The first line takes everything
	// from the very start of the element, leading whitespace included, and the
	// last runs to the very end; a block line collapses both.
	const fragments = [];

	for ( let i = starts.length - 1; i >= 0; i-- ) {
		const range = document.createRange();

		if ( i === 0 ) {
			range.setStart( element, 0 );
		} else {
			range.setStart( starts[ i ].node, starts[ i ].offset );
		}

		if ( i === starts.length - 1 ) {
			range.setEnd( element, element.childNodes.length );
		} else {
			range.setEnd( starts[ i + 1 ].node, starts[ i + 1 ].offset );
		}

		// extractContents() clones the elements a range CROSSES, but not the
		// ones it sits entirely inside. A middle line of the hero starts and
		// ends in the same text node, so it came out as bare text with no
		// `.sub-heading` around it, lost that span's white, took the
		// statement's navy, and vanished against the navy wash while every
		// geometry check passed. So rebuild each enclosing element below the
		// statement, innermost first, around the extracted text.
		let enclosing = range.commonAncestorContainer;

		if ( enclosing.nodeType !== Node.ELEMENT_NODE ) {
			enclosing = enclosing.parentNode;
		}

		let piece = range.extractContents();

		for ( let node = enclosing; node && node !== element; node = node.parentNode ) {
			const shell = node.cloneNode( false );

			shell.appendChild( piece );
			piece = shell;
		}

		fragments.unshift( piece );
	}

	// What is left is only the emptied shells of spans that were cloned into
	// the lines.
	element.textContent = '';

	return fragments.map( ( fragment ) => {
		const line = document.createElement( 'span' );

		line.className = 'tm-line';
		line.appendChild( fragment );
		element.appendChild( line );

		return line;
	} );
}

/**
 * Put an element straight into its finished state, whatever it was doing.
 * For failsafes: a missed entrance is a small loss, invisible text is not.
 */
export function settle( element ) {
	if ( ! element ) {
		return;
	}

	restore( element );
	gsap.set( element, { opacity: 1 } );
}

/**
 * Movement 2. Cuts `element` into its rendered lines and glides them in turn.
 *
 * A caller may hide the element beforehand (opacity 0) so it arrives blank;
 * the build brings it back to 1 in the same frame the lines take their start
 * positions, so nothing flashes. Descendants are restored from their original
 * markup when the build lands, so anything holding a reference to a node inside
 * the element, or a listener bound directly to one, will not survive it. Links
 * and delegated handlers are unaffected.
 */
export function build( element, { delay = 0, stagger = STAGGER, onComplete } = {} ) {
	if ( ! element ) {
		return null;
	}

	if ( reducedMotion() ) {
		restore( element );

		return gsap.fromTo( element, { opacity: 0 }, {
			opacity: 1, duration: 0.2, ease: 'none', delay, onComplete,
		} );
	}

	const lines = cutLines( element );

	if ( ! lines.length ) {
		settle( element );

		return null;
	}

	gsap.set( element, { opacity: 1 } );

	const tween = gsap.fromTo( lines,
		{ x: -DISTANCE, opacity: 0 },
		{
			x: 0,
			opacity: 1,
			duration: DURATION,
			ease: EASE,
			force3D: true,
			stagger,
			delay,
			onComplete() {
				running.delete( element );
				restore( element );

				if ( onComplete ) {
					onComplete();
				}
			},
		}
	);

	running.set( element, tween );

	return tween;
}
