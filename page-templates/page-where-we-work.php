<?php
/*
Template Name: Where we work
*/
get_header();
?>

    <section class="header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
            </div>
        </div>
    </section>

    <section class="where-we-work">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <div class="row gx-5">
                <div class="col-lg-4">
                    <div class="content">
						<?php the_field( 'content' ); ?>
                    </div>

                    <div class="row gx-5 locations-logo d-lg-flex d-none">
                        <div class="col-lg-6 col-md-3 col-6">
                            <img src="<?php the_field( 'global_com_logo' ); ?>" alt="">
                        </div>
                        <div class="col-lg-6 col-md-3 col-6 d-lg-flex justify-content-lg-end">
                            <div class="locations">
                                <h4>Locations</h4>
								<?php
								if ( have_rows( 'locations' ) ):
									echo '<ul>';
									while ( have_rows( 'locations' ) ) : the_row();
										echo '<li class="' . get_sub_field( 'marker_colour' ) . '">' . get_sub_field( 'location' ) . '</li>';
									endwhile;
									echo '</ul>';
								endif;
								?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8 map">
                    <img src="<?php the_field( 'map' ); ?>" alt="">
                </div>

                <div class="row gx-5 locations-logo d-lg-none">
                    <div class="col-md-3 col-6">
                        <img src="<?php the_field( 'global_com_logo' ); ?>" alt="">
                    </div>
                    <div class="col-md-3 col-6 d-lg-flex justify-content-lg-end">
                        <div class="locations">
                            <h4>Locations</h4>
							<?php
							if ( have_rows( 'locations' ) ):
								echo '<ul>';
								while ( have_rows( 'locations' ) ) : the_row();
									echo '<li class="' . get_sub_field( 'marker_colour' ) . '">' . get_sub_field( 'location' ) . '</li>';
								endwhile;
								echo '</ul>';
							endif;
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-form">
        <div class="container px-4">
            <h3>Get in touch</h3>
            <div class="row">
                <div class="col-lg-8 contact-gform-form">
					<?php echo do_shortcode( '[gravityform id="' . get_field( 'select_gform_form' ) . '" title="false" description="false" ajax="true"]' ); ?>
                </div>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>