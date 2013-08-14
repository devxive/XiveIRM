<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

// Import HTML and Helper Classes
// nimport('NUser.Access', false);

class IRMSystem
{
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
//		$query->select(array('tabapps.tab_key as tab_key', 'tabapps.tab_value AS tab_value'));
//		$query->join('LEFT', '#__xiveirm_contact_tabappvalues AS tabapps ON tabapps.contact_id = a.id');

		$db->setQuery($query);
		$coreResults = $db->loadObjectList();

		foreach($coreResults as $coreResult) {
			$results->contact = $coreResult;
		}

		// Create a new query object for the tabApps.
		$query = $db->getQuery(true);

		// Prepare the query
		$query
			->select(array('b.tab_key as tab_key', 'b.tab_value AS tab_value'))
			->from('#__xiveirm_contact_tabappvalues AS b')
			->where('b.contact_id = ' . $contactId . '');

		$db->setQuery($query);
		$tabResults = $db->loadObjectList();

		$tabsObject = new stdClass();
		foreach($tabResults as $tabResult) {
			$tabKey = $tabResult->tab_key;
			$tabsObject->$tabKey = json_decode($tabResult->tab_value);
		}

		$results->tabs = $tabsObject;

		return $results;
	}

}