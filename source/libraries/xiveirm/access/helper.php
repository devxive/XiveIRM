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
abstract class IRMAccessHelper
{
	/*
	 * Method to get the global client id as set in the component settings
	 *
	 * @return		int	Return the global client_id as set in the component settings, else false.
	 */
	public function getACL()
	{
		// Init variables
		$aclObject = new stdClass();
		$session = IRMSessionHelper::getValues();

		// Check for active session
		if ( !$session ) {
			return false;
		}

		// Get the array of users view levels
		$usersViewLvl = JAccess::getAuthorisedViewLevels($session->user_id);

		// Sort, kick duplicates and reindex values
		sort($usersViewLvl);
		$viewLvls = array_unique($usersViewLvl);
		$viewLvls = array_values($viewLvls);

		// Set the viewlvls to the returned ACL object (access)
		$aclObject->access = $viewLvls;

		$cat = JAccess::getActions('com_xiveirm', 'plugin');

		return $cat;
	}


	/*
	 * Method to get the global client id as set in the component settings
	 *
	 * @param     int       $itemId    The item id, can be eiter a category id or a plugin id
	 *
	 * @return    void
	 */
	public function getCategoryActions($itemId = 0)
	{
		// Check for itemId
		if ( $itemId == 0 ) {
			return false;
		}

		$user   = JFactory::getUser();
		$result = new JObject();

		$assetName = 'com_xiveirm.category.' . (int) $itemId;

		$actions = JAccess::getActions('com_xiveirm', 'category');

		foreach( $actions as $action ) {
			$result->set( $action->name, $user->authorise($action->name, $assetName) );
		}

		return $result;
	}
}