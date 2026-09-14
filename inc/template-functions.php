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
/**
 * Sanitises an editor field for display in a styled band.
 *
 * `strip_tags()` was throwing the editor's line breaks away, so paragraphs ran
 * together. `wp_kses_post()` keeps them, but it also keeps `style` attributes,
 * and these fields carry colours pasted out of Word: on the Insight Hub that
 * put near-white text on the navy masthead at 1.10:1, invisible. So the tags
 * that carry structure are allowed and every attribute that could repaint them
 * is not. The band's own CSS then owns colour, which is the point of a design
 * system.
 *
 * @param string $text Raw field value.
 * @return string      Safe HTML, structure intact, colour stripped.
 */
function ccg_editor_html( $text ) {
	$text = (string) $text;

	// These fields render inside a <p>, and ACF's wpautop wraps their content in
	// <p> too. A <p> inside a <p> is invalid, so the browser closes the outer one
	// and the copy ends up OUTSIDE the styled element: on the Insight Hub and the
	// services header that dropped it out of the band's white and onto Bootstrap's
	// default dark grey, over navy, at 1.10:1. Turn the paragraph boundaries into
	// the break they were standing for, then drop <p> from the allowlist below so
	// none can come back.
	$text = preg_replace( '#</p>\s*<p[^>]*>#i', '<br><br>', $text );
	$text = preg_replace( '#</?p[^>]*>#i', '', $text );

	return trim( wp_kses( $text, array(
		'br'     => array(),
		'strong' => array(),
		'b'      => array(),
		'em'     => array(),
		'i'      => array(),
		'span'   => array(),
		'ul'     => array(),
		'ol'     => array(),
		'li'     => array(),
		'a'      => array( 'href' => array(), 'target' => array(), 'rel' => array(), 'title' => array() ),
	) ) );
}

function ccg_statement_lead_html( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	// These fields are WYSIWYG, so they arrive carrying markup: an editor's
	// line breaks, the odd <strong>. Sanitise once here and never escape
	// again below, or the tags print as text on the page, which is what
	// esc_html() was doing to every <br /> on the Insight Hub.
	$text = ccg_editor_html( $text );

	// Already wrapped by an editor: their split wins.
	if ( false !== strpos( $text, 'statement-lead' ) ) {
		return $text;
	}

	// The end of the first sentence, counted outside tags. A bare /(.+?[.?!])/
	// would stop at the first full stop inside an href or a class name, so the
	// alternation steps over any whole tag before it looks for a terminator.
	$sentence = '/^((?:[^<.?!]|<[^>]*>)+?[.?!])(\s+)(.*)$/su';

	if ( preg_match( $sentence, $text, $m ) ) {
		return '<span class="statement-lead">' . $m[1] . '</span>' . $m[2] . $m[3];
	}

	// A single-sentence passage has no break to read, but the design still
	// opens on a serif phrase: Our News runs "Keep up to date" into "with our
	// latest news…" as one sentence. Fall back to the opening four words, the
	// length the handover's own two examples use. Only for a passage long
	// enough that a lead still leaves a remainder; anything shorter is the
	// statement itself and stays whole.
	$words = preg_split( '/(\s+)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE );

	if ( count( $words ) < 17 ) {
		return $text;
	}

	// Never cut inside a tag: if the opening four words carry an unclosed tag,
	// leave the passage whole rather than produce broken markup.
	$lead = implode( '', array_slice( $words, 0, 7 ) );

	if ( substr_count( $lead, '<' ) !== substr_count( $lead, '>' ) ) {
		return $text;
	}

	return '<span class="statement-lead">' . $lead . '</span>'
		. $words[7] . implode( '', array_slice( $words, 8 ) );
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

	// Sanitise once, then never escape: these are WYSIWYG fields and their own
	// line breaks have to render rather than print.
	$text  = ccg_editor_html( $text );
	$split = mb_strrpos( $text, ' and ' );

	if ( false === $split ) {
		return $text;
	}

	return mb_substr( $text, 0, $split + 1 )
		. '<span class="intro-snap">' . mb_substr( $text, $split + 1 ) . '</span>';
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
