<?php

/**
 * Detailed Insight report template.
 *
 * Helpers, content mirroring for Yoast, schema and REST provision for the
 * long-form report template selected per post from the Template dropdown.
 */

define( 'CCG_INSIGHT_DETAILED_TEMPLATE', 'page-templates/insight-detailed.php' );

/**
 * Is this post using the detailed Insight template?
 *
 * @param int|null $post_id
 * @return bool
 */
function ccg_is_detailed_insight( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();

	if ( ! $post_id || 'insight' !== get_post_type( $post_id ) ) {
		return false;
	}

	return CCG_INSIGHT_DETAILED_TEMPLATE === get_page_template_slug( $post_id );
}

/**
 * Build a stable, human readable anchor from a section heading.
 *
 * Duplicate headings get a numeric suffix so the contents menu and the
 * headings themselves can never disagree.
 *
 * @param string $heading
 * @param array  $used Anchors already issued, passed by reference.
 * @return string
 */
function ccg_insight_detailed_anchor( $heading, &$used ) {
	$anchor = sanitize_title( $heading );

	if ( ! $anchor ) {
		$anchor = 'section';
	}

	$base  = $anchor;
	$count = 2;

	while ( in_array( $anchor, $used, true ) ) {
		$anchor = $base . '-' . $count;
		$count ++;
	}

	$used[] = $anchor;

	return $anchor;
}

/**
 * Anchors the template hardcodes elsewhere in the page.
 *
 * A body section titled "Methodology" or "Key takeaways" would otherwise
 * produce a second element with the same id. The takeaways case is the worse
 * of the two: its panel sits earlier in the document than the article, so
 * getElementById resolves to the panel and the contents menu scrolls to the
 * wrong place with no error.
 *
 * Reserved unconditionally, not only when the matching field is filled. If the
 * list moved with the content, filling an empty methodology field would
 * silently renumber a section anchor and break inbound links.
 *
 * @return array
 */
function ccg_insight_detailed_reserved_anchors() {
	// "methodology" is deliberately absent. That section is authored as an
	// ordinary body section now, so it owns the anchor itself and reserving it
	// would push the real section to methodology-2.
	return array( 'key-takeaways', 'frequently-asked-questions' );
}

/**
 * Is this the section that renders inside the inverted methodology panel?
 *
 * Matched on the heading because that is what the approved spec keys off: the
 * section titled "Methodology" is the methodology block, and its own H2 gives
 * the panel its label, its anchor and its single contents-menu entry.
 *
 * @param string $heading
 * @return bool
 */
function ccg_insight_detailed_is_methodology( $heading ) {
	return 'methodology' === sanitize_title( $heading );
}

/**
 * Normalised body sections with anchors resolved once.
 *
 * @param int|null $post_id
 * @return array
 */
function ccg_insight_detailed_sections( $post_id = null ) {
	$post_id  = $post_id ?: get_the_ID();
	$rows     = get_field( 'body_sections', $post_id );
	$sections = array();
	$used     = ccg_insight_detailed_reserved_anchors();

	if ( ! $rows ) {
		return $sections;
	}

	foreach ( $rows as $row ) {
		$heading = isset( $row['heading'] ) ? trim( $row['heading'] ) : '';

		if ( ! $heading ) {
			continue;
		}

		$sections[] = array(
			'heading'    => $heading,
			'anchor'     => ccg_insight_detailed_anchor( $heading, $used ),
			'components' => isset( $row['components'] ) && is_array( $row['components'] ) ? $row['components'] : array(),
		);
	}

	return $sections;
}

/**
 * Contents menu entries, including the fixed trailing sections.
 *
 * @param int|null $post_id
 * @return array
 */
function ccg_insight_detailed_contents( $post_id = null ) {
	$post_id  = $post_id ?: get_the_ID();
	$contents = array();

	foreach ( ccg_insight_detailed_sections( $post_id ) as $section ) {
		$contents[] = array(
			'label'  => $section['heading'],
			'anchor' => $section['anchor'],
		);
	}

	// No hardcoded Methodology entry. That section supplies its own, from its
	// heading, exactly like every other section.
	if ( get_field( 'faqs', $post_id ) ) {
		$contents[] = array(
			'label'  => 'Frequently asked questions',
			'anchor' => 'frequently-asked-questions',
		);
	}

	return $contents;
}

