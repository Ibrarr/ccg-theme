<?php

add_action( 'wp_enqueue_scripts', 'add_custom_scripts' );
function add_custom_scripts() {
	wp_enqueue_script( 'jquery-ui', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', [ 'jquery' ], '1.12.1', true );

	wp_enqueue_style( 'site', CCG_TEMPLATE_URI . '/dist/css/app.css', [], filemtime( CCG_TEMPLATE_DIR . '/dist/css/app.css' ), 'all' );

	wp_enqueue_script( 'header', CCG_TEMPLATE_URI . '/dist/js/header.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/header.js' ), true );
	wp_localize_script( 'header', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );

	wp_enqueue_script( 'bottom-cta', CCG_TEMPLATE_URI . '/dist/js/bottom-cta.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/bottom-cta.js' ), true );

	if ( is_front_page() ) {
		wp_enqueue_script( 'homepage', CCG_TEMPLATE_URI . '/dist/js/homepage.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/homepage.js' ), true );
	}

	if ( is_home() ) {
		wp_enqueue_script( 'blog-archive', CCG_TEMPLATE_URI . '/dist/js/blog-archive.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/blog-archive.js' ), true );
		wp_localize_script( 'blog-archive', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	if ( is_tax( 'sector' ) ) {
		wp_enqueue_script( 'sector-term', CCG_TEMPLATE_URI . '/dist/js/sector-term.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/sector-term.js' ), true );
	}

	if ( is_tax( 'service' ) ) {
		wp_enqueue_script( 'service-term', CCG_TEMPLATE_URI . '/dist/js/service-term.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/service-term.js' ), true );
	}

    if ( is_tax( 'type' ) ) {
        wp_enqueue_script( 'type-term', CCG_TEMPLATE_URI . '/dist/js/type-term.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/type-term.js' ), true );
        wp_localize_script( 'type-term', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    if ( is_tax( 'source' ) ) {
        wp_enqueue_script( 'source-term', CCG_TEMPLATE_URI . '/dist/js/source-term.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/source-term.js' ), true );
        wp_localize_script( 'source-term', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

    if ( is_category() ) {
        wp_enqueue_script( 'category-term', CCG_TEMPLATE_URI . '/dist/js/category-term.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/category-term.js' ), true );
        wp_localize_script( 'category-term', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

	if ( is_singular( 'work' ) ) {
		wp_enqueue_script( 'work-single', CCG_TEMPLATE_URI . '/dist/js/work-single.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/work-single.js' ), true );
	}

	if ( is_singular( 'insight' ) ) {
		wp_enqueue_script( 'insight-single', CCG_TEMPLATE_URI . '/dist/js/insight-single.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/insight-single.js' ), true );
	}

	if ( is_singular( 'post' ) ) {
		wp_enqueue_script( 'blog-single', CCG_TEMPLATE_URI . '/dist/js/blog-single.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/blog-single.js' ), true );
	}

	if ( is_post_type_archive( 'news' ) ) {
		wp_enqueue_script( 'news-archive', CCG_TEMPLATE_URI . '/dist/js/news-archive.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/news-archive.js' ), true );
		wp_localize_script( 'news-archive', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	if ( is_post_type_archive( 'work' ) ) {
		wp_enqueue_script( 'work-archive', CCG_TEMPLATE_URI . '/dist/js/work-archive.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/work-archive.js' ), true );
		wp_localize_script( 'work-archive', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	if ( is_post_type_archive( 'insight' ) ) {
		wp_enqueue_script( 'insight-archive', CCG_TEMPLATE_URI . '/dist/js/insight-archive.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/insight-archive.js' ), true );
		wp_localize_script( 'insight-archive', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	}

	if ( is_page_template( 'page-templates/page-sectors.php' ) ) {
		wp_enqueue_script( 'sectors-home', CCG_TEMPLATE_URI . '/dist/js/sectors-home.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/sectors-home.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-services.php' ) ) {
		wp_enqueue_script( 'services-home', CCG_TEMPLATE_URI . '/dist/js/services-home.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/services-home.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-about.php' ) ) {
		wp_enqueue_script( 'about', CCG_TEMPLATE_URI . '/dist/js/about.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/about.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-senior-team.php' ) ) {
		wp_enqueue_script( 'team', CCG_TEMPLATE_URI . '/dist/js/team.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/team.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-awards.php' ) ) {
		wp_enqueue_script( 'awards', CCG_TEMPLATE_URI . '/dist/js/awards.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/awards.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-careers.php' ) ) {
		wp_enqueue_script( 'careers', CCG_TEMPLATE_URI . '/dist/js/careers.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/careers.js' ), true );
	}

	if ( is_page_template( 'page-templates/page-where-we-work.php' ) ) {
		wp_enqueue_script( 'where-we-work', CCG_TEMPLATE_URI . '/dist/js/where-we-work.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/where-we-work.js' ), true );
	}

	if ( is_page() && ! is_page_template() ) {
		wp_enqueue_script( 'default-page', CCG_TEMPLATE_URI . '/dist/js/default-page.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/default-page.js' ), true );
	}

    if ( is_search() || is_page_template( 'page-templates/page-search.php' ) ) {
        wp_enqueue_script( 'search-page', CCG_TEMPLATE_URI . '/dist/js/search.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/search.js' ), true );
        wp_localize_script( 'search-page', 'frontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
    }

	if ( is_page_template( 'page-templates/page-contact.php' ) ) {
		wp_enqueue_script( 'google-maps', 'https://maps.googleapis.com/maps/api/js?key=' . get_field( 'google_maps_api_key', 'option' ) . '&callback=Function.prototype', array(), null, true );
		wp_enqueue_script( 'contact', CCG_TEMPLATE_URI . '/dist/js/contact.js', [ 'jquery' ], filemtime( CCG_TEMPLATE_DIR . '/dist/js/contact.js' ), true );
	}
}