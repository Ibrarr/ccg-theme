/**
 * E3.4 and E4.6: the "Get in touch" heading, on every closing band and above
 * the Contact and Where We Work forms.
 *
 * The words "Get in touch" glide in from the left first, and the rest of the
 * heading slides in just before they reach their end position. A closing band
 * whose title has no serif phrase (sixteen service terms end on "Contact us")
 * takes the same glide as one piece.
 *
 * Both are movement 1 from text-motion.js, on GSAP, and play once, when the
 * heading reaches the reveal line or is already on screen once the page has
 * settled; see onReveal(). Before the script takes a heading over, the
 * stylesheet hides its pieces only when the head armed motion, so a page with
 * no JavaScript or with reduced motion shows the heading as it is.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { glide, onReveal, reducedMotion } from '../shared/text-motion';

gsap.registerPlugin( ScrollTrigger );

// "the rest of the paragraph slides in just before 'Get in touch' reaches its
// end position". On the glide's curve the phrase is visually home about 300ms
// into its 500ms, so that is where the rest sets off.
const REST_DELAY = 0.3;

const armed = document.documentElement.classList.contains( 'tm-armed' ) && ! reducedMotion();

[ ...document.querySelectorAll( '.bottom-cta .second-row h3, .contact-form h3' ) ]
	.filter( ( heading ) => heading.querySelector( '.cta-phrase' ) || heading.closest( '.bottom-cta' ) )
	.forEach( ( heading ) => {
		const phrase = heading.querySelector( '.cta-phrase' );
		const rest = heading.querySelector( '.cta-rest' );

		if ( ! phrase ) {
			onReveal( ScrollTrigger, heading, heading, () => glide( heading ) );

			return;
		}

		// A transform does nothing to an inline box, so the two halves become
		// inline-blocks while motion is on.
		if ( armed ) {
			heading.classList.add( 'cta-motion' );
		}

		onReveal( ScrollTrigger, heading, [ phrase, rest ], () => {
			glide( phrase );
			glide( rest, { delay: REST_DELAY } );
		} );
	} );