/**
 * Table cells for a row, trimmed to the number of columns defined.
 *
 * @param array $row
 * @param int   $columns
 * @return array
 */
function ccg_insight_detailed_table_cells( $row, $columns ) {
	$cells = array();

	for ( $i = 1; $i <= $columns; $i ++ ) {
		$key     = 'cell_' . $i;
		$cells[] = isset( $row[ $key ] ) ? $row[ $key ] : '';
	}

	return $cells;
}

/**
 * Author details for display, falling back to the WordPress post author.
 *
 * @param int|null $post_id
 * @return array
 */
function ccg_insight_detailed_author( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$name    = get_field( 'author_name', $post_id );

	if ( ! $name ) {
		$name = get_the_author_meta( 'display_name', get_post_field( 'post_author', $post_id ) );
	}

	return array(
		'name'         => $name,
		'role'         => get_field( 'author_role', $post_id ),
		'organisation' => get_field( 'author_organisation', $post_id ),
		'bio'          => get_field( 'author_bio', $post_id ),
		'headshot'     => get_field( 'author_headshot', $post_id ),
		'initials'     => ccg_insight_detailed_initials( $name ),
	);
}

/**
 * Up to two initials from a name.
 *
 * @param string $name
 * @return string
 */
function ccg_insight_detailed_initials( $name ) {
	$initials = '';

	foreach ( preg_split( '/\s+/', trim( (string) $name ) ) as $part ) {
		if ( '' === $part ) {
			continue;
		}

		$initials .= mb_strtoupper( mb_substr( $part, 0, 1 ) );

		if ( 2 === mb_strlen( $initials ) ) {
			break;
		}
	}

	return $initials;
}

/**
 * The kicker line above the title: sector, report type and year.
 *
 * @param int|null $post_id
 * @return string
 */
function ccg_insight_detailed_kicker( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();
	$parts   = array();
	$sector  = get_field( 'kicker_sector', $post_id );

	if ( ! $sector ) {
		$sectors = get_the_terms( $post_id, 'sector' );

		if ( $sectors && ! is_wp_error( $sectors ) ) {
			// Several sector names are stored with encoded ampersands, which
			// would otherwise be double escaped on output.
			$sector = html_entity_decode( $sectors[0]->name, ENT_QUOTES, 'UTF-8' );
		}
	}

	if ( $sector ) {
		$parts[] = $sector;
	}

	foreach ( array( 'report_type', 'report_year' ) as $field ) {
		$value = get_field( $field, $post_id );

		if ( $value ) {
			$parts[] = $value;
		}
	}

	return implode( '  ·  ', $parts );
}

/**
 * Render the report body as plain HTML.
 *
 * Mirrored into post_content on save so Yoast analyses the real copy and
 * reports an accurate wordCount. The ACF fields remain the source of truth;
 * post_content is derived and never edited by hand.
 *
 * @param int $post_id
 * @return string
 */
