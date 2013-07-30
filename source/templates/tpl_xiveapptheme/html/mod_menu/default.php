<?php
/**
 * @package		Joomla.Site
 * @subpackage	Templates.atomic
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
?>
<ul class="nav nav-list sidebar-<?php echo $params->get('class_sfx');?>"<?php
	$tag = '';
	if ($params->get('tag_id')!=NULL) {
		$tag = $params->get('tag_id').'';
		echo ' id="'.$tag.'"';
	}
?>>
<?php
foreach ($list as $i => &$item) :
	$id = '';
	if($item->id == $active_id)
	{
		$id = ' id="current"';
	}
	$class = '';
	if(in_array($item->id, $path))
	{
		// Changed the active style to match the Blueprint nav style
		$class .= 'active open ';
	}
	if($item->deeper) {
		$class .= 'parent ';
	}

	$class = ' class="'.$class.'item'.$item->id.'"';

	echo '<li'.$id.$class.'>';

	// Render the menu item.
	switch ($item->type) :
		case 'separator':
			require __DIR__ . '/default_'.$item->type . '.php';
			break;

		case 'heading':
			require __DIR__ . '/default_'.$item->type . '.php';
			break;

		case 'url':
			require __DIR__ . '/default_'.$item->type . '.php';
			break;

		case 'component':
			require __DIR__ . '/default_'.$item->type . '.php';
			break;

		default:
			require __DIR__ . '/default_url.php';
			break;

	endswitch;

	// The next item is deeper.
	if ($item->deeper) {
		echo '<ul class="submenu">';
	}
	// The next item is shallower.
	elseif ($item->shallower) {
		echo '</li>';
		echo str_repeat('</ul></li>', $item->level_diff);
	}
	// The next item is on the same level.
	else {
		echo '</li>';
	}
endforeach;
?></ul>