jQuery(document).ready(function ($) {
    // Persisted in a cookie rather than sessionStorage so a dismissal survives
    // the tab closing, and so it behaves the same way as the notification bar
    // and the newsletter popup.
    function dismissed() {
        return document.cookie.indexOf('announcePopupClosed=1') !== -1;
    }

    function remember() {
        const expires = new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toUTCString();

        document.cookie = 'announcePopupClosed=1; expires=' + expires + '; path=/; SameSite=Lax';
    }

    if (dismissed()) {
        $('.announce-popup-container').hide();
    } else {
        setTimeout(function () {
            $('.announce-popup-container').fadeIn();
        }, 2000);
    }

    $('.close-announce-popup').click(function () {
        $('.announce-popup-container').fadeOut();

        remember();
    });

    jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        setTimeout(function () {
            $('.announce-popup-container').fadeOut();

            remember();
        }, 2000);
    });
});