function ccg_insight_detailed_render_body( $post_id ) {
	$out = array();

	$standfirst = get_field( 'standfirst', $post_id );

	if ( $standfirst ) {
		$out[] = '<p>' . esc_html( $standfirst ) . '</p>';
	}

	$takeaways = get_field( 'takeaways', $post_id );

	if ( $takeaways ) {
		$items = array();

		foreach ( $takeaways as $row ) {
			if ( ! empty( $row['takeaway'] ) ) {
				$items[] = '<li>' . esc_html( $row['takeaway'] ) . '</li>';
			}
		}

		if ( $items ) {
			$out[] = '<h2>Key takeaways</h2>';
			$out[] = '<ul>' . implode( '', $items ) . '</ul>';
		}
	}

	foreach ( ccg_insight_detailed_sections( $post_id ) as $section ) {
		$out[] = '<h2>' . esc_html( $section['heading'] ) . '</h2>';

		foreach ( $section['components'] as $component ) {
			switch ( $component['acf_fc_layout'] ) {
				case 'rich_text':
					if ( ! empty( $component['text'] ) ) {
						$out[] = wp_kses_post( $component['text'] );
					}
					break;

				case 'image_figure':
					$caption = array();

					if ( ! empty( $component['caption'] ) ) {
						$caption[] = esc_html( $component['caption'] );
					}

					if ( ! empty( $component['source'] ) ) {
						$caption[] = esc_html( $component['source'] );
					}

					if ( $caption ) {
						$out[] = '<p>' . implode( ' ', $caption ) . '</p>';
					}
					break;

				case 'data_table':
					$out[] = ccg_insight_detailed_render_table( $component );
					break;

				case 'key_stat_callout':
					if ( ! empty( $component['stats'] ) ) {
						$stats = array();

						foreach ( $component['stats'] as $stat ) {
							$stats[] = esc_html( trim( $stat['value'] . ' ' . $stat['label'] ) );
						}

						$out[] = '<p>' . implode( '. ', $stats ) . '.</p>';
					}
					break;

				case 'pull_quote':
					if ( ! empty( $component['quote'] ) ) {
						$quote = '<blockquote><p>' . esc_html( $component['quote'] ) . '</p>';

						if ( ! empty( $component['attribution'] ) ) {
							$quote .= '<cite>' . esc_html( $component['attribution'] ) . '</cite>';
						}

						$out[] = $quote . '</blockquote>';
					}
					break;
			}
		}
	}

	$faqs = get_field( 'faqs', $post_id );

	if ( $faqs ) {
		$out[] = '<h2>Frequently asked questions</h2>';

		foreach ( $faqs as $faq ) {
			if ( empty( $faq['question'] ) ) {
				continue;
			}

			$out[] = '<h3>' . esc_html( $faq['question'] ) . '</h3>';
			// ACF applies wpautop to this field, so it is already HTML.
			$out[] = wp_kses_post( $faq['answer'] );
		}
	}

	return trim( implode( "\n\n", array_filter( $out ) ) );
}

/**
 * Render a data table component as semantic HTML.
 *
 * @param array $component
 * @return string
 */
function ccg_insight_detailed_render_table( $component ) {
	$headings = ! empty( $component['headings'] ) ? $component['headings'] : array();
	$rows     = ! empty( $component['rows'] ) ? $component['rows'] : array();
	$columns  = count( $headings );

	if ( ! $columns || ! $rows ) {
		return '';
	}

	$html = '<table>';

	if ( ! empty( $component['table_caption'] ) ) {
		$html .= '<caption>' . esc_html( $component['table_caption'] ) . '</caption>';
	}

	$html .= '<thead><tr>';

	foreach ( $headings as $heading ) {
		$html .= '<th scope="col">' . esc_html( $heading['heading'] ) . '</th>';
	}

	$html .= '</tr></thead><tbody>';

	foreach ( $rows as $row ) {
		$cells = ccg_insight_detailed_table_cells( $row, $columns );

		if ( ! strlen( trim( implode( '', $cells ) ) ) ) {
			continue;
		}

		$html .= '<tr>';

		foreach ( $cells as $index => $cell ) {
			$html .= 0 === $index
				? '<th scope="row">' . esc_html( $cell ) . '</th>'
				: '<td>' . esc_html( $cell ) . '</td>';
		}

		$html .= '</tr>';
	}

	return $html . '</tbody></table>';
}

/**
 * Mirror the rendered body into post_content so Yoast can analyse it.
 *
 * The Insight post type has no editor, so post_content is otherwise empty and
 * Yoast reports a wordCount of 7 with no meta analysis.
 */
add_action( 'acf/save_post', 'ccg_insight_detailed_sync_content', 30 );
function ccg_insight_detailed_sync_content( $post_id ) {
	if ( $post_id instanceof WP_Post ) {
		$post_id = $post_id->ID;
	}

	if ( ! is_numeric( $post_id ) || wp_is_post_revision( $post_id ) ) {
		return;
	}

	if ( ! ccg_is_detailed_insight( $post_id ) ) {
		return;
	}

	$content = ccg_insight_detailed_render_body( $post_id );

	if ( $content === get_post_field( 'post_content', $post_id ) ) {
		return;
	}

	remove_action( 'acf/save_post', 'ccg_insight_detailed_sync_content', 30 );

	wp_update_post( array(
		'ID'           => $post_id,
		'post_content' => $content,
	) );

	add_action( 'acf/save_post', 'ccg_insight_detailed_sync_content', 30 );
}

