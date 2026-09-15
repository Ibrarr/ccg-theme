/**
 * Section E, movements 1 and 2. Defined here once and reused; nothing else in
 * the theme should write its own text entrance.
 *
 * E2 gives two movements and no others:
 *
 *   glide  - the whole phrase slides in ~50px from the left while fading up.
 *            One piece, one motion. Every Libre Baskerville italic phrase.
 *   build  - the heading is split and each piece glides in turn, top first, so
 *            the statement assembles rather than appearing at once.
 *
 * E6 sets the craft rules these implement:
 *   1. once per visit        - callers pass `once` to their own trigger
 *   2. quick and confident   - a movement is 0.5s, the gap between pieces 0.07s
 *   4. no layout shift       - transform and opacity only, never a layout prop
 *   6. reduced motion        - movement is dropped, a short fade remains
 *   7. whole sentences       - SplitText's `aria: "auto"` keeps the element's
 *                              accessible name intact and hides the pieces
 */
import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin( SplitText );

// The three numbers come off :root, not out of this file, so the CSS
// implementation of the glide and this one read the SAME source. E2 asks for
// the movements to be defined once; two copies of "50px, 500ms" in two
// languages is how they drift.
function token( name, fallback ) {
	if ( typeof window === 'undefined' ) {
		return fallback;
	}

	const raw = getComputedStyle( document.documentElement ).getPropertyValue( name ).trim();
	const value = parseFloat( raw );

	return Number.isFinite( value ) ? value : fallback;
}

// E2: "about 50px to the left of its final position, then slides right into
// place while fading in" - Hoffman's own house move, taken from their build.
export const DISTANCE = token( '--text-glide-distance', 50 );

// E6.2: "roughly a third to half a second". Milliseconds in the token, seconds
// here, because that is the unit each language wants.
export const DURATION = token( '--text-glide-duration', 500 ) / 1000;

// E6.2: "the gap between lines in a build is a small fraction of that". 70ms is
// inside the 30-80ms band that keeps a cascade from reading as slow.
export const STAGGER = token( '--text-build-stagger', 70 ) / 1000;

// Entering elements take an ease-out so the movement is fastest at the moment
// the eye arrives. power3.out IS the cubic ease-out that
// --text-glide-ease spells as a bezier, so a tween and a transition using the
// tokens travel identically.
export const EASE = 'power3.out';

// Words rather than lines cascade faster, so the gap has to come down or a
// twelve-word statement runs past a second on its own.
export const WORD_STAGGER = 0.035;

export function reducedMotion() {
	return typeof window !== 'undefined'
		&& window.matchMedia
		&& window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
}

/**
 * Movement 1. Returns the tween so a caller can place it on a timeline.
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

/**
 * Movement 2. Splits `element` and glides the pieces in turn.
 *
 * `type` is "lines" for a display headline and "words" for the homepage hero,
 * which E2 allows explicitly: the hero's remainder shares its first line box
 * with the acid lead-in beside it, so a line split would have to turn that one
 * flowing sentence into stacked blocks and move the layout underneath it.
 *
 * The animation is created inside onSplit and returned from it, which is what
 * lets autoSplit re-split on a font load or a width change without stranding a
 * half-finished tween on elements that no longer exist.
 */
export function build( element, { type = 'lines', delay = 0, stagger, onDone } = {} ) {
	if ( ! element ) {
		return null;
	}

	if ( reducedMotion() ) {
		return gsap.fromTo( element, { opacity: 0 }, {
			opacity: 1, duration: 0.2, ease: 'none', delay, onComplete: onDone,
		} );
	}

	const gap = stagger || ( type === 'words' ? WORD_STAGGER : STAGGER );

	return SplitText.create( element, {
		type,
		// Spans, not divs: the pieces sit inside a sentence that is still
		// flowing inline, and a div would break it onto its own line.
		tag: 'span',
		// E6.7. "auto" puts the full sentence on the element as an aria-label
		// and hides the pieces, so the split is visual only and a screen reader
		// still reads one heading.
		aria: 'auto',
		// Libre Baskerville loads async, so a split taken before it arrives
		// measures the fallback's line breaks. autoSplit re-splits on the font
		// load and on any width change.
		autoSplit: true,
		onSplit( self ) {
			const pieces = type === 'words' ? self.words : self.lines;

			return gsap.fromTo( pieces,
				{ x: -DISTANCE, opacity: 0 },
				{
					x: 0,
					opacity: 1,
					duration: DURATION,
					ease: EASE,
					force3D: true,
					stagger: gap,
					delay,
					onComplete: onDone,
				}
			);
		},
	} );
}
