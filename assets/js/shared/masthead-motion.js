/**
 * E3.5: the index page mastheads (insight hub, Our Work, Our News, Awards,
 * Careers). The page title builds line by line, the intro paragraph glides in
 * after it as one piece, and on the insight hub the serif third paragraph
 * arrives last.
 *
 * The title and paragraph are hidden before the first paint by the `tm-armed`
 * flag the head sets (see ccg_text_motion_prepaint()), so the finished text
 * never shows for a frame and then vanishes. Without that flag, which is what
 * reduced motion and a page with no JavaScript both get, nothing here runs and
 * the masthead is simply there.
 */
import { cascade, sequence } from './text-motion';

const armed = document.documentElement.classList.contains( 'tm-armed' );
const header = document.querySelector( '.archive-header, .awards-header, .careers-header' );

if ( armed && header ) {
	const title = header.querySelector( 'h1.title' );
	const statement = header.querySelector( '.intro .statement' );

	cascade( [
		{
			element: title,
			run: ( at ) => sequence( title, { rest: 'build', delay: at } ),
		},
		{
			element: statement,
			run: ( at ) => sequence( statement, {
				phrase: '.statement-closer',
				order: 'last',
				rest: 'glide',
				delay: at,
			} ),
		},
	] );
}
