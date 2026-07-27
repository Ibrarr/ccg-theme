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

    $toggle.on('click', function () {
        const expanded = $(this).attr('aria-expanded') === 'true';

        $(this).attr('aria-expanded', expanded ? 'false' : 'true');
        $('#' + $(this).attr('aria-controls')).prop('hidden', expanded);
    });

    function closeAccordion() {
        $toggle.each(function () {
            $(this).attr('aria-expanded', 'false');
            $('#' + $(this).attr('aria-controls')).prop('hidden', true);
        });
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
    let lockTimer = null;

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

        clearTimeout(lockTimer);
        lockTimer = setTimeout(function () {
            lockedId = null;
        }, reducedMotion ? 100 : 900);
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

    let queued = false;

    function onScroll() {
        if (queued) {
            return;
        }

        queued = true;

        window.requestAnimationFrame(function () {
            queued = false;
            setActive();
            setStuck();
        });
    }

    setActive();
    setStuck();
    $(window).on('scroll resize', onScroll);
});
