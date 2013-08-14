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
class IRMItemHelper
{
	/*
	 * Global Checkout Method to flag a contact
	 * return true if success, else return false
	 * @id		int		The db row id from the contact
	 */
	public function flagIt($id, $type = false)
	{
		if($id == 0 && (int) $id) {
			return false;
		}

		// Init database object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$dbTable = '#__xiveirm_flags';
		$item = 'contacts.' . $id;

		// Check if we have already a flag
		$query
			->select('flag')
			->from($db->quoteName($dbTable))
			->where('item = ' . $db->quote($item) . '');
		$db->setQuery($query);
		$result = $db->loadResult();

		// If we only check the flag
		if($type == 'check') {
			return $result;
		}

		if($result) {
		// DELETE FLAG
			$query = 'DELETE FROM #__xiveirm_flags WHERE item = '.$db->quote($item).'';
			$action = false;
		} else {
		// CREATE FLAG
			// Set the fields
			$columns = array('item', 'flag');
			$values = array($db->quote($item), $db->quote(1));

			$query
				->insert($db->quoteName($dbTable))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$action = true;
		}

		$db->setQuery($query);

		// Try to store or get the error code for debugging
		try
		{
			if(!$db->execute()) {
				throw new Exception($db->getErrorMsg());
			} else {
				// Get the contact and prepare the return
				$query = $db->getQuery(true);
				$query
					->select(array('company', 'last_name', 'first_name'))
					->from($db->quoteName('#__xiveirm_contacts'))
					->where('id = ' . $db->quote($id) . '');
				$db->setQuery($query);
				$result = $db->loadObject();
				$result->action = $action;

				return $result;
			}
		} catch (Exception $e) {
			JError::raiseError(500, $e->getMessage());
			return false;
		}
	}
}