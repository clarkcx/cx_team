<?php
/*
Plugin Name: CX Team Profiles
Plugin URI: http://www.ablewild.com
Description: Add custom post types for showing team profiles.
Version: 1.0
Author: Pete Clark
Author URI: http://twitter.com/clarkcx
Licence: GPL2
*/

//////////////////////////////////////////////////////
///* CREATE CUSTOM POST TYPE: TEAM *///////////
//////////////////////////////////////////////////////

add_action('init', 'Profiles_register');
 
function Profiles_register() {
	$labels = array(
		'name' => _x('Profiles', 'post type general name'),
		'singular_name' => _x('Profile', 'post type singular name'),
		'add_new' => _x('Add New', 'Profile'),
		'add_new_item' => __('Add New Profile'),
		'edit_item' => __('Edit Profile'),
		'new_item' => __('New Profile'),
		'view_item' => __('View Profile'),
		'search_items' => __('Search Profiles'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/tiny_icon_profiles.png',
		'rewrite' => array("slug" => "team", 'with_front'=> false), // Permalinks format
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail','custom-fields'),
		'taxonomies' => array(''),
	//	'register_meta_box_cb' => 'add_profiles_metaboxes',
		'has_archive' => true
	  ); 
 
	register_post_type( 'Profiles' , $args );
}



?>