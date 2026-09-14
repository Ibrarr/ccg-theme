<?php

/**
 * Add schema to pages
 *
 * @return void
 */
function ccg_schema_type() {
	$schema = 'https://schema.org/';
	if ( is_single() ) {
		$type = "Article";
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} else {
		$type = 'WebPage';
	}
	echo 'itemscope itemtype="' . esc_url( $schema ) . esc_attr( $type ) . '"';
}

add_action( 'init', 'register_custom_page_templates' );
function register_custom_page_templates() {
	$template_dir = CCG_TEMPLATE_DIR . '/page-templates/';

	$template_files = glob( $template_dir . '*.php' );

	foreach ( $template_files as $template_file ) {
		$template_name  = str_replace( array( $template_dir, '.php' ), '', $template_file );
		$template_label = ucwords( str_replace( '-', ' ', $template_name ) );
		$template_label = str_replace( '_', ' ', $template_label );

		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-page-template', $template_name, array(
			'label' => $template_label,
		) );
	}
}
/**
 * Renders the homepage statement band's text.
 *
 * The approved design sets the statement in Libre Baskerville Italic and snaps
 * the closing phrase to Poppins Medium. The field is plain text with no markup,
 * so there is nothing to key the second half off.
 *
 * An editor can wrap their own phrase in <strong> in the field and that wins.
 * Failing that, the closing clause is taken to start at the last " and ", which
 * is exactly where the approved copy splits. The words themselves are never
 * altered, only wrapped.
 *
 * @param string $text Raw field value.
 * @return string Escaped HTML.
 */
function ccg_statement_band_html( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	// Author markup wins.
	if ( preg_match( '/<(strong|b|span|em)\b/i', $text ) ) {
		return wp_kses_post( $text );
	}

	$split = mb_strrpos( $text, ' and ' );

	if ( false === $split ) {
		return esc_html( $text );
	}

	return esc_html( mb_substr( $text, 0, $split + 1 ) )
		. '<span class="intro-snap">' . esc_html( mb_substr( $text, $split + 1 ) ) . '</span>';
}
