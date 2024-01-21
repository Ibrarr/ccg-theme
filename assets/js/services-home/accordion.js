jQuery(document).ready(function ($) {
    $('.service-item .open-close-accordion').click(function (event) {
        event.stopPropagation();

        let $description = $(this).closest('.service-item').find('.service-description');
        $('.analytics .service-description').not($description).slideUp();
        $description.slideToggle();
        $('.analytics .service-item').not(this).removeClass('active');
        $(this).closest('.service-item').toggleClass('active');
    });
});
