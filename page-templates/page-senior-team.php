<?php
/*
Template Name: Senior Team
*/
get_header();
?>

    <section class="team-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/navy.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><p class="statement"><?php echo ccg_editor_html( get_field( 'intro' ) ); ?></p></div>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
			<?php
			/**
			 * B5 (v0.12): a list page, with each biog revealed in place.
			 *
			 * The grid of headshot tiles is gone. Each person is now a row that
			 * expands, the same concertina the careers vacancies use, so the
			 * two disclosures on the site behave identically and share both the
			 * accordion script and the keyboard promotion.
			 *
			 * `.person-container` keeps its class because the theme's own GSAP
			 * ScrollTrigger reveal batches on it, and that reveal predates the
			 * rebrand.
			 */
			if ( have_rows( 'senior_team' ) ):
				while ( have_rows( 'senior_team' ) ) : the_row();
					$headshot = get_sub_field( 'headshot' );
					$x        = get_sub_field( 'x_twitter' );
					$linkedin = get_sub_field( 'linkedin' );
					?>
                    <div class="person-container">
                        <div class="person">
                            <div class="person-summary">
								<?php if ( $headshot ) { ?>
                                    <div class="person-headshot">
                                        <img src="<?php echo esc_url( $headshot ); ?>"
                                             alt="<?php the_sub_field( 'name' ); ?>" loading="lazy">
                                    </div>
								<?php } ?>
                                <div class="person-identity">
                                    <h2 class="name"><?php the_sub_field( 'name' ); ?></h2>
                                    <p class="job-title"><?php the_sub_field( 'job_title' ); ?></p>
                                </div>
                            </div>

                            <div class="person-details">
                                <div class="bio"><?php the_sub_field( 'bio' ); ?></div>
								<?php if ( $x || $linkedin ) { ?>
                                    <div class="social-icons">
										<?php if ( $x ) { ?>
                                            <a class="x-icon" rel="nofollow" href="<?php echo esc_url( $x ); ?>"
                                               target="_blank"><span class="screen-reader-text"><?php the_sub_field( 'name' ); ?> on X</span><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ) ?></a>
										<?php } ?>
										<?php if ( $linkedin ) { ?>
                                            <a class="linkedin-icon" rel="nofollow"
                                               href="<?php echo esc_url( $linkedin ); ?>"
                                               target="_blank"><span class="screen-reader-text"><?php the_sub_field( 'name' ); ?> on LinkedIn</span><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ) ?></a>
										<?php } ?>
                                    </div>
								<?php } ?>
                            </div>
                        </div>
                    </div>
				<?php
				endwhile;
			endif;
			?>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>
