<?php
get_header();

if (is_category()) {
    get_template_part( 'template-parts/archive', 'category' );
}

if ( is_tax() ) {
	$taxonomy = get_query_var( 'taxonomy' );
	switch ( $taxonomy ) {
		case 'sector':
			get_template_part( 'template-parts/archive', 'sector' );
			break;
		case 'service':
			get_template_part( 'template-parts/archive', 'service' );
			break;
        case 'type':
            get_template_part( 'template-parts/archive', 'type' );
            break;
        case 'source':
            get_template_part( 'template-parts/archive', 'source' );
            break;
		default:
			wp_redirect( home_url() );
			break;
	}
}



if ( is_post_type_archive() ) {
	switch ( get_post_type() ) {
		case 'insight':
			get_template_part( 'template-parts/archive', 'insight' );
			break;
		case 'work':
			get_template_part( 'template-parts/archive', 'work' );
			break;
		case 'news':
			get_template_part( 'template-parts/archive', 'news' );
			break;
		default:
			wp_redirect( home_url() );
			break;
	}
}


get_footer();