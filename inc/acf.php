<?php

add_action( 'acf/save_post', 'update_author_to_acf_field', 20 );
function update_author_to_acf_field( $post_id ) {
	$user = get_field( 'author', $post_id );
	if ( $user ) {
		wp_update_post( array( 'ID' => $post_id, 'post_author' => $user['ID'] ) );
	}
}

add_filter( 'acf/settings/save_json', 'my_acf_json_save_point' );
function my_acf_json_save_point( $path ) {
	return CCG_TEMPLATE_DIR . '/acf-json';
}

add_filter( 'acf/json/save_file_name', 'custom_acf_json_filename', 10, 3 );
function custom_acf_json_filename( $filename, $post, $load_path ) {
	$filename = str_replace(
		array(
			' ',
			'_',
		),
		array(
			'-',
			'-'
		),
		$post['title']
	);

	$filename = strtolower( $filename ) . '.json';

	return $filename;
}

add_filter( 'acf/settings/load_json', 'my_acf_json_load_point' );
function my_acf_json_load_point( $paths ) {
	// Remove the original path (optional).
	unset( $paths[0] );

	// Append the new path and return it.
	$paths[] = CCG_TEMPLATE_DIR . '/acf-json';

	return $paths;
}

function validate_total_columns( $value, $field_id, $max_total ) {
	$total = 0;
	foreach ( $value as $row ) {
		$total += intval( $row[ $field_id ] );
	}

	if ( $total > $max_total ) {
		return 'The total number of columns must not exceed ' . $max_total . '. Currently, they add up to ' . $total . '.';
	}

	return true;
}

function impact_column_repeater_validation( $valid, $value, $field_id, $max_total ) {
	if ( ! $valid ) {
		return $valid;
	}

	return validate_total_columns( $value, $field_id, $max_total );
}

add_filter( 'acf/validate_value/name=statistics_first_row_without_quote', function ( $valid, $value ) {
	return impact_column_repeater_validation( $valid, $value, 'field_6551376250ee8', 12 );
}, 10, 4 );

add_filter( 'acf/validate_value/name=statistics_second_row_without_quote', function ( $valid, $value ) {
	return impact_column_repeater_validation( $valid, $value, 'field_6551376550eee', 12 );
}, 10, 4 );

add_filter( 'acf/validate_value/name=statistics_first_row_with_quote', function ( $valid, $value ) {
	return impact_column_repeater_validation( $valid, $value, 'field_655134bb180c8', 12 );
}, 10, 4 );

add_filter( 'acf/validate_value/name=statistics_second_row_with_quote', function ( $valid, $value ) {
	return impact_column_repeater_validation( $valid, $value, 'field_6551370c50ee2', 12 );
}, 10, 4 );

add_shortcode( 'embed_iframe', 'iframe_shortcode' );
function iframe_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'src' => ''
	), $atts );

	return '<iframe src="' . esc_url( $a['src'] ) . '" allowfullscreen="allowfullscreen" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>';
}

add_action( 'acf/init', 'my_acf_init' );
function my_acf_init() {
	acf_update_setting( 'google_api_key', get_field( 'google_maps_api_key', 'option' ) );
}