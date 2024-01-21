<section class="archive-header">
    <div class="parallax-bars"
         id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/green.svg' ) ?></div>
    <div class="container px-4">
        <div class="row">
            <h1 class="title"><?php the_field( 'insight_hub_heading', 'option' ); ?></h1>
            <div class="col-lg-8 col-md-10 intro">
                <h2><?php the_field( 'insight_hub_description', 'option' ); ?></h2>
            </div>
        </div>
    </div>
</section>

<section class="featured-slider">
    <div class="parallax-bars"
         id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
    <div class="parallax-bars"
         id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
    <div class="container px-4">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
							<?php
							$featured_posts = new WP_Query( array(
								'post_type'      => 'insight',
								'posts_per_page' => 6,
								'meta_query'     => array(
									array(
										'key'     => 'pinned',
										'value'   => '1',
										'compare' => '='
									)
								)
							) );

							if ( $featured_posts->have_posts() ) {
								while ( $featured_posts->have_posts() ) {
									$featured_posts->the_post();
									$term = get_the_terms( get_the_ID(), 'type' )[0]->name;
									?>
                                    <li class="splide__slide">
                                        <div class="featured-post">
                                            <div class="row gx-0 h-100">
                                                <div class="col-md-6">
                                                    <div class="featured-image">
                                                        <img src="<?php the_post_thumbnail_url() ?>"
                                                             alt="<?php the_title(); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="featured-content">
                                                        <p class="term"><?php echo $term; ?></p>
                                                        <h3 class="title"><?php the_title(); ?></h3>
                                                        <p class="intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                                        <a class="global-button" href="<?php the_permalink(); ?>">Learn
                                                            more</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
									<?php
								}
							}

							$featured_post_count = $featured_posts->post_count;
							wp_reset_postdata();

							$recent_posts_to_fetch = 6 - $featured_post_count;

							if ( $recent_posts_to_fetch > 0 ) {
								$exclude_post_ids = wp_list_pluck( $featured_posts->posts, 'ID' );

								$recent_posts = new WP_Query( array(
									'post_type'      => 'insight',
									'posts_per_page' => $recent_posts_to_fetch,
									'post__not_in'   => $exclude_post_ids,
									'orderby'        => 'date',
									'order'          => 'DESC'
								) );

								if ( $recent_posts->have_posts() ) {
									while ( $recent_posts->have_posts() ) {
										$recent_posts->the_post();
										$term = get_the_terms( get_the_ID(), 'type' )[0]->name;
										?>
                                        <li class="splide__slide">
                                            <div class="featured-post">
                                                <div class="row gx-0 h-100">
                                                    <div class="col-md-6">
                                                        <div class="featured-image">
                                                            <img src="<?php the_post_thumbnail_url() ?>"
                                                                 alt="<?php the_title(); ?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="featured-content">
                                                            <p class="term"><?php echo $term; ?></p>
                                                            <h3 class="title"><?php the_title(); ?></h3>
                                                            <p class="intro"><?php echo strip_tags( get_field( 'intro' ) ); ?></p>
                                                            <a class="global-button" href="<?php the_permalink(); ?>">Learn
                                                                more</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
										<?php
									}
								}
								wp_reset_postdata();
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
        </div>
    </div>
</section>

<section class="archive-posts-container">
    <div class="container px-4">
        <div class="term-container">
            <div class="selected-terms"><!--Selected terms go here--></div>
            <div class="term-selector-container">
                <div class="term-selector">Type / Sector</div>
                <div class="term-selector-menu-container">
                    <div class="term-selector-menu">
						<?php
						$sectors = get_terms( array(
							'taxonomy'   => 'sector',
							'hide_empty' => false,
						) );

						$types = get_terms( array(
							'taxonomy'   => 'type',
							'hide_empty' => false,
						) );

						echo '<span class="term-header">Type</span>';
						foreach ( $types as $type ) {
							echo '<span class="term-selector-item type" data-value="' . esc_attr( $type->slug ) . '">' . esc_html( $type->name ) . '</span>';
						}

						echo '<span class="term-header">Sector</span>';
						foreach ( $sectors as $sector ) {
							if ( get_term_meta( $sector->term_id, 'include_on_frontend', true ) ) {
								echo '<span class="term-selector-item sector" data-value="' . esc_attr( $sector->slug ) . '">' . esc_html( $sector->name ) . '</span>';
							}
						}
						?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="posts-container">
            <!-- Posts will be loaded here -->
        </div>
        <div class="global-button" id="load-more-posts">See More</div>
        <div id="loading-indicator">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>
</section>

<?php
if ( ! get_field( 'insight_hub_disable_bottom_cta', 'option' ) ) {
	if ( get_field( 'insight_hub_custom_bottom_cta', 'option' ) ) {
		$custom_link = get_field( 'insight_hub_custom_bottom_cta_button', 'option' );
		?>
        <section class="bottom-cta">
            <div class="parallax-bars"
                 id="bottom-bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bottom-bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bottom-bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="container px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-md-10">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 second-row">
                                <h3><?php the_field( 'insight_hub_custom_bottom_cta_title', 'option' ); ?></h3>
								<?php
								if ( get_field( 'insight_hub_custom_bottom_cta_description', 'option' ) ) {
									echo '<p>' . get_field( 'insight_hub_custom_bottom_cta_description', 'option' ) . '</p>';
								}
								?>
                            </div>
                            <div class="col-lg-3 col-md-2 third-row">
                                <a class="global-button" href="<?php echo $custom_link['url']; ?>"
                                   target="<?php echo $custom_link['target']; ?>"><?php echo $custom_link['title']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<?php
	} else {
		$link = get_field( 'bottom_cta_button', 'option' );
		?>
        <section class="bottom-cta">
            <div class="parallax-bars"
                 id="bottom-bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bottom-bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bottom-bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="container px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-md-10">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 col-md-8 offset-md-2 second-row">
                                <h3><?php the_field( 'bottom_cta_title', 'option' ); ?></h3>
								<?php
								if ( get_field( 'bottom_cta_description', 'option' ) ) {
									echo '<p>' . get_field( 'bottom_cta_description', 'option' ) . '</p>';
								}
								?>
                            </div>
                            <div class="col-lg-3 col-md-2 third-row">
                                <a class="global-button" href="<?php echo $link['url']; ?>"
                                   target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
		<?php
	}
}
?>