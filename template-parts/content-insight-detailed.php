<?php
/**
 * The template for displaying the detailed Insight report
 *
 */

$post_type = 'insight';
$taxonomy  = 'type';
$terms     = get_the_terms( get_the_ID(), $taxonomy );
$term_name = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';

$kicker       = ccg_insight_detailed_kicker();
$sections     = ccg_insight_detailed_sections();
$contents     = ccg_insight_detailed_contents();
$author       = ccg_insight_detailed_author();
$faqs         = get_field( 'faqs' );
$takeaways    = get_field( 'takeaways' );
$publish_date = get_field( 'publish_date' );
$data_vintage = get_field( 'data_vintage' );

$cover    = get_field( 'cover_image' );
$cover_id = $cover ? $cover['ID'] : get_post_thumbnail_id( get_the_ID() );
$pdf      = get_field( 'pdf_file' );
$gform    = get_field( 'select_gform_form' );
?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="container px-4">

            <header class="report-hero">
                <nav class="report-breadcrumb" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
                    <span aria-hidden="true">/</span>
                    <a href="<?php echo esc_url( get_post_type_archive_link( $post_type ) ); ?>">Insight Hub</a>
                    <span aria-hidden="true">/</span>
                    <span aria-current="page"><?php the_title(); ?></span>
                </nav>

				<?php if ( $kicker ) { ?>
                    <p class="report-kicker"><?php echo esc_html( $kicker ); ?></p>
				<?php } ?>

                <h1 class="report-title"><?php the_title(); ?></h1>

				<?php if ( get_field( 'standfirst' ) ) { ?>
                    <p class="report-standfirst"><?php echo esc_html( get_field( 'standfirst' ) ); ?></p>
				<?php } ?>

				<?php if ( $publish_date || $data_vintage ) { ?>
                    <p class="report-dates">
						<?php
						$dates = array();

						if ( $publish_date ) {
							$dates[] = 'Published ' . date_i18n( 'M Y', strtotime( $publish_date ) );
						}

						if ( $data_vintage ) {
							$dates[] = esc_html( $data_vintage );
						}

						echo implode( '  ·  ', $dates );
						?>
                    </p>
				<?php } ?>
            </header>

			<?php if ( $takeaways ) { ?>
                <aside class="report-takeaways" aria-labelledby="key-takeaways">
                    <p class="report-takeaways-label" id="key-takeaways">Key Takeaways</p>
                    <ul>
						<?php foreach ( $takeaways as $row ) { ?>
							<?php if ( ! empty( $row['takeaway'] ) ) { ?>
                                <li><?php echo esc_html( $row['takeaway'] ); ?></li>
							<?php } ?>
						<?php } ?>
                    </ul>
                </aside>
			<?php } ?>

			<?php if ( $contents ) { ?>
                <nav class="report-contents-accordion" aria-label="On this page">
                    <button type="button" class="report-contents-toggle" aria-expanded="false"
                            aria-controls="report-contents-accordion-list">
                        <span>On this page: <?php echo count( $contents ); ?> sections</span>
                        <i aria-hidden="true"></i>
                    </button>
                    <div class="report-contents-panel" id="report-contents-accordion-list" hidden>
                        <ul>
							<?php foreach ( $contents as $item ) { ?>
                                <li>
                                    <a href="#<?php echo esc_attr( $item['anchor'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                                </li>
							<?php } ?>
                        </ul>
                    </div>
                </nav>
			<?php } ?>

            <div class="report-columns">

                <div class="report-main">

					<?php foreach ( $sections as $section ) { ?>
						<?php
						// The section titled Methodology renders in the inverted panel
						// rather than as ordinary body copy. Its own heading carries the
						// label, the anchor and the contents-menu entry.
						$is_methodology = ccg_insight_detailed_is_methodology( $section['heading'] );
						?>
                        <section class="<?php echo $is_methodology ? 'report-methodology' : 'report-section'; ?>">
                            <h2 id="<?php echo esc_attr( $section['anchor'] ); ?>"<?php echo $is_methodology ? ' class="report-methodology-label"' : ''; ?>><?php echo esc_html( $section['heading'] ); ?></h2>

							<?php
							foreach ( $section['components'] as $component ) {
								// Tables inside the panel need the dark treatment. Set per
								// component because the partial reads it from this scope.
								$variant = $is_methodology ? 'on-dark' : '';

								switch ( $component['acf_fc_layout'] ) {
									case 'rich_text':
										include( 'insight-detailed/rich-text.php' );
										break;
									case 'image_figure':
										include( 'insight-detailed/image-figure.php' );
										break;
									case 'data_table':
										include( 'insight-detailed/data-table.php' );
										break;
									case 'key_stat_callout':
										include( 'insight-detailed/key-stats.php' );
										break;
									case 'pull_quote':
										include( 'insight-detailed/pull-quote.php' );
										break;
								}
							}
							?>
                        </section>
					<?php } ?>

					<?php if ( $faqs ) { ?>
                        <section class="report-faq">
                            <h2 id="frequently-asked-questions">Frequently asked questions</h2>
                            <div class="report-faq-list">
								<?php foreach ( $faqs as $index => $faq ) { ?>
									<?php
									if ( empty( $faq['question'] ) ) {
										continue;
									}

									$is_open = 0 === $index;
									$faq_id  = 'report-faq-' . ( $index + 1 );
									?>
                                    <div class="report-faq-item<?php echo $is_open ? ' is-open' : ''; ?>">
                                        <h3>
                                            <button type="button" class="report-faq-question"
                                                    aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>"
                                                    aria-controls="<?php echo esc_attr( $faq_id ); ?>">
                                                <span><?php echo esc_html( $faq['question'] ); ?></span>
                                                <i aria-hidden="true"></i>
                                            </button>
                                        </h3>
                                        <div class="report-faq-answer"
                                             id="<?php echo esc_attr( $faq_id ); ?>"<?php echo $is_open ? '' : ' hidden'; ?>>
											<?php
											// ACF already runs wpautop on this field, so the value
											// arrives as HTML. Escaping it would print the tags.
											echo wp_kses_post( $faq['answer'] );
											?>
                                        </div>
                                    </div>
								<?php } ?>
                            </div>
                        </section>
					<?php } ?>

					<?php if ( $author['name'] ) { ?>
                        <div class="report-author">
							<?php if ( $author['headshot'] ) { ?>
                                <div class="report-author-avatar has-image">
									<?php echo wp_get_attachment_image( $author['headshot']['ID'], 'thumbnail', false, array( 'alt' => esc_attr( $author['name'] ) ) ); ?>
                                </div>
							<?php } else { ?>
                                <div class="report-author-avatar" aria-hidden="true">
                                    <span><?php echo esc_html( $author['initials'] ); ?></span>
                                </div>
							<?php } ?>
                            <div class="report-author-details">
                                <p class="report-author-name">Written by <?php echo esc_html( $author['name'] ); ?></p>
								<?php
								$credit = array_filter( array( $author['role'], $author['organisation'] ) );

								if ( $credit ) {
									echo '<p class="report-author-role">' . esc_html( implode( ', ', $credit ) ) . '</p>';
								}

								if ( $author['bio'] ) {
									echo '<p class="report-author-bio">' . esc_html( $author['bio'] ) . '</p>';
								}
								?>
                            </div>
                        </div>
					<?php } ?>

                </div>

                <aside class="report-side">

					<?php if ( $contents ) { ?>
                        <nav class="report-contents" aria-labelledby="report-contents-label">
                            <p class="report-contents-label" id="report-contents-label">On This Page</p>
                            <ul>
								<?php foreach ( $contents as $item ) { ?>
                                    <li>
                                        <a href="#<?php echo esc_attr( $item['anchor'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                                    </li>
								<?php } ?>
                            </ul>
                        </nav>
					<?php } ?>

					<?php if ( $gform ) { ?>
                        <div class="report-download">
							<?php if ( $cover_id ) { ?>
                                <div class="report-download-cover">
									<?php echo wp_get_attachment_image( $cover_id, 'large', false, array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
                                </div>
							<?php } ?>
                            <p class="report-download-heading"><?php echo esc_html( get_field( 'download_heading' ) ?: 'Download the Full Report' ); ?></p>
							<?php if ( get_field( 'download_body' ) ) { ?>
                                <p class="report-download-body"><?php echo esc_html( get_field( 'download_body' ) ); ?></p>
							<?php } ?>
                            <div class="report-download-form">
								<?php echo do_shortcode( '[gravityform id="' . esc_attr( $gform ) . '" title="false" description="false" ajax="true"]' ); ?>
                            </div>
                        </div>
					<?php } ?>

                    <div class="report-share">
                        <p class="report-share-label">Share</p>
                        <div class="social-icons">
                            <a class="mail-icon"
                               href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=Check out this insight from CCGroup <?php echo rawurlencode( get_permalink() ); ?>"
                               aria-label="Share by email"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/mail-icon.svg' ) ?></a>
                            <a class="linkedin-icon" rel="nofollow"
                               href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo rawurlencode( get_permalink() ); ?>&title=<?php echo rawurlencode( get_the_title() ); ?>"
                               aria-label="Share on LinkedIn"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ) ?></a>
                            <a class="x-icon" rel="nofollow"
                               href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>/&text='<?php echo rawurlencode( get_the_title() ); ?>'&via=<?php the_field( 'xtwitter_username', 'option' ); ?>"
                               aria-label="Share on X"
                               target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ) ?></a>
                        </div>
                    </div>

                </aside>

            </div>
        </div>
    </article>

