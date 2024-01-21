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

    let currentPostsPerPage = getPostsPerPage();
    let termSlug = $('#posts-container').data('term-slug');

    function loadPosts() {
        if (isLoading) return;
        isLoading = true;
        $('#loading-indicator').show();

        let postsPerPage = getPostsPerPage();

        $.ajax({
            url: frontendajax.ajaxurl,
            data: {
                action: 'load_source_posts',
                offset: offset,
                posts_per_page: postsPerPage,
                term_slug: termSlug
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
        loadPosts();
    }

    $(window).resize(function () {
        let newPostsPerPage = getPostsPerPage();
        if (newPostsPerPage !== currentPostsPerPage) {
            currentPostsPerPage = newPostsPerPage;
            updatePosts();
        }
    });

    loadPosts();

    $('#load-more-posts').on('click', function () {
        loadPosts();
    });
});