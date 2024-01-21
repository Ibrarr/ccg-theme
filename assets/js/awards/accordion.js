jQuery(document).ready(function ($) {
    $('.award-year').click(function () {
        $(this).find('.all-awards-container').slideToggle();
        $(this).toggleClass('active');
    });
});
