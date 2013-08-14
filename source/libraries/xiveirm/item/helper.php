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


	/*
	 * Method to get the contact and all related tabApps in a single object. Mostly used for other extensions, such as the XiveTransCorder App.
	 *
	 * @since 5.0
	 */
	public function getContactObject($contactId)
	{
		$results = new JObject();
		$db = JFactory::getDbo();

		// Create a new query object for the contact.
		$query = $db->getQuery(true);

		// Prepare the query
		$query
			->select('a.*')
			->from('#__xiveirm_contacts AS a')
			->where('a.id = ' . $contactId . '');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor_name');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the created by field 'created_by'
		$query->select('created_by.name AS creator_name');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the modified by field 'modified_by'
		$query->select('modified_by.name AS modified_name');
		$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

		// Join over the category 'catid'
		$query->select('catid.title AS catid_title');
		$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

		// Join over the category 'gender'
		$query->select('gender.title AS gender_title');
		$query->join('LEFT', '#__categories AS gender ON gender.id = a.gender');

		// Join over the flags
		$query->select('flags.flag AS flagged');
		$query->join('LEFT', '#__xiveirm_flags AS flags ON flags.item = CONCAT(\'contacts.\', a.id)');

		// Join over the tabapps
//		$query->select(array('tabapps.app_key as app_key', 'tabapps.app_value AS app_value'));
//		$query->join('LEFT', '#__xiveirm_contacts_appvalues AS tabapps ON tabapps.contacts_id = a.id');

		$db->setQuery($query);
		$coreResults = $db->loadObjectList();

		foreach($coreResults as $coreResult) {
			$results->contact = $coreResult;
		}

		// Create a new query object for the tabApps.
		$query = $db->getQuery(true);

		// Prepare the query
		$query
			->select(array('b.app_key as app_key', 'b.app_value AS app_value'))
			->from('#__xiveirm_contacts_appvalues AS b')
			->where('b.contacts_id = ' . $contactId . '');

		$db->setQuery($query);
		$tabResults = $db->loadObjectList();

		$tabsObject = new stdClass();
		foreach($tabResults as $tabResult) {
			$appKey = $tabResult->app_key;
			$tabsObject->$appKey = json_decode($tabResult->app_value);
		}

		$results->tabs = $tabsObject;

		return $results;
	}
}