/**
 * Section 5: masked line reveals and staggered fade-ups.
 *
 * Each of the six rules the section calls non-negotiable shapes something here:
 *
 * 1. Content-first. Nothing is hidden until this script runs, so a page with
 *    JavaScript off is complete. Hiding happens in JS rather than in a
 *    stylesheet for exactly that reason.
 * 2. Composited properties only. Everything below moves on transform and
 *    opacity; nothing touches height, width, font-size or top/left.
 * 3. Leave the LCP alone. Only elements sitting below the fold at load are ever
 *    hidden, so the hero and anything else in the first frame is untouched and
 *    the first paint is already complete.
 * 4. No per-letter splitting. The masked reveal wraps a heading's existing
 *    children in one span, so the text and its accessible name are unchanged.
 * 5. One IntersectionObserver, vanilla, no animation library. The theme ships
 *    GSAP for the parallax bars; this kit deliberately does not use it.
 * 6. prefers-reduced-motion: reduce and the whole thing stands down before it
 *    has touched the DOM.
 */

(function () {
	if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}

	// Cards, list rows and footer columns fade up.
	var UPS = '.article-card, .winning-award, .award-year, .vacancy,' +
		' footer .footer-logo, footer .main-footer-menu, footer .social-contact, footer .credit';

	function eligible(el) {
		if (!el) {
			return false;
		}

		// Carousels own their slides, and a Splide clone would animate twice.
		if (el.closest('.splide')) {
			return false;
		}

		// Rule 3, and the reason the page is whole in its first frame.
		return el.getBoundingClientRect().top > window.innerHeight;
	}

	function pick(selector) {
		return Array.prototype.filter.call(document.querySelectorAll(selector), eligible);
	}

	// The display heading is the one that opens a section, which is a shape
	// rather than a class: this theme writes it as a bare h3 in most bands and
	// as an h2 in the washed one, where the acid kicker comes first and is not
	// the display heading. Reading the structure rather than listing classes
	// means a new band gets the reveal without anyone remembering to add it.
	function displayHeadings() {
		var out = [];

		Array.prototype.forEach.call(document.querySelectorAll('section'), function (section) {
			var heading = section.querySelector('h2:not(.kicker), h3');

			if (eligible(heading) && out.indexOf(heading) === -1) {
				out.push(heading);
			}
		});

		return out;
	}

	var lines = displayHeadings();
	var ups = pick(UPS).filter(function (el) {
		// never both devices on the same element
		return lines.indexOf(el) === -1;
	});

	if (!lines.length && !ups.length) {
		return;
	}

	lines.forEach(function (el) {
		var inner = document.createElement('span');
		inner.className = 'motion-line-inner';

		while (el.firstChild) {
			inner.appendChild(el.firstChild);
		}

		el.appendChild(inner);
		el.classList.add('motion-line');
	});

	ups.forEach(function (el) {
		el.classList.add('motion-up');
	});

	// 60-90ms between neighbours, counted among the siblings that are actually
	// animating so a row of four cards steps evenly, and capped so a long list
	// never leaves its last row waiting.
	function stagger(el) {
		var siblings = el.parentElement ? el.parentElement.children : [];
		var index = 0;

		for (var i = 0; i < siblings.length; i++) {
			if (siblings[i] === el) {
				break;
			}

			if (siblings[i].classList.contains('motion-up')) {
				index++;
			}
		}

		return Math.min(index, 5) * 75;
	}

	var observer = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (!entry.isIntersecting) {
				return;
			}

			var el = entry.target;
			observer.unobserve(el);

			var delay = el.classList.contains('motion-up') ? stagger(el) : 0;

			window.setTimeout(function () {
				el.classList.add('is-revealed');
			}, delay);

			// The clip exists only while the line is travelling. Left on, it
			// would cut the descenders off a heading at rest.
			if (el.classList.contains('motion-line')) {
				window.setTimeout(function () {
					el.classList.remove('motion-line');
				}, delay + 800);
			}
		});
	}, { rootMargin: '0px 0px -10% 0px' });

	lines.concat(ups).forEach(function (el) {
		observer.observe(el);
	});
}());
