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


if (empty($widget->menu_items)) {
	// there are no menu items - silent abort
	return;
}


// load libraries

if ($widget->load_libraries(basename(__FILE__))) {
	$layout_dir = dirname(__FILE__);

	if ($widget->load_jquery) {
		echo
'<script type="text/javascript" src="' . plugins_url('/js/jquery-2.1.0.min.js', $layout_dir) . '"></script>
';
	}

	echo
'<script type="text/javascript" src="' . plugins_url('/js/touchmenu.js', $layout_dir) . '"></script>
';

	if ($widget->load_css) {
		echo
'<link href="' . plugins_url('/widget_layouts/default.css', $layout_dir) . '" rel="stylesheet" type="text/css" />
';
	}
}


// show module

$widget->view_id(true);

echo '
<div id="wdg_touchmenu_' . $widget->view_id() . '" class="wdg_touchmenu wdg_touchmenu_' . $widget->view_id() . '">
';

$idx = -1;
foreach ($widget->menu_items as $menu_item) {
	$idx++;
	echo '
	<div class="wdg_touchmenu_header wdg_touchmenu_' . $widget->view_id() . '_header_' . $idx . '">' . htmlspecialchars($menu_item['header_title']) . '</div>
';
}

echo '
	<noscript>Error: JavaScript is not enabled in your browser - Touch Menu is unable to run properly</noscript>
</div>
';

$idx = -1;
foreach ($widget->menu_items as $menu_item) {
	$idx++;
	echo '
<div class="wdg_touchmenu_content wdg_touchmenu_' . $widget->view_id() . '_content_' . $idx . '" style="display:none">' . $menu_item['content'] . '</div>
';
}

echo '
<script type="text/javascript">
document.wdg_touchmenu_db.push({
	view_id: ' . $widget->view_id() . ',
	offset_left: ' . $widget->offset_left . ',
	offset_top: ' . $widget->offset_top . ',
	content_z_index: "' . $widget->json_esc($widget->content_z_index) . '",
	css_class_header_selected: "' . $widget->json_esc($widget->css_class_header_selected) . '"
});
</script>
';

?>