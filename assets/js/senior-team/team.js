jQuery(document).ready(function ($) {
    let isAnimating = false;

    function setTopSectionHeight() {
        $(".person-container").each(function () {
            var $this = $(this);
            var headshotHeight = $this.find(".headshot").height() - 30;
            $this.find(".details-open .top-section").css("height", headshotHeight);
        });
    }

    $(window).on('load', function () {
        setTopSectionHeight();
    });

    $(window).resize(function () {
        setTopSectionHeight();
    });

    $(".headshot").each(function () {
        let $personContainer = $(this).closest(".person-container");
        let image = new Image();
        image.src = $(this).attr("src");

        image.onload = function () {
            let headshotHeight = $(this).height() - 30;
            $personContainer.find(".details-open .top-section").css("height", headshotHeight);
        };
    });

    $(".person-container").click(function () {
        let $this = $(this);
        let $activeItem = $(".person-container.active");
        let $detailsOpen = $this.find(".details-open");
        let $bio = $this.find(".bio");

        if (!$this.hasClass("active") && !isAnimating) {
            if ($activeItem.length > 0) {
                isAnimating = true;
                $activeItem.find(".details-open").fadeOut(500, function () {
                    $activeItem.removeClass("active");
                    isAnimating = false;
                    $activeItem.css("margin-bottom", "0");
                    $this.addClass("active");
                    $detailsOpen.show('slide', {
                        direction: "up",
                        easing: 'easeInCubic'
                    }, 500);
                    let marginHeight = $bio.height() + 60;
                    $this.css("margin-bottom", marginHeight + "px");
                });
            } else {
                $this.addClass("active");
                $detailsOpen.show('slide', {
                    direction: "up",
                    easing: 'easeInCubic'
                }, 500);
                let marginHeight = $bio.height() + 60;
                $this.css("margin-bottom", marginHeight + "px");
            }
        } else if ($this.hasClass("active") && !isAnimating) {
            isAnimating = true;
            $this.find(".details-open").fadeOut(500, function () {
                $this.removeClass("active");
                isAnimating = false;
                $this.css("margin-bottom", "0");
            });
        }
    });
});
