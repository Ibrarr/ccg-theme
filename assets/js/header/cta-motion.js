/**
 * B1 (v0.12): the "Get in touch" heading glides in.
 *
 * The phrase travels left to right into its resting position, and the rest of
 * the line follows just before the phrase lands. Both halves move on transform
 * and opacity only, so the sequence runs on the compositor and neither half
 * ever reflows the other.
 *
 * Three rules this file keeps, and the reason each one matters here:
 *
 *  1. A page with no JavaScript is complete. The pieces are hidden before the
 *     first paint only when the head has armed motion (`tm-armed`, which reduced
 *     motion and no-JS never get), and the stylesheet shows them after three
 *     seconds if this script never runs.
 *  2. The decision waits for the page to settle: `load`, then any archive grid
 *     still arriving by AJAX. Our News measured the heading at 719px on a 900px
 *     screen before its tiles were in, and at 2494px once they were. Deciding
 *     early called it "already on screen", left it finished, and it never
 *     animated. A heading that really is on screen once the page has settled
 *     plays at once; the rest play when they scroll into view.
 *  3. prefers-reduced-motion stands the whole thing down before it touches the
 *     DOM, with a CSS block behind it for anyone who changes the setting later.
 *
 * This is NOT the section 5 motion kit, which the client removed in Sept 2026.
 * It is the single animation B1 asks for, and nothing else on the page moves.
 */
(function () {
    'use strict';

    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) {
        return;
    }

    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    // Once nothing inside the heading is still transitioning, mark it settled so
    // the stylesheet can drop will-change. transitionend fires once per property
    // per half, so rather than count them, ask whether any transition is still
    // running. A cancelled transition counts as finished. Browsers without
    // getAnimations simply keep the hint, which is how it behaved before.
    function settleWhenStill(heading) {
        if (typeof heading.getAnimations !== 'function') {
            return;
        }

        function check() {
            if (heading.getAnimations({ subtree: true }).length) {
                return;
            }
            heading.classList.add('is-settled');
            heading.removeEventListener('transitionend', check);
            heading.removeEventListener('transitioncancel', check);
        }

        heading.addEventListener('transitionend', check);
        heading.addEventListener('transitioncancel', check);
    }

    function reveal(heading) {
        if (heading.classList.contains('is-in')) {
            return;
        }
        // Read a layout value first so the hidden state is applied before the
        // transition target, or the browser would skip straight to the end.
        void heading.offsetWidth;
        heading.classList.add('is-in');
        settleWhenStill(heading);
    }

    function start() {
        // Every closing band's title (E3.4, E4.6), and the contact page's "Get
        // in touch" heading. A title that opens with the serif phrase moves in
        // two parts; one without it, like the service pages' "Contact us",
        // glides as one piece.
        var headings = Array.prototype.slice.call(document.querySelectorAll('.bottom-cta .second-row h3, .contact-form h3'))
            .filter(function (heading) {
                return heading.querySelector('.cta-phrase') || heading.closest('.bottom-cta');
            });

        // Take the headings over straight away. .cta-motion is the hidden state,
        // so this replaces the stylesheet's pre-paint hiding without a flash.
        headings.forEach(function (heading) {
            heading.classList.add('cta-motion');

            if (!heading.querySelector('.cta-phrase')) {
                heading.classList.add('cta-motion--whole');
            }
        });

        var decided = false;
        var observer = new IntersectionObserver(function (entries) {
            if (!decided) {
                return;
            }
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                observer.unobserve(entry.target);
                reveal(entry.target);
            });
        }, { rootMargin: '0px 0px -15% 0px', threshold: 0 });

        function decide() {
            if (decided) {
                return;
            }
            decided = true;
            headings.forEach(function (heading) {
                if (heading.getBoundingClientRect().top < window.innerHeight * 0.85) {
                    reveal(heading);
                } else {
                    observer.observe(heading);
                }
            });
        }

        // Wait for the archive grid too. Eight templates fill #posts-container
        // by jQuery AJAX on DOM ready, and on staging the posts arrive about a
        // second after `load`, so until then the band sits a screen or two
        // higher than it will. Decided at `load`, the heading played there and
        // was finished before anyone scrolled down to it. jQuery fires ajaxStop
        // once the last request's success handler has put the posts in; two
        // frames later they are laid out. Capped, so a hung request cannot keep
        // the heading hidden. The observer itself reads live geometry, so
        // nothing after the decision can leave it stale.
        var checked = false;

        function afterRequests() {
            if (checked) {
                return;
            }
            checked = true;

            var $ = window.jQuery;

            if (!$ || !$.active) {
                decide();
                return;
            }

            $(document).one('ajaxStop', function () {
                requestAnimationFrame(function () {
                    requestAnimationFrame(decide);
                });
            });
            setTimeout(decide, 4000);
        }

        if (document.readyState === 'complete') {
            afterRequests();
        } else {
            window.addEventListener('load', afterRequests, { once: true });
            setTimeout(afterRequests, 2500);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
}());
