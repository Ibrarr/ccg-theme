jQuery(document).ready(function ($) {
    // Check if popup has been shown in this tab session
    if (sessionStorage.getItem('announcePopupClosed') === 'true') {
        $('.announce-popup-container').hide();
    } else {
        setTimeout(function () {
            $('.announce-popup-container').fadeIn();
        }, 2000);
    }

    $('.close-announce-popup').click(function () {
        $('.announce-popup-container').fadeOut();

        // Save to sessionStorage instead of cookies
        sessionStorage.setItem('announcePopupClosed', 'true');
    });

    jQuery(document).on('gform_confirmation_loaded', function (event, formId) {
        setTimeout(function () {
            $('.announce-popup-container').fadeOut();

            // Save to sessionStorage instead of cookies
            sessionStorage.setItem('announcePopupClosed', 'true');
        }, 2000);
    });
});