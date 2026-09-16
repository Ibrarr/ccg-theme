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

// cubic-bezier(x1, y1, x2, y2) as a GSAP ease: a function from progress to value.
// Written out rather than loaded through GSAP's CustomEase plugin, which put
// about 6.6KB on the homepage bundle for a fallback most visitors never see.
// Each axis of the curve is a cubic in the curve parameter s, with the ends
// pinned at 0 and 1. Solve x(s) = progress for s, by Newton's method and by
// bisection when the slope is too flat to trust, then return y(s).
function cubicBezier( x1, y1, x2, y2 ) {
	const axis = ( s, a, b ) => ( ( ( ( 1 - ( 3 * b ) ) + ( 3 * a ) ) * s + ( ( 3 * b ) - ( 6 * a ) ) ) * s + ( 3 * a ) ) * s;
	const slope = ( s, a, b ) => ( 3 * ( ( 1 - ( 3 * b ) ) + ( 3 * a ) ) * s * s ) + ( 2 * ( ( 3 * b ) - ( 6 * a ) ) * s ) + ( 3 * a );

	return ( progress ) => {
		if ( progress <= 0 ) {
			return 0;
		}

		if ( progress >= 1 ) {
			return 1;
		}

		let s = progress;

		for ( let i = 0; i < 8; i++ ) {
			const miss = axis( s, x1, x2 ) - progress;

			if ( Math.abs( miss ) < 1e-7 ) {
				return axis( s, y1, y2 );
			}

			const gradient = slope( s, x1, x2 );

			if ( Math.abs( gradient ) < 1e-6 ) {
				break;
			}

			s -= miss / gradient;
		}

		let low = 0;
		let high = 1;

		s = progress;

		for ( let i = 0; i < 40; i++ ) {
			const x = axis( s, x1, x2 );

			if ( Math.abs( x - progress ) < 1e-7 ) {
				break;
			}

			if ( x < progress ) {
				low = s;
			} else {
				high = s;
			}

			s = ( low + high ) / 2;
		}

		return axis( s, y1, y2 );
	};
}

// The reduced-motion fallback is ours rather than Hoffman's: a short fade with
// no travel. It is still an entrance, so it takes a strong ease-out, which puts
// most of the change in the first frames, rather than a linear ramp that builds
// evenly. The curve is the standard strong ease-out, cubic-bezier(0.23, 1, 0.32, 1).
const FADE_DURATION = 0.2;
const FADE_EASE = cubicBezier( 0.23, 1, 0.32, 1 );

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
			duration: FADE_DURATION,
			ease: FADE_EASE,
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

