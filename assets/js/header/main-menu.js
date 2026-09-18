import {gsap} from "gsap";

jQuery(document).ready(function ($) {
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function () {
        let currentScrollPos = window.pageYOffset;

        if (prevScrollpos > currentScrollPos || currentScrollPos <= 70) {
            // Cleared rather than set to a literal: the stylesheet owns this
            // value now, so the header returns to its inset whether or not a
            // notification bar is showing. contents-menu.js already falls back
            // to the computed value when the inline style is empty.
            document.getElementById("header").style.top = "";
            $('.main-menu-text').fadeIn(300);
        } else {
            document.getElementById("header").style.top = "-100px";
            $('.main-menu-text').fadeOut(300);
        }

        prevScrollpos = currentScrollPos;
    }

    // CSS owns the panel's height. This used to write it inline, which beat the
    // stylesheet, so the mobile panel was always a full viewport tall and ran
    // past the bottom of the screen once the notification bar took its share of
    // the top. The desktop branch also computed `'100%' - 50`, which is NaN, so
    // that half never applied anything. Same resolution as the header's `top`:
    // clear the property and let the stylesheet decide.
    function setMaxHeight() {
        $('.main-menu-container').css('height', '');
    }

    setMaxHeight();

    $(window).on('resize', function () {
        setMaxHeight();
    });

    const appHeight = () => {
        const windowHeight = $(window).innerHeight();
        $(':root').css('--app-height', windowHeight + 'px');
    }

    appHeight();
    $(window).on('resize', appHeight);

    function clickHandler(event) {
        const $item = $(this);
        const $ownSubMenu = $item.children('.sub-menu');

        // A click that started inside this item's OWN sub-menu belongs to that
        // sub-menu: either a nested toggle, which has its own handler, or an
        // ordinary link, which has to be allowed to navigate.
        //
        // The handler is bound to the <li>, not to its anchor, so without this
        // guard every click on a child link bubbled up to the parent item and
        // hit preventDefault(): the link never followed and toggleClass closed
        // the panel the reader had just opened. Returning before
        // stopPropagation() also lets the event carry on to any ancestor item,
        // which reaches the same conclusion and likewise stands aside.
        if ($ownSubMenu.length
            && ($ownSubMenu.is(event.target) || $ownSubMenu.has(event.target).length)) {
            return;
        }

        event.stopPropagation();
        event.preventDefault();

        // Close siblings at the same level only, and reset any items they had open inside.
        $item.siblings('.menu-item-has-children.active')
            .removeClass('active')
            .each(function () {
                const $sibling = $(this);
                $sibling.find('.menu-item-has-children.active').removeClass('active');
                $sibling.find('.sub-menu:visible').slideUp(500);
                $sibling.children('a').attr('aria-expanded', 'false');
                $sibling.find('a[aria-expanded="true"]').attr('aria-expanded', 'false');
            });

        const willOpen = !$item.hasClass('active');
        $item.toggleClass('active');
        $item.children('.sub-menu').slideToggle(500);
        $item.children('a').attr('aria-expanded', willOpen ? 'true' : 'false');

        // When this item closes, also reset any descendants it had open.
        // Only target sub-menus nested INSIDE the direct child sub-menu, so we
        // don't queue a second animation on the one slideToggle already handles.
        if (!willOpen) {
            $item.find('.menu-item-has-children.active').removeClass('active');
            $item.children('.sub-menu').find('.sub-menu:visible').slideUp(500);
            $item.find('a[aria-expanded="true"]').attr('aria-expanded', 'false');
        }
    }

    function hoverHandler() {
        if (!$(this).hasClass('active')) {
            $('#menu-main-menu .menu-item-has-children').not(this).removeClass('active').each(function () {
                const submenu = $(this).children('.sub-menu');
                setTimeout(function () {
                    submenu.slideUp(500);
                }, 500);
            });

            $(this).addClass('active');

            const submenu = $(this).children('.sub-menu');
            setTimeout(function () {
                submenu.slideDown(500);
            }, 500);
        }
    }

    function applyEventHandlers() {
        // if (window.matchMedia('(min-width: 991px)').matches) {
        //     $('.menu-item-has-children').off('click', clickHandler);
        //     $('.menu-item-has-children').hover(
        //         function () {
        //             const $this = $(this);
        //             const hoverTimeout = $this.data('hoverTimeout');
        //
        //             if ($('.menu-item-has-children.active').length > 0) {
        //                 if (hoverTimeout) {
        //                     clearTimeout(hoverTimeout);
        //                 }
        //
        //                 $this.data('hoverTimeout', setTimeout(function () {
        //                     hoverHandler.apply($this);
        //                 }, 300));
        //             } else {
        //                 hoverHandler.apply($this);
        //             }
        //         },
        //         function () {
        //             const $this = $(this);
        //             const hoverTimeout = $this.data('hoverTimeout');
        //
        //             if (hoverTimeout) {
        //                 clearTimeout(hoverTimeout);
        //                 $this.data('hoverTimeout', null);
        //             }
        //         }
        //     );
        // } else {
        //     $('.menu-item-has-children').off('mouseenter mouseleave', hoverHandler);
        //     $('.menu-item-has-children').on('click', clickHandler);
        // }
        $('.menu-item-has-children').off('mouseenter mouseleave', hoverHandler);
        $('.menu-item-has-children').off('click', clickHandler);
        $('.menu-item-has-children').on('click', clickHandler);
    }

    applyEventHandlers();

    // Initialise ARIA state and link each toggling anchor to its sub-menu by id.
    $('.menu-item-has-children').each(function (index) {
        const $li = $(this);
        const $a = $li.children('a').first();
        const $submenu = $li.children('.sub-menu').first();
        if (!$submenu.length) return;
        const submenuId = $submenu.attr('id') || 'sub-menu-' + index;
        $submenu.attr('id', submenuId);
        $a.attr('aria-expanded', 'false');
        $a.attr('aria-controls', submenuId);
    });

    // Space activates the toggle when the anchor has focus (Enter is native).
    $('.menu-item-has-children > a').on('keydown', function (event) {
        if (event.key === ' ' || event.code === 'Space') {
            event.preventDefault();
            $(this).parent('.menu-item-has-children').trigger('click');
        }
    });

    $(window).resize(applyEventHandlers);

    $('.menu-container').click(function (event) {
        if (!$(this).hasClass('menu-open')) {
            event.stopPropagation();

            if (!$(event.target).hasClass('header-search')) {
                $('.menu-container').toggleClass('menu-open');
                // The panel slides; the nav inside it is shown instantly. They
                // used to slide together, and because .nav-container is a CHILD
                // of .main-menu-container that meant jQuery measured the
                // panel's target height while the nav was still collapsed. The
                // first open worked because the nav had never been hidden yet;
                // every open after it animated to about 1px and then snapped
                // open when jQuery released the inline height.
                $('.nav-container').show();
                $('.search-container').hide();
                $('.main-menu-container').stop(true, true).slideDown(500);
                $('.mobile-menu-header-background').stop(true, true).slideDown(500);

                if (window.matchMedia('(max-width: 991px)').matches) {
                    setTimeout(function () {
                        $('.header-logo').addClass('mobile-menu-open');
                    }, 300);
                }

                if (!$('body').hasClass('no-scroll')) {
                    // html as well as body: html is the scrolling element, so
                    // locking body on its own left the page scrolling behind
                    // the open menu.
                    $('body, html').addClass('no-scroll');
                }
            }
        }
    });

    $('.main-menu-container, .nav-container, .menu-item').click(function (event) {
        event.stopPropagation();
    });

    $('.header-search').click(function (event) {
        event.stopPropagation();

        $('.menu-container').addClass('search-open');
        $('.menu-container').addClass('menu-open');
        // Same reasoning as the menu handler above: the panel slides, its
        // contents swap instantly.
        $('.search-container').show();
        $('.nav-container').hide();
        $('.main-menu-container').stop(true, true).slideDown(500);
        $('.mobile-menu-header-background').stop(true, true).slideDown(500);

        if (window.matchMedia('(max-width: 991px)').matches) {
            setTimeout(function () {
                $('.header-logo').addClass('mobile-menu-open');
            }, 300);
        }

        if (!$('body').hasClass('no-scroll')) {
            $('body, html').addClass('no-scroll');
        }
    });

    $('.cross').click(function (event) {
        event.stopPropagation();

        $('.main-menu-container').stop(true, true).slideUp(500);
        $('.mobile-menu-header-background').stop(true, true).slideUp(500);
        $('.menu-container').removeClass('menu-open');
        $('.menu-container').removeClass('search-open');
        $('body, html').removeClass('no-scroll');

        if (window.matchMedia('(max-width: 991px)').matches) {
            $('.header-logo').removeClass('mobile-menu-open');
        }
    });

    $(document).mousemove(function (event) {
        if ($('.menu-container').hasClass('menu-open')) {
            if (!$(event.target).closest('.menu-container, .main-menu-container').length) {
                $('body').addClass('custom-cursor');
            } else {
                $('body').removeClass('custom-cursor');
            }
        } else {
            $('body').removeClass('custom-cursor');
        }
    });

    $(document).click(function (event) {
        if (!$(event.target).closest('.menu-container, .main-menu-container').length) {
            if ($('.menu-container').hasClass('menu-open') || $('.menu-container').hasClass('search-open')) {
                $('.menu-container').removeClass('menu-open');
                $('.menu-container').removeClass('search-open');
                $('body').removeClass('custom-cursor');
                $('body, html').removeClass('no-scroll');
                $('.main-menu-container').stop(true, true).slideUp(500);
                $('.mobile-menu-header-background').stop(true, true).slideUp(500);

                if (window.matchMedia('(max-width: 991px)').matches) {
                    $('.header-logo').removeClass('mobile-menu-open');
                }
            }
        }
    });

    // function fadePathInAndOut() {
    //     const path = document.querySelector('.header-logo svg g g path:last-child');
    //
    //     const timeline = gsap.timeline();
    //
    //     timeline.to({}, {duration: 1});
    //
    //     timeline.to(path, {duration: .5, opacity: 0});
    //
    //     timeline.to({}, {duration: 1});
    //
    //     timeline.to(path, {duration: .5, opacity: 1});
    //
    //     timeline.to({}, {duration: 1});
    //
    //     timeline.repeat(-1);
    // }
    //
    // fadePathInAndOut();
});