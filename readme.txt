=== Two Level Conditional Menu ===
Contributors: pwp2
Tags: menu, navigation
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: Trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin converts a two-level menu to a conditional two-level menu.

== Description ==

A conditional two-level menu only shows the second level of the menu on relevant pages.

For example, if you had a menu that had a structure like so:

* Home
* About
 * History
 * People
* Contact
 * Mail
 * Phone
 * Email

You would only be able to see the "History" and "People" menu items on the "About," "History," and "People" pages.

== Installation ==

1. Upload `two-level-conditional-menu.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress

== Changelog ==

= 1.2 =
* Updated: menu can be anywhere, not just in 'primary' location.
* Updated: Any 'container' that wp_nav_menu accepts will work.
* Fixed bug: Any pages that had multiple posts couldn't have sub-menu items.

= 1.1 =
* Fixed bug: If the blog page was in the menu, sub-menu items weren't showing up.

= 1.0 =
