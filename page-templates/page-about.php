<?php
/*
Template Name: About Us
*/
get_header();

$button = get_field( 'button' );
?>

    <section class="about-slider slide-1">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="splide">
            <div class="splide__track">
                <ul class="splide__list">
					<?php
					if ( have_rows( 'slider' ) ):
						while ( have_rows( 'slider' ) ) : the_row();
							?>
                            <li class="splide__slide">
                                <div class="container px-4 h-100 d-flex align-items-center justify-content-center">
                                    <div class="about-slide">
										<?php if ( get_sub_field( 'tag' ) ) { ?>
                                            <p class="tag"><?php the_sub_field( 'tag' ); ?></p>
										<?php } ?>

										<?php if ( get_sub_field( 'header' ) ) { ?>
                                            <h1 class="heading"><?php the_sub_field( 'header' ); ?></h1>
										<?php } ?>

										<?php if ( get_sub_field( 'sub_heading' ) && get_sub_field( 'sub_heading_2nd_colour' ) ) { ?>
                                            <h2 class="sub-heading two-color"><?php the_sub_field( 'sub_heading' ); ?>
                                                <span><?php the_sub_field( 'sub_heading_2nd_colour' ); ?></span></h2>
										<?php } ?>

										<?php if ( get_sub_field( 'sub_heading' ) && ! get_sub_field( 'sub_heading_2nd_colour' ) ) { ?>
                                            <h2 class="sub-heading"><?php the_sub_field( 'sub_heading' ); ?></h2>
										<?php } ?>

										<?php if ( get_sub_field( 'body' ) ) { ?>
                                            <p class="body"><?php the_sub_field( 'body' ); ?></p>
										<?php } ?>
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

    <section class="about-content">
        <div class="parallax-bars"
             id="bar-four"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
        <div class="container px-4 position-relative">
            <div class="row">
                <div class="header">
					<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/down-arrow.svg' ) ?>
                    <p>About us</p>
                </div>
            </div>
            <div class="row content">
                <div class="col-lg-4">
                    <h3><?php the_field( 'heading' ); ?></h3>
                </div>
                <div class="col-lg-4">
					<?php the_field( 'body_column_1' ); ?>
                </div>
                <div class="col-lg-4">
					<?php the_field( 'body_column_2' ); ?>
                </div>
            </div>
            <div class="row"><a class="global-button" href="<?php echo $button['url']; ?>"
                                target="<?php echo $button['target']; ?>"><?php echo $button['title']; ?></a>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>