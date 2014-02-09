=== Touch Menu ===
Contributors: Creative Pulse
Donate link: 
Tags: free, font, css, size, sidebar, wordpress, widget
Requires at least: 2.8.0
Tested up to: 3.8.0
Stable tag: 1.1
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Touch Menu shows menus compatible to both mouse and touch-screen devices

== Description ==

Touch Menu shows menus on a website compatible to regular browsers as well as touch screen devices (smartphones, tablets).

Touch Menu is MVC compatible. If you have enough experience with the computing language PHP you can write your own layout mechanism (viewer).

You can find more information at the plugin's homepage http://www.creativepulse.gr/en/appstore/touchmenu

== Installation ==

Use the regular installation procedure to install this plugin.

That is, go to Admin > Plugins > Add New > Upload.

Alternatively you can upload it into the `/wordpress/wp-content/plugins/` directory
and then activate it in the Admin control panel for plugins.

== Frequently Asked Questions == 

= How do I add input for my menu items? =

Create a page - not a post - with a title of your choice. The content of that page is irrelevant, we only need it as a category root. Then create more pages, one for each menu item you need. On your menu-item pages set as "Parent" the first page (or category page) you created on the start.

When you're done go to Appearance > Widgets, find the Touch Menu widget and use the drop-down box "Menu source" to set it to the category page.

= How do I change the order menu items are presented? =

Edit the menu-item pages and use the "Order" field to set the order you want.

= The panels with the menu content do not appear, or they appear under other parts of the website =

Edit the Touch Menu widget and set a number in the "Z-Index" field. Try 5, increase it in case it doesn't work.

== Screenshots == 

No screenshots at this moment

== Changelog ==

= 1.3 =
* Opening version for WordPress

== Upgrade Notice ==

You can simply overwrite the old files with the new ones.

No upgrade notice exists as this is the first version of the plugin.
