jQuery(document).ready(function ($) {
    $('.sector-dropdown').click(function (event) {
        const sectorDropdown = $('.sector-dropdown');
        const dropdownMenuContainer = $('.sector-dropdown-menu-container');
        const dropdownTop = sectorDropdown.offset().top;
        const dropdownBottom = dropdownTop + dropdownMenuContainer.outerHeight() + 60;
        const windowScrollTop = $(window).scrollTop();
        const windowBottom = windowScrollTop + $(window).height();

        sectorDropdown.toggleClass('dropdown-open');
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
        const $trigger = $(".sector-dropdown, .sector-dropdown-menu-container");
        if ($trigger !== event.target && !$trigger.has(event.target).length) {
            $('.sector-dropdown').removeClass('dropdown-open');
            $('.sector-dropdown-menu-container').hide();
        }
    });

    function moveDropdown() {
        if (window.matchMedia('(max-width: 991px)').matches) {
            $(".sectors-links").insertAfter(".contact-container");
        } else {
            $(".sectors-links").insertAfter(".title-intro");
        }
    }

    moveDropdown();
    $(window).resize(function () {
        moveDropdown();
    });
});