/**
 * Run the same mirror after a REST write.
 *
 * ACF saves its fields through the REST controller rather than acf/save_post,
 * so without this the reports pushed programmatically would ship with an empty
 * post_content and a zero wordCount. Fires late enough that meta and ACF values
 * are already stored.
 */
add_action( 'rest_after_insert_insight', 'ccg_insight_detailed_sync_content', 30 );

/**
 * Fall back to the standfirst when no meta description has been written.
 *
 * The brief calls out that Insights currently ship with no meta description at
 * all. An editable Yoast field alone does not fix that, because an empty field
 * still produces no tag. This guarantees every detailed report has one while
 * leaving a hand-written Yoast description to win.
 */
add_filter( 'wpseo_metadesc', 'ccg_insight_detailed_metadesc_fallback' );
function ccg_insight_detailed_metadesc_fallback( $description ) {
	if ( $description || ! is_singular( 'insight' ) || ! ccg_is_detailed_insight() ) {
		return $description;
	}

	$standfirst = wp_strip_all_tags( (string) get_field( 'standfirst' ) );

	if ( ! $standfirst ) {
		return $description;
	}

	if ( mb_strlen( $standfirst ) > 155 ) {
		$standfirst = rtrim( mb_substr( $standfirst, 0, 152 ), " ,.;:" ) . '...';
	}

	return $standfirst;
}

/**
 * Add Article and FAQPage pieces to the Yoast schema graph.
 *
 * Done in code rather than through Yoast's content type settings so the output
 * is deterministic and travels with the theme.
 */
add_filter( 'wpseo_schema_graph', 'ccg_insight_detailed_schema_graph', 20, 2 );
function ccg_insight_detailed_schema_graph( $graph, $context ) {
	if ( ! is_singular( 'insight' ) || ! ccg_is_detailed_insight() ) {
		return $graph;
	}

	$post_id = get_the_ID();

	// Every @id has to be built from the same base Yoast used for its own
	// nodes, or the references dangle. get_permalink() is not that base: it
	// applies is_ssl() while Yoast builds ids from the stored indexable, so on
	// a mixed scheme install the two disagree.
	$canonical = ( isset( $context->canonical ) && $context->canonical ) ? $context->canonical : get_permalink( $post_id );

	// Yoast's WebPage @id is the bare permalink. It carried a #webpage fragment
	// in older versions, which is where this template's original value came
	// from, and that node no longer exists.
	$webpage = ( isset( $context->main_schema_id ) && $context->main_schema_id ) ? $context->main_schema_id : $canonical;

	$article_types = array( 'Article', 'NewsArticle', 'Report', 'ScholarlyArticle', 'TechArticle', 'BlogPosting' );
	$has_article   = false;

	foreach ( $graph as $piece ) {
		foreach ( (array) ( isset( $piece['@type'] ) ? $piece['@type'] : array() ) as $type ) {
			if ( in_array( $type, $article_types, true ) ) {
				$has_article = true;
				break 2;
			}
		}
	}

	$article = array(
		'@type'            => 'Article',
		'@id'              => $canonical . '#article',
		'isPartOf'         => array( '@id' => $webpage ),
		'mainEntityOfPage' => array( '@id' => $webpage ),
		'headline'         => get_the_title( $post_id ),
		'datePublished'    => get_the_date( DATE_W3C, $post_id ),
		'dateModified'     => get_the_modified_date( DATE_W3C, $post_id ),
		'inLanguage'       => get_bloginfo( 'language' ),
	);

	// Yoast only emits an Organization node when the site is set to represent a
	// company and has both a name and a logo. Claiming a publisher regardless
	// leaves a reference to a node that was never output, so follow Yoast's own
	// Article generator and only claim one when it actually exists. The
	// reference is taken rather than rebuilt because a site representing a
	// person uses a hashed id, not #organization.
	if ( isset( $context->site_represents_reference ) && $context->site_represents_reference ) {
		$article['publisher'] = $context->site_represents_reference;
	}

	$description = get_field( 'standfirst', $post_id );

	if ( $description ) {
		$article['description'] = ccg_insight_detailed_plain_text( $description );
	}

	$author = ccg_insight_detailed_author( $post_id );

	if ( $author['name'] ) {
		$article['author'] = array(
			'@type' => 'Person',
			'name'  => $author['name'],
		);

		if ( $author['organisation'] ) {
			$article['author']['affiliation'] = array(
				'@type' => 'Organization',
				'name'  => $author['organisation'],
			);
		}
	}

	$thumbnail_id = get_post_thumbnail_id( $post_id );

	if ( $thumbnail_id ) {
		$article['image'] = array( '@id' => $canonical . '#primaryimage' );
	}

	$body = ccg_insight_detailed_plain_text( get_post_field( 'post_content', $post_id ) );

	if ( $body ) {
		$article['wordCount']   = ccg_insight_detailed_word_count( $body );
		$article['articleBody'] = $body;
	}

	// Only skipped when Yoast or a plugin already published an Article. The FAQ
	// piece is independent of that, so it is added either way.
	if ( ! $has_article ) {
		$graph[] = $article;
	}

	$faq = ccg_insight_detailed_faq_schema( $post_id, $webpage, $canonical );

	if ( $faq ) {
		$graph[] = $faq;
	}

	return $graph;
}

