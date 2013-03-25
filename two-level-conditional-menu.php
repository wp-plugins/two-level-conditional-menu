<?php

/*
Plugin Name: Two Level Conditional Menu
Plugin URI: http://wordpress.org/extend/plugins/two-level-conditional-menu/
Description: Replaces two-level menu with a two-level conditional menu.
Version: 1.0
Author: Peter Massey-Plantinga
Author URI: http://massey-plantinga.com
License: GPL2
*/

function two_level_nav_menu( $menu ) {
	$end_div = substr( $menu, -6 ) == '</div>';

	// Remove the second-level menus
	$menu = preg_replace( '/<ul class="[^"]*sub-menu[^"]*">.*?<\/ul>/s', '', $menu );

	// Trim ending </div>
	$output = preg_replace( '/<\/div>$/', '', $menu );

	// Add second level menu
	global $post;
	if ( $post ) {

		// Gather current menu items
		$locations = get_nav_menu_locations();
		$menu_items = wp_get_nav_menu_items( $locations[ 'primary' ] );

		$second_level = "";
		if ( ! empty( $menu_items ) ) {

			// First, find parent id, if it exists
			$parent_id = -1;
			foreach ( $menu_items as $item ) {
				if ( $item->object_id == $post->ID ) {
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
				if ( $item->object_id == $post->ID )
					$class = ' class="current-menu-item"';
				
				// add menu item
				if ( $item->menu_item_parent == $parent_id )
					$second_level .= "<li$class><a href='$item->url'>$item->title</a></li>";
			}
		}

		if ( ! empty( $second_level ) )
			$output .= "<ul class='second-level menu'>$second_level</ul>";
	}

	// Re-add ending div
	if ( $end_div )
		$output .= "</div>";

	return $output;
}
add_filter( 'wp_nav_menu', 'two_level_nav_menu' );

