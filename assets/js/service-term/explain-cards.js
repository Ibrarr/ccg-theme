jQuery(document).ready(function ($) {
    $('.explain-card').click(function() {
        $(this).prependTo('.cards-container');

        if ($(window).width() < 450) {
            $('html, body').scrollTop($('#explain-cards').offset().top - 50);
        }
    });
});