/**
 * Flatten rendered HTML to the plain text the schema should carry.
 *
 * wp_strip_all_tags() on its own removes the tags without putting anything in
 * their place, so adjacent table cells run together as "Increase69%" and the
 * word count reads them as one word. Block level boundaries become spaces
 * first, then entities are decoded so the output is not littered with &#039;.
 *
 * @param string $html
 * @return string
 */
function ccg_insight_detailed_plain_text( $html ) {
	if ( ! $html ) {
		return '';
	}

	$text = preg_replace( '#<(td|th|tr|p|div|li|h[1-6]|caption|table|thead|tbody|br)\b[^>]*>#i', ' ', $html );
	$text = preg_replace( '#</(td|th|tr|p|div|li|h[1-6]|caption|table|thead|tbody)>#i', ' ', $text );
	$text = wp_strip_all_tags( $text );
	$text = html_entity_decode( $text, ENT_QUOTES | ENT_HTML5, 'UTF-8' );

	return trim( preg_replace( '/\s+/u', ' ', $text ) );
}

/**
 * Word count that survives non-ASCII copy.
 *
 * str_word_count() is byte based and treats accented characters as word
 * breaks, which understates any report quoting a non-English name or market.
 *
 * @param string $text
 * @return int
 */
function ccg_insight_detailed_word_count( $text ) {
	$words = preg_split( '/\s+/u', trim( $text ), -1, PREG_SPLIT_NO_EMPTY );

	return is_array( $words ) ? count( $words ) : 0;
}

/**
 * FAQPage piece built from the FAQ repeater.
 *
 * @param int         $post_id
 * @param string      $webpage   The Yoast WebPage node @id.
 * @param string|null $canonical Base for this piece's own ids. Passed in rather
 *                               than rebuilt so it cannot drift from the
 *                               Article's ids on a mixed scheme install.
 * @return array|false
 */
