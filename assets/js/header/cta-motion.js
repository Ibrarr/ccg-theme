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
 *  1. Nothing is hidden in CSS. The resting styles are the finished heading;
 *     the `.cta-motion` class that introduces the hidden state is added by this
 *     script, so a page with no JavaScript is complete and the first frame is
 *     never blank.
 *  2. Only headings BELOW the fold are hidden. Anything already on screen when
 *     the script runs is left alone, which keeps the LCP untouched and means a
 *     reader never watches content they can already see animate itself in.
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

    function start() {
        var headings = document.querySelectorAll('.bottom-cta h3:has(.cta-phrase), .contact-form h3:has(.cta-phrase)');

        // :has() is well supported now, but a miss here would silently animate
        // nothing, so fall back to finding the phrase and walking up one level.
        if (!headings.length) {
            var phrases = document.querySelectorAll('.cta-phrase');
            headings = Array.prototype.map.call(phrases, function (el) {
                return el.parentElement;
            });
        }

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) {
                    return;
                }
                entry.target.classList.add('is-in');
                observer.unobserve(entry.target);
            });
        }, { rootMargin: '0px 0px -12% 0px', threshold: 0.1 });

        Array.prototype.forEach.call(headings, function (heading) {
            if (!heading || !heading.querySelector('.cta-phrase')) {
                return;
            }

            // Already on screen: leave it finished.
            if (heading.getBoundingClientRect().top < window.innerHeight) {
                return;
            }

            heading.classList.add('cta-motion');
            observer.observe(heading);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
}());
