<?php
/*

Plugin Name: Custom News Post
 * Plugin URI:  phila.gov
 * Description: Creation of a custom post type for the City of Philadelphia News.
 * Author: Karissa Demi
 *	Author URL: http://karissademi.com
 * Version:     .1

Custom Post Types for Phila.gov
This file contains CPTs for
	News
	Documents
	Locations
	Forms
*/

add_action( 'init', 'phila_create_post_type' );
/**
 * Register news post type.
 *
 */

function phila_create_post_type() {
    $labels = array(
        'name'				=> 	__( 'News' ),
        'add_new_item' 		=>	__( 'Add News Item' ),
		'edit_item' 		=>	__( 'Edit News Item' ),
		'new_item' 			=>	__( 'New Post' ),
		'view_item' 		=>	__( 'View News Item' ),
		'search_items' 		=>	__('Search News Items'),
		'not_found' 		=>	__('No News Items Found'),
		'not_found_in_trash' => __('No News Found In Trash')  
      );
	$args = array(
		'labels' => $labels,
		'description' => 'News Items for the City of Philadelphia',
		'rewrite' => array('slug' => 'news'),
		'public' => true,
		'has_archive' => true,
		'menu_position' => 20,
		'menu_icon' => 'dashicons-rss',
		//'capability_type' => 'news', not sure about these
		//'capabilities' => '',
		'supports' => array('title','editor','revisions','thumbnail', 'excerpt'),//consider use of custom fields for end date capability 
		'taxonomies' => array('category','post_tag'), //TO DO - add audience once I know it's good
		'has_archive'=> 'true'
			//TO DO see if we need to change slug type
  );	
	register_post_type( 'phila_news', $args );	
}

?>