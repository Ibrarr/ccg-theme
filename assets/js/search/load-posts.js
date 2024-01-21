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
    let searchTimeout;

    let currentPostsPerPage = getPostsPerPage();

    let params = new URLSearchParams(window.location.search);
    let initialSearchTerm = params.get('term') || '';

    function loadPosts(search = initialSearchTerm) {
        if (isLoading) return;
        isLoading = true;
        $('#loading-indicator').show();

        let postsPerPage = getPostsPerPage();

        $.ajax({
            url: frontendajax.ajaxurl,
            data: {
                action: 'load_search_results_page',
                search: search,
                offset: offset,
                posts_per_page: postsPerPage
            },
            success: function (response) {
                let data = JSON.parse(response);
                if (search) {
                    $('#search-count').text(data.total_count + ' results for ' + search);
                } else {
                    $('#search-count').text(data.total_count + ' results');

                }

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

    $('.search-input input').on('keyup', function () {
        clearTimeout(searchTimeout);
        let searchQuery = $(this).val();

        searchTimeout = setTimeout(function () {
            loadPosts(searchQuery);
            offset = 0;
        }, 500);
    });

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
        loadPosts();
    }

    $(window).resize(function () {
        let newPostsPerPage = getPostsPerPage();
        if (newPostsPerPage !== currentPostsPerPage) {
            currentPostsPerPage = newPostsPerPage;
            updatePosts();
        }
    });

    $('#load-more-posts').on('click', function () {
        loadPosts();
    });

    loadPosts();
});