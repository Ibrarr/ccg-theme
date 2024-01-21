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

    <section class="term-content">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
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
            <div class="row">
                <div class="col-md-3 col-6 contact-container">
                    <a href="/contact-us" class="global-button">Get in touch</a>
                </div>
            </div>
        </div>
    </section>

<?php
$pinned_posts = new WP_Query( array(
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
		'posts_per_page' => $remaining_posts_to_fetch,
		'post__not_in'   => array_merge( array( get_the_ID() ), $exclude_post_ids ),
		'tax_query'      => array(
			array(
				'taxonomy'         => 'service',
				'field'            => 'slug',
				'terms'            => $term_name_text,
				'include_children' => false
			),
		),
	);

	$query = new WP_Query( $args );

	if ( $pinned_posts->have_posts() || $query->have_posts() ) {
		echo '<section class="related-content">';
		echo '<div class="container px-4">';
		echo '<h3>' . $term_name_text . ' Insights & experience</h3>';
		echo '<div class="row mb-3">';

		while ( $pinned_posts->have_posts() ) {
			$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
			$pinned_posts->the_post();
			require( 'article-card.php' );
		}

		while ( $query->have_posts() ) {
			$term_name = get_the_terms( get_the_ID(), 'sector' )[0]->name;
			$query->the_post();
			require( 'article-card.php' );
		}

		echo '</div>';
		echo '<div class="row"><a class="global-button" href="/our-work?services=' . $current_term->slug . '">See More</a></div>';
		echo '</div>';
		echo '</section>';
	}
	wp_reset_postdata();
}

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