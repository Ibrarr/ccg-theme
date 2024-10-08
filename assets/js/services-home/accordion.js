jQuery(document).ready(function ($) {
    $('.service-item .open-close-accordion').click(function (event) {
        event.stopPropagation();

        let $currentItem = $(this).closest('.service-item');
        let $description = $currentItem.find('.service-description');

        $('.service-description').not($description).slideUp();

        $('.service-item').not($currentItem).find('.open-close-accordion .minus').fadeOut();
        $('.service-item').not($currentItem).find('.open-close-accordion .plus').fadeIn();

        $description.slideToggle();

        $('.service-item').not($currentItem).removeClass('active');
        $currentItem.toggleClass('active');

        if ($currentItem.hasClass('active')) {
            $currentItem.find('.open-close-accordion .plus').fadeOut();
            $currentItem.find('.open-close-accordion .minus').fadeIn();
        } else {
            $currentItem.find('.open-close-accordion .minus').fadeOut();
            $currentItem.find('.open-close-accordion .plus').fadeIn();
        }
    });
});
