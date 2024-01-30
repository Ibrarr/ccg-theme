<?php

/**
 * Register Insights Post Type
 */
add_action( 'init', 'insight_post_type', 0 );
function insight_post_type() {

	$labels = array(
		'name'                  => _x( 'Insights', 'Post Type General Name', 'ccg' ),
		'singular_name'         => _x( 'Insight', 'Post Type Singular Name', 'ccg' ),
		'menu_name'             => __( 'Insights', 'ccg' ),
		'name_admin_bar'        => __( 'Insight', 'ccg' ),
		'archives'              => __( 'Insight Archives', 'ccg' ),
		'attributes'            => __( 'Insight Attributes', 'ccg' ),
		'parent_item_colon'     => __( 'Parent insight:', 'ccg' ),
		'all_items'             => __( 'All insights', 'ccg' ),
		'add_new_item'          => __( 'Add New insight', 'ccg' ),
		'add_new'               => __( 'Add New', 'ccg' ),
		'new_item'              => __( 'New insight', 'ccg' ),
		'edit_item'             => __( 'Edit insight', 'ccg' ),
		'update_item'           => __( 'Update insight', 'ccg' ),
		'view_item'             => __( 'View insight', 'ccg' ),
		'view_items'            => __( 'View insights', 'ccg' ),
		'search_items'          => __( 'Search insight', 'ccg' ),
		'not_found'             => __( 'Not found', 'ccg' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ccg' ),
		'featured_image'        => __( 'Featured Image', 'ccg' ),
		'set_featured_image'    => __( 'Set featured image', 'ccg' ),
		'remove_featured_image' => __( 'Remove featured image', 'ccg' ),
		'use_featured_image'    => __( 'Use as featured image', 'ccg' ),
		'insert_into_item'      => __( 'Insert into insight', 'ccg' ),
		'uploaded_to_this_item' => __( 'Uploaded to this insight', 'ccg' ),
		'items_list'            => __( 'Insights list', 'ccg' ),
		'items_list_navigation' => __( 'Insights list navigation', 'ccg' ),
		'filter_items_list'     => __( 'Filter insights list', 'ccg' ),
	);
	$args   = array(
		'label'               => __( 'Insight', 'ccg' ),
		'description'         => __( 'Post Type Description', 'ccg' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'taxonomies'          => array( 'sector', 'type', ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-lightbulb',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'insight-hub',
		'rewrite'             => array(
			'slug'       => 'insight',
			'with_front' => false,
		),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);
	register_post_type( 'insight', $args );

}

/**
 * Register Work Post Type
 */
add_action( 'init', 'work_post_type', 0 );
function work_post_type() {

	$labels = array(
		'name'                  => _x( 'Work', 'Post Type General Name', 'ccg' ),
		'singular_name'         => _x( 'Work', 'Post Type Singular Name', 'ccg' ),
		'menu_name'             => __( 'Work', 'ccg' ),
		'name_admin_bar'        => __( 'Work', 'ccg' ),
		'archives'              => __( 'Work Archives', 'ccg' ),
		'attributes'            => __( 'Work Attributes', 'ccg' ),
		'parent_item_colon'     => __( 'Parent work:', 'ccg' ),
		'all_items'             => __( 'All work', 'ccg' ),
		'add_new_item'          => __( 'Add New work', 'ccg' ),
		'add_new'               => __( 'Add New', 'ccg' ),
		'new_item'              => __( 'New work', 'ccg' ),
		'edit_item'             => __( 'Edit work', 'ccg' ),
		'update_item'           => __( 'Update work', 'ccg' ),
		'view_item'             => __( 'View work', 'ccg' ),
		'view_items'            => __( 'View work', 'ccg' ),
		'search_items'          => __( 'Search work', 'ccg' ),
		'not_found'             => __( 'Not found', 'ccg' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ccg' ),
		'featured_image'        => __( 'Featured Image', 'ccg' ),
		'set_featured_image'    => __( 'Set featured image', 'ccg' ),
		'remove_featured_image' => __( 'Remove featured image', 'ccg' ),
		'use_featured_image'    => __( 'Use as featured image', 'ccg' ),
		'insert_into_item'      => __( 'Insert into work', 'ccg' ),
		'uploaded_to_this_item' => __( 'Uploaded to this work', 'ccg' ),
		'items_list'            => __( 'Work list', 'ccg' ),
		'items_list_navigation' => __( 'Work list navigation', 'ccg' ),
		'filter_items_list'     => __( 'Filter Work list', 'ccg' ),
	);
	$args   = array(
		'label'               => __( 'Work', 'ccg' ),
		'description'         => __( 'Post Type Description', 'ccg' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'taxonomies'          => array( 'sector', 'type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-networking',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'our-work',
		'rewrite'             => array(
			'slug'       => 'work',
			'with_front' => false,
		),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);
	register_post_type( 'work', $args );

}

/**
 * Register News Post Type
 */
add_action( 'init', 'news_post_type', 0 );
function news_post_type() {

	$labels = array(
		'name'                  => _x( 'News', 'Post Type General Name', 'ccg' ),
		'singular_name'         => _x( 'News', 'Post Type Singular Name', 'ccg' ),
		'menu_name'             => __( 'News', 'ccg' ),
		'name_admin_bar'        => __( 'News', 'ccg' ),
		'archives'              => __( 'News Archives', 'ccg' ),
		'attributes'            => __( 'News Attributes', 'ccg' ),
		'parent_item_colon'     => __( 'Parent news:', 'ccg' ),
		'all_items'             => __( 'All news', 'ccg' ),
		'add_new_item'          => __( 'Add New news', 'ccg' ),
		'add_new'               => __( 'Add New', 'ccg' ),
		'new_item'              => __( 'New news', 'ccg' ),
		'edit_item'             => __( 'Edit news', 'ccg' ),
		'update_item'           => __( 'Update news', 'ccg' ),
		'view_item'             => __( 'View news', 'ccg' ),
		'view_items'            => __( 'View news', 'ccg' ),
		'search_items'          => __( 'Search news', 'ccg' ),
		'not_found'             => __( 'Not found', 'ccg' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ccg' ),
		'featured_image'        => __( 'Featured Image', 'ccg' ),
		'set_featured_image'    => __( 'Set featured image', 'ccg' ),
		'remove_featured_image' => __( 'Remove featured image', 'ccg' ),
		'use_featured_image'    => __( 'Use as featured image', 'ccg' ),
		'insert_into_item'      => __( 'Insert into news', 'ccg' ),
		'uploaded_to_this_item' => __( 'Uploaded to this news', 'ccg' ),
		'items_list'            => __( 'News list', 'ccg' ),
		'items_list_navigation' => __( 'News list navigation', 'ccg' ),
		'filter_items_list'     => __( 'Filter news list', 'ccg' ),
	);
	$args   = array(
		'label'               => __( 'News', 'ccg' ),
		'description'         => __( 'Post Type Description', 'ccg' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'thumbnail' ),
		'taxonomies'          => array( 'sector', 'type' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 6,
		'menu_icon'           => 'dashicons-admin-links',
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => 'our-news',
		'rewrite'             => array(
			'slug'       => 'news',
			'with_front' => false,
		),
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true,
	);
	register_post_type( 'news', $args );

}

/**
 * Change Posts to Blog
 */
add_action( 'init', 'change_post_labels_to_news' );
function change_post_labels_to_news() {
	$get_post_type = get_post_type_object( 'post' );

	$labels                     = $get_post_type->labels;
	$labels->name               = 'Blogs';
	$labels->singular_name      = 'Blog';
	$labels->add_new            = 'Add Blog';
	$labels->add_new_item       = 'Add Blog';
	$labels->edit_item          = 'Edit Blog';
	$labels->new_item           = 'Blog';
	$labels->view_item          = 'View Blogs';
	$labels->search_items       = 'Search Blogs';
	$labels->not_found          = 'No Blogs found';
	$labels->not_found_in_trash = 'No Blogs found in Trash';
	$labels->all_items          = 'All Blogs';
	$labels->menu_name          = 'Blog';
	$labels->name_admin_bar     = 'Blog';
}