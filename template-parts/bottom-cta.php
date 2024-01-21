<?php
if ( ! get_field( 'disable_bottom_cta' ) ) {
	if ( get_field( 'custom_bottom_cta' ) ) {
		$custom_link = get_field( 'custom_bottom_cta_button' );
		?>
        <section class="bottom-cta">
            <div class="parallax-bars"
                 id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="parallax-bars"
                 id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/keyline.svg' ) ?></div>
            <div class="container px-4">
                <div class="row justify-content-center">
                    <div class="col-lg-9 col-md-10">
                        <div class="row">
                            <div class="col-lg-3 col-md-2 first-row">
                            </div>
                            <div class="col-lg-6 col-md-8 second-row">
                                <h3><?php the_field( 'custom_bottom_cta_title' ); ?></h3>
								<?php
								if ( get_field( 'custom_bottom_cta_description' ) ) {
									echo '<p>' . get_field( 'custom_bottom_cta_description' ) . '</p>';
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