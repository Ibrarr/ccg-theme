<?php
get_header();

if ( have_posts() ) :
	while ( have_posts() ) : the_post();

		switch ( get_post_type() ) {
			case 'insight':
				if ( has_term( 'insight-reports', 'type', get_the_ID() ) ) {
					get_template_part( 'template-parts/content', 'insight-report' );
				} elseif ( has_term( 'webinars', 'type', get_the_ID() ) ) {
					get_template_part( 'template-parts/content', 'webinars' );
				} else {
					get_template_part( 'template-parts/content', 'insight' );
				}
				break;
			case 'work':
				get_template_part( 'template-parts/content', 'work' );
				break;
			default:
				get_template_part( 'template-parts/content', 'blog' );
				break;
		}

	endwhile;
endif;

get_footer();