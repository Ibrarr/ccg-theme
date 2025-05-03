jQuery(document).ready(function ($) {
    if (document.cookie.indexOf('newsletterPopupClosed=true') !== -1) {
        $('.newsletter-popup-container').hide();
    } else {
        setTimeout(function () {
            $('.newsletter-popup-container').fadeIn();
            }, 30000);
    }

    $('.close-newsletter').click(function () {
        $('.newsletter-popup-container').fadeOut();

        document.cookie = "newsletterPopupClosed=true; expires=" + new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
    });

    jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        setTimeout(function () {
            $('.newsletter-popup-container').fadeOut();

            document.cookie = "newsletterPopupClosed=true; expires=" + new Date(new Date().getTime() + 365 * 24 * 60 * 60 * 1000).toUTCString() + "; path=/";
        }, 5000);
    });
});
