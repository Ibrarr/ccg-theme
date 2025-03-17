<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php ccg_schema_type(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="wrapper" class="hfeed">
    <header id="header" role="banner">
        <div class="container px-4">
            <div class="row justify-content-end">
                <div class="col-xl-8 col-lg-9 col-6">
                    <div class="menu-container">
                        <p class="main-menu-text">CCGroup, a Hoffman Agency, is a B2B Technology PR & Marketing Consultancy</p>
                        <p class="menu-text">Menu</p>
                        <p class="search-text">Search</p>
                        <div class="search-hamburger">
                            <div class="header-search"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/search.svg' ) ?></div>
                            <div class="menu"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/menu.svg' ) ?></div>
                            <div class="cross"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/menu-close.svg' ) ?></div>
                        </div>
                    </div>
                    <div class="main-menu-container">
                        <div class="mobile-menu-header-background"></div>
                        <div class="container px-0 position-relative">
                            <div class="search-container">
                                <div class="header-search-input"><input type="text" placeholder="Search"></div>
                                <p id="header-search-count"><!-- Search results number will be loaded here --></p>
                                <div id="header-search-container"><!-- Posts will be loaded here --></div>
                                <div class="global-button" id="load-more-search-results">See More</div>
                                <div id="search-loading-indicator">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <p class="recent-posts">Recent <span>News & Views</span></p>
                                <section class="recent-insights-search">
                                    <div class="splide">
                                        <div class="splide__track">
                                            <ul class="splide__list">
												<?php
												$featured_posts = new WP_Query( array(
													'post_type'      => array( 'insight', 'post', 'work' ),
													'posts_per_page' => 6,
													'meta_query'     => array(
														array(
															'key'     => 'pinned',
															'value'   => '1',
															'compare' => '='
														)
													)
												) );

												if ( $featured_posts->have_posts() ) {
													while ( $featured_posts->have_posts() ) {
														$featured_posts->the_post();
														$term_name = get_the_terms( get_the_ID(), 'type' )[0]->name;
														echo '<li class="splide__slide">';
														require( 'template-parts/article-card-slider.php' );
														echo '</li>';
													}
												}

												$featured_post_count = $featured_posts->post_count;
												wp_reset_postdata();

												$recent_posts_to_fetch = 6 - $featured_post_count;

												$recent_posts_fetched = 0;

												if ( $recent_posts_to_fetch > 0 ) {
													$exclude_post_ids = wp_list_pluck( $featured_posts->posts, 'ID' );

													$recent_posts = new WP_Query( array(
														'post_type'      => array( 'insight', 'post', 'work' ),
														'posts_per_page' => $recent_posts_to_fetch,
														'post__not_in'   => $exclude_post_ids,
													) );

													if ( $recent_posts->have_posts() ) {
														while ( $recent_posts->have_posts() ) {
															$recent_posts->the_post();
															$post_type = get_post_type();
															$term_name = $post_type === 'insight' ? get_the_terms( get_the_ID(), 'type' )[0]->name : 'Blog';
															echo '<li class="splide__slide">';
															require( 'template-parts/article-card-slider.php' );
															echo '</li>';
															$recent_posts_fetched ++;
														}
													}
													wp_reset_postdata();
												}

												if ( $featured_post_count == 0 && $recent_posts_fetched == 0 ) {
													echo '<p>No results found.</p>';
												}
												?>
                                            </ul>
                                        </div>
                                        <div class="splide__arrows splide__arrows--ltr">
                                            <button
                                                    class="splide__arrow splide__arrow--prev"
                                                    type="button"
                                                    aria-label="Previous slide"
                                                    aria-controls="splide02-track"
                                            >
												<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                                            </button>
                                            <button
                                                    class="splide__arrow splide__arrow--next"
                                                    type="button"
                                                    aria-label="Next slide"
                                                    aria-controls="splide02-track"
                                            >
												<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                                            </button>
                                        </div>
                                    </div>
                                </section>

                                <p class="recent-posts">Latest Updates</p>
                                <section class="latest-updates">
                                    <div class="splide">
                                        <div class="splide__track">
                                            <ul class="splide__list">
												<?php
												$recent_news = new WP_Query( array(
													'post_type'      => 'news',
													'posts_per_page' => 6,
												) );

												if ( $recent_news->have_posts() ) {
													while ( $recent_news->have_posts() ) {
														$recent_news->the_post();
														$term_name = get_the_terms( get_the_ID(), 'source' )[0]->name;
														echo '<li class="splide__slide">';
														require( 'template-parts/article-card-slider-news.php' );
														echo '</li>';
													}
												}
												wp_reset_postdata();
												?>
                                            </ul>
                                        </div>
                                        <div class="splide__arrows splide__arrows--ltr">
                                            <button
                                                    class="splide__arrow splide__arrow--prev"
                                                    type="button"
                                                    aria-label="Previous slide"
                                                    aria-controls="splide03-track"
                                            >
												<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                                            </button>
                                            <button
                                                    class="splide__arrow splide__arrow--next"
                                                    type="button"
                                                    aria-label="Next slide"
                                                    aria-controls="splide03-track"
                                            >
												<?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/arrow.svg' ) ?>
                                            </button>
                                        </div>
                                    </div>
                                </section>

                                <div class="header-footer">
                                    <div class="row">
                                        <div class="col-md-5 col-6">
                                            <h6>Contact us</h6>
											<?php the_field( 'ccg_address', 'option' ) ?>
                                            <p>Call:
                                                <a href="tel:<?php the_field( 'ccg_phone', 'option' ) ?>"><?php the_field( 'ccg_phone', 'option' ) ?></a>
                                            </p>
                                        </div>
                                        <div class="col-md-5 col-6 d-flex align-items-end">
											<?php wp_nav_menu( array(
												'theme_location' => 'info-menu',
											) ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="nav-container">
                                <nav id="nav" role="navigation" itemscope
                                     itemtype="https://schema.org/SiteNavigationElement">
									<?php wp_nav_menu( array(
										'theme_location' => 'main-menu',
									) ); ?>
                                </nav>

                                <p class="recent-posts">Recent <span>News & Views</span></p>
                                <section class="recent-insights">
                                    <div class="splide">
                                        <div class="splide__track">
                                            <ul class="splide__list">
												<?php
												$featured_posts = new WP_Query( array(
													'post_type'      => array( 'insight', 'post', 'work' ),
													'posts_per_page' => 6,
													'meta_query'     => array(
														array(
															'key'     => 'pinned',
															'value'   => '1',
															'compare' => '='
														)
													)
												) );

												if ( $featured_posts->have_posts() ) {
													while ( $featured_posts->have_posts() ) {
														$featured_posts->the_post();
														$term_name = get_the_terms( get_the_ID(), 'type' )[0]->name;
														echo '<li class="splide__slide">';
														require( 'template-parts/article-card-slider.php' );
														echo '</li>';
													}
												}

												$featured_post_count = $featured_posts->post_count;
												wp_reset_postdata();

												$recent_posts_to_fetch = 6 - $featured_post_count;

												$recent_posts_fetched = 0;

												if ( $recent_posts_to_fetch > 0 ) {
													$exclude_post_ids = wp_list_pluck( $featured_posts->posts, 'ID' );

													$recent_posts = new WP_Query( array(
														'post_type'      => array( 'insight', 'post', 'work' ),
														'posts_per_page' => $recent_posts_to_fetch,
														'post__not_in'   => $exclude_post_ids,
													) );

													if ( $recent_posts->have_posts() ) {
														while ( $recent_posts->have_posts() ) {
															$recent_posts->the_post();
															$post_type = get_post_type();
															$term_name = $post_type === 'insight' ? get_the_terms( get_the_ID(), 'type' )[0]->name : 'Blog';
															echo '<li class="splide__slide">';
															require( 'template-parts/article-card-slider.php' );
															echo '</li>';
															$recent_posts_fetched ++;
														}
													}
													wp_reset_postdata();
												}

												if ( $featured_post_count == 0 && $recent_posts_fetched == 0 ) {
													echo '<p>No results found.</p>';
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
                                </section>

                                <div class="header-footer">
                                    <div class="row">
                                        <div class="col-md-5 col-6">
                                            <h6>Contact us</h6>
											<?php the_field( 'ccg_address', 'option' ) ?>
                                            <p>Call:
                                                <a href="tel:<?php the_field( 'ccg_phone', 'option' ) ?>"><?php the_field( 'ccg_phone', 'option' ) ?></a>
                                            </p>
                                        </div>
                                        <div class="col-md-5 col-6 d-flex align-items-end">
											<?php wp_nav_menu( array(
												'theme_location' => 'info-menu',
											) ); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <div class="container px-4">
        <div class="header-logo">
            <a href="/"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/logos/header-logo.svg' ) ?></a>
        </div>
    </div>

    <div class="announce-popup-container">
        <div class="announce-popup">
            <div class="close-announce-popup"><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/new-cross.svg' ) ?></div>
            <div class="popup-content">
                <h3>CCGroup is now proud to be part of The Hoffman Agency</h3>
                <a href="https://ccgrouppr.com/blog/ccgroup-x-the-hoffman-agency/">
                    <p>Find out more here <i><?php echo file_get_contents( CCG_TEMPLATE_DIR . '/assets/images/icons/feather-arrow-right.svg' ) ?></i></p>
                </a>
            </div>
            <div class="popup-image">
                <img src="https://ccgrouppr.com/wp-content/uploads/2025/03/hoffman-popup.png" alt="">
            </div>
        </div>
    </div>

    <main id="content" role="main">