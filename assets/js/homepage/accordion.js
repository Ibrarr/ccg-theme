jQuery(document).ready(function ($) {
    $('.service-item').first().addClass('active').find('.service-description').show();

    $('.service-item .open-close-accordion').click(function (event) {
        event.stopPropagation();

        let $currentItem = $(this).closest('.service-item');
        let $currentDescription = $currentItem.find('.service-description');

        $('.service-item').not($currentItem).removeClass('active').find('.service-description').slideUp().addClass('inactive');

        $currentItem.toggleClass('active');
        if ($currentItem.hasClass('active')) {
            $currentDescription.slideDown().removeClass('inactive');
        } else {
            $currentDescription.slideUp().addClass('inactive');
        }
    });
});
