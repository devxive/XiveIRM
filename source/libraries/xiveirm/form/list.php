<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	XiveIRM - Interoperable Relationship Management System
 *
 * @package		XiveIRM
 * @subPackage	Library
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		3.2
 */

defined('_NFW_FRAMEWORK') or die();

/**
 * Component helper class
 */
class IRMFormList
{
	/*
	 * Method to build a select list for the main category options
	 *
	 * @param     string    $app       The name of the component as stored in components folder (com_mycomponent)
	 *
	 * @return    html                 This will return a ready to use list for usage within the XiveIRM forms
	 */
	public static function getCategoryOptions($app)
	{
		// Get the child categories
		$childs = IRMFormHelper::getChildCategories($app);

		$list = array();

		foreach ( $childs as $child ) {
			$actions = IRMAccessHelper::getCategoryActions($child->id);
			if ( $actions->get('core.view') ) {
				$list[$child->id] = JText::_($child->title);
			}
		}

		return $list;
	}
}