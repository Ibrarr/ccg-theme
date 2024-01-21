jQuery(document).ready(function ($) {
    let hoverTimeout;
    let screenWidth = $(window).width();

    if (screenWidth > 991) {
        $(".headings h4").hover(function () {
            var className = $(this).attr("class");

            clearTimeout(hoverTimeout);

            hoverTimeout = setTimeout(function () {
                $(".cards > div").fadeOut(300);
                $("." + className).fadeIn(300);
            }, 300);
        }, function () {
            clearTimeout(hoverTimeout);
        });
    } else {
        $(".headings h4").click(function () {
            var className = $(this).attr("class");

            if (!$(this).hasClass("active")) {
                $(".headings h4").removeClass("active");
                $(".cards > div").fadeOut(300);
                $("." + className).fadeIn(300);
                $(this).addClass("active");
            }
        });
    }
});
