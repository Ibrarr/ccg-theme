const mix = require('laravel-mix');

mix.js([
    'assets/js/header/main-menu.js',
    'assets/js/header/sliders.js',
    'assets/js/header/load-search-results.js'
], 'js/header.js');

mix.js([
    'assets/js/homepage/gsap-animations.js',
    'assets/js/homepage/header-slider.js',
    'assets/js/homepage/sector-slider.js',
], 'js/homepage.js');

mix.js([
    'assets/js/sectors-home/gsap-animations.js',
    'assets/js/sectors-home/dropdown.js',
    'assets/js/sectors-home/slider.js',
], 'js/sectors-home.js');

mix.js([
    'assets/js/services-home/gsap-animations.js',
    'assets/js/services-home/accordion.js',
], 'js/services-home.js');

mix.js([
    'assets/js/contact/gsap-animations.js',
    'assets/js/contact/google-maps.js',
], 'js/contact.js');

mix.js([
    'assets/js/awards/gsap-animations.js',
    'assets/js/awards/accordion.js',
], 'js/awards.js');

mix.js([
    'assets/js/careers/gsap-animations.js',
    'assets/js/careers/cards.js',
    'assets/js/careers/accordion.js',
    'assets/js/careers/benefits.js',
], 'js/careers.js');

mix.js([
    'assets/js/about/gsap-animations.js',
    'assets/js/about/slider.js',
], 'js/about.js');

mix.js([
    'assets/js/senior-team/gsap-animations.js',
    'assets/js/senior-team/team.js',
], 'js/team.js');

mix.js([
    'assets/js/work-single/gsap-animations.js',
    'assets/js/work-single/dropdown.js',
], 'js/work-single.js');

mix.js([
    'assets/js/blog-single/gsap-animations.js',
], 'js/blog-single.js');

mix.js([
    'assets/js/insight-single/gsap-animations.js',
], 'js/insight-single.js');

mix.js([
    'assets/js/sector-term/gsap-animations.js',
    'assets/js/sector-term/newsletter-popup.js',
    'assets/js/sector-term/dropdown.js',
], 'js/sector-term.js');

mix.js([
    'assets/js/service-term/gsap-animations.js',
    'assets/js/service-term/dropdown.js',
    'assets/js/service-term/services-slider.js',
], 'js/service-term.js');

mix.js([
    'assets/js/insight-archive/gsap-animations.js',
    'assets/js/insight-archive/slider.js',
    'assets/js/insight-archive/load-posts.js'
], 'js/insight-archive.js');

mix.js([
    'assets/js/work-archive/gsap-animations.js',
    'assets/js/work-archive/load-posts.js'
], 'js/work-archive.js');

mix.js([
    'assets/js/blog-archive/gsap-animations.js',
    'assets/js/blog-archive/load-posts.js'
], 'js/blog-archive.js');

mix.js([
    'assets/js/bottom-cta/gsap-animations.js',
], 'js/bottom-cta.js');

mix.js([
    'assets/js/default-page/gsap-animations.js',
], 'js/default-page.js');

mix.js([
    'assets/js/where-we-work/gsap-animations.js',
], 'js/where-we-work.js');

mix.js([
    'assets/js/news-archive/gsap-animations.js',
    'assets/js/news-archive/load-posts.js'
], 'js/news-archive.js');

mix.js([
    'assets/js/type-term/gsap-animations.js',
    'assets/js/type-term/load-posts.js'
], 'js/type-term.js');

mix.js([
    'assets/js/source-term/gsap-animations.js',
    'assets/js/source-term/load-posts.js'
], 'js/source-term.js');

mix.js([
    'assets/js/category-term/gsap-animations.js',
    'assets/js/category-term/load-posts.js'
], 'js/category-term.js');

mix.js([
    'assets/js/search/gsap-animations.js',
    'assets/js/search/load-posts.js'
], 'js/search.js');

mix.sass('assets/css/app.scss', 'css/app.css')
    .options({
        processCssUrls: false
    });

mix.setPublicPath('dist');

mix.options({
    postCss: [
        require('autoprefixer')({
            overrideBrowserslist: ['last 3 versions'],
            cascade: false
        })
    ]
});

mix.disableNotifications();