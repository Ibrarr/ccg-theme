<?php
/*
Template Name: Holding Page
*/
get_header();
$maintenance_page = get_posts(array('post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-templates/page-holding-page.php'));
if ($maintenance_page) {
    $heading = get_field('heading', $maintenance_page[0]->ID);
} else {
    $heading = get_field('heading');
}
?>

    <section class="header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="container px-4">
            <div class="row">
                <div class="col-lg-6 col-md-8">
                    <h1 class="title"><?php echo $heading; ?></h1>
                    <div class="main-logo"><a
                                href="/"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/logos/header-logo.svg' ) ?></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();
?>