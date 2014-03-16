<?php

/**
 * Touch Menu
 *
 * @version 1.2
 * @author Creative Pulse
 * @copyright Creative Pulse 2013-2014
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


$instance = wp_parse_args((array) $instance, array('title' => '', 'layout' => '', 'offset_left' => '', 'offset_top' => '', 'css_class_header_selected' => ''));

$default_name = '';
$layouts = array();
$path = $dir . '/widget_layouts';
if ($dp = @opendir($path)) {
	while (false !== ($file = readdir($dp))) {
		if (preg_match('/^(.*)\.php$/', $file, $m)) {
			if (strtolower($m[1]) == 'default') {
				$default_name = $m[1];
			}
			else {
				$layouts[] = $m[1];
			}
		}
	}
	closedir($dp);

	sort($layouts);
}
else {
	echo __('Error: Unable to open widget layouts directory');
	return;
}

if ($default_name) {
	array_unshift($layouts, $default_name);
	if (empty($instance['layout'])) {
		$instance['layout'] = $default_name;
	}
}

echo
'<p><label for="' . $this->get_field_id('title') . '">' . __('Title') . ':</label>
	<input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . attribute_escape($instance['title']) . '" />
</p>

<p><label for="' . $this->get_field_id('source_category') . '">' . __('Menu source') . ':</label>
	<select class="widefat" id="' . $this->get_field_id('source_category') . '" name="' . $this->get_field_name('source_category') . '">
		<option value="0">[ ' . __('choose one') . ' ]</option>
';

global $cp_touchmenu_items, $cp_touchmenu_items_order;
$cp_touchmenu_items = array();
$cp_touchmenu_items_order = array();
foreach (get_pages() as $rec) {
	$cp_touchmenu_items[$rec->ID] = array(
		'parent_id' => intval(@$rec->post_parent),
		'header_title' => trim(@$rec->post_title)
	);

	$cp_touchmenu_items_order[$rec->ID] = intval(@$rec->menu_order);
}

asort($cp_touchmenu_items_order);

if (!function_exists('cp_touchmenu_form_list_pages')) {
	function cp_touchmenu_form_list_pages($parent_id, $selected_id, $level) {
		global $cp_touchmenu_items, $cp_touchmenu_items_order;

		foreach ($cp_touchmenu_items_order as $id => $order) {
			if ($cp_touchmenu_items[$id]['parent_id'] == $parent_id) {
				echo
'		<option value="' . $id . '"' . ($id == $selected_id ? ' selected="selected"' : '') . '>' . str_repeat('-- ', $level) . htmlspecialchars($cp_touchmenu_items[$id]['header_title']) . '</option>
';

				cp_touchmenu_form_list_pages($id, $selected_id, $level + 1);
			}
		}
	}
}
cp_touchmenu_form_list_pages(0, intval(@$instance['source_category']), 0);

unset($cp_touchmenu_items);
unset($cp_touchmenu_items_order);

echo
'	</select>
</p>

<p><label for="' . $this->get_field_id('layout') . '">' . __('Layout') . ':</label>
	<select class="widefat" id="' . $this->get_field_id('layout') . '" name="' . $this->get_field_name('layout') . '">
';

foreach ($layouts as $layout) {
	echo
'		<option value="' . htmlspecialchars($layout) . '"' . ($instance['layout'] == $layout ? ' selected="selected"' : '') . '>' . htmlspecialchars($layout) . '</option>
';
}

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

?>