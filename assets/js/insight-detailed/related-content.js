jQuery(document).ready(function ($) {
    const $section = $('.related-content');
    const $cards = $section.find('.article-card');

    if (!$cards.length) {
        return;
    }

    /**
     * The shared related-content styles start the cards hidden for a GSAP
     * reveal that this template does not load. The cards are visible by
     * default here; the hidden starting state is only applied once this script
     * runs, so a JavaScript failure can never leave the section blank.
     */
    $section.addClass('js-reveal');

    if (!('IntersectionObserver' in window)) {
        $cards.addClass('is-revealed');

        return;
    }

    const observer = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }

            const index = $cards.index(entry.target);

            setTimeout(function () {
                entry.target.classList.add('is-revealed');
            }, Math.max(index, 0) * 100);

            observer.unobserve(entry.target);
        });
    }, {rootMargin: '0px 0px -10% 0px'});

    $cards.each(function () {
        // The shared component hides the last cards at some breakpoints. A
        // display: none card never intersects, so reveal it up front rather
        // than leaving it stuck hidden if it becomes visible on resize.
        if (this.offsetParent === null) {
            this.classList.add('is-revealed');

            return;
        }

        observer.observe(this);
    });
});