<?php
/**
 * Related content: a couple of pinned Insights, then the most recent sharing
 * this post's type. Pinned entries are capped because every pinned Insight is
 * currently an Insight Report, so letting them take all four slots would show
 * the same list on every report and never anything topical.
 */
$related_limit  = 4;
$related_pinned = 2;

$pinned_posts = new WP_Query( array(
	'post_type'      => $post_type,
	'posts_per_page' => $related_pinned,
	'fields'         => 'ids',
	'post__not_in'   => array( get_the_ID() ),
	'meta_query'     => array(
		array(
			'key'     => 'pinned',
			'value'   => '1',
			'compare' => '='
		)
	)
) );

$related_ids = $pinned_posts->posts;

if ( count( $related_ids ) < $related_limit ) {
	$args = array(
		'post_type'      => $post_type,
		'posts_per_page' => $related_limit - count( $related_ids ),
		'fields'         => 'ids',
		'post__not_in'   => array_merge( array( get_the_ID() ), $related_ids ),
	);

	if ( $term_name ) {
		$args['tax_query'] = array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $terms[0]->slug,
				'include_children' => false
			),
		);
	}

	$related_ids = array_merge( $related_ids, ( new WP_Query( $args ) )->posts );
}

if ( $related_ids ) {
	$related_posts = new WP_Query( array(
		'post_type'      => $post_type,
		'post__in'       => $related_ids,
		'orderby'        => 'post__in',
		'posts_per_page' => $related_limit,
	) );

	if ( $related_posts->have_posts() ) {
		echo '<section class="related-content">';
		echo '<div class="container px-4">';
		echo '<h3>Related content</h3>';
		echo '<div class="row mb-3">';

		while ( $related_posts->have_posts() ) {
			$related_posts->the_post();
			require( 'article-card.php' );
		}

		echo '</div>';
		echo '<div class="row"><a class="global-button" href="/insight-hub' . ( $term_name ? '?types=' . sanitize_title( $term_name ) : '' ) . '">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}

	wp_reset_postdata();
}

include( 'bottom-cta.php' );
?>
