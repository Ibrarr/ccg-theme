<?php get_header(); ?>
    <style>
        .home.page .sectors-slider:before {
            background: url(<?php echo wp_get_attachment_image_src(get_field('background_image'), 'header-image')[0] ?>) center center/cover no-repeat;
        }
    </style>

    <section class="header-slider">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="splide">
            <div class="splide__track">
                <ul class="splide__list">
					<?php
					if ( have_rows( 'header_slider' ) ):
						while ( have_rows( 'header_slider' ) ) : the_row();
							?>
                            <li class="splide__slide">
                                <img src="<?php echo wp_get_attachment_image_src( get_sub_field( 'image' ), 'header-image' )[0] ?>">
                                <div class="container px-4 h-100 d-flex align-items-end">
                                    <div class="header-slide">
                                        <h1 class="heading"><?php the_sub_field( 'title' ); ?></h1>
                                        <p class="sub-heading"><?php the_sub_field( 'body' ); ?></p>
                                    </div>
                                </div>
                            </li>
						<?php
						endwhile;
					endif;
					?>
                </ul>
            </div>
        </div>
    </section>

    <section class="intro">
        <div class="container px-4">
            <h2><?php the_field( 'intro' ); ?></h2>
        </div>
    </section>

    <section class="recent-posts">
        <div class="container-fluid">
            <div class="row">
				<?php
				$pinned_posts = new WP_Query( array(
					'post_type'      => 'work',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => 'pinned',
							'value'   => '1',
							'compare' => '='
						)
					)
				) );

				if ( $pinned_posts->have_posts() ) {
					while ( $pinned_posts->have_posts() ) {
						$pinned_posts->the_post();
						$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
						?>
                        <div class="col-md-6 recent-post-container"
                             style="background: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'header-image' )[0]; ?>) center center / cover no-repeat;">
                            <div class="recent-post px-2">
                                <a href="<?php the_permalink(); ?>">
                                    <p class="post-term"><?php echo $term_name; ?></p>
                                    <p class="post-title"><?php the_title(); ?></p>
                                    <p class="post-intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                </a>
                            </div>
                        </div>
						<?php
					}
				}
				$pinned_post_count = $pinned_posts->post_count;
				wp_reset_postdata();

				$remaining_posts_to_fetch = 1 - $pinned_post_count;

				if ( $remaining_posts_to_fetch > 0 ) {
					$args = array(
						'post_type'      => 'work',
						'posts_per_page' => 1,
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
							?>
                            <div class="col-md-6 recent-post-container"
                                 style="background: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'header-image' )[0]; ?>) center center / cover no-repeat;">
                                <div class="recent-post px-2">
                                    <a href="<?php the_permalink(); ?>">
                                        <p class="post-term"><?php echo $term_name; ?></p>
                                        <p class="post-title"><?php the_title(); ?></p>
                                        <p class="post-intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                    </a>
                                </div>
                            </div>
							<?php
						}
					}
					wp_reset_postdata();
				}
				?>
				<?php
				$pinned_posts = new WP_Query( array(
					'post_type'      => 'insight',
					'posts_per_page' => 1,
					'meta_query'     => array(
						array(
							'key'     => 'pinned',
							'value'   => '1',
							'compare' => '='
						)
					)
				) );

				if ( $pinned_posts->have_posts() ) {
					while ( $pinned_posts->have_posts() ) {
						$pinned_posts->the_post();
						$term_name = get_the_terms( get_the_ID(), 'type' )[0]->name;
						?>
                        <div class="col-md-6 recent-post-container"
                             style="background: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'header-image' )[0]; ?>) center center / cover no-repeat;">
                            <div class="recent-post px-2">
                                <a href="<?php the_permalink(); ?>">
                                    <p class="post-term"><?php echo $term_name; ?></p>
                                    <p class="post-title"><?php the_title(); ?></p>
                                    <p class="post-intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                </a>
                            </div>
                        </div>
						<?php
					}
				}
				$pinned_post_count = $pinned_posts->post_count;
				wp_reset_postdata();

				$remaining_posts_to_fetch = 1 - $pinned_post_count;

				if ( $remaining_posts_to_fetch > 0 ) {
					$args = array(
						'post_type'      => 'insight',
						'posts_per_page' => 1,
					);

					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();
							$term_name = get_the_terms( get_the_ID(), 'type' )[0]->name;
							?>
                            <div class="col-md-6 recent-post-container"
                                 style="background: url(<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'header-image' )[0]; ?>) center center / cover no-repeat;">
                                <div class="recent-post px-2">
                                    <a href="<?php the_permalink(); ?>">
                                        <p class="post-term"><?php echo $term_name; ?></p>
                                        <p class="post-title"><?php the_title(); ?></p>
                                        <p class="post-intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                    </a>
                                </div>
                            </div>
							<?php
						}
					}
					wp_reset_postdata();
				}
				?>
            </div>
        </div>
    </section>

    <section class="sectors-slider">
        <div class="container px-4">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="first-slide">
                                <h3 class="heading"><?php the_field( 'sectors_header' ); ?></h3>
                                <p class="sub-heading"><?php the_field( 'sectors_sub_heading' ); ?></p>
                                <p class="global-button next-slide"><?php the_field( 'sectors_button_text' ); ?></p>
                            </div>
                        </li>
						<?php
						$sector_slugs = array();
						if ( have_rows( 'sectors_order' ) ) {
							while ( have_rows( 'sectors_order' ) ) : the_row();
								$sector_slugs[] = get_sub_field( 'sector_slug' );
							endwhile;
						}

						$terms = get_terms( array(
							'taxonomy'   => 'sector',
							'hide_empty' => false,
							'slug'       => $sector_slugs,
						) );

						foreach ( $sector_slugs as $slug ) {
							$term = get_term_by( 'slug', $slug, 'sector' );

							if ( $term && get_term_meta( $term->term_id, 'include_on_frontend', true ) ) {
								$image_id  = get_term_meta( $term->term_id, 'image', true );
								$image_url = wp_get_attachment_image_url( $image_id, 'header-image' );
								?>
                                <li class="splide__slide">
                                    <img src="<?php echo $image_url; ?>">
                                    <div class="sector-slide">
                                        <a href="<?php echo get_term_link( $term ); ?>">
                                            <h3 class="heading"><?php echo $term->name; ?></h3>
                                            <p class="sub-heading"><?php echo $term->description; ?></p>
                                        </a>
                                    </div>
                                </li>
								<?php
							}
						}
						?>
                    </ul>
                </div>
                <div class="splide__arrows splide__arrows--ltr">
                    <button
                            class="splide__arrow splide__arrow--prev"
                            type="button"
                            aria-label="Previous slide"
                            aria-controls="splide01-track"
                    >
						<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                    </button>
                    <button
                            class="splide__arrow splide__arrow--next"
                            type="button"
                            aria-label="Next slide"
                            aria-controls="splide01-track"
                    >
						<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                    </button>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>