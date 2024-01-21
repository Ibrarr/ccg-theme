<?php
/**
 * The template for displaying type terms
 *
 */
$current_term   = get_queried_object();
$term_name_text = single_term_title( '', false );
?>

    <section class="archive-header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4 pb-4">
            <div class="row">
                <h1 class="title"><?php echo $term_name_text ?></h1>
                <div class="col-lg-8 col-md-10 intro">
                    <h2><?php echo strip_tags( term_description(), '<a>' ); ?></h2>
                </div>
            </div>
        </div>
    </section>

    <section class="archive-posts-container">
        <div class="container px-4">
            <div class="row" id="posts-container" data-term-slug="<?php echo get_queried_object()->slug; ?>">
                <!-- Posts will be loaded here -->
            </div>
            <div class="global-button" id="load-more-posts">See More</div>
            <div id="loading-indicator">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
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