<?php
/*
Template Name: Search
*/
get_header();

$term = $_GET['term'] ?? '';
?>

    <section class="header">
        <div class="parallax-bars"
             id="bar-one"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/pink.svg' ) ?></div>
        <div class="parallax-bars"
             id="bar-two"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/bars/yellow.svg' ) ?></div>
        <div class="container px-4">
            <h1 class="title"><?php the_title(); ?></h1>
            <div class="row">
                <div class="col-lg-8 intro"><h2><?php the_field( 'intro' ); ?></div>
            </div>
        </div>
    </section>

    <section class="search-posts-container">
        <div class="container px-4">
            <div class="search-input"><input type="text" placeholder="Search" value="<?php echo $term; ?>"></div>
            <p id="search-count"><!-- Search results number will be loaded here --></p>
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
include( CCG_TEMPLATE_DIR . '/template-parts/bottom-cta.php' );
get_footer();
?>