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

                    <div class="row locations-logo d-lg-flex d-none">
                        <h4>Offices</h4>
                        <div>
                            <div class="locations">
                                <h4>North America</h4>
								<?php
								if ( have_rows( 'north_america_locations' ) ):
									echo '<ul>';
									while ( have_rows( 'north_america_locations' ) ) : the_row();
										echo '<li class="yellow">' . get_sub_field( 'location' ) . '</li>';
									endwhile;
									echo '</ul>';
								endif;
								?>
                            </div>
                        </div>
                        <div>
                            <div class="locations">
                                <h4>Europe</h4>
			                    <?php
			                    if ( have_rows( 'europe_locations' ) ):
				                    echo '<ul>';
				                    while ( have_rows( 'europe_locations' ) ) : the_row();
					                    echo '<li class="red">' . get_sub_field( 'location' ) . '</li>';
				                    endwhile;
				                    echo '</ul>';
			                    endif;
			                    ?>
                            </div>
                        </div>
                        <div>
                            <div class="locations">
                                <h4>Asia-Pacific</h4>
			                    <?php
			                    if ( have_rows( 'asia-pacific_locations' ) ):
				                    echo '<ul>';
				                    while ( have_rows( 'asia-pacific_locations' ) ) : the_row();
					                    echo '<li class="blue">' . get_sub_field( 'location' ) . '</li>';
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

                <div class="row locations-logo d-lg-none">
                    <h4>Offices</h4>
                    <div>
                        <div class="locations">
                            <h4>North America</h4>
			                <?php
			                if ( have_rows( 'north_america_locations' ) ):
				                echo '<ul>';
				                while ( have_rows( 'north_america_locations' ) ) : the_row();
					                echo '<li class="yellow">' . get_sub_field( 'location' ) . '</li>';
				                endwhile;
				                echo '</ul>';
			                endif;
			                ?>
                        </div>
                    </div>
                    <div>
                        <div class="locations">
                            <h4>Europe</h4>
			                <?php
			                if ( have_rows( 'europe_locations' ) ):
				                echo '<ul>';
				                while ( have_rows( 'europe_locations' ) ) : the_row();
					                echo '<li class="red">' . get_sub_field( 'location' ) . '</li>';
				                endwhile;
				                echo '</ul>';
			                endif;
			                ?>
                        </div>
                    </div>
                    <div>
                        <div class="locations">
                            <h4>Asia-Pacific</h4>
			                <?php
			                if ( have_rows( 'asia-pacific_locations' ) ):
				                echo '<ul>';
				                while ( have_rows( 'asia-pacific_locations' ) ) : the_row();
					                echo '<li class="blue">' . get_sub_field( 'location' ) . '</li>';
				                endwhile;
				                echo '</ul>';
			                endif;
			                ?>
                        </div>
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