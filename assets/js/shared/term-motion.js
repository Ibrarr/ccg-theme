/**
 * Sector and service pages: three single entrances from section E's tier 2.
 *
 *   E4.2  the opening statement under the title glides in on load
 *   E4.3  the violet quote band's quote glides in as the band arrives, and the
 *         attribution follows a beat later
 *   E4.5  the "Why CCGroup" statement glides in as its band arrives
 *
 * The kicker above the Why CCGroup statement is an eyebrow, which E5 keeps
 * still, and nothing inside a card moves.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { BEAT, glide, onReveal } from './text-motion';

gsap.registerPlugin( ScrollTrigger );

// E4.2. Hidden before the first paint by the head's `tm-armed` flag.
const opening = document.documentElement.classList.contains( 'tm-armed' )
	? document.querySelector( '.term-header .intro .statement' )
	: null;

if ( opening ) {
	glide( opening );
}

// E4.5.
const why = document.querySelector( '.why-ccg .statement' );

onReveal( ScrollTrigger, why, why, () => glide( why ) );

// E4.3. The band is a carousel that may already have moved on by the time it
// scrolls into view, so the quote that glides is whichever one is on screen
// then, and every other copy is simply shown.
const band = document.querySelector( '.quotes-slider' );

if ( band ) {
	const quotes = [ ...band.querySelectorAll( '.quote' ) ];
	const authors = [ ...band.querySelectorAll( '.author' ) ];

	onReveal( ScrollTrigger, band, [ ...quotes, ...authors ], () => {
		const left = band.getBoundingClientRect().left;
		const width = band.getBoundingClientRect().width;
		const onScreen = [ ...band.querySelectorAll( '.splide__slide' ) ].find( ( slide ) => {
			const box = slide.getBoundingClientRect();

			return box.width > 0 && box.left >= left - 2 && box.left < left + ( width / 2 );
		} ) || band;

		const quote = onScreen.querySelector( '.quote' );
		const author = onScreen.querySelector( '.author' );

		gsap.set( [ ...quotes, ...authors ].filter( ( el ) => el !== quote && el !== author ), { opacity: 1 } );

		glide( quote );
		glide( author, { delay: BEAT } );
	} );
}
