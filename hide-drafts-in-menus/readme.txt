=== Hide Drafts in Menus ===
Contributors: room34
Donate link: http://room34.com/donation
Tags: menus, drafts, unpublished, pages
Requires at least: 4.0
Tested up to: 6.5.3
Stable tag: 1.5.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide unpublished pages in your custom menus.

== Description ==

If you unpublish a page or post, it still appears in your custom menus. This plugin automatically prevents pages/posts set to "draft" or "pending review" from appearing in custom menus on the site, while keeping their place in the menu structure. When they're published, they'll appear in the menu automatically.

This plugin also modifies the custom menu editing screen to clearly indicate the unpublished status of items in the menu.

_NOTE: We are aware of a limitation in the plugin, that a page/post must be published to appear in the list of items you can select to add to the menu. We are working on a solution. In the meantime, the workaround is to temporarily publish the page/post, add it to the menu, and then set it back to "draft"._

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. There's no step 3!

== Frequently Asked Questions ==

== Screenshots ==

== Changelog ==

= 1.5.1 - 2024.05.14 =

* Fixed an issue that would cause categories to be hidden. Added a check to ensure that the logic for hiding menu items only applies to items that are a post type (e.g. post, page, custom post type), not taxonomies or other types of objects.
* Changed CSS styling for hidden items on the admin Menus page to match the default WordPress styling for deleted items.
* Bumped "tested up to" to 6.5.3.

= 1.5.0 - 2023.11.01 =

* Changed SQL query to simply look for all posts that are *not* set to a status of `publish`, rather than the previous check for a few specific status values.
* Removed now-redundant post status text on menu editing screen (WordPress now displays the status rather than the post type for non-published menu items) and changed visual appearance to a light red background and partial opacity.

= 1.4.1 - 2023.07.23 =

* Fixed bug that prevented draft page CSS from being applied for menus that are not assigned to a theme menu location.
* Updated "tested up to" to 6.3.

= 1.4.0 =

* Added global `$r34hdm_results` variable to eliminate duplicate queries, resulting in a slight performance increase.
* Updated "tested up to" to 5.8.1.

= 1.3.1 =
* Fixed bug that would return a PHP warning if the menu is empty. In some cases this may have been breaking the page preview in the Customizer.
* Updated "tested up to" to 5.3.

= 1.3.0 =
* Added rekey array of menu items after removing hidden items.
* Added `wp_nav_menu_objects` action.

= 1.2.1 =
* Added 'future' status to pages hidden in menus.

= 1.2.0.1 =
* Fixed database error message that occurred when there were no unpublished items.

= 1.2.0 =
* Added 'trash' status to pages hidden in menus.

= 1.1.0 =
* Fixed issues that would trigger PHP notices on **Appearance > Menus** page.

= 1.0.0.2 =
* Tested with WordPress 4.9.5.

= 1.0.0.1 =
* Updated "Tested up to" to 4.7.
* Added note about pages needing to be published temporarily to appear in the list of options.

= 1.0.0 =
Initial release in WordPress Plugin Directory.
