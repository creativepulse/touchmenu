<?php

/*
Plugin Name: Touch Menu
Plugin URI: http://www.creativepulse.gr/en/appstore/touchmenu
Version: 1.2
Description: Touch Menu shows menus compatible to both mouse and touch-screen devices
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Author: Creative Pulse
Author URI: http://www.creativepulse.gr
*/


class CpExt_TouchMenu extends WP_Widget {

	function __construct() {
		$options = array('classname' => 'CpExt_TouchMenu', 'description' => 'Touch Menu shows menus compatible to both mouse and touch-screen devices');
		$this->WP_Widget('CpExt_TouchMenu', 'TouchMenu', $options);
	}

	function register_widget() {
		register_widget(get_class($this));
	}

	function widget($args, $instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/widget.php');
	}

	function update($new_instance, $old_instance) {
		$fields = array('title', 'source_category', 'layout', 'load_css', 'load_jquery', 'offset_left', 'offset_top', 'content_z_index', 'css_class_header_selected');
		foreach ($fields as $field) {
			$old_instance[$field] = $new_instance[$field];
		}
		return $old_instance;
	}

	function form($instance) {
		$dir = dirname(__FILE__);
		require($dir . '/inc/form.php');
	}

}

add_action('widgets_init', array('CpExt_TouchMenu', 'register_widget'));

?>