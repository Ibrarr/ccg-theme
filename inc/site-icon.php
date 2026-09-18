<?php
/**
 * The site icon ships with the theme.
 *
 * The Hoffman mark is a theme asset rather than a Site Icon upload, so a deploy
 * carries it and no database row has to change on each environment. Filtering
 * get_site_icon_url() rather than printing our own <link> tags means WordPress
 * keeps emitting the markup it always did, and everything else that reads the
 * site icon follows: the /favicon.ico request it answers, the touch icon, and
 * the admin. The stored Site Icon attachment is left alone.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The icon file closest to the size WordPress asked for.
 *
 * @param int $size Requested square size in pixels.
 * @return string
 */
function ccg_site_icon_file( $size ) {
	$sizes = [ 16, 32, 180, 192, 512 ];
	$size  = (int) $size > 0 ? (int) $size : 512;

	foreach ( $sizes as $available ) {
		if ( $available >= $size ) {
			return 'icon-' . $available . '.png';
		}
	}

	return 'icon-512.png';
}

add_filter(
	'get_site_icon_url',
	function ( $url, $size ) {
		return CCG_TEMPLATE_URI . '/assets/images/favicon/' . ccg_site_icon_file( $size );
	},
	10,
	2
);

// has_site_icon() reads the option, so an install with no Site Icon set would
// print no icon markup at all and the filter above would never run. This covers
// that case with the same tags WordPress itself emits.
add_action(
	'wp_head',
	function () {
		if ( has_site_icon() ) {
			return;
		}

		$base = CCG_TEMPLATE_URI . '/assets/images/favicon/';

		printf( '<link rel="icon" href="%sicon-32.png" sizes="32x32" />' . "\n", esc_url( $base ) );
		printf( '<link rel="icon" href="%sicon-192.png" sizes="192x192" />' . "\n", esc_url( $base ) );
		printf( '<link rel="apple-touch-icon" href="%sicon-180.png" />' . "\n", esc_url( $base ) );
	},
	99
);
