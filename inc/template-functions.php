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

/**
 * D3 (v0.12): promote a legacy pull quote to a <blockquote> so the T3 rule sees it.
 *
 * Editors wrote pull quotes as italic paragraphs rather than blockquotes, so the
 * template's pull-quote rule — which is scoped to `blockquote` — never fired on
 * any of them. The catch is here rather than in the content, so nothing is
 * rewritten in the database and removing this function restores the old output
 * exactly.
 *
 * The test is NOT "the paragraph is italic". An audit of every field on this
 * install holding italic markup (78 fields, 1,352 paragraphs) found 47 wholly
 * italic paragraphs, of which only 8 are quotes. The other 39 are sub-headings
 * ("Client Commitment", "Do's in analyst presentations:"), standfirsts and
 * parenthetical asides, and promoting those would be worse than promoting none.
 *
 * The signal that separates them is the opening quotation mark, so all three of
 * these have to hold:
 *
 *   1. the paragraph's entire content is one <em> or <i>;
 *   2. its text opens with a quotation mark, straight or curly, in any of the
 *      forms the editors have actually used;
 *   3. it runs to at least eight words, so a short quoted aside stays inline.
 *
 * A genuine pull quote that opens without a quotation mark is left alone on
 * purpose: nothing in the markup distinguishes it from an aside, and that call
 * belongs to an editor re-marking it as a blockquote.
 *
 * DOM rather than a regex because the paragraph has to be checked for a
 * blockquote ancestor: an editor who did mark a quote up properly has <p>
 * inside it, and promoting that would nest one blockquote inside another.
 */
function ccg_pull_quote_html( $html ) {
	$html = (string) $html;

	if ( '' === trim( $html ) || false === stripos( $html, '<p' ) ) {
		return $html;
	}

	// Cheap guard: no italic markup, nothing to promote.
	if ( false === stripos( $html, '<em' ) && ! preg_match( '#<i[\s>]#i', $html ) ) {
		return $html;
	}

	if ( ! class_exists( 'DOMDocument' ) ) {
		return $html;
	}

	$doc = new DOMDocument();

	// The meta charset is how DOMDocument is told this is UTF-8; without it
	// libxml assumes ISO-8859-1 and every curly quote in the copy — which is
	// the very character this function tests for — arrives as mojibake.
	$previous = libxml_use_internal_errors( true );
	$loaded   = $doc->loadHTML(
		'<?xml encoding="UTF-8"?><div id="ccg-pq-root">' . $html . '</div>',
		LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
	);
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );

	if ( ! $loaded ) {
		return $html;
	}

	$root = $doc->getElementById( 'ccg-pq-root' );

	if ( ! $root ) {
		return $html;
	}

	$promoted = 0;

	// Collected first: replacing a node while iterating a live DOMNodeList
	// skips the element after it.
	$paragraphs = iterator_to_array( $doc->getElementsByTagName( 'p' ) );

	foreach ( $paragraphs as $paragraph ) {
		if ( ! ccg_is_legacy_pull_quote( $paragraph ) ) {
			continue;
		}

		$quote = $doc->createElement( 'blockquote' );
		$inner = $doc->createElement( 'p' );

		// The <em> is unwrapped: the blockquote rule sets the italic itself, and
		// an <em> inside it would ask the browser for a second level of emphasis
		// that Libre Baskerville Italic has no face for.
		$italic = ccg_pull_quote_wrapper( $paragraph );

		while ( $italic->firstChild ) {
			$inner->appendChild( $italic->firstChild );
		}

		$quote->appendChild( $inner );
		$paragraph->parentNode->replaceChild( $quote, $paragraph );
		$promoted ++;
	}

	if ( ! $promoted ) {
		return $html;
	}

	$out = '';

	foreach ( $root->childNodes as $child ) {
		$out .= $doc->saveHTML( $child );
	}

	return $out;
}

/**
 * The single <em>/<i> a paragraph wraps, or null if it wraps anything else.
 *
 * Whitespace-only text nodes are ignored, because the editor's markup is
 * pretty-printed and `<p>\n  <em>…</em>\n</p>` is the common shape.
 */
function ccg_pull_quote_wrapper( DOMElement $paragraph ) {
	$element = null;

	foreach ( $paragraph->childNodes as $child ) {
		if ( XML_TEXT_NODE === $child->nodeType ) {
			if ( '' !== trim( $child->textContent ) ) {
				return null;
			}
			continue;
		}

		if ( XML_ELEMENT_NODE !== $child->nodeType || null !== $element ) {
			return null;
		}

		if ( ! in_array( strtolower( $child->nodeName ), array( 'em', 'i' ), true ) ) {
			return null;
		}

		$element = $child;
	}

	return $element;
}

