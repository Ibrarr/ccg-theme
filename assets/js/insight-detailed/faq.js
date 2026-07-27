jQuery(document).ready(function ($) {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /**
     * The panels have no fixed height, so the open and close transitions run
     * from an explicit pixel height measured at the moment of the toggle. Once
     * an open panel has finished animating its height is released back to auto
     * so it can still reflow on resize or when the content changes.
     */
    function open($item, $button, panel) {
        $item.addClass('is-open');
        $button.attr('aria-expanded', 'true');
        panel.hidden = false;

        if (reducedMotion) {
            panel.style.maxHeight = '';

            return;
        }

        panel.style.maxHeight = '0px';
        // Read back the layout so the browser treats the next change as a
        // transition rather than folding both values into one frame.
        void panel.offsetHeight;
        panel.style.maxHeight = panel.scrollHeight + 'px';
    }

    function close($item, $button, panel) {
        $button.attr('aria-expanded', 'false');

        if (reducedMotion) {
            $item.removeClass('is-open');
            panel.hidden = true;
            panel.style.maxHeight = '';

            return;
        }

        // Pin the current height first, otherwise there is nothing to animate
        // down from once the height is released.
        panel.style.maxHeight = panel.scrollHeight + 'px';
        void panel.offsetHeight;
        $item.removeClass('is-open');
        panel.style.maxHeight = '0px';
    }

    $('.report-faq-question').on('click', function () {
        const $button = $(this);
        const $item = $button.closest('.report-faq-item');
        const panel = document.getElementById($button.attr('aria-controls'));

        if (!panel) {
            return;
        }

        if ($button.attr('aria-expanded') === 'true') {
            close($item, $button, panel);
        } else {
            open($item, $button, panel);
        }
    });

    $('.report-faq-answer').on('transitionend', function (event) {
        if (event.originalEvent.propertyName !== 'max-height') {
            return;
        }

        const panel = this;
        const isOpen = $(panel).closest('.report-faq-item').hasClass('is-open');

        if (isOpen) {
            // Release the height so long answers stay correct after a reflow.
            panel.style.maxHeight = '';
        } else {
            panel.hidden = true;
            panel.style.maxHeight = '';
        }
    });
});
