import {gsap} from "gsap";
import {ScrollTrigger} from "gsap/ScrollTrigger";

function initializeGSAP() {
    gsap.registerPlugin(ScrollTrigger);
    ScrollTrigger.batch("#posts-container .article-card", {
        batchMax: 6,
        onEnter: (batch) => {
            gsap.to(batch, {autoAlpha: 1, y: -20, stagger: 0.15, overwrite: true});
        },
    });
}

jQuery(document).ready(function ($) {
    initializeGSAP();

    let offset = 0;
    let isLoading = false;
    let sectors = [];
    let types = [];

    let currentPostsPerPage = getPostsPerPage();

    let params = new URLSearchParams(window.location.search);
    let initialSectorTerm = params.get('sectors') || '';
    let initialTypeTerm = params.get('types') || '';

    function loadPosts() {
        if (isLoading) return;
        isLoading = true;
        $('#loading-indicator').show();

        let postsPerPage = getPostsPerPage();

        $.ajax({
            url: frontendajax.ajaxurl,
            data: {
                action: 'load_insight_hub_posts',
                sector: sectors.join(','),
                type: types.join(','),
                offset: offset,
                posts_per_page: postsPerPage
            },
            success: function (response) {
                let data = JSON.parse(response);
                if (data.count > 0) {
                    if (offset === 0) {
                        $('#posts-container').html(data.html);
                    } else {
                        $('#posts-container').append(data.html);
                    }

                    if (data.count < postsPerPage) {
                        $('#load-more-posts').hide();
                    } else {
                        $('#load-more-posts').show();
                        offset += postsPerPage;
                    }
                    initializeGSAP();
                } else if (data.count === 0 && offset === 0) {
                    $('#posts-container').html(data.html);
                    $('#load-more-posts').hide();
                }
                $('#loading-indicator').hide();
                isLoading = false;
            },
            error: function () {
                $('#loading-indicator').hide();
                isLoading = false;
            }
        });
    }

    function getPostsPerPage() {
        let screenWidth = window.innerWidth;
        if (screenWidth > 991) {
            return 9;
        } else if (screenWidth >= 768 && screenWidth <= 991) {
            return 8;
        } else {
            return 10;
        }
    }

    function updatePosts() {
        offset = 0;
        sectors = [];
        types = [];

        // Collect selected sectors and types
        $('.selected-terms .selected-term').each(function () {
            let term = $(this);
            let value = term.data('value');
            if (term.hasClass('sector')) {
                sectors.push(value);
            } else if (term.hasClass('type')) {
                types.push(value);
            }
        });

        loadPosts();
    }

    function preselectDropdownOptions() {
        function selectTerm(term, isSector) {
            let value = term.data('value');
            let text = term.text();

            $('.selected-terms').append('<span class="selected-term' + (isSector ? ' sector' : ' type') + '" data-value="' + value + '">' + text + '</span>');
            term.hide();
        }

        if (initialSectorTerm) {
            let sectorTerm = $(".term-selector-item.sector[data-value='" + initialSectorTerm + "']");
            if (sectorTerm.length) {
                sectors.push(initialSectorTerm);
                selectTerm(sectorTerm, true);
            }
        }
        if (initialTypeTerm) {
            let typeTerm = $(".term-selector-item.type[data-value='" + initialTypeTerm + "']");
            if (typeTerm.length) {
                types.push(initialTypeTerm);
                selectTerm(typeTerm, false);
            }
        }
    }

    $(window).resize(function () {
        let newPostsPerPage = getPostsPerPage();
        if (newPostsPerPage !== currentPostsPerPage) {
            currentPostsPerPage = newPostsPerPage;
            updatePosts();
        }
    });

    preselectDropdownOptions();
    loadPosts();

    $('#load-more-posts').on('click', function () {
        loadPosts();
    });

    $('.term-selector').click(function (event) {
        const termSelector = $('.term-selector');
        const selectorMenuContainer = $('.term-selector-menu-container');
        const selectorTop = termSelector.offset().top;
        const selectorBottom = selectorTop + selectorMenuContainer.outerHeight() + 60;
        const windowScrollTop = $(window).scrollTop();
        const windowBottom = windowScrollTop + $(window).height();

        termSelector.toggleClass('dropdown-open');
        selectorMenuContainer.toggle();

        if (selectorTop < windowScrollTop) {
            window.scrollTo(0, selectorTop - 30);
        } else if (selectorBottom > windowBottom) {
            const necessaryScroll = selectorBottom - $(window).height() + 30;
            window.scrollTo(0, necessaryScroll);
        }

        event.stopPropagation();
    });

    $('.term-selector-item').click(function () {
        let item = $(this);
        let value = item.data('value');
        let text = item.text();
        let isSector = item.hasClass('sector');

        $('.selected-terms').append('<span class="selected-term' + (isSector ? ' sector' : ' type') + '" data-value="' + value + '">' + text + '</span>');
        item.hide();

        $('.term-selector-menu-container').hide();

        updatePosts();
    });

    $('.selected-terms').on('click', '.selected-term', function () {
        let selectedTerm = $(this);
        let value = selectedTerm.data('value');

        selectedTerm.remove();

        $('.term-selector-item[data-value="' + value + '"]').show();

        updatePosts();
    });

    $(document).click(function (event) {
        const $trigger = $(".term-selector, .term-selector-menu-container");
        if ($trigger !== event.target && !$trigger.has(event.target).length) {
            $('.term-selector').removeClass('dropdown-open');
            $('.term-selector-menu-container').hide();
        }
    });
});