<?php

/**
 * Load posts for Insight Hub
 */
add_action( 'wp_ajax_load_insight_hub_posts', 'load_insight_hub_posts' );
add_action( 'wp_ajax_nopriv_load_insight_hub_posts', 'load_insight_hub_posts' );
function load_insight_hub_posts() {
	$sectors        = isset( $_GET['sector'] ) && $_GET['sector'] !== '' ? explode( ',', $_GET['sector'] ) : array();
	$types          = isset( $_GET['type'] ) && $_GET['type'] !== '' ? explode( ',', $_GET['type'] ) : array();
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;

	$pinned_posts_query = new WP_Query( array(
		'post_type'      => array( 'post', 'insight' ),
		'posts_per_page' => 6,
		'meta_query'     => array(
			array(
				'key'     => 'pinned',
				'value'   => '1',
				'compare' => '='
			)
		)
	) );

	$pinned_post_ids = wp_list_pluck( $pinned_posts_query->posts, 'ID' );
	wp_reset_postdata();

	$args = array(
		'post_type'      => array( 'post', 'insight' ),
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
		'post__not_in'   => $pinned_post_ids
	);

	$tax_query = array( 'relation' => 'AND' );

	foreach ( $sectors as $sector ) {
		$tax_query[] = array(
			'taxonomy' => 'sector',
			'field'    => 'slug',
			'terms'    => $sector,
		);
	}

	foreach ( $types as $type ) {
		if ( $type === 'blog' ) {
			$args['post_type'] = 'post';
		} else {
			$tax_query[] = array(
				'taxonomy' => 'type',
				'field'    => 'slug',
				'terms'    => $type,
			);
		}
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();

		$post_type = get_post_type();
		$term_name = $post_type === 'insight' ? get_the_terms( get_the_ID(), 'type' )[0]->name : 'Blog';

		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load post for Work archive
 */
add_action( 'wp_ajax_load_our_work_posts', 'load_our_work_posts' );
add_action( 'wp_ajax_nopriv_load_our_work_posts', 'load_our_work_posts' );
function load_our_work_posts() {
	$sectors        = isset( $_GET['sector'] ) && $_GET['sector'] !== '' ? explode( ',', $_GET['sector'] ) : array();
	$services       = isset( $_GET['service'] ) && $_GET['service'] !== '' ? explode( ',', $_GET['service'] ) : array();
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;

	$args = array(
		'post_type'      => 'work',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
	);

	$tax_query = array( 'relation' => 'AND' );

	foreach ( $sectors as $sector ) {
		$tax_query[] = array(
			'taxonomy' => 'sector',
			'field'    => 'slug',
			'terms'    => $sector,
		);
	}

	foreach ( $services as $service ) {
		$tax_query[] = array(
			'taxonomy' => 'service',
			'field'    => 'slug',
			'terms'    => $service,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load posts for Blog archive
 */
add_action( 'wp_ajax_load_our_blog_posts', 'load_our_blog_posts' );
add_action( 'wp_ajax_nopriv_load_our_blog_posts', 'load_our_blog_posts' );
function load_our_blog_posts() {
	$categories     = isset( $_GET['category'] ) && $_GET['category'] !== '' ? explode( ',', $_GET['category'] ) : array();
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;

	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
	);

	$tax_query = array( 'relation' => 'AND' );

	foreach ( $categories as $category ) {
		$tax_query[] = array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$term_name = get_the_terms( get_the_ID(), 'category' )[0]->name;
		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load posts for News archive
 */
add_action( 'wp_ajax_load_our_news_posts', 'load_our_news_posts' );
add_action( 'wp_ajax_nopriv_load_our_news_posts', 'load_our_news_posts' );
function load_our_news_posts() {
	$sources        = isset( $_GET['source'] ) && $_GET['source'] !== '' ? explode( ',', $_GET['source'] ) : array();
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;

	$args = array(
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
	);

	$tax_query = array( 'relation' => 'AND' );

	foreach ( $sources as $source ) {
		$tax_query[] = array(
			'taxonomy' => 'source',
			'field'    => 'slug',
			'terms'    => $source,
		);
	}

	if ( count( $tax_query ) > 1 ) {
		$args['tax_query'] = $tax_query;
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$terms     = get_the_terms( get_the_ID(), 'source' );
		$term_name = $terms[0]->name;
		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card-news.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load search results for header search
 */
add_action( 'wp_ajax_load_search_results_header', 'load_search_results_header' );
add_action( 'wp_ajax_nopriv_load_search_results_header', 'load_search_results_header' );
function load_search_results_header() {
	$search = $_GET['search'] ?? '';
	$offset = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;

	$args = array(
		'post_type'      => array( 'post', 'insight', 'work', 'news' ),
		'post_status'    => 'publish',
		's'              => $search,
		'posts_per_page' => 12,
		'offset'         => $offset,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array(
			'total_count' => 0,
			'count'       => 0,
			'html'        => '<p class="no-results">No results found</p>'
		) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		ob_start();
		if ( 'news' === get_post_type() ) {
			// Add attributes for news posts
			$attributes = 'target="_blank" rel="nofollow noopener noreferrer"';
		}
		echo '<div class="search-post-container"><a href="' . get_the_permalink() . '" ' . $attributes . '><p class="title">' . get_the_title() . '</p></a></div>';
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array(
		'total_count' => $query->found_posts,
		'count'       => $query->post_count,
		'html'        => $posts_html
	) );
	die();
}

/**
 * Load search results for search page
 */
add_action( 'wp_ajax_load_search_results_page', 'load_search_results_page' );
add_action( 'wp_ajax_nopriv_load_search_results_page', 'load_search_results_page' );
function load_search_results_page() {
	$search         = $_GET['search'] ?? '';
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;

	$args = array(
		'post_type'      => array( 'post', 'insight', 'work', 'news' ),
		'post_status'    => 'publish',
		's'              => $search,
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array(
			'total_count' => 0,
			'count'       => 0,
			'html'        => ''
		) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();

		$post_type = get_post_type();
		if ( $post_type === 'insight' ) {
			$term_name = get_the_terms( get_the_ID(), 'type' )[0]->name;
		} elseif ( $post_type === 'work' ) {
			$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
		} elseif ( $post_type === 'news' ) {
			$term_name = get_the_terms( get_the_ID(), 'source' )[0]->name;
		} else {
			$term_name = 'Blog';
		}

		ob_start();
		if ( $post_type === 'news' ) {
			require( CCG_TEMPLATE_DIR . '/template-parts/article-card-news.php' );
		} else {
			require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		}
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array(
		'total_count' => $query->found_posts,
		'count'       => $query->post_count,
		'html'        => $posts_html
	) );
	die();
}

/**
 * Load posts for type archives
 */
add_action( 'wp_ajax_load_type_posts', 'load_type_posts' );
add_action( 'wp_ajax_nopriv_load_type_posts', 'load_type_posts' );
function load_type_posts() {
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;
	$term_slug      = isset( $_GET['term_slug'] ) ? sanitize_text_field( $_GET['term_slug'] ) : '';

	$args = array(
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
	);

	if ( $term_slug === 'blog' ) {
		$args['post_type'] = 'post';
	} else {
		$args['post_type'] = 'insight';
		$args['tax_query'] = array(
			array(
				'taxonomy'         => 'type',
				'field'            => 'slug',
				'terms'            => $term_slug,
				'include_children' => false
			),
		);
	}

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$post_type = get_post_type();
		$term_name = $post_type === 'insight' ? get_the_terms( get_the_ID(), 'type' )[0]->name : 'Blog';

		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load posts for category archives
 */
add_action( 'wp_ajax_load_category_posts', 'load_category_posts' );
add_action( 'wp_ajax_nopriv_load_category_posts', 'load_category_posts' );
function load_category_posts() {
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;
	$term_slug      = $_GET['term_slug'] ?? '';

	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
		'tax_query'      => array(
			array(
				'taxonomy'         => 'category',
				'field'            => 'slug',
				'terms'            => $term_slug,
				'include_children' => false
			),
		),
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$terms     = get_the_terms( get_the_ID(), 'category' );
		$term_name = $terms[0]->name;
		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}

/**
 * Load posts for source archives
 */
add_action( 'wp_ajax_load_source_posts', 'load_source_posts' );
add_action( 'wp_ajax_nopriv_load_source_posts', 'load_source_posts' );
function load_source_posts() {
	$offset         = isset( $_GET['offset'] ) ? (int) $_GET['offset'] : 0;
	$posts_per_page = isset( $_GET['posts_per_page'] ) ? (int) $_GET['posts_per_page'] : 9;
	$term_slug      = $_GET['term_slug'] ?? '';

	$args = array(
		'post_type'      => 'news',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'offset'         => $offset,
		'tax_query'      => array(
			array(
				'taxonomy'         => 'source',
				'field'            => 'slug',
				'terms'            => $term_slug,
				'include_children' => false
			),
		),
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		echo json_encode( array( 'count' => 0, 'html' => '<h3 class="no-results">No results found</h3>' ) );
		die();
	}

	$posts_html = '';
	while ( $query->have_posts() ) {
		$query->the_post();
		$terms     = get_the_terms( get_the_ID(), 'source' );
		$term_name = $terms[0]->name;
		ob_start();
		require( CCG_TEMPLATE_DIR . '/template-parts/article-card.php' );
		$posts_html .= ob_get_clean();
	}

	echo json_encode( array( 'count' => $query->post_count, 'html' => $posts_html ) );
	die();
}