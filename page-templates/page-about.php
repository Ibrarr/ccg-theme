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
						// Every slide rendered its own <h1>, so the page had four. The
						// statement on the first slide is the page's heading, per the
						// client, so it takes the rank and the display words below it
						// become paragraphs. Classes and copy are untouched: only the
						// tag changes, so nothing about the slider moves.
						$ccg_about_index = 0;
						while ( have_rows( 'slider' ) ) : the_row();
							$ccg_about_index ++;
							$ccg_is_first = ( 1 === $ccg_about_index );
							?>
                            <li class="splide__slide">
                                <div class="container px-4 h-100 d-flex align-items-center justify-content-center">
                                    <div class="about-slide">
										<?php if ( get_sub_field( 'tag' ) ) { ?>
                                            <p class="tag"><?php the_sub_field( 'tag' ); ?></p>
										<?php } ?>

										<?php if ( get_sub_field( 'header' ) ) { ?>
                                            <p class="heading"><?php the_sub_field( 'header' ); ?></p>
										<?php } ?>

										<?php
										$ccg_statement_tag = $ccg_is_first ? 'h1' : 'h2';
										if ( get_sub_field( 'sub_heading' ) && get_sub_field( 'sub_heading_2nd_colour' ) ) { ?>
                                            <<?php echo $ccg_statement_tag; ?> class="sub-heading two-color"><?php the_sub_field( 'sub_heading' ); ?>
                                                <span><?php the_sub_field( 'sub_heading_2nd_colour' ); ?></span></<?php echo $ccg_statement_tag; ?>>
										<?php } ?>

										<?php if ( get_sub_field( 'sub_heading' ) && ! get_sub_field( 'sub_heading_2nd_colour' ) ) { ?>
                                            <<?php echo $ccg_statement_tag; ?> class="sub-heading"><?php the_sub_field( 'sub_heading' ); ?></<?php echo $ccg_statement_tag; ?>>
										<?php } ?>

										<?php if ( get_sub_field( 'body' ) ) { ?>
                                            <p class="body"><?php echo ccg_statement_lead_html( get_sub_field( 'body' ) ); ?></p>
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