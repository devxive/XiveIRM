<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xapptheme_sidebarshortcuts
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$icon1 = array('icon' => $params->get('icon1_icon'), 'color' => $params->get('icon1_color'), 'title' => $params->get('icon1_title'), 'link' => $params->get('icon1_link'));
$icon2 = array('icon' => $params->get('icon2_icon'), 'color' => $params->get('icon2_color'), 'title' => $params->get('icon2_title'), 'link' => $params->get('icon2_link'));
$icon3 = array('icon' => $params->get('icon3_icon'), 'color' => $params->get('icon3_color'), 'title' => $params->get('icon3_title'), 'link' => $params->get('icon3_link'));
$icon4 = array('icon' => $params->get('icon4_icon'), 'color' => $params->get('icon4_color'), 'title' => $params->get('icon4_title'), 'link' => $params->get('icon4_link'));
?>
<div id="sidebar-shortcuts" class="custom<?php echo $moduleclass_sfx; ?>">
	<div id="sidebar-shortcuts-large">
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $icon1['link']); ?>" class="btn btn-small <?php echo $icon1['color']; ?>" data-rel="tooltip" data-placement="bottom" data-original-title="<?php echo $icon1['title']; ?>">
			<i class="<?php echo $icon1['icon']; ?>"></i>
		</a>
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $icon2['link']); ?>" class="btn btn-small <?php echo $icon2['color']; ?>" data-rel="tooltip" data-placement="bottom" data-original-title="<?php echo $icon2['title']; ?>">
			<i class="<?php echo $icon2['icon']; ?>"></i>
		</a>
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $icon3['link']); ?>" class="btn btn-small <?php echo $icon3['color']; ?>" data-rel="tooltip" data-placement="bottom" data-original-title="<?php echo $icon3['title']; ?>">
			<i class="<?php echo $icon3['icon']; ?>"></i>
		</a>
		<a href="<?php echo JRoute::_('index.php?Itemid=' . $icon4['link']); ?>" class="btn btn-small <?php echo $icon4['color']; ?>" data-rel="tooltip" data-placement="bottom" data-original-title="<?php echo $icon4['title']; ?>">
			<i class="<?php echo $icon4['icon']; ?>"></i>
		</a>
	</div>
	<div id="sidebar-shortcuts-mini">
		<span class="btn btn-success"></span>
		<span class="btn btn-info"></span>
		<span class="btn btn-warning"></span>
		<span class="btn btn-danger"></span>
	</div>
</div>