function ccg_insight_detailed_faq_schema( $post_id, $webpage, $canonical = null ) {
	$canonical = $canonical ?: get_permalink( $post_id );
	$faqs      = get_field( 'faqs', $post_id );

	if ( ! $faqs ) {
		return false;
	}

	$questions = array();

	foreach ( $faqs as $index => $faq ) {
		if ( empty( $faq['question'] ) || empty( $faq['answer'] ) ) {
			continue;
		}

		$questions[] = array(
			'@type'          => 'Question',
			'@id'            => $canonical . '#faq-' . ( $index + 1 ),
			'position'       => $index + 1,
			'name'           => ccg_insight_detailed_plain_text( $faq['question'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => ccg_insight_detailed_plain_text( $faq['answer'] ),
			),
		);
	}

	if ( ! $questions ) {
		return false;
	}

	return array(
		'@type'      => 'FAQPage',
		'@id'        => $canonical . '#faqpage',
		'isPartOf'   => array( '@id' => $webpage ),
		'inLanguage' => get_bloginfo( 'language' ),
		'mainEntity' => $questions,
	);
}

/**
 * Expose the Yoast title and meta description over REST.
 *
 * Needed so the back catalogue can be pushed programmatically with its meta
 * intact. Yoast stores both as protected meta, so they are surfaced as
 * explicit REST fields rather than registered meta.
 */
add_action( 'rest_api_init', 'ccg_insight_detailed_register_rest_fields' );
function ccg_insight_detailed_register_rest_fields() {
	$fields = array(
		'yoast_meta_title'       => '_yoast_wpseo_title',
		'yoast_meta_description' => '_yoast_wpseo_metadesc',
	);

	foreach ( $fields as $rest_key => $meta_key ) {
		register_rest_field( 'insight', $rest_key, array(
			'get_callback'    => function ( $post ) use ( $meta_key ) {
				return (string) get_post_meta( $post['id'], $meta_key, true );
			},
			'update_callback' => function ( $value, $post ) use ( $meta_key ) {
				if ( ! current_user_can( 'edit_post', $post->ID ) ) {
					return new WP_Error( 'ccg_rest_cannot_edit', 'Sorry, you are not allowed to edit this post.', array( 'status' => 403 ) );
				}

				return update_post_meta( $post->ID, $meta_key, sanitize_text_field( $value ) );
			},
			'schema'          => array(
				'type'        => 'string',
				'description' => 'Yoast ' . str_replace( '_', ' ', $rest_key ),
				'context'     => array( 'view', 'edit' ),
			),
		) );
	}
}

/**
 * Stop ACF demanding required fields that do not apply on a REST write.
 *
 * ACF builds its REST schema from every field group that could apply to the post
 * type. On a create there is no post yet, so it cannot narrow by the location
 * rules that actually decide which group is in play, and it never evaluates
 * conditional logic. On an Insight that leaves a write having to satisfy:
 *
 * - custom_bottom_cta_title and custom_bottom_cta_button, which only ever appear
 *   once the Custom Bottom CTA toggle is on
 * - intro, body, heading and select_gform_form from the three older Insight
 *   groups, none of which apply to the detailed template
 * - type and sector, which a partial update has no reason to resend
 *
 * The effect is that no Insight can be created or patched over REST at all. This
 * relaxes required-ness for REST writes on this post type only. The editor keeps
 * its own validation, so an author still cannot save a report without a
 * standfirst.
 */
add_filter( 'acf/rest/get_fields', 'ccg_insight_rest_relax_required', 10, 3 );
function ccg_insight_rest_relax_required( $fields, $resource, $http_method ) {
	if ( 'insight' !== ( $resource['sub_type'] ?? '' ) ) {
		return $fields;
	}

	if ( in_array( strtoupper( (string) $http_method ), array( 'GET', 'HEAD', 'DELETE' ), true ) ) {
		return $fields;
	}

	return ccg_insight_rest_clear_required( $fields );
}

/**
 * Walk a field list clearing required, including repeater sub-fields and the
 * sub-fields inside each Flexible Content layout, because the schema is built
 * from those nested arrays rather than from a flat list.
 *
 * A repeater's min and max are cleared for the same reason. ACF turns them into
 * minItems and maxItems in the REST schema, so the editor's authoring guidance
 * becomes a hard validation rule over the API: three takeaways is a sensible
 * house style, but a migration pushing a report that genuinely has two should
 * not get a 400. The caps still apply in the editor.
 */
function ccg_insight_rest_clear_required( $fields ) {
	foreach ( $fields as $i => $field ) {
		$fields[ $i ]['required'] = 0;

		if ( isset( $field['min'] ) ) {
			$fields[ $i ]['min'] = '';
		}

		if ( isset( $field['max'] ) ) {
			$fields[ $i ]['max'] = '';
		}

		if ( ! empty( $field['sub_fields'] ) ) {
			$fields[ $i ]['sub_fields'] = ccg_insight_rest_clear_required( $field['sub_fields'] );
		}

		if ( ! empty( $field['layouts'] ) ) {
			foreach ( $field['layouts'] as $key => $layout ) {
				if ( ! empty( $layout['sub_fields'] ) ) {
					$fields[ $i ]['layouts'][ $key ]['sub_fields'] = ccg_insight_rest_clear_required( $layout['sub_fields'] );
				}
			}
		}
	}

	return $fields;
}
