/**
 * Reduced motion for jQuery's own animations.
 *
 * The section E text motion and the Splide carousels already stand down under
 * prefers-reduced-motion, but every jQuery slide and fade (the menu panel, the
 * concertinas, the About beat swap, the homepage sectors panel's 1.5s fade) ran
 * at full length. jQuery.fx.off makes each of them jump straight to its end
 * state, which is what the setting asks for. Followed live, so turning the
 * setting on or off applies without a reload.
 */
jQuery(function ($) {
    if (!window.matchMedia) {
        return;
    }

    const query = window.matchMedia('(prefers-reduced-motion: reduce)');
    const apply = () => {
        $.fx.off = query.matches;
    };

    apply();

    if (query.addEventListener) {
        query.addEventListener('change', apply);
    }
});
