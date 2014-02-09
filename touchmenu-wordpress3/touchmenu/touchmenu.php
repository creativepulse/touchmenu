<?php

/*
Plugin Name: Touch Menu
Plugin URI: http://www.creativepulse.gr/en/appstore/touchmenu
Version: 1.1
Description: Touch Menu shows menus compatible to both mouse and touch-screen devices
License: http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
Author: Creative Pulse
Author URI: http://www.creativepulse.gr
*/


class CpExt_TouchMenu extends WP_Widget {

	function __construct() {
		$options = array('classname' => 'CpExt_TouchMenu', 'description' => 'Sitex outputs simple text, HTML, JavaScript content');
		$this->WP_Widget('CpExt_TouchMenu', 'TouchMenu', $options);
	}

	function register_widget() {
		register_widget(get_class($this));
	}

	function widget($args, $instance) {
		$layout = (string) @$instance['layout'];
		if ($layout == '') {
			$layout = 'default';
		}

		require_once(dirname(__FILE__) . '/inc/widget.php');
		$widget = CpWidget_TouchMenu::get_model();
		$widget->prepare($instance);

		echo $args['before_widget'];
		echo empty($instance['title']) ? '' : $args['before_title'] . $instance['title'] . $args['after_title'] . "\n";

		$layout_filename = dirname(__FILE__) . '/widget_layouts/' . $layout . '.php';
		if (strpos($layout, "\0") !== false || strpos($layout, '..') !== false || strpos($layout, ':') !== false || strpos($layout, '/') !== false || strpos($layout, '\\') !== false) {
			echo __('Invalid layout value') . "\n";
		}
		else if (!file_exists($layout_filename)) {
			echo __('Layout script not found') . "\n";
		}
		else {
			require($layout_filename);
		}

		echo $args['after_widget'];
	}

	function update($new_instance, $old_instance) {
		$fields = array('title', 'source_category', 'load_css', 'load_jquery', 'offset_left', 'offset_top', 'content_z_index', 'css_class_header_selected');
		foreach ($fields as $field) {
			$old_instance[$field] = $new_instance[$field];
		}
		return $old_instance;
	}

	function form_list_pages($parent_id, $selected_id, $level) {
		foreach ($this->items_order as $id => $order) {
			if ($this->items[$id]['parent_id'] == $parent_id) {
				echo
'		<option value="' . $id . '"' . ($id == $selected_id ? ' selected="selected"' : '') . '>' . str_repeat('-- ', $level) . htmlspecialchars($this->items[$id]['header_title']) . '</option>
';

				$this->form_list_pages($id, $selected_id, $level + 1);
			}
		}
	}

	function form($instance) {
		$instance = wp_parse_args((array) $instance, array('title' => '', 'offset_left' => '', 'offset_top' => '', 'css_class_header_selected' => ''));

		echo
'<p><label for="' . $this->get_field_id('title') . '">' . __('Title') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . attribute_escape($instance['title']) . '" />
</p>

<p><label for="' . $this->get_field_id('source_category') . '">' . __('Menu source') . ':</label>
	<select class="widefat" id="' . $this->get_field_id('source_category') . '" name="' . $this->get_field_name('source_category') . '">
		<option value="0">[ ' . __('choose one') . ' ]</option>
';

		$this->items = array();
		$this->items_order = array();
		foreach (get_pages() as $rec) {
			$this->items[$rec->ID] = array(
				'parent_id' => intval(@$rec->post_parent),
				'header_title' => trim(@$rec->post_title)
			);

			$this->items_order[$rec->ID] = intval(@$rec->menu_order);
		}

		asort($this->items_order);

		$this->form_list_pages(0, intval(@$instance['source_category']), 0);

		echo
'	</select>
</p>

<p><input id="' . $this->get_field_id('load_css') . '" name="' . $this->get_field_name('load_css') . '" type="checkbox" ' . checked(!empty($instance['load_css'])) . ' />
	<label for="' . $this->get_field_id('load_css') . '">' . __('Load CSS') . '</label>
	&nbsp; &nbsp; &nbsp;
	<input id="' . $this->get_field_id('load_jquery') . '" name="' . $this->get_field_name('load_jquery') . '" type="checkbox" ' . checked(!empty($instance['load_jquery'])) . ' />
	<label for="' . $this->get_field_id('load_jquery') . '">' . __('Load jQuery') . '</label>
</p>

<p><label>' . __('Offset') . ':</label>
	&nbsp; &nbsp;
	<label for="' . $this->get_field_id('offset_left') . '">' . __('Left') . '</label>
	<input id="' . $this->get_field_id('offset_left') . '" name="' . $this->get_field_name('offset_left') . '" type="text" value="' . attribute_escape($instance['offset_left']) . '" size="6" />
	&nbsp; &nbsp;
	<label for="' . $this->get_field_id('offset_top') . '">' . __('Top') . '</label>
	<input id="' . $this->get_field_id('offset_top') . '" name="' . $this->get_field_name('offset_top') . '" type="text" value="' . attribute_escape($instance['offset_top']) . '" size="6" />
</p>

<p><label for="' . $this->get_field_id('content_z_index') . '">' . __('Z-Index') . '</label>
	<input id="' . $this->get_field_id('content_z_index') . '" name="' . $this->get_field_name('content_z_index') . '" type="text" value="' . attribute_escape($instance['content_z_index']) . '" size="6" />
</p>

<p><label for="' . $this->get_field_id('css_class_header_selected') . '">' . __('CSS class for selected header') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('css_class_header_selected') . '" name="' . $this->get_field_name('css_class_header_selected') . '" type="text" value="' . attribute_escape($instance['css_class_header_selected']) . '" />
</p>
';
	}

}

add_action('widgets_init', array('CpExt_TouchMenu', 'register_widget'));

?>