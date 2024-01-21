jQuery(document).ready(function ($) {
    $('.vacancy').click(function (event) {
        event.stopPropagation();

        $('.vacancy.active').not(this).find('.vacancy-details').slideUp();
        $('.vacancy.active').not(this).removeClass('active');

        $(this).find('.vacancy-details').slideToggle();
        $(this).toggleClass('active');
    });

    $('.download-spec').click(function (event) {
        event.stopPropagation();
    });
});
