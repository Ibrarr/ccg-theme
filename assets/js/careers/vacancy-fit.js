// Vacancy titles cap their size against the card, so a long word always fits
// its line (see _vacancies.scss). The stylesheet's own cap is sized for the
// longest word any live vacancy has used, which held every title down to it.
// This measures each title's own longest word instead, so only a title whose
// word cannot fit steps down, and the rest keep B4's size closed and the 44px
// role's size open. Without JavaScript the stylesheet's cap still applies.
jQuery(document).ready(function () {
    const titles = document.querySelectorAll('.vacancy > h4');

    if (!titles.length || !document.fonts) {
        return;
    }

    // The two states set in different weights. Read the opened family from a
    // hidden, empty copy of an opened vacancy rather than naming a font here.
    const probe = titles[0].parentElement.cloneNode(false);
    probe.classList.add('active');
    probe.style.cssText = 'position:absolute;visibility:hidden;pointer-events:none';
    const probeTitle = document.createElement('h4');
    probe.appendChild(probeTitle);
    titles[0].parentElement.parentElement.appendChild(probe);
    const closedFamily = getComputedStyle(titles[0]).fontFamily;
    const openFamily = getComputedStyle(probeTitle).fontFamily;
    probe.remove();

    const context = document.createElement('canvas').getContext('2d');

    // The widest word as a multiple of the font size, plus 0.6% for rounding
    // between canvas and the page's own line layout.
    const widestWord = (text, family) => {
        context.font = `100px ${family}`;
        const widths = text.trim().split(/\s+/).map((word) => context.measureText(word).width);
        return (Math.max(...widths) / 100) * 1.006;
    };

    Promise.all([
        document.fonts.load(`100px ${closedFamily}`),
        document.fonts.load(`100px ${openFamily}`),
    ]).then(() => {
        titles.forEach((title) => {
            title.style.setProperty('--title-word', widestWord(title.textContent, closedFamily).toFixed(3));
            title.style.setProperty('--title-word-open', widestWord(title.textContent, openFamily).toFixed(3));
        });
    });
});
