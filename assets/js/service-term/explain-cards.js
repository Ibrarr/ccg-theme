jQuery(document).ready(function ($) {
    $('.explain-card').click(function() {
        const cards = $('.explain-card');

        cards.css('z-index', 1).css('transform', 'scale(1)');

        let index = cards.index(this);

        if (index === 0) {
            $(cards[0]).css('z-index', 3).css('transform', 'scale(1.03)');
            $(cards[1]).css('z-index', 2);
            $(cards[2]).css('z-index', 1);
        } else if (index === 1) {
            $(cards[0]).css('z-index', 2);
            $(cards[1]).css('z-index', 3).css('transform', 'scale(1.03)');
            $(cards[2]).css('z-index', 1);
        } else if (index === 2) {
            $(cards[0]).css('z-index', 1);
            $(cards[1]).css('z-index', 2);
            $(cards[2]).css('z-index', 3).css('transform', 'scale(1.03)');
        }
    });
});
