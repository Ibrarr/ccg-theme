<?php
/**
 * The template for displaying work posts
 *
 */
$post_type     = 'work';
$taxonomy      = 'sector';
$terms         = get_the_terms( get_the_ID(), $taxonomy );
$term_name     = $terms[0]->name;
$quote_enabled = get_field( 'enable_quote' );

$thumbnail_id = get_post_thumbnail_id( get_the_ID() );
$image_srcset = wp_get_attachment_image_srcset( $thumbnail_id );
?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <section class="post-header">
                <div class="row">
                    <div class="col-lg-8 title-intro">
                        <p class="term"><?php echo $term_name; ?></p>
                        <h1 class="title"><?php the_title(); ?></h1>
                        <div class="intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
                    </div>
                    <div class="col-lg-4 d-flex align-items-center justify-content-lg-end justify-content-md-start justify-content-end sectors-links">
                        <div class="work-dropdown-container">
                            <div class="work-dropdown">Our Sectors</div>
                            <div class="work-dropdown-menu-container">
                                <div class="work-dropdown-menu">
									<?php
									$terms = get_terms( array(
										'taxonomy'   => 'sector',
										'hide_empty' => true,
									) );

									foreach ( $terms as $term ) {
										if ( get_term_meta( $term->term_id, 'include_on_frontend', true ) ) {
											echo '<a class="work-dropdown-item" href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
										}
									}

									if ( have_rows( 'dropdown_links' ) ):
										while ( have_rows( 'dropdown_links' ) ) : the_row();
											$link = get_sub_field( 'link' );
											echo '<a class="work-dropdown-item" href="' . esc_url( $link['url'] ) . '" target="' . esc_attr( $link['target'] ) . '">' . esc_html( $link['title'] ) . '</a>';
										endwhile;
									endif;
									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="post-content">
                <div class="row gx-5">
                    <div class="col-lg-7 content">
                        <h3>Challenge</h3>
						<?php the_field( 'challenge' ); ?>
                        <h3>Solution</h3>
						<?php the_field( 'solution' ); ?>
                    </div>
					<?php if ( get_field( 'image_text' ) ) { ?>
                        <div class="col-lg-5 image image-text">
                            <div class="row">
                                <div class="col-lg-12 col-md-6">
                                    <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title(); ?>"
                                         srcset="<?php echo esc_attr( $image_srcset ); ?>"
                                         sizes="(min-width: 391px) 1024px, 100vw">
                                </div>
                                <div class="col-lg-12 col-md-6">
                                    <p><?php the_field( 'image_text' ); ?></p>
                                </div>
                            </div>
                        </div>
					<?php } else { ?>
                        <div class="col-lg-5 image">
                            <img src="<?php the_post_thumbnail_url() ?>" alt="<?php the_title(); ?>"
                                 srcset="<?php echo esc_attr( $image_srcset ); ?>"
                                 sizes="(min-width: 391px) 1024px, 100vw">
                        </div>
					<?php } ?>
                </div>
            </section>
        </div>
    </article>

    <section class="impact">
        <div class="container px-4">
            <h3>Impact</h3>
            <div class="row gx-lg-4">
				<?php
				if ( $quote_enabled ) {
					?>
                    <div class="col-lg-8 col-md-12">
						<?php
						if ( have_rows( 'statistics_first_row_with_quote' ) ):
							echo '<div class="row">';
							while ( have_rows( 'statistics_first_row_with_quote' ) ): the_row();
								$number_or_plus     = get_sub_field( 'number_or_plus' );
								$number_of_columns  = get_sub_field( 'number_of_columns' );
								$number_stat        = get_sub_field( 'number_stat' );
								$number_description = get_sub_field( 'number_description' );
								$plus_description   = get_sub_field( 'plus_description' );
								if ( $number_or_plus === 'number' ) {
									?>
                                    <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> number">
                                        <p class="title"><?php echo $number_stat; ?></p>
                                        <p class="description"><?php echo $number_description; ?></p>
                                    </div>
								<?php } elseif ( $number_or_plus === 'plus' ) { ?>
                                    <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> plus">
                                        <p class="title">+</p>
                                        <p class="description"><?php echo $plus_description; ?></p>
                                    </div>
									<?php
								}
							endwhile;
							echo '</div>';
						endif;
						if ( have_rows( 'statistics_second_row_with_quote' ) ):
							echo '<div class="row">';
							while ( have_rows( 'statistics_second_row_with_quote' ) ): the_row();
								$number_or_plus     = get_sub_field( 'number_or_plus' );
								$number_of_columns  = get_sub_field( 'number_of_columns' );
								$number_stat        = get_sub_field( 'number_stat' );
								$number_description = get_sub_field( 'number_description' );
								$plus_description   = get_sub_field( 'plus_description' );
								if ( $number_or_plus === 'number' ) {
									?>
                                    <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> number">
                                        <p class="title"><?php echo $number_stat; ?></p>
                                        <p class="description"><?php echo $number_description; ?></p>
                                    </div>
								<?php } elseif ( $number_or_plus === 'plus' ) { ?>
                                    <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> plus">
                                        <p class="title">+</p>
                                        <p class="description"><?php echo $plus_description; ?></p>
                                    </div>
									<?php
								}
							endwhile;
							echo '</div>';
						endif;
						?>
                    </div>
                    <div class="col-lg-4 col-md-6 quote">
                        <p class="quote-text"><?php the_field( 'quote' ); ?></p>
                        <p class="quote-name-company">
							<?php
							if ( get_field( 'quote_name' ) ) {
								echo '<span>' . get_field( 'quote_name' ) . '</span>';
							}
							if ( get_field( 'quote_name' ) && get_field( 'quote_job_title' ) ) {
								echo ' — ';
							}
							if ( get_field( 'quote_job_title' ) ) {
								the_field( 'quote_job_title' );
							} ?>
                        </p>
                    </div>
					<?php
				} else {
					if ( have_rows( 'statistics_first_row_with_quote' ) ):
						echo '<div class="row">';
						while ( have_rows( 'statistics_first_row_with_quote' ) ): the_row();
							$number_or_plus     = get_sub_field( 'number_or_plus' );
							$number_of_columns  = get_sub_field( 'number_of_columns' );
							$number_stat        = get_sub_field( 'number_stat' );
							$number_description = get_sub_field( 'number_description' );
							$plus_description   = get_sub_field( 'plus_description' );

							if ( $number_of_columns == 3 ) {
								$number_of_columns = $number_of_columns - 1;
							} else if ( $number_of_columns == 6 ) {
								$number_of_columns = $number_of_columns - 2;
							}

							if ( $number_or_plus === 'number' ) {
								?>
                                <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> number">
                                    <p class="title"><?php echo $number_stat; ?></p>
                                    <p class="description"><?php echo $number_description; ?></p>
                                </div>
							<?php } elseif ( $number_or_plus === 'plus' ) { ?>
                                <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> plus">
                                    <p class="title">+</p>
                                    <p class="description"><?php echo $plus_description; ?></p>
                                </div>
								<?php
							}
						endwhile;
						echo '</div>';
					endif;
					if ( have_rows( 'statistics_second_row_with_quote' ) ):
						echo '<div class="row">';
						while ( have_rows( 'statistics_second_row_with_quote' ) ): the_row();
							$number_or_plus     = get_sub_field( 'number_or_plus' );
							$number_of_columns  = get_sub_field( 'number_of_columns' );
							$number_stat        = get_sub_field( 'number_stat' );
							$number_description = get_sub_field( 'number_description' );
							$plus_description   = get_sub_field( 'plus_description' );

							if ( $number_of_columns == 3 ) {
								$number_of_columns = $number_of_columns - 1;
							} else if ( $number_of_columns == 6 ) {
								$number_of_columns = $number_of_columns - 2;
							}

							if ( $number_or_plus === 'number' ) {
								?>
                                <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> number">
                                    <p class="title"><?php echo $number_stat; ?></p>
                                    <p class="description"><?php echo $number_description; ?></p>
                                </div>
							<?php } elseif ( $number_or_plus === 'plus' ) { ?>
                                <div class="col-md-<?php echo $number_of_columns; ?> col-<?php echo $number_of_columns * 2; ?> plus">
                                    <p class="title">+</p>
                                    <p class="description"><?php echo $plus_description; ?></p>
                                </div>
								<?php
							}
						endwhile;
						echo '</div>';
					endif;
				}
				?>
            </div>
        </div>
    </section>

