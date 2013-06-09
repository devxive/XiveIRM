<?php
/**
 * @version     3.1.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * @param	array	A named array
 * @return	array
 */
function MedtranscoordBuildRoute(&$query)
{
	$segments = array();
    
	if (isset($query['task'])) {
		$segments[] = implode('/',explode('.',$query['task']));
		unset($query['task']);
	}
	if (isset($query['id'])) {
		$segments[] = $query['id'];
		unset($query['id']);
	}

	return $segments;
}

/**
 * @param	array	A named array
 * @param	array
 *
 * Formats:
 *
 * index.php?/medtranscoord/task/id/Itemid
 *
 * index.php?/medtranscoord/id/Itemid
 */
function MedtranscoordParseRoute($segments)
{
	$vars = array();
    
	// view is always the first element of the array
	$count = count($segments);
    
    if ($count)
	{
		$count--;
		$segment = array_pop($segments) ;
		if (is_numeric($segment)) {
			$vars['id'] = $segment;
		}
        else{
            $count--;
            $vars['task'] = array_pop($segments) . '.' . $segment;
        }
	}

	if ($count)
	{   
        $vars['task'] = implode('.',$segments);
	}
	return $vars;
}
