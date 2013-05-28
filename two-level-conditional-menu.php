<?php

/*
Plugin Name: Two Level Conditional Menu
Plugin URI: http://wordpress.org/extend/plugins/two-level-conditional-menu/
Description: Replaces two-level menu with a two-level conditional menu.
Version: 1.2
Author: Peter Massey-Plantinga
Author URI: http://massey-plantinga.com
License: GPL2
*/
function two_level_conditional_menu( $menu, $args ) {

	// Start output by removing the second-level menu
	$output = preg_replace( '/<ul class="[^"]*sub-menu[^"]*">.*?<\/ul>/s', '', $menu );

	// Remove endtag if exists
	$endtag = "</$args->container>";
	$endtag_exists = substr( $output, -strlen( $endtag ) ) == $endtag;
	if ( $endtag_exists )
		$output = substr( $output, 0, -strlen( $endtag ) );

	// Add second level menu
	global $post;
	global $wp_query;
	if ( $post ) { 
		/* This menu-gathering code is from wp_nav_menu */
		// Get the nav menu based on the requested menu
		$menu = wp_get_nav_menu_object( $args->menu );

		// Get the nav menu based on the theme_location
		if ( ! $menu && $args->theme_location && ( $locations = get_nav_menu_locations() ) && isset( $locations[ $args->theme_location ] ) ) 
			$menu = wp_get_nav_menu_object( $locations[ $args->theme_location ] );

		// get the first menu that has items if we still can't find a menu
		if ( ! $menu && !$args->theme_location ) { 
			$menus = wp_get_nav_menus();
			foreach ( $menus as $menu_maybe ) { 
				if ( $menu_items = wp_get_nav_menu_items( $menu_maybe->term_id, array( 'update_post_term_cache' => false ) ) ) { 
					$menu = $menu_maybe;
					break;
				}	 
			}	 
		}	 

		// If the menu exists, get its items.
		if ( $menu && ! is_wp_error( $menu ) && !isset( $menu_items ) ) 
			$menu_items = wp_get_nav_menu_items( $menu->term_id, array( 'update_post_term_cache' => false ) );

		$second_level = ""; 
		if ( ! empty( $menu_items ) ) 
		{	 
			// First, find parent id, if it exists
			$parent_id = -1; 
			$current_page_id = $wp_query->get_queried_object_ID();
			foreach ( $menu_items as $item ) {

				// Are we on the current page?
				if ( $item->object_id == $current_page_id ) {

					// Correctly set menu parent
					if ( $item->menu_item_parent == 0 ) {
						$parent_id = $item->ID;
					} else {
						$parent_id = $item->menu_item_parent;
					}
					break;
				}
			}

			// Now add menu items
			foreach ( $menu_items as $item ) {

				// Figure out if we're on the current page
				$class = '';
				if ( $item->object_id == $current_page_id )
					$class = ' class="current-menu-item"';

				// add menu item
				if ( $item->menu_item_parent == $parent_id )
					$second_level .= "<li$class><a href='$item->url'>$item->title</a></li>";
			}
		}

		if ( ! empty( $second_level ) )
			$output .= "<ul class='second-level menu'>$second_level</ul>";
	}

	// Re-add ending tag
	if ( $endtag_exists )
		$output .= $endtag;

	return $output;
}
add_filter( 'wp_nav_menu', 'two_level_conditional_menu', 10, 2 );

