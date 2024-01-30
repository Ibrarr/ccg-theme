<?php

/**
 * Remove default tag taxonomy
 */
add_action('init', 'remove_taxonomy');
function remove_taxonomy(){
	unregister_taxonomy_for_object_type('post_tag', 'post');
}

/**
 * Remove comments
 */
add_action('admin_init', 'disable_comments_support');
function disable_comments_support() {
	// Post types for which to disable comments
	$post_types = get_post_types();
	foreach ($post_types as $post_type) {
		if(post_type_supports($post_type, 'comments')) {
			remove_post_type_support($post_type, 'comments');
			remove_post_type_support($post_type, 'trackbacks');
		}
	}
}

/**
 * Remove comments from menu
 */
add_action('wp_before_admin_bar_render', 'remove_comments_from_admin_bar');
function remove_comments_from_admin_bar() {
	global $wp_admin_bar;
	$wp_admin_bar->remove_menu('comments');
}

/**
 * Remove comments page
 */
add_action('admin_menu', 'remove_comments_admin_menu');
function remove_comments_admin_menu() {
	remove_menu_page('edit-comments.php');
}