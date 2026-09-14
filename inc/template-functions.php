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
/**
 * Wraps a passage's opening sentence in a span so it can carry the serif lead
 * voice, per the type scale's statement-block role.
 *
 * The design sets the first sentence of these passages in Libre Baskerville
 * Italic and the remainder in Poppins, which needs one inline element. The
 * copy, its order and its meaning are untouched: only a span is added around
 * text that is already there. Author markup is honoured rather than stripped,
 * and a passage the splitter cannot read simply comes back unwrapped.
 *
 * @param string $text The passage.
 * @return string      The passage with its opening sentence wrapped.
 */
function ccg_statement_lead_html( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	// Already wrapped by an editor, or carrying markup we should not re-cut.
	if ( false !== strpos( $text, 'statement-lead' ) ) {
		return wp_kses_post( $text );
	}

	// The end of the first sentence: a full stop, question or exclamation mark
	// followed by whitespace.
	if ( preg_match( '/^(.+?[.?!])(\s+)(\S.*)$/su', $text, $m ) ) {
		return '<span class="statement-lead">' . esc_html( $m[1] ) . '</span>'
			. esc_html( $m[2] ) . esc_html( $m[3] );
	}

	// A single-sentence passage has no break to read, but the design still
	// opens on a serif phrase: Our News runs "Keep up to date" into "with our
	// latest news…" as one sentence. Fall back to the opening four words, the
	// length the handover's own two examples use. Only for a passage long
	// enough that a lead still leaves a remainder; anything shorter is the
	// statement itself and stays whole. An editor who wants a different split
	// wraps their own phrase in the field, which the guard above honours.
	$words = preg_split( '/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE );

	if ( count( $words ) < 17 ) {
		return esc_html( $text );
	}

	$lead      = implode( '', array_slice( $words, 0, 7 ) );
	$separator = $words[7];
	$rest      = implode( '', array_slice( $words, 8 ) );

	return '<span class="statement-lead">' . esc_html( $lead ) . '</span>'
		. esc_html( $separator ) . esc_html( $rest );
}

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

/**
 * Flags the templates whose header sits on a light ground.
 *
 * Section 2 names `has-light-header` as the hook for the nav keyline on
 * templates where the section below the header is white and the white nav
 * would otherwise lose its edge: the insight article, the blog post, the
 * sector and service pages and the case study. The dark mastheads (homepage,
 * the listing pages, About) never carry it.
 *
 * On this theme the keyline itself is already delivered by the nav pill, which
 * carries a full `--hairline` border on all four sides rather than a single
 * bottom rule, so this class is the documented hook rather than the mechanism.
 * Anything scoped to it must not add a second edge.
 *
 * @param string[] $classes Body classes.
 * @return string[]
 */
function ccg_light_header_body_class( $classes ) {
	if ( is_singular( array( 'post', 'insight', 'work' ) ) || is_tax( array( 'sector', 'service' ) ) ) {
		$classes[] = 'has-light-header';
	}

	return $classes;
}

add_filter( 'body_class', 'ccg_light_header_body_class' );
