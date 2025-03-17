jQuery(document).ready(function ($) {
    if (document.cookie.indexOf('announcePopupClosed=true') !== -1) {
        $('.announce-popup-container').hide();
    } else {
        setTimeout(function () {
            $('.announce-popup-container').fadeIn();
        }, 30000);
    }

    $('.close-announce-popup').click(function () {
        $('.announce-popup-container').fadeOut();

        document.cookie = "announcePopupClosed=true; expires=" + new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
    });

    jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        setTimeout(function () {
            $('.announce-popup-container').fadeOut();

            document.cookie = "announcePopupClosed=true; expires=" + new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
        }, 5000);
    });
});