<?php
/**
 * @version     3.3.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * @param	array	A named array
 * @return	array
 */
function XiveirmBuildRoute(&$query)
{
	$segments = array();
    
	if (isset($query['view'])) {
		if (empty($query['Itemid'])) {
			$segments[] = $query['view'];
		}
		else {
			$menu = &JSite::getMenu();
			$menuItem = &$menu->getItem($query['Itemid']);

			if (!isset($menuItem->query['view']) || $menuItem->query['view'] != $query['view']) {
				$segments[] = $query['view'];
			}
		}
		unset($query['view']);
	}

	if (isset($query['task'])) {
		switch ($query['task']) {
			case 'irmcustomerform.edit':
				$segments[] = 'edit';
				$segments[] = $query['id'];
				unset($query['id']);
				break;
			case 'irmcustomerform.cancel':
				$segments[] = 'cancel';
				$segments[] = $query['id'];
				unset($query['id']);
				break;
			case 'api.cancel':
				$segments[] = 'cancel';
				$segments[] = $query['id'];
				unset($query['id']);
				break;
			case 'irmcustomer':
				$segments[] = 'show';
//				$segments[] = $query['id'];
				unset($query['id']);
				break;
			default:
				$segments[] = implode('/',explode('.',$query['task']));
				$segments[] = $query['id'];
				unset($query['id']);
				break;
		}
		unset($query['task']);
	}
	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/xiveirm/task/id/Itemid
 *
 * index.php?/xiveirm/id/Itemid
 */
function XiveirmParseRoute($segments)
{
	$vars = array();
    
	// view is always the first element of the array
	$count = count($segments);

	switch ($segments[0]) {
		case 'edit':
			$vars['view'] = 'irmcustomerform';
			$vars['id'] = $segments[$count - 1];
			break;
		case 'cancel':
			$vars['task'] = 'api.cancel';
			$vars['id'] = $segments[$count - 1];
			break;
		case 'show':
			$vars['view'] = 'irmcustomer';
			$vars['id'] = $segments[$count - 1];
			break;
		default:
			$vars['task'] = $segments[0] . '.' . $segments[1];
			$vars['id'] = $segments[$count - 1];
			break;
	}
	return $vars;
}