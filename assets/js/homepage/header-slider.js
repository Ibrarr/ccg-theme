import Splide from '@splidejs/splide';

const headerSlider = new Splide('.header-slider .splide', {
    type: 'loop',
    pagination: false,
    arrows: false,
    pauseOnHover: false,
    drag: false,
    autoplay: true,
    updateOnMove: true,
    cover: true,
    height: '85vh',
    perPage: 1,
    interval: 5000,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)'
});

// Splide's loop mode clones slides, and a clone of the first slide carries a
// copy of its <h1>, so the page reported two. The clones are presentational
// duplicates, so their heading is demoted to a <p> with the same classes: the
// markup and the styling are identical, only the rank goes. Runs after mount
// because the clones do not exist before it.
headerSlider.on('mounted', () => {
    document.querySelectorAll('.header-slider .splide__slide--clone h1').forEach((h1) => {
        const p = document.createElement('p');
        p.className = h1.className;
        p.innerHTML = h1.innerHTML;
        h1.replaceWith(p);
    });
});

headerSlider.mount();