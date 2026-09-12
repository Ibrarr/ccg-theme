jQuery(document).ready(function ($) {
    const $bar = $('.notification-bar');

    if (!$bar.length) {
        return;
    }

    // A year, matching the newsletter popup's persistence. SameSite is set here
    // where the older popups omit it.
    function remember() {
        const expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();

        document.cookie = 'ccgNoticeDismissed=1; expires=' + expires + '; path=/; SameSite=Lax';
    }

    $bar.on('click', '.notification-bar-dismiss', function () {
        // One class does the whole job. It hides the bar and sets
        // --ccg-topbar-height to 0, so the fixed header, the logo, the page
        // padding and the Insight anchor offsets all return to their no-bar
        // geometry in the same frame.
        //
        // Deliberately not animated: a dismissal should feel immediate, and a
        // user-initiated shift is excluded from Cumulative Layout Shift.
        document.documentElement.classList.add('notice-dismissed');

        remember();
    });
});
