jQuery(document).ready(function ($) {
    let offset = 0;
    let isLoading = false;
    let searchTimeout;

    function loadPosts(search = '') {
        if (isLoading) return;
        isLoading = true;
        $('#search-loading-indicator').show();

        $.ajax({
            url: frontendajax.ajaxurl,
            data: {
                action: 'load_search_results_header',
                search: search,
                offset: offset,
            },
            success: function (response) {
                let data = JSON.parse(response);
                $('#header-search-count').text(data.total_count + ' results');

                if (data.count > 0) {
                    if (offset === 0) {
                        $('#header-search-container').html(data.html);
                    } else {
                        $('#header-search-container').append(data.html);
                    }

                    if (data.count < 12) {
                        $('#load-more-search-results').hide();
                    } else {
                        $('#load-more-search-results').show();
                        offset += 12;
                    }
                } else if (data.count === 0 && offset === 0) {
                    $('#header-search-container').html(data.html);
                    $('#load-more-search-results').hide();
                }
                $('#search-loading-indicator').hide();
                isLoading = false;
            },
            error: function () {
                $('#search-loading-indicator').hide();
                isLoading = false;
            }
        });
    }

    $('.header-search-input input').on('keyup', function () {
        clearTimeout(searchTimeout);
        let searchQuery = $(this).val();

        searchTimeout = setTimeout(function () {
            loadPosts(searchQuery);
            offset = 0;
        }, 500);
    });

    $('#load-more-search-results').on('click', function () {
        loadPosts();
    });
});