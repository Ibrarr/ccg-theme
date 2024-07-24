<?php
/**
 * The template for displaying service terms
 *
 */
$current_term   = get_queried_object();
$term_name_text = single_term_title( '', false );
?>

    <section class="term-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-8 title-intro">
                    <p class="term">Services</p>
					<?php
					if ( $current_term->parent ) {
						$parent_term      = get_term( $current_term->parent );
						$parent_term_name = $parent_term->name;
						echo '<h2 class="parent-term">' . $parent_term_name . '</h2>';
						echo '<h1 class="child-term">' . $term_name_text . '</h1>';
					} else {
						echo '<h1 class="title">' . $term_name_text . '</h1>';
					}
					?>
                    <div class="intro"><h2><?php echo strip_tags( term_description(), '<a>' ); ?></h2></div>
                </div>
                <div class="col-lg-4 col-md-3 col-6 d-flex align-items-center justify-content-end services-links">
                    <div class="service-dropdown-container">
                        <div class="service-dropdown">Our Services</div>
                        <div class="service-dropdown-menu-container">
                            <div class="service-dropdown-menu">
								<?php
								$parent_terms = get_terms( array(
									'taxonomy'   => 'service',
									'hide_empty' => false,
									'parent'     => 0,
								) );

								foreach ( $parent_terms as $parent_term ) {
									echo '<span class="service-header">' . esc_html( $parent_term->name ) . '</span>';

									$child_terms = get_term_children( $parent_term->term_id, 'service' );

									if ( ! empty( $child_terms ) ) {
										foreach ( $child_terms as $child_term_id ) {
											$child_term = get_term( $child_term_id, 'service' );
											echo '<a class="service-dropdown-item" href="' . get_term_link( $child_term ) . '">' . esc_html( $child_term->name ) . '</a>';
										}
									}
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="content-staff-member">
<!--        <div class="parallax-bars"-->
<!--             id="bar-two">--><?php //echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?><!--</div>-->
        <div class="container px-4 position-relative">
            <div class="row">
				<?php
				if ( have_rows( 'content', $current_term ) ):
					while ( have_rows( 'content', $current_term ) ) : the_row();
						?>
                        <div class="col-lg-6 content">
							<?php if ( get_sub_field( 'header' ) ) { ?>
                                <h3><?php the_sub_field( 'header' ); ?></h3>
							<?php } ?>
							<?php the_sub_field( 'body' ); ?>
                        </div>
					<?php
					endwhile;
				endif;
				?>
				<?php if ( ! get_field( 'disable_staff_member', $current_term ) ) { ?>
				<?php if ( get_field( 'headshot', $current_term ) ) { ?>
                <div class="col-md-6">
                    <div class="staff-member">
                        <div class="row">
                            <div class="col-lg-6 col-6 pe-0">
                                <div class="content">
                                    <p class="name"><?php the_field( 'name', $current_term ); ?></p>
                                    <p class="job-title"><?php the_field( 'job_title', $current_term ); ?></p>
                                    <div class="social-icons">
                                        <a class="x-icon" rel="nofollow"
                                           href="<?php the_field( 'x_twitter', $current_term ); ?>"
                                           target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ) ?></a>
                                        <a class="linkedin-icon" rel="nofollow"
                                           href="<?php the_field( 'linkedin', $current_term ); ?>"
                                           target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ) ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-5 col-6 p-0 headshot-container">
                                <div class="headshot">
                                    <img src="<?php the_field( 'headshot', $current_term ); ?>"
                                         alt="<?php the_field( 'name', $current_term ); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php } ?>
			<?php } ?>
