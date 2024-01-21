<?php

add_action( 'after_setup_theme', 'ccg_setup' );
function ccg_setup() {
	load_theme_textdomain( 'ccg', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'html5', array( 'search-form', 'navigation-widgets' ) );
	add_theme_support( 'woocommerce' );
	add_image_size( 'header-image', 1920, 1080 );
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 1920;
	}
	register_nav_menus( array( 'main-menu' => esc_html__( 'Main Menu', 'ccg' ) ) );
}

add_action( 'wp_enqueue_scripts', 'ccg_enqueue' );
function ccg_enqueue() {
	wp_enqueue_style( 'ccg-style', get_stylesheet_uri() );
	wp_enqueue_script( 'jquery' );
}

add_action( 'wp_footer', 'ccg_footer' );
function ccg_footer() {
	?>
    <script>
        jQuery(document).ready(function ($) {
            var deviceAgent = navigator.userAgent.toLowerCase();
            if (deviceAgent.match(/(iphone|ipod|ipad)/)) {
                $("html").addClass("ios");
                $("html").addClass("mobile");
            }
            if (deviceAgent.match(/(Android)/)) {
                $("html").addClass("android");
                $("html").addClass("mobile");
            }
            if (navigator.userAgent.search("MSIE") >= 0) {
                $("html").addClass("ie");
            } else if (navigator.userAgent.search("Chrome") >= 0) {
                $("html").addClass("chrome");
            } else if (navigator.userAgent.search("Firefox") >= 0) {
                $("html").addClass("firefox");
            } else if (navigator.userAgent.search("Safari") >= 0 && navigator.userAgent.search("Chrome") < 0) {
                $("html").addClass("safari");
            } else if (navigator.userAgent.search("Opera") >= 0) {
                $("html").addClass("opera");
            }
        });
    </script>
	<?php
}

if ( ! function_exists( 'ccg_wp_body_open' ) ) {
	function ccg_wp_body_open() {
		do_action( 'wp_body_open' );
	}
}

add_action( 'wp_body_open', 'ccg_skip_link', 5 );
function ccg_skip_link() {
	echo '<a href="#content" class="skip-link screen-reader-text">' . esc_html__( 'Skip to the content', 'ccg' ) . '</a>';
}

add_action( 'wp_head', 'ccg_pingback_header' );
function ccg_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s" />' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}

add_action( 'wpseo_register_extra_replacements', 'register_custom_yoast_variable' );
function register_custom_yoast_variable() {
	wpseo_register_var_replacement( '%%intro120%%', 'get_intro_text_for_yoast', 'advanced', 'Gets the first 120 characters of the intro field' );
}

function get_intro_text_for_yoast() {
	$intro = get_field( 'intro' );

	if ( $intro ) {
		return ( strlen( $intro ) > 120 ) ? substr( $intro, 0, 126 ) . '...' : $intro;
	}

	return '';
}

register_nav_menus( array( 'footer-menu' => esc_html__( 'Footer Menu', 'ccg' ) ) );
register_nav_menus( array( 'info-menu' => esc_html__( 'Info Menu', 'ccg' ) ) );

add_action( 'gform_after_submission', 'send_to_hubspot', 10, 2 );
function send_to_hubspot( $entry, $form ) {
	$hubspot_form_guid = rgar( $entry, '6' );

	$hubspot_data = array(
		'firstname' => rgar( $entry, '1' ),
		'lastname'  => rgar( $entry, '2' ),
		'company'   => rgar( $entry, '3' ),
		'jobtitle'  => rgar( $entry, '4' ),
		'email'     => rgar( $entry, '5' ),
		'phone'     => rgar( $entry, '8' ),
		'message'   => rgar( $entry, '7' ),
	);

	$hubspot_data = array_filter( $hubspot_data );

	$hubspot_api_url = get_field( 'hubspot_api_url', 'option' ) . $hubspot_form_guid;
	$hubspot_api_key = get_field( 'hubspot_api_key', 'option' );

	$post_json = json_encode( array(
		'fields' => array_map( function ( $value, $name ) {
		}, $hubspot_data, array_keys( $hubspot_data ) )
	) );

	$response = wp_remote_post( $hubspot_api_url, array(
		'body'    => $post_json,
		'headers' => array(
			'Content-Type'  => 'application/json',
			'Authorization' => 'Bearer ' . $hubspot_api_key
		)
	) );

//	error_log( 'HubSpot Response: ' . print_r( $response, true ) );
}

//add_action( 'template_redirect', 'custom_taxonomy_term_redirect' );
//function custom_taxonomy_term_redirect() {
//	if ( is_tax( 'type' ) ) {
//		$term_slug = get_query_var( 'term_slug' );
//		if ( $term_slug ) {
//			$redirect_url = home_url( "/insight-hub/?types=$term_slug" );
//			wp_redirect( $redirect_url, 301 );
//			exit();
//		}
//	}
//}

add_filter( 'template_include', 'custom_404_redirect' );
function custom_404_redirect( $template ) {
	if ( is_404() ) {
		status_header( 404 );
		$generic_404_template = locate_template( '404.php' );
		if ( $generic_404_template ) {
			return $generic_404_template;
		}
	}

	return $template;
}

add_action( 'init', 'custom_modify_category_taxonomy' );
function custom_modify_category_taxonomy() {
	$args = array(
		'rewrite' => array(
			'with_front' => false,
		),
	);

	register_taxonomy( 'category', 'post', $args );
}

add_action( 'template_redirect', 'wpb_change_search_url' );
function wpb_change_search_url() {
	if ( is_search() && empty( $_GET['s'] ) ) {
		wp_redirect( home_url( "/search/" ) );
		exit();
	} else if ( is_search() && ! empty( $_GET['s'] ) ) {
		wp_redirect( home_url( "/search/" ) . '?term=' . urlencode( get_query_var( 's' ) ) );
		exit();
	}
}

add_action( 'template_redirect', 'enable_maintenance_mode' );
function enable_maintenance_mode() {
	if ( ! is_user_logged_in() && get_field( 'enable_maintenance_mode', 'option' ) ) {
		add_filter( 'body_class', function ( $classes ) {
			$classes[] = 'page-template-page-holding-page';

			return $classes;
		} );

		status_header( 503 );
		include( CCG_TEMPLATE_DIR . '/page-templates/page-holding-page.php' );
		die();
	}
}