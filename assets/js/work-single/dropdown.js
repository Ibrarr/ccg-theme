jQuery(document).ready(function ($) {
    $('.work-dropdown').click(function (event) {
        const workDropdown = $('.work-dropdown');
        const dropdownMenuContainer = $('.work-dropdown-menu-container');
        const dropdownTop = workDropdown.offset().top;
        const dropdownBottom = dropdownTop + dropdownMenuContainer.outerHeight() + 60;
        const windowScrollTop = $(window).scrollTop();
        const windowBottom = windowScrollTop + $(window).height();

        workDropdown.toggleClass('dropdown-open');
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
        const $trigger = $(".work-dropdown, .work-dropdown-menu-container");
        if ($trigger !== event.target && !$trigger.has(event.target).length) {
            $('.work-dropdown').removeClass('dropdown-open');
            $('.work-dropdown-menu-container').hide();
        }
    });

    function moveDropdown() {
        if (window.matchMedia('(max-width: 991px)').matches) {
            $(".sectors-links").insertBefore(".image");
        } else {
            $(".sectors-links").insertAfter(".title-intro");
        }
    }

    moveDropdown();
    $(window).resize(function () {
        moveDropdown();
    });
});