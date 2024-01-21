jQuery(document).ready(function ($) {
    $('.service-dropdown').click(function (event) {
        const serviceDropdown = $('.service-dropdown');
        const dropdownMenuContainer = $('.service-dropdown-menu-container');
        const dropdownTop = serviceDropdown.offset().top;
        const dropdownBottom = dropdownTop + dropdownMenuContainer.outerHeight() + 60;
        const windowScrollTop = $(window).scrollTop();
        const windowBottom = windowScrollTop + $(window).height();

        serviceDropdown.toggleClass('dropdown-open');
        dropdownMenuContainer.toggle();

        if (dropdownTop < windowScrollTop) {
            window.scrollTo(0, dropdownTop - 30);
        } else if (dropdownBottom > windowBottom) {
            const necessaryScroll = dropdownBottom - $(window).height() + 30;
            window.scrollTo(0, necessaryScroll);
        }

        event.stopPropagation();
    });

    $(document).click(function (event) {
        const $trigger = $(".service-dropdown, .service-dropdown-menu-container");
        if ($trigger !== event.target && !$trigger.has(event.target).length) {
            $('.service-dropdown').removeClass('dropdown-open');
            $('.service-dropdown-menu-container').hide();
        }
    });

    function moveDropdown() {
        if (window.matchMedia('(max-width: 991px)').matches) {
            $(".services-links").insertAfter(".contact-container");
        } else {
            $(".services-links").insertAfter(".title-intro");
        }
    }

    moveDropdown();
    $(window).resize(function () {
        moveDropdown();
    });
});