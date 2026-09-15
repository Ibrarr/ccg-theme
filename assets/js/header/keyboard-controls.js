/**
 * Keyboard affordances for the theme's div-based controls.
 *
 * Every interactive control in this theme is built from a <div> or a <p>: the
 * burger, the search toggle, the three dropdowns, the load-more buttons, the
 * services, awards and careers accordions, the team cards and the explain
 * deck. A <div> cannot take focus, so none of them is reachable by keyboard
 * and the stylesheet's focus rules never fire for any of them. The handover's
 * QA checklist asks for keyboard operability in five separate places.
 *
 * This is progressive enhancement over the markup that is already there. It
 * adds a role, a tab stop and Enter/Space, then lets each element's own click
 * handler do the work, so mouse behaviour is untouched and no handler is
 * rewritten. Nothing is moved, with one exception: a disclosure whose trigger
 * is a heading gets that heading's contents wrapped in a real <button>.
 * role="button" on the heading itself would cost the heading its rank, and on
 * the surrounding container it would hide the panel's own contents from
 * assistive technology, because a button is announced as a leaf.
 */

const siblingPanel = ( element ) => element.nextElementSibling;

// selector: the element that already carries the click, so a synthetic click
//           reproduces exactly what the mouse does.
// panel:    what opens, so aria-expanded can track something real. Optional.
// label:    an accessible name, for the controls that are an icon and nothing
//           else. Ignored where the element already has text.
const CONTROLS = [
	{
		selector: '.search-hamburger .menu',
		label: 'Open menu',
		panel: () => document.querySelector( '.main-menu-container' ),
	},
	{
		selector: '.search-hamburger .cross',
		label: 'Close menu',
		panel: () => document.querySelector( '.main-menu-container' ),
	},
	{
		selector: '.header-search',
		label: 'Search',
		panel: () => document.querySelector( '.search-container' ),
	},
	{
		selector: '.term-selector, .sector-dropdown, .service-dropdown',
		panel: siblingPanel,
	},
	{
		selector: '.open-close-accordion',
		label: 'Show details',
		panel: ( element ) => {
			const item = element.closest( '.service-item' );
			return item ? item.querySelector( '.service-description' ) : null;
		},
	},
	{
		selector: '.plus-icon',
		label: 'Show profile',
		panel: ( element ) => {
			const closed = element.closest( '.details-closed' );
			return closed ? closed.nextElementSibling : null;
		},
	},
	{ selector: '.explain-card .right-arrow', label: 'Next card' },
	{ selector: '.global-button, .hero-button, p.next-slide' },
];

// Disclosures whose trigger is a heading. The heading keeps its rank; the
// button goes inside it.
const HEADING_TRIGGERS = [
	{ selector: '.award-year > h4', panel: siblingPanel },
	{ selector: '.vacancy > h4', panel: siblingPanel },
	// B5: the senior team rows. The heading is nested inside the summary rather
	// than a direct sibling of the panel, so it needs its own resolver.
	{
		selector: '.person-summary h2.name',
		panel: ( heading ) => {
			const person = heading.closest( '.person' );
			return person ? person.querySelector( '.person-details' ) : null;
		},
	},
];

let panelId = 0;

const isOpen = ( element ) => !! element && element.getClientRects().length > 0;

// A control that is already a native button, link or field, or that sits
// inside one, is reachable as it stands and must be left alone.
const alreadyOperable = ( element ) =>
	[ 'BUTTON', 'A', 'INPUT', 'SELECT', 'TEXTAREA' ].indexOf( element.tagName ) > -1 ||
	element.hasAttribute( 'tabindex' ) ||
	!! element.closest( 'a[href], button' );

function trackExpanded( trigger, panel ) {
	if ( ! panel ) {
		return;
	}

	if ( ! panel.id ) {
		panel.id = 'ccg-panel-' + ++panelId;
	}

	trigger.setAttribute( 'aria-controls', panel.id );

	const sync = () =>
		trigger.setAttribute( 'aria-expanded', isOpen( panel ) ? 'true' : 'false' );

	sync();

	// The state class lands before jQuery's slide finishes, and slideToggle
	// writes inline styles rather than a class, so watch both and read again
	// once the 500ms animation has settled.
	new MutationObserver( sync ).observe( panel, {
		attributes: true,
		attributeFilter: [ 'class', 'style', 'hidden' ],
	} );

	trigger.addEventListener( 'click', () => setTimeout( sync, 600 ) );
}

function makeOperable( element, control ) {
	if ( element.dataset.ccgKeyboard ) {
		return;
	}

	element.dataset.ccgKeyboard = 'true';
	element.setAttribute( 'role', 'button' );
	element.setAttribute( 'tabindex', '0' );

	if (
		control.label &&
		! element.getAttribute( 'aria-label' ) &&
		! element.textContent.trim()
	) {
		element.setAttribute( 'aria-label', control.label );
	}

	element.addEventListener( 'keydown', ( event ) => {
		if ( event.key !== 'Enter' && event.key !== ' ' && event.key !== 'Spacebar' ) {
			return;
		}

		// Space would otherwise scroll the page out from under the control.
		event.preventDefault();
		element.click();
	} );

	if ( control.panel ) {
		trackExpanded( element, control.panel( element ) );
	}
}

function wrapHeading( heading, control ) {
	if ( heading.querySelector( 'button' ) ) {
		return;
	}

	const button = document.createElement( 'button' );
	button.type = 'button';
	button.className = 'ccg-kb-trigger';

	while ( heading.firstChild ) {
		button.appendChild( heading.firstChild );
	}

	heading.appendChild( button );

	if ( control.panel ) {
		trackExpanded( button, control.panel( heading ) );
	}
}

function enhance() {
	CONTROLS.forEach( ( control ) => {
		document.querySelectorAll( control.selector ).forEach( ( element ) => {
			if ( alreadyOperable( element ) ) {
				return;
			}

			makeOperable( element, control );
		} );
	} );

	HEADING_TRIGGERS.forEach( ( control ) => {
		document
			.querySelectorAll( control.selector )
			.forEach( ( heading ) => wrapHeading( heading, control ) );
	} );
}

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', enhance );
} else {
	enhance();
}
