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


if (!class_exists('CpWidget_TouchMenu')) {

	class CpWidget_TouchMenu {

		public static function get_model() {
			static $instance = null;
			if ($instance === null) {
				$instance = new CpWidget_TouchMenu();
			}
			return $instance;
		}

		public function view_id($new = false) {
			static $num = 0;

			if ($new) {
				$num++;
			}

			return $num;
		}

		public function load_libraries($name) {
			static $libraries = array();
			$result = !isset($libraries[$name]);
			$libraries[$name] = true;
			return $result;
		}

		public function json_esc($input, $esc_html = true) {
			$result = '';
			if (!is_string($input)) {
				$input = (string) $input;
			}

			$conv = array("\x08" => '\\b', "\t" => '\\t', "\n" => '\\n', "\f" => '\\f', "\r" => '\\r', '"' => '\\"', "'" => "\\'", '\\' => '\\\\');
			if ($esc_html) {
				$conv['<'] = '\\u003C';
				$conv['>'] = '\\u003E';
			}

			for ($i = 0, $len = strlen($input); $i < $len; $i++) {
				if (isset($conv[$input[$i]])) {
					$result .= $conv[$input[$i]];
				}
				else if ($input[$i] < ' ') {
					$result .= sprintf('\\u%04x', ord($input[$i]));
				}
				else {
					$result .= $input[$i];
				}
			}

			return $result;
		}

		private function cmp($a, $b) {
			if ($a['order'] == $b['order']) {
				return 0;
			}
			return $a['order'] > $b['order'] ? 1 : -1;
		}

		public function prepare($params) {
			$this->category_id = intval(@$params['source_category']);
			$this->load_css = !empty($params['load_css']);
			$this->load_jquery = !empty($params['load_jquery']);
			$this->offset_left = intval(@$params['offset_left']);
			$this->offset_top = intval(@$params['offset_top']);
			$this->content_z_index = trim(@$params['content_z_index']);
			$this->css_class_header_selected = trim(@$params['css_class_header_selected']);

			if ($this->category_id == 0) {
				// category not set - silent abort
				return;
			}

			$this->menu_items = array();
			foreach (get_pages() as $rec) {
				if ($rec->post_parent == $this->category_id) {
					$content = (string) @$rec->post_content;
					$content = apply_filters('the_content', $content);
					$content = str_replace(']]>', ']]&gt;', $content);

					$this->menu_items[] = array(
						'id' => $rec->ID,
						'header_title' => trim(@$rec->post_title),
						'order' => intval(@$rec->menu_order),
						'content' => $content
					);
				}
			}

			usort($this->menu_items, array(get_class($this), 'cmp'));

			if ($this->load_libraries('*')) {
				echo
'<script type="text/javascript">
document.wdg_touchmenu_db = [];
</script>
';
			}
		}
	}

}


// process instance

$layout = (string) @$instance['layout'];
if ($layout == '') {
	$layout = 'default';
}

$widget = CpWidget_TouchMenu::get_model();
$widget->prepare($instance);

echo $args['before_widget'];
echo empty($instance['title']) ? '' : $args['before_title'] . $instance['title'] . $args['after_title'] . "\n";

$layout_filename = $dir . '/widget_layouts/' . $layout . '.php';
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

?>