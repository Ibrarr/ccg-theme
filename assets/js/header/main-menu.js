import {gsap} from "gsap";

jQuery(document).ready(function ($) {
    let prevScrollpos = window.pageYOffset;
    window.onscroll = function () {
        let currentScrollPos = window.pageYOffset;

        if (prevScrollpos > currentScrollPos || currentScrollPos <= 70) {
            document.getElementById("header").style.top = "50px";
            $('.main-menu-text').fadeIn(300);
        } else {
            document.getElementById("header").style.top = "-100px";
            $('.main-menu-text').fadeOut(300);
        }

        prevScrollpos = currentScrollPos;
    }

    function setMaxHeight() {
        if ($(window).width() > 960) {
            var maxHeight = $(window).height() * 0.85;
            var windowHeight = $(window).height();
            var menuHeight = $('.main-menu-container').height();

            if (menuHeight > windowHeight) {
                $('.main-menu-container').css('height', maxHeight + 'px');
            } else {
                $('.main-menu-container').css('height', '100%' - 50);
            }

        } else {
            $('.main-menu-container').css('height', 'var(--app-height)');
        }
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
        event.stopPropagation();
        event.preventDefault();

        $('.menu-item-has-children.active').not(this).removeClass('active');
        $('.sub-menu:visible').not($(this).children('.sub-menu')).slideUp(500);

        $(this).toggleClass('active');
        let submenu = $(this).children('.sub-menu');
        submenu.slideToggle(500);
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

    $(window).resize(applyEventHandlers);

    $('.menu-container').click(function (event) {
        if (!$(this).hasClass('menu-open')) {
            event.stopPropagation();

            if (!$(event.target).hasClass('header-search')) {
                $('.menu-container').toggleClass('menu-open');
                $('.main-menu-container').slideDown(500);
                $('.nav-container').slideDown(500);
                $('.mobile-menu-header-background').slideDown(500);

                if (window.matchMedia('(max-width: 991px)').matches) {
                    setTimeout(function () {
                        $('.header-logo').addClass('mobile-menu-open');
                    }, 300);
                }

                if (!$('body').hasClass('no-scroll')) {
                    $('body').addClass('no-scroll');
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
        $('.main-menu-container').slideDown(500);
        $('.search-container').slideDown(500);
        $('.mobile-menu-header-background').slideDown(500);
        $('.nav-container').slideUp(1000);

        if (window.matchMedia('(max-width: 991px)').matches) {
            setTimeout(function () {
                $('.header-logo').addClass('mobile-menu-open');
            }, 300);
        }

        if (!$('body').hasClass('no-scroll')) {
            $('body').addClass('no-scroll');
        }
    });

    $('.cross').click(function (event) {
        event.stopPropagation();

        $('.main-menu-container').slideUp(500);
        $('.search-container').slideUp(500);
        $('.nav-container').slideUp(500);
        $('.mobile-menu-header-background').slideUp(500);
        $('.menu-container').removeClass('menu-open');
        $('.menu-container').removeClass('search-open');
        $('body').removeClass('no-scroll');

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
                $('body').removeClass('no-scroll');
                $('.main-menu-container').slideUp(500);
                $('.search-container').slideUp(500);

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