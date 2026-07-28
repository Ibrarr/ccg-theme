jQuery(document).ready(function ($) {
    const $rail = $('.report-contents');
    const $accordion = $('.report-contents-accordion');

    const $links = $rail.find('a[href^="#"]').add($accordion.find('a[href^="#"]'));

    if (!$links.length) {
        return;
    }

    /**
     * Collapsible contents menu below lg.
     */
    const $toggle = $('.report-contents-toggle');

    // The panel is collapsed by CSS off aria-expanded rather than the hidden
    // attribute, so the open and close can animate. is-open rides on the
    // accordion because the bar's bottom corners have to stop rounding while a
    // panel is joined to them.
    function setExpanded(expanded) {
        $toggle.attr('aria-expanded', expanded ? 'true' : 'false');
        $accordion.toggleClass('is-open', expanded);
    }

    $toggle.on('click', function () {
        setExpanded($(this).attr('aria-expanded') !== 'true');
    });

    function closeAccordion() {
        setExpanded(false);
    }

    /**
     * Anchor navigation. Closing the accordion shifts the content up, so the
     * scroll is done after that reflow rather than letting the browser jump to
     * a position measured before it. scroll-margin-top on the headings keeps
     * them clear of the fixed header.
     */
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // While a click-driven smooth scroll is running the spy would report
    // whichever heading is passing the line, so the clicked section is marked
    // active immediately and the spy is held off until the scroll settles.
    let lockedId = null;
    let settleCancel = null;

    /**
     * Runs once the page has actually stopped moving. A fixed timer cannot do
     * this: a smooth scroll from the foot of a long report up to an early
     * section runs for several seconds, so a timer releases mid-flight and the
     * header and menu spring back before the reader has arrived. Native
     * scrollend where it exists, a short pause in movement where it does not,
     * and a hard cap so a scroll the browser abandons cannot strand the lock.
     */
    function whenScrollSettles(done) {
        if (settleCancel) {
            settleCancel();
        }

        let finished = false;

        function finish() {
            if (finished) {
                return;
            }

            finished = true;
            settleCancel = null;
            window.removeEventListener('scrollend', finish);
            clearTimeout(cap);
            done();
        }

        const cap = setTimeout(finish, 4000);

        settleCancel = function () {
            finished = true;
            window.removeEventListener('scrollend', finish);
            clearTimeout(cap);
        };

        if ('onscrollend' in window) {
            window.addEventListener('scrollend', finish);

            return;
        }

        let last = window.scrollY;
        let still = 0;

        (function tick() {
            if (finished) {
                return;
            }

            if (window.scrollY === last) {
                still++;
            } else {
                still = 0;
                last = window.scrollY;
            }

            // Roughly a tenth of a second of no movement.
            if (still >= 6) {
                finish();

                return;
            }

            window.requestAnimationFrame(tick);
        })();
    }

    $links.on('click', function (event) {
        const hash = $(this).attr('href');
        const target = document.getElementById(hash.slice(1));

        if (!target) {
            return;
        }

        event.preventDefault();
        closeAccordion();

        lockedId = hash.slice(1);
        markActive(lockedId);

        target.scrollIntoView({
            behavior: reducedMotion ? 'auto' : 'smooth',
            block: 'start'
        });

        history.replaceState(null, '', hash);

        suppressed = true;
        captureHeader();

        whenScrollSettles(function () {
            lockedId = null;
            suppressed = false;

            // The one state change of the whole journey, and it happens with
            // the page at a standstill: the header retreats and the menu
            // follows it up, both on their own transitions. Nothing has moved
            // until now, so there is nothing to jump.
            hideHeader();
            setActive();
            setStuck();
            setSticky();
        });
    });

    /**
     * Scroll spy. Marks the section currently under the header line, falling
     * back to the first section before it has been reached.
     */
    const targets = [];

    $links.each(function () {
        const id = $(this).attr('href').slice(1);
        const element = document.getElementById(id);

        if (element && !targets.some(function (item) {
            return item.id === id;
        })) {
            targets.push({id: id, element: element});
        }
    });

    if (!targets.length) {
        return;
    }

    function anchorOffset() {
        const value = getComputedStyle(document.body).getPropertyValue('--rd-anchor-offset');

        return parseInt(value, 10) || 120;
    }

    let currentId = null;

    function markActive(id) {
        if (id === currentId) {
            return;
        }

        currentId = id;

        $links.each(function () {
            $(this).closest('li').toggleClass('is-active', $(this).attr('href') === '#' + id);
        });
    }

    function setActive() {
        if (lockedId) {
            return;
        }

        // The reading line sits below the sticky header, not level with it. A
        // heading resting just under the header has been reached, so it should
        // read as current rather than the section above it.
        const line = anchorOffset() + 80;
        let active = targets[0].id;

        targets.forEach(function (target) {
            if (target.element.getBoundingClientRect().top <= line) {
                active = target.id;
            }
        });

        // Once the page is scrolled to the bottom the last section is current,
        // even if its heading sits above the line.
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 2) {
            active = targets[targets.length - 1].id;
        }

        markActive(active);
    }

    /**
     * Flags the sidebar once it has stuck, so the cover image can collapse and
     * leave room for the share row. Only relevant at lg and above, where the
     * sidebar is sticky.
     */
    const $side = $('.report-side');
    const desktop = window.matchMedia('(min-width: 992px)');

    function setStuck() {
        if (!$side.length) {
            return;
        }

        if (!desktop.matches) {
            $side.removeClass('is-stuck');

            return;
        }

        const offset = parseInt(getComputedStyle(document.body).getPropertyValue('--rd-sticky-top'), 10) || 120;

        $side.toggleClass('is-stuck', $side[0].getBoundingClientRect().top <= offset + 1);
    }

    /**
     * The contents menu follows the reader below lg using position: sticky, so
     * it is the same element throughout: it stays in the flow, lifts off when
     * the reader passes it, and settles back into place on the way up. Nothing
     * is duplicated and the article never gains a gap.
     *
     * Only the sticky offset is set here. Where the header goes, the menu goes.
     */
    let suppressed = false;
    let wasStuck = false;

    function gap() {
        return parseInt(getComputedStyle(document.body).getPropertyValue('--rd-pinned-gap'), 10) || 12;
    }

    function headerEl() {
        return document.getElementById('header');
    }

    function headerHeight() {
        const header = headerEl();

        return header ? header.getBoundingClientRect().height : 49;
    }

    /**
     * The header's destination rather than where it is now. Its top is
     * transitioned, so reading the computed value part way through the slide
     * gives a point in the middle of it.
     */
    function headerTargetTop() {
        const header = headerEl();

        if (!header) {
            return 50;
        }

        return parseInt(header.style.top !== '' ? header.style.top : getComputedStyle(header).top, 10);
    }

    function offScreen() {
        return -($accordion.outerHeight() + 20);
    }

    function setSticky() {
        if (!$accordion.length) {
            return;
        }

        if (desktop.matches) {
            $accordion.css('top', '');

            return;
        }

        // Out of the way whenever the header itself has retreated: where the
        // header goes, the menu goes. A negative sticky offset parks it above
        // the viewport without taking it out of the flow, so it still returns
        // to its own place further up the page.
        //
        // Deliberately not keyed off `suppressed`. While a chosen section is
        // being scrolled to the header is held in place, so the menu holds with
        // it and the pair only retreat once the page has stopped.
        const hide = headerTargetTop() < 0;
        const offset = hide ? offScreen() : headerTargetTop() + headerHeight() + gap();

        $accordion.css('top', offset + 'px');

        // Collapse as it lifts off, so an open list cannot ride down the page
        // over the article. Acted on only when the state changes: opening the
        // panel alters the layout enough to nudge the header, and closing on
        // every frame instead let that feed back and shut the panel the moment
        // it was opened.
        const stuck = $accordion[0].getBoundingClientRect().top <= offset + 1;

        if (stuck !== wasStuck) {
            // Drives the panel from pushing the article to overlaying it. While
            // stuck, an opening panel that added height would shift everything
            // below, the browser would correct the scroll to compensate, and
            // that correction reads as a scroll down and retracts the header.
            $accordion.toggleClass('is-stuck', stuck);

            if (stuck) {
                closeAccordion();
            }
        }

        wasStuck = stuck;
    }

    /**
     * Following a link leaves both the menu and the site header exactly where
     * they are for the whole journey, then retreats them together once the page
     * has come to a complete stop.
     *
     * The header's own handler hides it on any downward scroll, so a jump to a
     * later section would otherwise take it off screen part way through, and a
     * jump to an earlier one would bring it back. Either is movement the reader
     * did not ask for, in the middle of movement they did. Holding it in place
     * each frame while the scroll is in flight means nothing shifts until the
     * page is still, and then only once.
     */
    function hideHeader() {
        const header = headerEl();

        if (header) {
            header.style.top = '-100px';
        }
    }

    // Whatever the header was doing when the link was clicked is what it keeps
    // doing for the whole journey. Pinning it to a fixed value instead would
    // make a header that happened to be retreated slide back in as the travel
    // started, which is the same unasked-for movement in the other direction.
    let heldHeaderTop = null;

    function captureHeader() {
        const header = headerEl();

        heldHeaderTop = header
            ? (header.style.top !== '' ? header.style.top : getComputedStyle(header).top)
            : null;
    }

    function holdHeader() {
        const header = headerEl();

        if (header && heldHeaderTop !== null) {
            header.style.top = heldHeaderTop;
        }
    }

    let queued = false;

    function onScroll() {
        if (queued) {
            return;
        }

        queued = true;

        window.requestAnimationFrame(function () {
            queued = false;

            if (suppressed) {
                // Still travelling to the chosen section. This runs after the
                // header's own scroll handler, so it has the last word. The
                // retreat is driven by the scroll settling, not by this handler.
                holdHeader();
            }

            setActive();
            setStuck();
            setSticky();
        });
    }

    setActive();
    setStuck();
    setSticky();
    $(window).on('scroll resize', onScroll);
});
