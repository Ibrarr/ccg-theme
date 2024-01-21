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
                <div class="col-lg-8 intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
            </div>
        </div>
    </section>

    <section class="team">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <div class="row">
				<?php
				if ( have_rows( 'senior_team' ) ):
					while ( have_rows( 'senior_team' ) ) : the_row();
						$name       = get_sub_field( 'name' );
						$name_words = explode( ' ', $name );
						?>
                        <div class="col-lg-3 col-6 person-container">
                            <div class="person">
                                <div class="headshot">
                                    <img src="<?php the_sub_field( 'headshot' ); ?>"
                                         alt="<?php the_sub_field( 'name' ); ?>">
                                </div>
                                <div class="details-closed">
                                    <div class="info">
                                        <p class="name"><?php the_sub_field( 'name' ); ?></p>
                                        <p class="job-title"><?php the_sub_field( 'job_title' ); ?></p>
                                    </div>
                                    <div class="plus-icon"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/team-plus.svg' ) ?></div>
                                </div>

                                <div class="details-open">
                                    <div class="top-section">
                                        <p class="name"><?php the_sub_field( 'name' ); ?></p>
                                        <p class="job-title"><?php the_sub_field( 'job_title' ); ?></p>
                                        <!--                                        <a href="mailto:-->
										<?php //the_sub_field( 'email' ); ?><!--" class="email">Email-->
                                        <!--                                            - -->
										<?php //echo $name_words[0]; ?><!--</a>-->
                                        <?php if (get_sub_field( 'x_twitter' ) || get_sub_field( 'linkedin' )) { ?>
                                            <div class="social-icons">
                                                <?php if (get_sub_field( 'x_twitter' )) { ?>
                                                <a class="x-icon" rel="nofollow"
                                                   href="<?php the_sub_field( 'x_twitter' ); ?>"
                                                   target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/x-icon.svg' ) ?></a>
                                                <?php } ?>
                                                <?php if (get_sub_field( 'linkedin' )) { ?>
                                                <a class="linkedin-icon" rel="nofollow"
                                                   href="<?php the_sub_field( 'linkedin' ); ?>"
                                                   target="_blank"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/social-icons/linkedin-icon.svg' ) ?></a>
                                                <?php } ?>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <div class="bio"><?php the_sub_field( 'bio' ); ?></div>
                                    <div class="cross-icon"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/team-cross.svg' ) ?></div>
                                </div>
                            </div>
                        </div>
					<?php
					endwhile;
				endif;
				?>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>