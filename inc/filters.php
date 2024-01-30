<?php

/**
 * Modify the document title separator.
 */
add_filter( 'document_title_separator', 'ccg_document_title_separator' );
function ccg_document_title_separator( $sep ) {
	$sep = esc_html( '|' );

	return $sep;
}

/**
 * Modify the title before displaying it.
 */
add_filter( 'the_title', 'ccg_title' );
function ccg_title( $title ) {
	if ( $title == '' ) {
		return esc_html( '...' );
	} else {
		return wp_kses_post( $title );
	}
}

/**
 * Add schema to menu link
 */
add_filter( 'nav_menu_link_attributes', 'ccg_schema_url', 10 );
function ccg_schema_url( $atts ) {
	$atts['itemprop'] = 'url';

	return $atts;
}

/**
 * Modify the "read more" link in the content.
 */
add_filter( 'the_content_more_link', 'ccg_read_more_link' );
function ccg_read_more_link() {
	if ( ! is_admin() ) {
		return ' <a href="' . esc_url( get_permalink() ) . '" class="more-link">' . sprintf( __( '...%s', 'ccg' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
	}
}

/**
 * Modify the "read more" link in excerpts.
 */
add_filter( 'excerpt_more', 'ccg_excerpt_read_more_link' );
function ccg_excerpt_read_more_link( $more ) {
	if ( ! is_admin() ) {
		global $post;

		return ' <a href="' . esc_url( get_permalink( $post->ID ) ) . '" class="more-link">' . sprintf( __( '...%s', 'ccg' ), '<span class="screen-reader-text">  ' . esc_html( get_the_title() ) . '</span>' ) . '</a>';
	}
}

/**
 * Disable big image size threshold.
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Override intermediate image sizes.
 */
add_filter( 'intermediate_image_sizes_advanced', 'ccg_image_insert_override' );
function ccg_image_insert_override( $sizes ) {
	unset( $sizes['medium_large'] );
	unset( $sizes['1536x1536'] );
	unset( $sizes['2048x2048'] );

	return $sizes;
}

/**
 * Enable classic editor
 */
add_filter( 'use_block_editor_for_post', '__return_false', 10 );

/**
 * Replace news permalink with ACF field
 */
add_filter( 'post_type_link', 'modify_news_post_link', 10, 2 );
function modify_news_post_link( $url, $post ) {
	if ( $post->post_type == 'news' ) {
		$news_link = get_post_meta( $post->ID, 'news_link', true );
		if ( $news_link ) {
			return $news_link;
		}
	}

	return $url;
}

/**
 * Add body classes
 */
add_filter( 'body_class', 'custom_body_classes' );
function custom_body_classes( $classes ) {
	if ( 'insight' === get_post_type() ) {
		if ( has_term( 'insight-reports', 'type', get_the_ID() ) ) {
			$classes[] = 'report';
		} elseif ( has_term( 'webinars', 'type', get_the_ID() ) ) {
			$classes[] = 'webinar';
		} else {
			$classes[] = 'standard';
		}
	}

	return $classes;
}

/**
 * Fetch all Gravity Forms for ACF dropdown field
 */
add_filter( 'acf/load_field/name=newsletter_popup_form_gravity_form_id', 'acf_populate_gf_forms_ids' );
add_filter( 'acf/load_field/name=select_gform_form', 'acf_populate_gf_forms_ids' );
function acf_populate_gf_forms_ids( $field ) {
	if ( class_exists( 'GFFormsModel' ) ) {
		$choices = [];

		foreach ( \GFFormsModel::get_forms() as $form ) {
			$choices[ $form->id ] = $form->title;
		}

		$field['choices'] = $choices;
	}

	return $field;
}