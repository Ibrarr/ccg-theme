jQuery(document).ready(function ($) {
    $('.service-item .open-close-accordion').click(function (event) {
        event.stopPropagation();

        let $currentItem = $(this).closest('.service-item');
        let $description = $currentItem.find('.service-description');

        // Slide up all other descriptions except the current one
        $('.service-description').not($description).slideUp();

        // Fade out minus and fade in plus for all other items
        $('.service-item').not($currentItem).find('.open-close-accordion .minus').fadeOut();
        $('.service-item').not($currentItem).find('.open-close-accordion .plus').fadeIn();

        // Slide toggle the current item's description
        $description.slideToggle();

        // Toggle active class on the current item and remove from others
        $('.service-item').not($currentItem).removeClass('active');
        $currentItem.toggleClass('active');

        // If the current item is active, fade out the plus and fade in the minus, else reverse
        if ($currentItem.hasClass('active')) {
            $currentItem.find('.open-close-accordion .plus').fadeOut();
            $currentItem.find('.open-close-accordion .minus').fadeIn();
        } else {
            $currentItem.find('.open-close-accordion .minus').fadeOut();
            $currentItem.find('.open-close-accordion .plus').fadeIn();
        }
    });
});
