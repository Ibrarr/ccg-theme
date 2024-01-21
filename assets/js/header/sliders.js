import Splide from "@splidejs/splide";

const recentInsights = new Splide('.recent-insights .splide', {
    type: 'rewind',
    pagination: false,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        640: {
            perPage: 2,
        },
    }
});

recentInsights.mount();

const recentInsightsSearch = new Splide('.recent-insights-search .splide', {
    type: 'rewind',
    pagination: false,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        640: {
            perPage: 2,
        },
    }
});

recentInsightsSearch.mount();

const recentUpdates = new Splide('.latest-updates .splide', {
    type: 'rewind',
    pagination: false,
    perMove: 1,
    perPage: 3,
    speed: 1000,
    easing: 'cubic-bezier(0.77, 0, 0.175, 1)',
    breakpoints: {
        640: {
            perPage: 2,
        },
    }
});

recentUpdates.mount();