// An element's own inline width, held while its lines are cut. A passage that
// sizes its container (a flex item, like the About page's beats) measures as
// narrower once its lines cannot wrap, and the container re-centred sideways
// for the length of the entrance: a layout shift of 0.017 at 375px. Holding the
// element at the width it already had keeps everything around it still.
const heldWidth = new WeakMap();

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

			// A word's first box is where it starts.
			const boxes = range.getClientRects();
			const box = boxes[ 0 ];

			if ( ! box ) {
				continue;
			}

			if ( lastTop === null || ( box.top - lastTop ) > tolerance ) {
				starts.push( { node, offset: match.index } );
				lastTop = box.top;
			}

			// But a word can itself be broken across lines: "ultra-competitive"
			// wraps after its hyphen, and overflow-wrap splits anything too long for
			// its column. Missing that merged two lines into one wrapper, and a
			// nowrap line holding two lines' worth of text overflowed its box. So
			// find the character the next line starts on, and start a line there.
			if ( boxes.length > 1 && ( boxes[ boxes.length - 1 ].top - box.top ) > tolerance ) {
				for ( let i = match.index + 1; i < match.index + match[ 0 ].length; i++ ) {
					range.setStart( node, i );
					range.setEnd( node, i + 1 );

					const glyph = range.getClientRects()[ 0 ];

					if ( glyph && ( glyph.top - lastTop ) > tolerance ) {
						starts.push( { node, offset: i } );
						lastTop = glyph.top;
					}
				}
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

	if ( heldWidth.has( element ) ) {
		element.style.width = heldWidth.get( element );
		heldWidth.delete( element );
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

	heldWidth.set( element, element.style.width );
	element.style.width = `${ element.getBoundingClientRect().width }px`;

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

	// A centred line that measures a fraction wider on its own than it did in the
	// flow cannot stay centred: an overflowing line starts at the left edge, so
	// at 375px a whole line of the homepage About statement jumped 20px left.
	// Centred lines are given room either side, which keeps their centre.
	const centred = getComputedStyle( element ).textAlign === 'center';

	return fragments.map( ( fragment ) => {
		// A custom element rather than a span: editor bands style their own spans
		// (the homepage About statement paints every span acid serif), and a span
		// wrapper would take that styling and reflow the passage mid-flight.
		const line = document.createElement( 'tm-line' );

		line.className = centred ? 'tm-line tm-line--centred' : 'tm-line';
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
			opacity: 1, duration: FADE_DURATION, ease: FADE_EASE, delay, onComplete,
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

/*
 * The addendum's choreographies are all this one shape: a passage cut into its
 * rendered lines, with a serif phrase inside it that moves as one piece, either
 * before the rest (E3.1's lead-in, E4.1's lead phrase) or after it (E3.2's
 * closing phrase, E3.5's third paragraph). E6.3 is the reason the phrase is
 * held together: serif phrases always glide whole.
 */

// Half a beat: the lead-in is halfway home when the rest of the sentence sets
// off, so it is plainly first without the sentence waiting on it (E3.1).
export const HALF_BEAT = DURATION / 2;

// A beat: a follower sets off as the piece before it lands (E4.3's attribution).
export const BEAT = DURATION;

// Split a cut line into the phrase and the rest. A line that is all phrase or
// all rest moves as itself. Only a line holding both is divided, into
// inline-blocks, because an inline box cannot be transformed; the spaces and
// breaks between the pieces stay outside them, so the words sit where they did.
function partsOf( line, phrase ) {
	const isPhrase = ( node ) => node.nodeType === Node.ELEMENT_NODE && node.matches( phrase );
	// Cutting leaves the emptied shell of any span a line boundary fell inside:
	// a paragraph that opens with the phrase leaves an empty copy of the phrase's
	// span at the end of the line before. A shell holds nothing to move, and
	// wrapped in an inline-block it would open a line of its own, so shells stay
	// where they are, like breaks.
	const holdsText = ( node ) => node.textContent.trim() !== '';
	const nodes = [ ...line.childNodes ];
	const meaningful = nodes.filter( ( node ) => (
		node.nodeType === Node.ELEMENT_NODE ? node.tagName !== 'BR' && holdsText( node ) : holdsText( node )
	) );
	const phraseCount = meaningful.filter( isPhrase ).length;

	if ( phraseCount === 0 ) {
		return [ { el: line, phrase: false } ];
	}

	if ( phraseCount === meaningful.length ) {
		return [ { el: line, phrase: true } ];
	}

	const pieces = [];
	let current = null;

	const open = ( isPhrasePiece, before ) => {
		const el = document.createElement( 'tm-part' );

		el.className = 'tm-part';
		line.insertBefore( el, before );
		current = { el, phrase: isPhrasePiece };
		pieces.push( current );
	};

	nodes.forEach( ( node ) => {
		if ( node.nodeType === Node.TEXT_NODE ) {
			const [ , lead, core, trail ] = node.textContent.match( /^(\s*)([\s\S]*?)(\s*)$/ );

			if ( core === '' ) {
				current = null;
				return;
			}

			if ( lead ) {
				line.insertBefore( document.createTextNode( lead ), node );
				current = null;
			}

			if ( ! current || current.phrase ) {
				open( false, node );
			}

			current.el.appendChild( document.createTextNode( core ) );

			if ( trail ) {
				line.insertBefore( document.createTextNode( trail ), node );
				current = null;
			}

			node.remove();
			return;
		}

		// A break stays in the line itself. Inside an inline-block its empty line
		// would become the block's baseline and lift the text a whole line.
		if ( node.nodeType !== Node.ELEMENT_NODE || node.tagName === 'BR' || ! holdsText( node ) ) {
			current = null;
			return;
		}

		const isPhrasePiece = isPhrase( node );

		if ( ! current || current.phrase !== isPhrasePiece ) {
			open( isPhrasePiece, node );
		}

		current.el.appendChild( node );
	} );

	return pieces;
}

/**
 * Movement 2 with a voice change. Returns { timeline, lastStart }, lastStart
 * being when the final piece sets off, so a caller can cascade the next element
 * from it.
 *
 *   phrase  selector for the serif phrase that moves whole. Optional.
 *   order   'first': the phrase glides, the rest follows half a beat later.
 *           'last':  the rest arrives, then the phrase lands a stagger behind.
 *   rest    'build': the rest arrives line by line.
 *           'glide': the rest arrives as one piece.
 */
export function sequence( element, { phrase = null, order = 'first', rest = 'build', delay = 0, onComplete } = {} ) {
	if ( ! element ) {
		return null;
	}

	if ( reducedMotion() ) {
		restore( element );

		const timeline = gsap.fromTo( element, { opacity: 0 }, {
			opacity: 1, duration: FADE_DURATION, ease: FADE_EASE, delay, onComplete,
		} );

		return { timeline, lastStart: delay };
	}

	const hasPhrase = Boolean( phrase && element.querySelector( phrase ) );

	// Nothing to hold back and nothing to build: the passage is one piece, and a
	// block can simply glide.
	if ( ! hasPhrase && rest === 'glide' && getComputedStyle( element ).display !== 'inline' ) {
		restore( element );
		gsap.set( element, { opacity: 1 } );

		return { timeline: glide( element, { delay, onComplete } ), lastStart: delay };
	}

	const lines = cutLines( element );

	if ( ! lines.length ) {
		settle( element );

		return null;
	}

	const phrasePieces = [];
	const restLines = [];

	lines.forEach( ( line ) => {
		const parts = hasPhrase ? partsOf( line, phrase ) : [ { el: line, phrase: false } ];
		const restHere = parts.filter( ( part ) => ! part.phrase ).map( ( part ) => part.el );

		parts.filter( ( part ) => part.phrase ).forEach( ( part ) => phrasePieces.push( part.el ) );

		if ( restHere.length ) {
			restLines.push( restHere );
		}
	} );

	const hidden = { x: -DISTANCE, opacity: 0 };
	const home = { x: 0, opacity: 1, duration: DURATION, ease: EASE, force3D: true };

	// The pieces take their start positions in the same frame the element comes
	// back to full opacity, so nothing flashes.
	gsap.set( [ ...phrasePieces, ...restLines.flat() ], hidden );
	gsap.set( element, { opacity: 1 } );

	const timeline = gsap.timeline( {
		delay,
		onComplete() {
			running.delete( element );
			restore( element );

			if ( onComplete ) {
				onComplete();
			}
		},
	} );

	const phraseFirst = phrasePieces.length && order === 'first';
	const restStart = phraseFirst ? HALF_BEAT : 0;
	let lastStart = 0;

	if ( phraseFirst ) {
		timeline.to( phrasePieces, home, 0 );
	}

	restLines.forEach( ( pieces, index ) => {
		const at = restStart + ( rest === 'build' ? index * STAGGER : 0 );

		timeline.to( pieces, home, at );
		lastStart = Math.max( lastStart, at );
	} );

	if ( phrasePieces.length && ! phraseFirst ) {
		const at = restLines.length ? lastStart + STAGGER : 0;

		timeline.to( phrasePieces, home, at );
		lastStart = Math.max( lastStart, at );
	}

	running.set( element, timeline );

	return { timeline, lastStart: delay + lastStart };
}

/**
 * Runs several elements as one cascade: each sets off a stagger after the last
 * piece of the one before it, so a title and its paragraph read as one thing
 * being set rather than two animations. Each step is { element, run( at ) },
 * where run returns what sequence() returns.
 */
export function cascade( steps, { delay = 0 } = {} ) {
	let at = delay;

	steps.forEach( ( step ) => {
		if ( ! step || ! step.element ) {
			return;
		}

		const handle = step.run( at );

		if ( handle ) {
			at = handle.lastStart + ( step.gap !== undefined ? step.gap : STAGGER );
		}
	} );

	return at;
}

/**
 * For entrances further down the page (E3.2, E4.1, E4.3, E4.5, F2).
 *
 * When motion is armed the stylesheet hides these before the first paint, so
 * nothing can show and then vanish. This takes them over: they stay hidden
 * until they reach the reveal line, and play once, the first time they do.
 *
 * The decision waits for the page to settle, which is `load` and then any archive
 * grid still arriving: see whenSettled(). Measured at parse time, the Our News
 * "Get in touch" heading sat at 719px, on a 900px screen, because the news tiles
 * above it had not loaded; once they had, it sat at 2494px. Deciding early
 * played it off screen before anyone scrolled to it. The wait is capped, so a
 * slow request cannot hold text back for long.
 *
 * ScrollTrigger measures where each start line is when the trigger is created,
 * and re-measures by itself only on load and resize. Anything that lands above
 * a trigger afterwards (card images finishing, a Load more click, a filter)
 * leaves that line too high, and the footer lockup played mid-grid, out of
 * sight. So while anything is still waiting, a change in the page's height
 * re-measures.
 *
 * ScrollTrigger is passed in by the caller so this module does not register the
 * plugin twice in bundles that already do.
 */
export function onReveal( ScrollTrigger, trigger, targets, play, { line = 0.85 } = {} ) {
	const list = ( Array.isArray( targets ) ? targets : [ targets ] ).filter( Boolean );

	if ( ! trigger || ! list.length ) {
		return;
	}

	const armed = document.documentElement.classList.contains( 'tm-armed' );

	if ( ! armed || reducedMotion() ) {
		// Nothing was hidden, so there is nothing to take over and nothing moves.
		list.forEach( ( el ) => el.classList.add( 'tm-owned' ) );

		return;
	}

	// Hidden inline first, then released from the stylesheet's rule, in the same
	// frame, so the elements never show in between.
	gsap.set( list, { opacity: 0 } );
	list.forEach( ( el ) => el.classList.add( 'tm-owned' ) );

	let played = false;

	const go = () => {
		if ( played ) {
			return;
		}

		played = true;
		waiting.delete( go );

		if ( ! waiting.size && heightWatch ) {
			heightWatch.disconnect();
			heightWatch = null;
		}

		play();
	};

	const decide = () => {
		if ( played ) {
			return;
		}

		if ( trigger.getBoundingClientRect().top < window.innerHeight * line ) {
			go();

			return;
		}

		ScrollTrigger.create( { trigger, start: `top ${ Math.round( line * 100 ) }%`, once: true, onEnter: go } );

		// Near the foot of a short page an element can never reach its start
		// line, so reaching the bottom of the page plays anything still waiting.
		ScrollTrigger.create( {
			trigger: document.documentElement,
			start: 'bottom bottom',
			once: true,
			onEnter: go,
		} );

		if ( ! played ) {
			waiting.add( go );
			watchHeight( ScrollTrigger );
		}
	};

	whenSettled( decide );
}

// Reveals created but not yet played, across every onReveal() in this bundle.
const waiting = new Set();
let heightWatch = null;

// Re-measure this bundle's triggers whenever the page changes height, a moment
// after it stops changing, until nothing is left waiting.
function watchHeight( ScrollTrigger ) {
	if ( heightWatch || typeof ResizeObserver === 'undefined' ) {
		return;
	}

	let height = document.documentElement.scrollHeight;
	let timer = null;

	heightWatch = new ResizeObserver( () => {
		const now = document.documentElement.scrollHeight;

		if ( now === height ) {
			return;
		}

		height = now;
		clearTimeout( timer );
		timer = setTimeout( () => ScrollTrigger.refresh(), 150 );
	} );

	heightWatch.observe( document.body );
}

// Runs `callback` once the page has settled: loaded (or 2.5s on), and then no
// archive grid still on its way.
//
// Eight templates fill #posts-container by jQuery AJAX on DOM ready: the
// Insight Hub, Our Work, Our News, Our Blog, the type, source and category
// archives and the search page. Locally the posts arrive before `load`; on
// staging admin-ajax answers about a second after it, so everything below the
// grid measured a screen or two higher than it would sit. Decided then, the
// "Get in touch" heading and the footer lockup played while nobody could see
// them. jQuery counts its own requests in flight and fires `ajaxStop` once the
// last one's success handler has run, which is after the posts are in the page.
// Two frames later they are laid out. Capped, so a hung request cannot keep
// text hidden.
export function whenSettled( callback ) {
	let done = false;
	let checked = false;

	const run = () => {
		if ( ! done ) {
			done = true;
			callback();
		}
	};

	const afterRequests = () => {
		if ( checked ) {
			return;
		}

		checked = true;

		const $ = window.jQuery;

		if ( ! $ || ! $.active ) {
			run();

			return;
		}

		$( document ).one( 'ajaxStop', () => requestAnimationFrame( () => requestAnimationFrame( run ) ) );
		setTimeout( run, 4000 );
	};

	if ( document.readyState === 'complete' ) {
		afterRequests();

		return;
	}

	window.addEventListener( 'load', afterRequests, { once: true } );
	setTimeout( afterRequests, 2500 );
}