/**
 * Whether a paragraph is a pull quote an editor wrote as italic body copy.
 */
function ccg_is_legacy_pull_quote( DOMElement $paragraph ) {
	// Already inside a quote: an editor marked this one up properly.
	for ( $parent = $paragraph->parentNode; $parent instanceof DOMElement; $parent = $parent->parentNode ) {
		if ( 'blockquote' === strtolower( $parent->nodeName ) ) {
			return false;
		}
	}

	if ( ! ccg_pull_quote_wrapper( $paragraph ) ) {
		return false;
	}

	// textContent has already resolved entities, so &ldquo; is a real curly
	// quote by the time it is read here.
	$text = trim( preg_replace( '/^[\s\x{00A0}\x{200B}\x{FEFF}]+/u', '', $paragraph->textContent ) );

	if ( '' === $text ) {
		return false;
	}

	$opening = array( '"', "'", "\xE2\x80\x9C", "\xE2\x80\x9D", "\xE2\x80\x98", "\xE2\x80\x99", '«', '„', '“', '‘' );

	if ( ! in_array( mb_substr( $text, 0, 1 ), $opening, true ) ) {
		return false;
	}

	// Eight words, so a short quoted aside inside a sentence stays inline.
	// str_word_count() is not multibyte safe and this copy carries curly
	// punctuation and accented names, so count whitespace runs instead.
	return count( preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY ) ) >= 8;
}

/**
 * B3 (v0.12): the opening CLAUSE as the serif lead, not the opening sentence.
 *
 * Where We Work opens "With offices across Europe, North America, and
 * Asia-Pacific, we have the global reach…", and the phrase the client casts in
 * Libre Baskerville Italic ends at the last comma of that opening clause, part
 * way through the first sentence. ccg_statement_lead_html() splits on a
 * sentence terminator, so it cannot reach it.
 *
 * The split is the last comma BEFORE the first sentence terminator: the phrase
 * itself contains commas ("Europe, North America, and Asia-Pacific"), so the
 * first comma is the wrong one. Falls back to the sentence helper when the
 * passage has no such clause, so a copy change can never leave the band
 * unstyled.
 */
function ccg_statement_clause_html( $text ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	$html = ccg_editor_html( $text );

	if ( false !== strpos( $html, 'statement-lead' ) ) {
		return $html;
	}

	// The first sentence, counted outside tags, exactly as the sentence helper
	// does: a bare /[.?!]/ would stop inside an href. A line break after the
	// terminator ends the sentence too, as it does there.
	if ( preg_match( '/^((?:[^<.?!]|<[^>]*>)+?[.?!])((?:\s|<br\s*\/?>)+)(.*)$/su', $html, $m ) ) {
		$first     = $m[1];
		$remainder = $m[2] . $m[3];
	} else {
		$first     = $html;
		$remainder = '';
	}

	$cut = strrpos( $first, ',' );

	// A clause that is the whole sentence is not a lead-in, and one shorter
	// than a few words is a stray comma rather than an opening phrase.
	if ( false === $cut || $cut >= strlen( $first ) - 2 || substr_count( substr( $first, 0, $cut ), ' ' ) < 2 ) {
		return ccg_statement_lead_html( $text );
	}

	$lead = substr( $first, 0, $cut + 1 );

	// Never cut inside a tag.
	if ( substr_count( $lead, '<' ) !== substr_count( $lead, '>' ) ) {
		return ccg_statement_lead_html( $text );
	}

	return '<span class="statement-lead">' . $lead . '</span>' . substr( $first, $cut + 1 ) . $remainder;
}

/**
 * B6 (v0.12): the closing paragraph of a masthead statement in Libre
 * Baskerville Italic.
 *
 * Takes HTML that has already been through ccg_statement_lead_html(), so the
 * two roles compose: the opening sentence is the serif lead and the last
 * paragraph is the serif closer, with the Poppins body between them.
 *
 * ccg_editor_html() turns every paragraph boundary into a <br><br> pair, so
 * that pair is what a paragraph break looks like by the time it reaches here.
 * It only fires at three paragraphs or more, which is the "bottom (third)" the
 * client describes: a two-paragraph statement would otherwise have its whole
 * second half in italic.
 */
function ccg_statement_closer_html( $html ) {
	$html = (string) $html;

	if ( '' === trim( $html ) || false !== strpos( $html, 'statement-closer' ) ) {
		return $html;
	}

	$blocks = preg_split( '#(?:\s*<br\s*/?>\s*){2,}#i', $html );

	if ( count( $blocks ) < 3 ) {
		return $html;
	}

	$last = array_pop( $blocks );

	if ( '' === trim( $last ) || substr_count( $last, '<' ) !== substr_count( $last, '>' ) ) {
		return $html;
	}

	// Rebuild with the same separator the helper split on, so the rendered
	// spacing is unchanged.
	return implode( '<br><br>', $blocks ) . '<br><br><span class="statement-closer">' . $last . '</span>';
}

