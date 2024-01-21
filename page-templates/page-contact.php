<?php
/*
Template Name: Contact Us
*/
get_header();
?>
    <section class="contact-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php echo strip_tags( get_field( 'intro' ), '<a>' ); ?></h2></div>
            </div>
        </div>
    </section>

    <section class="offices">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <div class="row gx-5">
                <div class="col-12">
                    <div class="acf-map" data-zoom="16">
                        <div class="marker"
                             data-lat="<?php echo esc_attr( get_field( 'uk_office_map' )['lat'] ); ?>"
                             data-lng="<?php echo esc_attr( get_field( 'uk_office_map' )['lng'] ); ?>">
                        </div>
                    </div>
                </div>
				<?php
				if ( have_rows( 'office_locations' ) ):
					while ( have_rows( 'office_locations' ) ) : the_row();
						?>
                        <div class="col-md-6">
                            <div class="office-location">
                                <h3><?php the_sub_field( 'location' ); ?></h3>
								<?php the_sub_field( 'information' ); ?>
                            </div>
                        </div>
					<?php
					endwhile;
				endif;
				?>
            </div>
        </div>
    </section>

    <section class="contact-form" id="form">
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-four"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="container px- position-relative">
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