<?php

/**
 * Register Sector Taxonomy
 */
add_action( 'init', 'sector_taxonomy', 0 );
function sector_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Sectors', 'Taxonomy General Name', 'ccg' ),
		'singular_name'              => _x( 'Sector', 'Taxonomy Singular Name', 'ccg' ),
		'menu_name'                  => __( 'Sector', 'ccg' ),
		'all_items'                  => __( 'All sectors', 'ccg' ),
		'parent_item'                => __( 'Parent sector', 'ccg' ),
		'parent_item_colon'          => __( 'Parent sector:', 'ccg' ),
		'new_item_name'              => __( 'New sector Name', 'ccg' ),
		'add_new_item'               => __( 'Add New sector', 'ccg' ),
		'edit_item'                  => __( 'Edit sector', 'ccg' ),
		'update_item'                => __( 'Update sector', 'ccg' ),
		'view_item'                  => __( 'View sector', 'ccg' ),
		'separate_items_with_commas' => __( 'Separate sectors with commas', 'ccg' ),
		'add_or_remove_items'        => __( 'Add or remove sectors', 'ccg' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ccg' ),
		'popular_items'              => __( 'Popular sectors', 'ccg' ),
		'search_items'               => __( 'Search sectors', 'ccg' ),
		'not_found'                  => __( 'Not Found', 'ccg' ),
		'no_terms'                   => __( 'No sectors', 'ccg' ),
		'items_list'                 => __( 'Sectors list', 'ccg' ),
		'items_list_navigation'      => __( 'Sectors list navigation', 'ccg' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'meta_box_cb'       => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'sector',
			'with_front' => false,
		),
	);
	register_taxonomy( 'sector', array( 'insight', 'work' ), $args );

	// Register predefined terms
	wp_insert_term( 'Mobile & Telecoms', 'sector' );
	wp_insert_term( 'Fintech', 'sector' );
	wp_insert_term( 'Enterprise Tech', 'sector' );
	wp_insert_term( 'Cybersecurity', 'sector' );
	wp_insert_term( 'Deep Tech', 'sector' );
	wp_insert_term( 'Connectivity', 'sector' );
	wp_insert_term( 'Emerging Tech', 'sector' );
}

/**
 * Register Type Taxonomy
 */
add_action( 'init', 'type_taxonomy', 0 );
function type_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Types ', 'Taxonomy General Name', 'ccg' ),
		'singular_name'              => _x( 'Type', 'Taxonomy Singular Name', 'ccg' ),
		'menu_name'                  => __( 'Type', 'ccg' ),
		'all_items'                  => __( 'All types ', 'ccg' ),
		'parent_item'                => __( 'Parent type', 'ccg' ),
		'parent_item_colon'          => __( 'Parent type:', 'ccg' ),
		'new_item_name'              => __( 'New type Name', 'ccg' ),
		'add_new_item'               => __( 'Add New type', 'ccg' ),
		'edit_item'                  => __( 'Edit type', 'ccg' ),
		'update_item'                => __( 'Update type', 'ccg' ),
		'view_item'                  => __( 'View type', 'ccg' ),
		'separate_items_with_commas' => __( 'Separate types  with commas', 'ccg' ),
		'add_or_remove_items'        => __( 'Add or remove types ', 'ccg' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ccg' ),
		'popular_items'              => __( 'Popular types ', 'ccg' ),
		'search_items'               => __( 'Search types ', 'ccg' ),
		'not_found'                  => __( 'Not Found', 'ccg' ),
		'no_terms'                   => __( 'No types ', 'ccg' ),
		'items_list'                 => __( 'Types  list', 'ccg' ),
		'items_list_navigation'      => __( 'Types  list navigation', 'ccg' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'meta_box_cb'       => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'type',
			'with_front' => false,
		),
	);
	register_taxonomy( 'type', array( 'insight' ), $args );

	// Register predefined terms
	wp_insert_term( 'Insight Reports', 'type' );
	wp_insert_term( 'Tech Talks', 'type' );
	wp_insert_term( 'Blog', 'type' );
	wp_insert_term( 'Webinars', 'type' );
	wp_insert_term( 'Podcasts', 'type' );
}

/**
 * Register Source Taxonomy
 */
add_action( 'init', 'source_taxonomy', 0 );
function source_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Sources', 'Taxonomy General Name', 'ccg' ),
		'singular_name'              => _x( 'Source', 'Taxonomy Singular Name', 'ccg' ),
		'menu_name'                  => __( 'Source', 'ccg' ),
		'all_items'                  => __( 'All sources', 'ccg' ),
		'parent_item'                => __( 'Parent source', 'ccg' ),
		'parent_item_colon'          => __( 'Parent source:', 'ccg' ),
		'new_item_name'              => __( 'New source Name', 'ccg' ),
		'add_new_item'               => __( 'Add New source', 'ccg' ),
		'edit_item'                  => __( 'Edit source', 'ccg' ),
		'update_item'                => __( 'Update source', 'ccg' ),
		'view_item'                  => __( 'View source', 'ccg' ),
		'separate_items_with_commas' => __( 'Separate sources with commas', 'ccg' ),
		'add_or_remove_items'        => __( 'Add or remove sources', 'ccg' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ccg' ),
		'popular_items'              => __( 'Popular sources', 'ccg' ),
		'search_items'               => __( 'Search sources', 'ccg' ),
		'not_found'                  => __( 'Not Found', 'ccg' ),
		'no_terms'                   => __( 'No sources', 'ccg' ),
		'items_list'                 => __( 'Sources list', 'ccg' ),
		'items_list_navigation'      => __( 'Sources list navigation', 'ccg' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'meta_box_cb'       => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'       => 'source',
			'with_front' => false,
		),
	);
	register_taxonomy( 'source', array( 'news' ), $args );
}

/**
 * Register Services  Taxonomy
 */
add_action( 'init', 'services_taxonomy', 0 );
function services_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Services', 'Taxonomy General Name', 'ccg' ),
		'singular_name'              => _x( 'Service', 'Taxonomy Singular Name', 'ccg' ),
		'menu_name'                  => __( 'Service', 'ccg' ),
		'all_items'                  => __( 'All services', 'ccg' ),
		'parent_item'                => __( 'Parent service', 'ccg' ),
		'parent_item_colon'          => __( 'Parent service:', 'ccg' ),
		'new_item_name'              => __( 'New service Name', 'ccg' ),
		'add_new_item'               => __( 'Add New service', 'ccg' ),
		'edit_item'                  => __( 'Edit service', 'ccg' ),
		'update_item'                => __( 'Update service', 'ccg' ),
		'view_item'                  => __( 'View service', 'ccg' ),
		'separate_items_with_commas' => __( 'Separate services with commas', 'ccg' ),
		'add_or_remove_items'        => __( 'Add or remove services', 'ccg' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ccg' ),
		'popular_items'              => __( 'Popular services', 'ccg' ),
		'search_items'               => __( 'Search services', 'ccg' ),
		'not_found'                  => __( 'Not Found', 'ccg' ),
		'no_terms'                   => __( 'No services', 'ccg' ),
		'items_list'                 => __( 'Services list', 'ccg' ),
		'items_list_navigation'      => __( 'Services list navigation', 'ccg' ),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'meta_box_cb'       => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
		'show_in_rest'      => true,
		'rewrite'           => array(
			'slug'         => 'service',
			'with_front'   => false,
			'hierarchical' => true,
		),
	);
	register_taxonomy( 'service', array( 'work' ), $args );
}