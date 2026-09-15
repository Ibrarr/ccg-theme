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

        $('.person.active').not(this).each(function () {
            $(this).find('.person-details').stop(true, true).slideUp();
            $(this).removeClass('active');
        });

        $this.find('.person-details').stop(true, true).slideToggle();
        $this.toggleClass('active');
    });

    // A link inside an open biog is a link, not a toggle.
    $('.person-details a').on('click', function (event) {
        event.stopPropagation();
    });
});
