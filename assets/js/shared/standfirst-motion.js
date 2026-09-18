/**
 * E4.4: an article's standfirst glides in on page load, and nothing more.
 * Editorial pages should feel calm. Blog posts, insights of every type, case
 * studies and the detailed report all carry the same standfirst role.
 *
 * Hidden before the first paint by the head's `tm-armed` flag, so without it
 * (reduced motion, or no JavaScript) the standfirst is simply there.
 */
import { glide } from './text-motion';

const standfirst = document.documentElement.classList.contains( 'tm-armed' )
	? document.querySelector( '.intro .statement, .report-standfirst' )
	: null;

if ( standfirst ) {
	glide( standfirst );
}
