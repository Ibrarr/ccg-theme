/**
 * A2 (v0.12): a form field clicked or tapped takes no focus ring.
 *
 * The register asks for a 1.5px navy ring on :focus-visible and expects pointer
 * users never to see it. That holds for buttons and links, but a browser
 * matches :focus-visible on any focus of a field that takes typing, a click
 * included, because the next input could come from the keyboard.
 *
 * So this records how focus arrived. A pointer press flags <html> and the
 * stylesheet stands the field ring down under that flag. Tab clears the flag
 * before focus moves, so keyboard focus always gets the ring, including for
 * someone who clicked into the form and then tabbed on. Typing into a clicked
 * field leaves the flag alone, so the ring does not appear mid-sentence.
 */

const root = document.documentElement;

document.addEventListener(
	'pointerdown',
	() => root.classList.add( 'pointer-focus' ),
	true
);

document.addEventListener(
	'keydown',
	( event ) => {
		if ( 'Tab' === event.key ) {
			root.classList.remove( 'pointer-focus' );
		}
	},
	true
);