<!--            <div class="row">-->
<!--                <div class="col-md-3 col-6 contact-container">-->
<!--                    <a href="/contact-us" class="global-button">Get in touch</a>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </section>

    <?php if (get_field('enable_services', $current_term)) { ?>
        <section class="services-slider">
            <div class="container px-4">
                <div class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <li class="splide__slide">
                                <div class="first-slide">
                                    <div class="down-arrow"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/down-arrow.svg' ) ?></div>
                                    <h3 class="heading">Our <?php echo $term_name_text ?> services</h3>
                                    <p class="description"><?php the_field( 'services_description', $current_term ); ?></p>
                                </div>
                            </li>
                            <?php
                            if ( have_rows( 'services', $current_term ) ):
                                $total_count = count( get_field( 'services', $current_term ) );
                                $current_count = 1;
                                while ( have_rows( 'services', $current_term ) ) : the_row();
                                    ?>
                                    <li class="splide__slide">
                                        <div class="service-slide">
                                            <h3 class="heading"><?php the_sub_field( 'title' ); ?></h3>
                                            <p class="description"><?php the_sub_field( 'description' ); ?></p>
                                            <span class="count"><?php echo $current_count; ?> — <?php echo $total_count ?></span>
                                        </div>
                                    </li>
                                    <?php
                                    $current_count++;
                                endwhile;
                            endif;
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
    <?php } ?>

    <section class="why-ccg" style="background-image: url('<?php echo ( CCG_TEMPLATE_URI . '/assets/images/backgrounds/ccg-background.jpg' ) ?>');">
        <div class="container px-4 position-relative">
            <div class="row content">
                <div class="col-12"><h1><?php the_field( 'why_choose_ccgroup_1st_title', 'option' ) ?></h1></div>
                <div class="col-md-6">
                    <h2><?php the_field( 'why_choose_ccgroup_2nd_title', 'option' ) ?></h2>
                    <h3><?php the_field( 'why_choose_ccgroup_3rd_title', 'option' ) ?></h3>
                </div>
                <div class="col-md-6">
                    <p><?php the_field( 'why_choose_ccgroup_description', 'option' ) ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php if (get_field('enable_quotes', $current_term)) { ?>
        <section class="quotes-slider">
            <div class="container px-4">
                <div class="splide">
                    <div class="splide__track">
                        <ul class="splide__list">
                            <?php
                            if ( have_rows( 'quotes', $current_term ) ):
                                $total_count = count( get_field( 'quotes', $current_term ) );
                                $current_count = 1;
                                while ( have_rows( 'quotes', $current_term ) ) : the_row();
                                    $link = get_sub_field( 'link');
                                    ?>
                                    <li class="splide__slide">
                                        <div class="service-slide">
                                            <p class="quote">“<?php the_sub_field( 'quote' ); ?>”</p>
                                            <p class="author">
                                                <?php the_sub_field( 'author_name' ); ?>,
                                                <?php the_sub_field( 'author_job_title' ); ?> -
                                                <strong><?php the_sub_field( 'author_company' ); ?></strong>
                                            </p>
                                            <?php if ($link) { ?>
                                                <a class="link" href="<?php echo $link['url']; ?>"
                                                   target="<?php echo $link['target']; ?>"><?php echo $link['title']; ?></a>
                                            <?php } ?>
                                            <span class="count"><?php echo $current_count; ?> — <?php echo $total_count ?></span>
                                        </div>
                                    </li>
                                    <?php
                                    $current_count++;
                                endwhile;
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <?php if (get_field('enable_case_studies', $current_term)) { ?>
        <section class="related-content">
            <div class="container px-4">
                <h3><?php echo $term_name_text ?> case studies</h3>
                <div class="row mb-3">
                        <?php
                        if ( have_rows( 'case_studies', $current_term ) ):
                            while ( have_rows( 'case_studies', $current_term ) ) : the_row();
                                $case_study = get_sub_field('case_study');
                                $post = $case_study;
                                $intro = get_sub_field('intro');
                                $term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
                                require( 'article-card-longer.php' );
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                </div>
            </div>
        </section>
    <?php } ?>

    <?php if (get_field('enable_awards', $current_term)) { ?>
        <section class="awards">
            <div class="container px-4">
                <div class="container px-4">
                    <h3>Awards</h3>
                    <div class="row mb-3">
                        <?php
                        if ( have_rows( 'awards', $current_term ) ):
                            while ( have_rows( 'awards', $current_term ) ) : the_row();
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
    <?php } ?>

    <?php if (get_field('enable_service_explained', $current_term)) { ?>
        <section class="explained">
            <div class="container px-4">
                <div class="container px-4">
                    <h3><?php echo $term_name_text ?> explained</h3>
                    <div class="row mb-3">
                        <?php
                        if ( have_rows( 'explained', $current_term ) ):
                            while ( have_rows( 'explained', $current_term ) ) : the_row();
                                ?>
                                <?php
                            endwhile;
                        endif;
                        ?>
                    </div>
                </div>
        </section>
    <?php } ?>

    <?php if (get_field('enable_challenges_opportunities', $current_term)) { ?>
        <section class="challenges-opportunities">
            <div class="container px-4">
                <div class="container px-4">
                    <h3>Challenges & opportunities</h3>
                    <div class="row mb-3">
                        <div class="col-lg-4 col-12">
                            <p class="large"><?php the_field('challenges_opportunities_large_text', $current_term) ?></p>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <p class="body"><?php the_field('challenges_opportunities_body_text', $current_term) ?></p>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <img src="<?php the_field('challenges_opportunities_image', $current_term) ?>" alt="">
                        </div>
                    </div>
                </div>
        </section>
    <?php } ?>

    <?php if (get_field('enable_thought_leadership', $current_term)) { ?>
        <section class="thought-leadership">
            <div class="container px-4">
                <h3>Thought leadership</h3>
                <div class="row mb-3">
                    <?php
                    $thought_leadership_posts = get_field('thought_leadership_posts', $current_term);
                    if ($thought_leadership_posts) {
                        foreach ($thought_leadership_posts as $post) {
                            setup_postdata($post);
                            $terms = get_the_terms( get_the_ID(), 'sector' );
                            $term_name = $terms ? $terms[0]->name : 'Blog';
                            require( 'article-card.php' );
                        }
                        wp_reset_postdata();
                    }
                    ?>
                </div>
                <div class="row"><a class="global-button" href="/our-work">See More</a></div>
            </div>
        </section>
    <?php } ?>

    <section class="other-services">
        <div class="container px-4">
            <div class="container px-4">
                <h3>Other services</h3>
                <div class="row mb-3">
                    <div class="col-lg-4 col-md-6">
                        <h3 class="service-header">Analytics</h3>
                        <?php
                        $analytic_terms = get_terms( array(
                            'taxonomy'   => 'service',
                            'hide_empty' => false,
                            'parent'     => 0,
                        ) );

                        foreach ( $analytic_terms as $analytic_term ) {
                            if ( $analytic_term->name === 'Analytics' ) {
                                $child_terms = get_term_children( $analytic_term->term_id, 'service' );

                                if ( ! empty( $child_terms ) ) {
                                    foreach ( $child_terms as $child_term_id ) {
                                        $child_term = get_term( $child_term_id, 'service' );
                                        ?>
                                        <div class="service-item">
                                            <div class="service-name">
                                                <p><a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h3 class="service-header">Strategy</h3>
                        <?php
                        $strategy_terms = get_terms( array(
                            'taxonomy'   => 'service',
                            'hide_empty' => false,
                            'parent'     => 0,
                        ) );

                        foreach ( $strategy_terms as $strategy_term ) {
                            if ( $strategy_term->name === 'Strategy' ) {
                                $child_terms = get_term_children( $strategy_term->term_id, 'service' );

                                if ( ! empty( $child_terms ) ) {
                                    foreach ( $child_terms as $child_term_id ) {
                                        $child_term = get_term( $child_term_id, 'service' );
                                        ?>
                                        <div class="service-item">
                                            <div class="service-name">
                                                <p><a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <h3 class="service-header">Activation</h3>
                        <?php
                        $activation_terms = get_terms( array(
                            'taxonomy'   => 'service',
                            'hide_empty' => false,
                            'parent'     => 0,
                        ) );

                        foreach ( $activation_terms as $activation_term ) {
                            if ( $activation_term->name === 'Activation' ) {
                                $child_terms = get_term_children( $activation_term->term_id, 'service' );

                                if ( ! empty( $child_terms ) ) {
                                    foreach ( $child_terms as $child_term_id ) {
                                        $child_term = get_term( $child_term_id, 'service' );
                                        ?>
                                        <div class="service-item">
                                            <div class="service-name">
                                                <p><a href="<?php echo get_term_link( $child_term ); ?>"><?php echo $child_term->name ?></a></p>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
    </section>

<?php
if ( ! get_field( 'disable_bottom_cta', $current_term ) ) {
	if ( get_field( 'custom_bottom_cta', $current_term ) ) {
		$custom_link = get_field( 'custom_bottom_cta_button', $current_term );
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
                                <h3><?php the_field( 'custom_bottom_cta_title', $current_term ); ?></h3>
								<?php
								if ( get_field( 'custom_bottom_cta_description', $current_term ) ) {
									echo '<p>' . get_field( 'custom_bottom_cta_description', $current_term ) . '</p>';
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