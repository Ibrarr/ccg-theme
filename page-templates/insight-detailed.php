<?php
/*
Template Name: Insight Report (Detailed)
Template Post Type: insight
*/
get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', 'insight-detailed' );

	endwhile;
endif;

get_footer();
