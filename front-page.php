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

    <section class="latest-content">
        <div class="container px-4">
            <h3>Insight reports</h3>
            <div class="row mb-3">
				<?php
				$args = array(
					'post_type' => array('insight', 'posts'),
					'posts_per_page' => 4,
					'orderby' => 'date',
					'order' => 'DESC'
				);

				$latest_posts = new WP_Query($args);

                while ($latest_posts->have_posts()) {
                    $latest_posts->the_post();
                    $terms = get_the_terms(get_the_ID(), 'sector');
                    $term_name = $terms ? $terms[0]->name : 'Blog';
                    require('template-parts/article-card.php');
                }
                wp_reset_postdata();
				?>
            </div>
            <div class="row">
                <a class="global-button" href="/insight-hub">See More</a>
            </div>
        </div>
    </section>

    <section class="about-ccg">
        <div class="container px-4">
            <?php the_field( 'about_ccg' ); ?>
        </div>
    </section>

    <section class="services-slider">
	    <?php
	    if ( have_rows( 'first_service_cards' ) ):
		    $total_count = count( get_field( 'first_service_cards' ) );
		    $current_count = 1;
		    while ( have_rows( 'first_service_cards' ) ) : the_row();
			    ?>
                <li class="splide__slide first-set">
                    <div class="sector-slide">
                        <h3 class="heading"><?php the_sub_field( 'title' ); ?></h3>
					    <?php the_sub_field( 'description' ); ?>
                        <span class="count"><?php the_field( 'first_service_name' ); ?>, <?php echo $current_count; ?> — <?php echo $total_count ?></span>
                    </div>
                </li>
			    <?php
			    $current_count++;
		    endwhile;
	    endif;
	    if ( have_rows( 'second_service_cards' ) ):
		    $total_count = count( get_field( 'second_service_cards' ) );
		    $current_count = 1;
		    while ( have_rows( 'second_service_cards' ) ) : the_row();
			    ?>
                <li class="splide__slide second-set">
                    <div class="sector-slide">
                        <h3 class="heading"><?php the_sub_field( 'title' ); ?></h3>
					    <?php the_sub_field( 'description' ); ?>
                        <span class="count"><?php the_field( 'second_service_name' ); ?>, <?php echo $current_count; ?> — <?php echo $total_count ?></span>
                    </div>
                </li>
			    <?php
			    $current_count++;
		    endwhile;
	    endif;
	    if ( have_rows( 'third_service_cards' ) ):
		    $total_count = count( get_field( 'third_service_cards' ) );
		    $current_count = 1;
		    while ( have_rows( 'third_service_cards' ) ) : the_row();
			    ?>
                <li class="splide__slide third-set">
                    <div class="sector-slide">
                        <h3 class="heading"><?php the_sub_field( 'title' ); ?></h3>
					    <?php the_sub_field( 'description' ); ?>
                        <span class="count"><?php the_field( 'third_service_name' ); ?>, <?php echo $current_count; ?> — <?php echo $total_count ?></span>
                    </div>
                </li>
			    <?php
			    $current_count++;
		    endwhile;
	    endif;
	    ?>
        <div class="container px-4">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="first-slide">
                                <div class="down-arrow"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/down-arrow.svg' ) ?></div>
                                <p class="service-head">Our Services:</p>
                                <div class="accordion-container">
                                    <div class="service-item first">
                                        <div class="service-name">
                                            <h4><?php the_field( 'first_service_name' ); ?></a></h4>
                                            <div class="open-close-accordion">
                                                <div class="plus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?></div>
                                                <div class="minus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/minus.svg' ) ?></div>
                                            </div>
                                        </div>
                                        <div class="service-description">
                                            <?php the_field( 'first_service_description' ); ?>
                                        </div>
                                    </div>
                                    <div class="service-item second">
                                        <div class="service-name">
                                            <h4><?php the_field( 'second_service_name' ); ?></a></h4>
                                            <div class="open-close-accordion">
                                                <div class="plus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?></div>
                                                <div class="minus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/minus.svg' ) ?></div>
                                            </div>
                                        </div>
                                        <div class="service-description">
                                            <?php the_field( 'second_service_description' ); ?>
                                        </div>
                                    </div>
                                    <div class="service-item third">
                                        <div class="service-name">
                                            <h4><?php the_field( 'third_service_name' ); ?></a></h4>
                                            <div class="open-close-accordion">
                                                <div class="plus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/plus.svg' ) ?></div>
                                                <div class="minus"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/minus.svg' ) ?></div>
                                            </div>
                                        </div>
                                        <div class="service-description">
                                            <?php the_field( 'third_service_description' ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
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

    <section class="case-studies">
        <div class="container px-4">
            <h3>Case studies</h3>
            <div class="row mb-3">
				<?php
				$args = array(
					'post_type' => array('work'),
					'posts_per_page' => 4,
					'orderby' => 'date',
					'order' => 'DESC'
				);

				$latest_posts = new WP_Query($args);

				while ($latest_posts->have_posts()) {
					$latest_posts->the_post();
					$terms = get_the_terms(get_the_ID(), 'sector');
					$term_name = $terms ? $terms[0]->name : 'Blog';
					require('template-parts/article-card.php');
				}
				wp_reset_postdata();
				?>
            </div>
            <div class="row">
                <a class="global-button" href="/our-work">See More</a>
            </div>
        </div>
    </section>

    <section class="awards">
        <div class="container px-4">
            <h3>Awards</h3>
            <div class="row mb-3">
				<?php
				if ( have_rows( 'awards' ) ):
					while ( have_rows( 'awards' ) ) : the_row();
						?>
                        <div class="col-lg-4 col-6 mb-4 award">
                            <img class="image" src="<?php the_sub_field( 'image' ); ?>" alt="">
                            <p class="years"><?php the_sub_field( 'years' ); ?></p>
                            <p class="name"><?php the_sub_field( 'name' ); ?></p>
                        </div>
					<?php
					endwhile;
				endif;
				?>
            </div>
            <div class="row"><a class="global-button" href="/contact-us">Get in touch</a></div>
        </div>
    </section>


<?php get_footer(); ?>