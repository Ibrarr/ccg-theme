/**
 * Two homepage entrances from section E, both on scroll.
 *
 *   E3.2  the violet statement band: its serif italic lines build one after
 *         another, and the closing Poppins phrase ("and a team of world class
 *         technology marketers") lands last, because the change of voice is
 *         the punchline.
 *   E4.1  the About statement: the acid serif lead phrase glides in first and
 *         the rest follows.
 */
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { HALF_BEAT, glide, onReveal, sequence } from '../shared/text-motion';

gsap.registerPlugin( ScrollTrigger );

// E3.2.
const band = document.querySelector( 'section.intro .statement' );

onReveal( ScrollTrigger, band, band, () => sequence( band, {
	phrase: '.intro-snap',
	order: 'last',
	rest: 'build',
} ) );

// E4.1. The lead phrase is the editor's own highlight span in the first
// paragraph; any paragraph after it arrives with the rest of the sentence.
const about = document.querySelector( '.about-ccg' );

if ( about ) {
	const paragraphs = [ ...about.querySelectorAll( '.container > p' ) ];

	onReveal( ScrollTrigger, about, paragraphs, () => {
		const [ first, ...others ] = paragraphs;
		const hasLead = Boolean( first && first.querySelector( ':scope > span' ) );

		sequence( first, { phrase: 'span', order: 'first', rest: 'glide' } );
		others.forEach( ( paragraph ) => glide( paragraph, { delay: hasLead ? HALF_BEAT : 0 } ) );
	} );
}
