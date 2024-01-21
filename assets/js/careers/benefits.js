jQuery(document).ready(function ($) {
    $('.global-button-benefit').click(function () {
        $('.benefits-list li:nth-child(n+10)').slideDown();
        $(this).hide();
    });
});
