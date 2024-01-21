<section class="archive-header">
    <div class="parallax-bars"
         id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/navy.svg' ) ?></div>
    <div class="parallax-bars"
         id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
    <div class="container px-4">
        <div class="row">
            <h1 class="title"><?php the_field( 'blog_heading', 'option' ); ?></h1>
            <div class="col-lg-8 col-md-10 intro">
                <h2><?php the_field( 'blog_description', 'option' ); ?></h2>
            </div>
        </div>
    </div>
</section>

<section class="archive-posts-container">
    <div class="container px-4">
        <div class="term-container">
            <div class="selected-terms"><!--Selected terms go here--></div>
            <div class="term-selector-container">
                <div class="term-selector">Category</div>
                <div class="term-selector-menu-container">
                    <div class="term-selector-menu">
						<?php
						$categories = get_terms( array(
							'taxonomy'   => 'category',
							'hide_empty' => true,
						) );

						foreach ( $categories as $category ) {
							echo '<span class="term-selector-item category" data-value="' . esc_attr( $category->slug ) . '">' . esc_html( $category->name ) . '</span>';
						}
						?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" id="posts-container">
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
if ( ! get_field( 'our_blog_disable_bottom_cta', 'option' ) ) {
	if ( get_field( 'our_blog_custom_bottom_cta', 'option' ) ) {
		$custom_link = get_field( 'our_blog_custom_bottom_cta_button', 'option' );
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
                                <h3><?php the_field( 'our_blog_custom_bottom_cta_title', 'option' ); ?></h3>
								<?php
								if ( get_field( 'our_blog_custom_bottom_cta_description', 'option' ) ) {
									echo '<p>' . get_field( 'our_blog_custom_bottom_cta_description', 'option' ) . '</p>';
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
?>