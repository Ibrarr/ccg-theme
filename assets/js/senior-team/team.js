/**
 * B5 (v0.12): the senior team concertina.
 *
 * Same behaviour as the careers vacancies, deliberately: one row open at a
 * time, jQuery slide on the panel, and `stop(true, true)` so a second click
 * cancels rather than queues. The previous version measured headshot heights
 * and absolutely positioned an overlay panel across two grid columns; none of
 * that applies to a list.
 */
jQuery(document).ready(function ($) {
    $('.person').on('click', function (event) {
        event.stopPropagation();

        var $this = $(this);

        // 250ms: a disclosure answers inside the 150-250ms band. jQuery's default
        // 400ms read as the list catching up with the click.
        $('.person.active').not(this).each(function () {
            $(this).find('.person-details').stop(true, true).slideUp(250);
            $(this).removeClass('active');
        });

        $this.find('.person-details').stop(true, true).slideToggle(250);
        $this.toggleClass('active');
    });

    // A link inside an open biog is a link, not a toggle.
    $('.person-details a').on('click', function (event) {
        event.stopPropagation();
    });
});