/**
 * B1 (v0.12): wrap a named opening phrase so it can carry the serif voice and
 * the glide-in, with the rest of the line following it.
 *
 * The phrase is matched only at the START of the passage and only outside
 * tags, so an editor who rewrites the heading simply gets the plain string
 * back rather than a span in the middle of a sentence. `cta-motion.js` reads
 * these two classes; with no JavaScript they are type roles and nothing else.
 */
function ccg_cta_phrase_html( $text, $phrase = 'Get in touch' ) {
	$text = trim( (string) $text );

	if ( '' === $text ) {
		return '';
	}

	$html = ccg_editor_html( $text );

	if ( 0 !== stripos( $html, $phrase ) ) {
		return $html;
	}

	$rest = substr( $html, strlen( $phrase ) );

	return '<span class="cta-phrase">' . substr( $html, 0, strlen( $phrase ) ) . '</span>'
		. ( '' === trim( $rest ) ? '' : '<span class="cta-rest">' . $rest . '</span>' );
}

/**
 * A1 (v0.12) sweeps card CTAs into 7.5's caps-and-arrow recipe. Where that CTA
 * is an editor's link inside a WYSIWYG field (the homepage services cards end
 * their description with one), it cannot be targeted by position without also
 * catching an ordinary in-copy link. So the link is marked by what it says:
 * only an anchor whose whole label is "Read more" or "Find out more" gets the
 * class, and every other link in the field is left exactly as written.
 */
function ccg_more_link_html( $html ) {
	if ( ! is_string( $html ) || false === stripos( $html, '<a' ) ) {
		return $html;
	}

	return preg_replace_callback(
		'#<a\b([^>]*)>(\s*(?:read more|find out more)(?:\s+here)?\s*)</a>#i',
		function ( $match ) {
			$attributes = $match[1];

			if ( preg_match( '#\bclass=(["\'])(.*?)\1#i', $attributes, $class ) ) {
				$attributes = str_replace( $class[0], 'class=' . $class[1] . trim( $class[2] . ' more-link' ) . $class[1], $attributes );
			} else {
				$attributes .= ' class="more-link"';
			}

			return '<a' . $attributes . '>' . $match[2] . '</a>';
		},
		$html
	);
}

/**
 * T7: one About masthead beat, cast by what it holds rather than by field name.
 *
 * The slider's fields have been filled two ways. The July content puts each
 * statement in sub_heading and each ethos passage in body. The copy now on live,
 * which is what the T7 captures show, puts the statement in header with a
 * supporting paragraph in sub_heading, and each ethos lead sentence in
 * sub_heading with its continuation in the second-colour field. Keyed to field
 * names, live's copy came out with its statement as a 72px display word and its
 * support as the serif statement.
 *
 * - A header of four words or more is a statement. Anything shorter is a
 *   display word ("Connection").
 * - A statement is Libre Baskerville Regular, and the first beat's is the
 *   page's one H1. A sub_heading beneath a header statement is the Poppins
 *   Medium support; with no header, the sub_heading is the statement itself.
 * - Beneath a display word, sub_heading and its continuation are the ethos lead
 *   sentence, rendered exactly as body copy already is: serif lead, Poppins
 *   remainder.
 *
 * The July content renders exactly as it did (same elements, classes, text and
 * layout; only whitespace between tags differs), so this is safe whichever copy
 * an install holds.
 */
