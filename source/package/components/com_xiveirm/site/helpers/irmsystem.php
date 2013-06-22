<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

class IRMSystem
{
	/*
	 * 
	 * returns a prepared array
	 */
	public function getTabData($customer_cid, $tab_key)
	{
		if(!$customer_cid || !$tab_key)
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__xiveirm_customer_add')
			->where('customer_cid = ' . $db->quote($customer_cid) . '')
			->where('tab_key = ' . $db->quote($tab_key) . '');
		$db->setQuery($query);

		// Try to get the data or the error code for debugging
		try
		{
			$result = $db->loadObject();

			if($result) {
				$tab_value = json_decode($result->tab_value);
				$result->tab_value = $tab_value;
			} else {
				$result = new stdClass;
			}

			return $result;
		} catch (Exception $e) {
			$error = array();
			$error['code'] = (int)$e->getCode();
			$error['message'] = $e->getMessage();

			return $error;
		}
		
	}

	/*
	 * Method to get an array of options. Used for select lists, radio and checkbox sets
	 * return array if success, else return false
	 * $table without prefix, category alias for the join left clause, client id (based on users usergroup and the xiveirm options where we declare which is the global group)
	 */
	public function getListOptions($ext, $alias = null)
	{
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Get an array with authorized view levels
		$userId = (int) JFactory::getUser()->id;
		$viewlvlHelper = JAccess::getAuthorisedViewLevels($userId);

		// Get the component global viewlevel and inject in viewlvlArray
		$componentHelper = JComponentHelper::getParams('com_xiveirm');
		if($componentHelper) {
			$globalAccessLvl = $componentHelper->get('access');
			$viewlvlHelper[] = $globalAccessLvl;
		}

		// Sort and kick duplicate values
		sort($viewlvlHelper);
		$viewlvlArray = array_unique($viewlvlHelper);

		// Build the sql IN clause
		$viewlevels = implode(',', $viewlvlArray);

		// Make sure we select from supported rows
		if($ext == 'contacts' && $alias == null) {
			// Prebuild the extension
			$extension = 'com_xiveirm.' . $ext;

			// Prepare the query
			$query
				->select(array('id', 'title', 'access'))
				->from('#__categories')
				->where('extension = ' . $db->quote($extension) . '')
				->where('access IN (' . $viewlevels . ')');

				$db->setQuery($query);
				$results = $db->loadObjectList();

				/* 
				 * We have to store all results in appropriate arrays and the arrays in an object
				 * so users/clients can set if they want only their own or all results.
				 * 
				 */
				$superglobal = new stdClass;
				$clients = array(); // Based on currently user authorized view levels
				$global = array(); // Based on com_xiveirm settings

				foreach ($results as $result) {
					if($result->access != $globalAccessLvl) {
						$client[$result->id] = $result->title;
					} else {
						$global[$result->id] = $result->title;
					}
				}
		} else if($ext == 'options' && $alias != null) {
			// Prepare the query
			$query
				->select(array('a.opt_value', 'a.opt_name', 'a.access', 'b.id', 'b.alias'))
				->from('#__xiveirm_options AS a')
				->join('INNER', '#__categories as b ON (a.catid = b.id)')
				->where('b.alias = ' . $db->quote($alias) . '')
				->where('a.access IN (' . $viewlevels . ')');

				$db->setQuery($query);
				$results = $db->loadObjectList();

				/* 
				 * We have to store all results in appropriate arrays and the arrays in an object
				 * so users/clients can set if they want only their own or all results.
				 * 
				 */
				$superglobal = new stdClass;
				$clients = array(); // Based on currently user authorized view levels
				$global = array(); // Based on com_xiveirm settings

				foreach ($results as $result) {
					if($result->access != $globalAccessLvl) {
						$client[$result->opt_value] = $result->opt_name;
					} else {
						$global[$result->opt_value] = $result->opt_name;
					}
				}
		} else {
			return JFactory::getApplication()->enqueueMessage('You have an error in your syntax', 'error');;
		}

		$superglobal->client = $client;
		$superglobal->global = $global;

		return $superglobal;
	}
}