<?php
/*
Template Name: Sectors
*/
get_header();

$sector_slugs = array();
if ( have_rows( 'sectors_order' ) ) {
	while ( have_rows( 'sectors_order' ) ) : the_row();
		$sector_slugs[] = get_sub_field( 'sector_slug' );
	endwhile;
}
?>

    <section class="sectors-header">
        <div class="sector-name-slide">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
						<?php
						$terms = get_terms( array(
							'taxonomy'   => 'sector',
							'hide_empty' => false,
							'slug'       => $sector_slugs,
						) );

						foreach ( $sector_slugs as $slug ) {
							$term = get_term_by( 'slug', $slug, 'sector' );

							if ( get_term_meta( $term->term_id, 'include_on_frontend', true ) ) {
								?>
                                <li class="splide__slide">
                                    <div class="sector-name">
                                        <h3 class="heading"><?php echo $term->name; ?></h3>
                                    </div>
                                </li>
								<?php
							}
						}
						?>
                    </ul>
                </div>
            </div>
        </div>
        <img src="<?php echo wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'header-image' )[0]; ?>"
             alt="">
    </section>

    <section class="sectors-dropdown">
        <div class="container px-4 d-flex justify-content-end">
            <div class="sector-dropdown-container">
                <div class="sector-dropdown">Sectors</div>
                <div class="sector-dropdown-menu-container">
                    <div class="sector-dropdown-menu">
						<?php
						$terms = get_terms( array(
							'taxonomy' => 'sector',
							'slug'     => $sector_slugs,
						) );

						foreach ( $sector_slugs as $slug ) {
							$term = get_term_by( 'slug', $slug, 'sector' );

							if ( get_term_meta( $term->term_id, 'include_on_frontend', true ) ) {
								echo '<a class="sector-dropdown-item" href="' . get_term_link( $term ) . '">' . $term->name . '</a>';
							}
						}
						?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="sectors-slider">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <div class="splide">
                <div class="splide__track">
                    <ul class="splide__list">
                        <li class="splide__slide">
                            <div class="first-slide">
                                <p class="term">Sectors</p>
                                <h3 class="heading"><?php the_field( 'header' ); ?></h3>
                                <p class="sub-heading"><?php the_field( 'sub_heading' ); ?></p>
                                <p class="global-button next-slide"><?php the_field( 'button_text' ); ?></p>
                            </div>
                        </li>
						<?php
						$terms = get_terms( array(
							'taxonomy'   => 'sector',
							'hide_empty' => false,
							'slug'       => $sector_slugs,
						) );

						foreach ( $sector_slugs as $slug ) {
							$term = get_term_by( 'slug', $slug, 'sector' );

							if ( get_term_meta( $term->term_id, 'include_on_frontend', true ) ) {
								$image_id  = get_term_meta( $term->term_id, 'image', true );
								$image_url = wp_get_attachment_image_url( $image_id, 'header-image' );
								?>
                                <li class="splide__slide">
                                    <img src="<?php echo $image_url; ?>">
                                    <div class="sector-slide">
                                        <a href="<?php echo get_term_link( $term ); ?>">
                                            <h3 class="heading"><?php echo $term->name; ?></h3>
                                            <p class="sub-heading"><?php echo $term->description; ?></p>
                                        </a>
                                    </div>
                                </li>
								<?php
							}
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
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>