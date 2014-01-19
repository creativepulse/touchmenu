<?php

/**
 * Touch Menu
 *
 * @version 1.0
 * @author Creative Pulse
 * @copyright Creative Pulse 2013
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link http://www.creativepulse.gr
 */


if (!class_exists('Mod_TouchMenu')) {

    class Mod_TouchMenu {

        public $version = '1.0';

        public $menu_items = array();
        public $category_id = 0;
        public $load_css = true;
        public $load_jquery = false;
        public $offset_left = 0;
        public $offset_top = 0;
        public $css_class_header_selected = '';

        public function instance_id($new = false) {
            static $num = -1;

            if ($new) {
                $num++;
            }

            return $num;
        }

        public function libraries_loaded($set = false) {
            static $result = false;

            if ($set === true) {
                $result = true;
            }

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

        public function prepare(&$params) {
            $this->instance_id(true);

            $this->category_id = intval($params->get('source_category'));
            $this->load_css = intval($params->get('load_css'));
            $this->load_jquery = intval($params->get('load_jquery'));
            $this->offset_left = intval($params->get('offset_left'));
            $this->offset_top = intval($params->get('offset_top'));
            $this->css_class_header_selected = trim($params->get('css_class_header_selected'));

            if ($this->category_id == 0) {
                // category not set - silent abort
                return;
            }

            $db = JFactory::getDBO();

            $db->setQuery(
"SELECT o.`title`, o.`introtext`, o.`fulltext`
FROM #__categories a, #__content o
WHERE a.`id` = {$this->category_id} AND a.`published` = 1 AND a.`id` = o.`catid` AND o.`state` = 1
ORDER BY o.`ordering`"
            );

            $res = $db->loadObjectList();

            if (!empty($res)) {
                $this->menu_items = $res;
            }
        }
    }

}
