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
		'description' => __('Here at Ablewild, we believe in working with the best specialists for the best results. Here are our team of experts.We like to do stuff!
In order to keep us up-to-date with our industry, or just to get out and be inspired by something new, we go to as many events as possible and share our experiences here.'),
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/images/admin/tiny_icon_profiles.png',
		'rewrite' => array("slug" => "team", 'with_front'=> false), // Permalinks format
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail','custom-fields','page-attributes'),
		//'taxonomies' => array(''),
		'register_meta_box_cb' => 'add_profiles_metaboxes',
		'has_archive' => true
	  ); 
 
	register_post_type( 'Profiles' , $args );
}

/*************************************
* Custom meta fields
*************************************/

function add_profiles_metaboxes() {
	add_meta_box('cx_profile_info', 'Additional Info', 'cx_profile_info', 'profiles', 'normal', 'default');
}

// The profile Additional Info metabox

function cx_profile_info() {
	global $post;
	
	// Noncename neede to verify where the data originated
	echo '<input type="hidden" name="profilemeta_noncename" id="profilemeta_noncename" value="' . wp_create_nonce( plugin_basename(__FILE__) ) . '" />';
	
	// Get the additional info data if it's already been entered
	$profile_job_title = get_post_meta($post->ID, '_job_title', true);
	$profile_email = get_post_meta($post->ID, '_email', true);
	$profile_url = get_post_meta($post->ID, '_url', true);
	$profile_twitter = get_post_meta($post->ID, '_twitter', true);
	$profile_github = get_post_meta($post->ID, '_github', true);
	
	// Echo out the field
	echo '<p>Job title:</p>';
	echo '<input type="text" name="_job_title" value="' . $profile_job_title . '" class="widefat" />';
	echo '<p>Email:</p>';
	echo '<input type="text" name="_email" value="' . $profile_email . '" class="widefat" />';
	echo '<p>URL:</p>';
	echo '<input type="text" name="_url" value="' . $profile_url . '" class="widefat" />';
	echo '<p>Twitter:</p>';
	echo '<input type="text" name="_twitter" value="' . $profile_twitter . '" class="widefat" />';
	echo '<p>GitHib:</p>';
	echo '<input type="text" name="_github" value="' . $profile_github . '" class="widefat" />';
	
}
	
// Save the Metabox data

function cx_save_profile_meta($post_id, $post) {

	// Verify this came from our screen and with proper authorization,
	// because save_post can be triggered at other times
	if ( !wp_verify_nonce( $_POST['profilemeta_noncename'], plugin_basename(__FILE__) )) {
		return $post->ID;
	}
	
	// Is the user allowed to edit the post or page?
	if ( !current_user_can( 'edit_post', $post->ID ))
		return $post->ID;
	// OK, we're authenticated: we need to find and save the data
	// We'll put it into an array to make it easier to loop though.
	
	$profiles_meta['_email'] = $_POST['_email'];
	$profiles_meta['_job_title'] = $_POST['_job_title'];
	$profiles_meta['_url'] = $_POST['_url'];
	$profiles_meta['_twitter'] = $_POST['_twitter'];
	$profiles_meta['_github'] = $_POST['_github'];
	
	// Add values of $profiles_meta as custom fields
	
	foreach ($profiles_meta as $key => $value) { // Cycle through the $profiles_meta array!
		 if( $post->post_type == 'revision' ) return; // Don't store custom data twice
		 $value = implode(',', (array)$value); // If $value is an array, make it a CSV (unlikely)
		 if(get_post_meta($post->ID, $key, FALSE)) { // If the custom field already has a value
		 	update_post_meta($post->ID, $key, $value);
		 } else { // If the custom field doesn't have a value
		 	add_post_meta($post->ID, $key, $value);
		 }
		 if(!$value) delete_post_meta($post->ID, $key); // Delete if blank
	}
}

add_action('save_post_profiles', 'cx_save_profile_meta', 1, 2); // save the custom fields

/*************************************
* Custom Taxonomies
*************************************/

function create_profile_tax_skills() {
	// create a new taxonomy
	$labels = array(
		'singular_name' => __( 'Skill' ),
		'add_new_item' => __( 'Add New Skill' )
	);

	register_taxonomy(
		'skill',
		'profiles',
		array(
			'hierarchical' => true,
			'label' => __( 'Skills' ),
			'labels' => $labels,
			'rewrite' => array( 'slug' => 'skills' )
		)
	);
}

add_action( 'init', 'create_profile_tax_skills' );


/*************************************
* Custom templates
*************************************/

add_filter('template_include', 'team_template');

function team_template( $template ) {
  if ( is_post_type_archive('profiles') ) {
    $theme_files = array('archive-team.php', 'cx_team/archive-team.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . 'archive-team.php';
    }
  }
  return $template;
}

/*************************************
* Change display order 
*************************************/

function change_profile_display_order( $query ) {
    if ( is_post_type_archive( 'profiles' ) ) {
        // Display 50 posts for a custom post type called 'projects'
        $query->set( 'orderby', 'menu_order title' );
        $query->set( 'order', 'asc' );
        return;
    }
}
add_action( 'pre_get_posts', 'change_profile_display_order', 1 );

?>