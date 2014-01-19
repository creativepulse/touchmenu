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


// prerequisite validation

defined('_JEXEC') or die('Restricted access');

if (!isset($mod) || empty($mod)) {
    echo JText::_('Module model not loaded');
    return;
}

if (version_compare($mod->version, '1.0') > 0) {
    echo JText::_('Module viewer is older that the model');
    return;
}

if (empty($mod->menu_items)) {
    // there are no menu items - silent abort
    return;
}


// load libraries

if (!$mod->libraries_loaded()) {
    $document = JFactory::getDocument();
    $document->addScript('modules/mod_touchmenu/js/touchmenu.js');
    $document->addScriptDeclaration('document.mod_touchmenu_db = [];');

    if ($mod->load_css) {
        $document->addStyleSheet('modules/mod_touchmenu/tmpl/default.css');
    }

    if ($mod->load_jquery) {
        $document->addScript('modules/mod_touchmenu/js/jquery-2.0.3.min.js');
    }

    $mod->libraries_loaded(true);
}


// show module

echo '
<div id="mod_touchmenu_' . $mod->instance_id() . '" class="mod_touchmenu mod_touchmenu_' . $mod->instance_id() . '">
';

$idx = -1;
foreach ($mod->menu_items as $menu_item) {
    $idx++;
    echo '
    <div class="mod_touchmenu_header mod_touchmenu_' . $mod->instance_id() . '_header_' . $idx . '">' . htmlspecialchars($menu_item->title) . '</div>
';
}

echo '
    <noscript>Error: JavaScript is not enabled in your browser - Touch Menu is unable to run properly</noscript>
</div>
';

$idx = -1;
foreach ($mod->menu_items as $menu_item) {
    $idx++;
    echo '
<div class="mod_touchmenu_content mod_touchmenu_' . $mod->instance_id() . '_content_' . $idx . '" style="display:none">' . $menu_item->introtext . $menu_item->fulltext . '</div>
';
}

echo '
<script type="text/javascript">
// <![CDATA[
document.mod_touchmenu_db.push({
    instance_id: ' . $mod->instance_id() . ',
    offset_left: ' . $mod->offset_left . ',
    offset_top: ' . $mod->offset_top . ',
    css_class_header_selected: "' . $mod->json_esc($mod->css_class_header_selected) . '"
});
// ]]>
</script>
';
