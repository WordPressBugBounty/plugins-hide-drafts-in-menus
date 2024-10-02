<?php
/*
Plugin Name: Hide Drafts in Menus
Plugin URI: http://room34.com
Description: Hide unpublished pages in your custom menus.
Version: 1.5.1
Author: Room 34 Creative Services, LLC
Author URI: http://room34.com
License: GPL2
Text Domain: r34hdm
*/

/*  Copyright 2023 Room 34 Creative Services, LLC (email: info@room34.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as 
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Don't load directly
if (!defined('ABSPATH')) { exit; }

// Get array of IDs
function r34hdm_get_item_ids($items) {
	// Get IDs from item list
	$ids = array();
	foreach ((array)$items as $key => $item) {
		// Skip objects that are not a post type
		if ($item->type !== 'post_type') { continue; }
		// Post IDs are stored in the object_id property of nav_menu_item posts
		$ids[$key] = intval($item->object_id);
	}
	return $ids;
}

// Get list of unpublished items
function r34hdm_get_unpublished_items($items) {
	global $wpdb, $r34hdm_results;
	
	// Get IDs from item list
	$ids = r34hdm_get_item_ids($items);
	if (empty($ids)) { return false; }
	$ids_imploded = implode(',', $ids);
	
	// Prevent duplicate queries!
	if (empty($r34hdm_results)) { $r34hdm_results = array(); }
	if (isset($r34hdm_results[$ids_imploded])) { return $r34hdm_results[$ids_imploded]; }
	
	// Find posts in IDs list that are not set to 'publish'
	$sql = "SELECT `ID`, `post_title`, `post_status` FROM `" . $wpdb->prefix . "posts` WHERE `post_status` != 'publish' AND `ID` IN (" . implode(',',$ids) . ")";
	$r34hdm_results[$ids_imploded] = $wpdb->get_results($sql);
	return $r34hdm_results[$ids_imploded];
}

// Exclude draft pages from navigation
function r34hdm_hide_drafts_in_menus($items, $menu, $args) {
	if (is_admin()) { return $items; }
	
	// Get IDs from item list
	$ids = r34hdm_get_item_ids($items);
	
	// Get list of unpublished items
	$unpublished_items = r34hdm_get_unpublished_items($items);
	
	// Remove unpublished items from list
	if (!empty($unpublished_items)) {
		foreach ((array)$unpublished_items as $unpublished) {
			$key = array_search($unpublished->ID, $ids);
			unset($items[$key]);
		}
	}

	// Reset array keys (starting at 1) for some uses that may depend on these keys
	// Based on: https://stackoverflow.com/a/591224
	if (!empty($items)) {
		$items = array_combine(range(1, count($items)), array_values($items));
	}

	return $items;
}
add_filter('wp_get_nav_menu_items', 'r34hdm_hide_drafts_in_menus', 10, 3);
add_filter('wp_nav_menu_objects', function($items, $args) {
	return r34hdm_hide_drafts_in_menus($items, null, $args);
}, 10, 2);

function r34hdm_flag_drafts_in_menu_admin() {
	if (!is_admin()) { return; }
	
	if (function_exists('get_current_screen') && $current_screen = get_current_screen()) {
		if ($current_screen->id == 'nav-menus') {
			if ($menus = get_terms('nav_menu')) {
				echo	'<!-- CSS for unpublished menu items added by Hide Drafts in Menus plugin. -->' . "\n" .
						'<style type="text/css">' . "\n" ;
				foreach ((array)$menus as $menu_obj) {
					// Get menu items
					if ($menu = wp_get_nav_menu_object($menu_obj->slug)) {
						$items = wp_get_nav_menu_items($menu->term_id, array('order' => 'DESC'));
					
						// Get IDs from item list
						$ids = r34hdm_get_item_ids($items);
	
						// Get list of unpublished items
						$unpublished_items = r34hdm_get_unpublished_items($items);
	
						// Apply CSS for appropriate items
						if (!empty($unpublished_items)) {
							foreach ((array)$unpublished_items as $unpublished) {
								$key = array_search($unpublished->ID, $ids);
								echo	'	#menu-item-' . $items[$key]->ID . ' .menu-item-handle { background: #fcf0f1; border-color: #d63638; opacity: 0.5; }' . "\n";
							}
						}
					}
				}
				echo '</style>' . "\n\n";
			}
		}
	}
}
add_action('admin_enqueue_scripts', 'r34hdm_flag_drafts_in_menu_admin');