<?php
$pinned_posts = new WP_Query( array(
	'post_type'      => $post_type,
	'posts_per_page' => 4,
	'meta_query'     => array(
		array(
			'key'     => 'pinned',
			'value'   => '1',
			'compare' => '='
		)
	)
) );

$remaining_posts_to_fetch = 4;

if ( $pinned_posts->have_posts() ) {
	$remaining_posts_to_fetch -= $pinned_posts->post_count;
}

if ( $remaining_posts_to_fetch > 0 ) {
	$exclude_post_ids = wp_list_pluck( $pinned_posts->posts, 'ID' );
	$args             = array(
		'post_type'      => $post_type,
		'posts_per_page' => $remaining_posts_to_fetch,
		'post__not_in'   => array_merge( array( get_the_ID() ), $exclude_post_ids ),
		'tax_query'      => array(
			array(
				'taxonomy'         => $taxonomy,
				'field'            => 'slug',
				'terms'            => $term_name,
				'include_children' => false
			),
		),
	);

	$query = new WP_Query( $args );

	if ( $pinned_posts->have_posts() || $query->have_posts() ) {
		echo '<section class="related-content">';
		echo '<div class="parallax-bars" id="bar-three">' . file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) . '</div>';
		echo '<div class="container px-4">';
		echo '<h3>Related content</h3>';
		echo '<div class="row mb-3">';

		while ( $pinned_posts->have_posts() ) {
			$pinned_posts->the_post();
			require( 'article-card.php' );
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			require( 'article-card.php' );
		}

		echo '</div>';
		echo '<div class="row"><a class="global-button" href="/our-work?sectors=' . sanitize_title( $term_name ) . '">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}
	wp_reset_postdata();
}

include( 'bottom-cta.php' );
?>