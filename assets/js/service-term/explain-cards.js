jQuery(document).ready(function ($) {
    $('.explain-card').click(function() {
        $(this).prependTo('.cards-container');
    });
});