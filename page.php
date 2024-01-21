<?php get_header(); ?>

    <section class="header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4 position-relative">
            <h1 class="title"><?php the_title(); ?></h1>
			<?php if ( get_field( 'intro' ) ) { ?>
                <div class="row">
                    <div class="col-lg-8 intro"><?php the_field( 'intro' ); ?></div>
                </div>
			<?php } ?>
        </div>
    </section>

    <section class="content">
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-three"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4 position-relative">
            <div class="row">
                <div class="col-lg-8">
					<?php the_field( 'body' ); ?>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>