function ccg_about_beat_html( $beat, $is_first ) {
	$field  = function ( $name ) use ( $beat ) {
		return trim( (string) ( $beat[ $name ] ?? '' ) );
	};
	$tag    = $field( 'tag' );
	$header = $field( 'header' );
	$sub    = $field( 'sub_heading' );
	$second = $field( 'sub_heading_2nd_colour' );
	$body   = $field( 'body' );
	$rank   = $is_first ? 'h1' : 'h2';
	$html   = '';

	$header_words = count( preg_split( '/\s+/u', trim( strip_tags( $header ) ), -1, PREG_SPLIT_NO_EMPTY ) );
	$statement    = '' !== $header && $header_words >= 4;

	if ( '' !== $tag ) {
		$html .= '<p class="tag">' . $tag . '</p>';
	}

	if ( $statement ) {
		$html .= '<' . $rank . ' class="sub-heading">' . $header . '</' . $rank . '>';

		if ( '' !== $sub ) {
			$html .= '<p class="support">' . trim( $sub . ' ' . $second ) . '</p>';
		}
	} elseif ( '' !== $header ) {
		$html .= '<p class="heading">' . $header . '</p>';

		if ( '' !== $sub ) {
			$html .= '<h2 class="body"><span class="statement-lead">' . $sub . '</span>' . ( '' !== $second ? ' ' . $second : '' ) . '</h2>';
		}
	} elseif ( '' !== $sub && '' !== $second ) {
		$html .= '<' . $rank . ' class="sub-heading two-color">' . $sub . ' <span>' . $second . '</span></' . $rank . '>';
	} elseif ( '' !== $sub ) {
		$html .= '<' . $rank . ' class="sub-heading">' . $sub . '</' . $rank . '>';
	}

	if ( '' !== $body ) {
		$html .= '<p class="body">' . ccg_statement_lead_html( $body ) . '</p>';
	}

	return $html;
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
	//
	// A sentence can end its paragraph, so a line break counts as the gap after
	// it, not only a space. Live's Careers intro is exactly that: one sentence,
	// then a new paragraph. Needing a space sent it to the four-word fallback
	// below, so only "Join one of the" took the serif B2 asks for.
	$sentence = '/^((?:[^<.?!]|<[^>]*>)+?[.?!])((?:\s|<br\s*\/?>)+)(.*)$/su';

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
	// A5 (v0.12): this class is now the ONLY mechanism the light header runs
	// off, so the eight per-template copies of the logo-inversion rule are gone
	// and this list has to be exactly right. See `.has-light-header` in
	// `_header-logo.scss`.
	//
	// T11 asks for the class on Contact and Where We Work; Careers is light
	// too and had the CSS without the class. The site map, like the default
	// page template below, opens on white too, and without the class its logo
	// was white on white, with only the underscore showing.
	$light_templates = array(
		'page-templates/page-contact.php',
		'page-templates/page-where-we-work.php',
		'page-templates/page-careers.php',
		'page-templates/page-site-map.php',
	);

	// The webinar variant is the one insight that keeps the dark photographic
	// masthead, so it is not a light header. The gated report was the other,
	// until C6 moved it to the article grammar.
	if ( is_singular( 'insight' ) && has_term( 'webinars', 'type', get_the_ID() ) ) {
		return $classes;
	}

	// The default page template (the privacy and cookie policies) opens on
	// white. The front page also uses it, and its hero is dark.
	$default_page = is_page() && ! is_front_page() && '' === get_page_template_slug();

	if ( is_singular( array( 'post', 'insight', 'work' ) )
		|| is_tax( array( 'sector', 'service' ) )
		|| $default_page
		|| ( is_page() && in_array( get_page_template_slug(), $light_templates, true ) ) ) {
		$classes[] = 'has-light-header';
	}

	return $classes;
}

add_filter( 'body_class', 'ccg_light_header_body_class' );

/**
 * The first term name for a card eyebrow, or an empty string.
 *
 * Twenty-one call sites read `get_the_terms( …, $tax )[0]->name` straight. When
 * a post carries no term in that taxonomy get_the_terms() returns false, and
 * `false[0]->name` is two warnings and a null: "Trying to access array offset
 * on false", then "Attempt to read property on null". Not a fatal, so the page
 * still renders, which is exactly why it went unnoticed: the label simply comes
 * out blank and the log fills up. Turn WP_DEBUG_DISPLAY on and the warnings
 * print into the markup instead.
 *
 * Worth guarding before anyone tidies the sector taxonomy, because deleting a
 * term detaches it from every post it held in a single action, and this is what
 * those posts' eyebrows read.
 */
function ccg_term_eyebrow( $post_id, $taxonomy ) {
	$terms = get_the_terms( $post_id, $taxonomy );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$first = reset( $terms );

	return isset( $first->name ) ? $first->name : '';
}

/**
 * The eyebrow a card should carry, chosen by post type.
 *
 * The theme's own convention, written out longhand in
 * ccg_load_search_results_page(): an insight shows its type, a case study its
 * sector, a news item its source, and anything else the word Blog.
 *
 * The header's two "Recent News & Views" sliders query insight, post and work
 * together but read `type` for all three, and only an insight has one. So every
 * pinned case study and blog post in the search panel carried a blank label, on
 * every page of the site.
 */
function ccg_card_eyebrow( $post_id ) {
	switch ( get_post_type( $post_id ) ) {
		case 'insight':
			return ccg_term_eyebrow( $post_id, 'type' );

		case 'work':
			return ccg_term_eyebrow( $post_id, 'sector' );

		case 'news':
			return ccg_term_eyebrow( $post_id, 'source' );

		default:
			return 'Blog';
	}
}
