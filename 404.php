<?php
get_header();
?>

    <section class="header">
        <div class="container px-4">
            <h1 class="title"><?php the_field( '404_heading', 'option' ); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php the_field( '404_intro', 'option' ); ?></div>
            </div>
        </div>
    </section>

<?php
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>