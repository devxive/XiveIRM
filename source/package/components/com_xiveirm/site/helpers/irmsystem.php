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
	 * Method to get the global client id as set in the component settings
	 *
	 * @return		int	Return the global client_id as set in the component settings, else false.
	 */
	public function getGlobalClientId()
	{
		if($global_client_id = JComponentHelper::getParams('com_xiveirm')->get('global_group')) {
			return $global_client_id;
		} else {
			return false;
		}
	}

	/*
	 * Method to get access the XiveIRMSystem session
	 *
	 * @return		
	 */
	public function getSession($value = false)
	{
		if(!$value) {
			$result = JFactory::getSession()->get('XiveIRMSystem');
		} else {
			$result = JFactory::getSession()->get('XiveIRMSystem')->$value;
		}

		return $result;
	}

	/*
	 * 
	 * returns a prepared array
	 */
	public function getTabData($contact_id, $tab_key)
	{
		if(!$contact_id || !$tab_key)
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__xiveirm_contact_tabappvalues')
			->where('contact_id = ' . $db->quote($contact_id) . '')
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
	 * the alias could also be a client_id id we want to get all contacts related to the client which is logged on. in this case alias have to be an integer
	 */
	public function getListOptions($ext, $alias = null)
	{
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Get an array with authorized view levels
		$userId = (int) JFactory::getUser()->id;
		$viewlvlHelper = JAccess::getAuthorisedViewLevels($userId);

		// Get the component global viewlevel and inject in viewlvlArray to identify all categories with taht view access level
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
		if($ext == 'categories' && $alias == false) {
			// Prebuild the extension
			$extension = 'com_xiveirm.contacts';

			// Prepare the query
			$query
				->select(array('id', 'title', 'access'))
				->from('#__categories')
				->where('extension = ' . $db->quote($extension) . '')
				->where('published = 1')
				->where('access IN (' . $viewlevels . ')');

			$db->setQuery($query);
			$results = $db->loadObjectList();

			/* 
			 * We have to store all results in appropriate arrays and the arrays in an object
			 * so users/clients can set if they want only their own or all results.
			 * 
			 */
			$superglobal = new stdClass;
			$client = array(); // Based on currently user authorized view levels
			$global = array(); // Based on com_xiveirm settings

			foreach ($results as $result) {
				if($result->access != $globalAccessLvl) {
					$client[$result->id] = $result->title;
				} else {
					$global[$result->id] = $result->title;
				}
			}

			$superglobal->client = $client;
			$superglobal->global = $global;

			return $superglobal;
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
			$client = array(); // Based on currently user authorized view levels
			$global = array(); // Based on com_xiveirm settings

			foreach ($results as $result) {
				if($result->access != $globalAccessLvl) {
					$client[$result->opt_value] = $result->opt_name;
				} else {
					$global[$result->opt_value] = $result->opt_name;
				}
			}

			$superglobal->client = $client;
			$superglobal->global = $global;

			return $superglobal;
		} else if($ext == 'contacts' && $alias != null && (int)$alias) {
			// Prepare the query
			$query
				->select(array('a.id', 'a.customer_id', 'a.last_name', 'a.first_name', 'a.company', 'a.catid', 'b.title', 'b.access'))
				->from('#__xiveirm_contacts AS a')
				->join('LEFT', '#__categories as b ON (a.catid = b.id)')
				->where('a.client_id = ' . $alias . '')
			// Ungleich leerstring (wenn Feld schonmal angefasst)
				->where('a.company <> ""')
			// NOT NULL (wenn feld noch vollkommen unberührt)
				->where('a.company IS NOT NULL')
				->where('b.access IN (' . $viewlevels . ')');

				$db->setQuery($query);
				$results = $db->loadObjectList();

				/* 
				 * We have to store all results in appropriate arrays and the arrays in an object
				 * so users/clients can set if they want only their own or all results.
				 * 
				 */
				$superglobal = new stdClass;
				$contacts = array(); // Based on currently user authorized view levels in the contact category and client_id (alias usergroups)
				$contacts_categories = array();

				foreach ($results as $result) {
					$contacts_categories[$result->catid] = $result->title;

					$contacts[$result->catid][] = array(
						'id' => $result->id,
						'customer_id' => $result->customer_id,
						'last_name' => $result->last_name,
						'first_name' => $result->first_name,
						'company' => $result->company
					);
				}

				$superglobal->categories = $contacts_categories;
				$superglobal->contacts = $contacts;

				return $superglobal;
		} else {
			return JFactory::getApplication()->enqueueMessage('You have an error in your syntax', 'error');;
		}
	}


	/*
	 * Global Method to load all TabApps and Widgets related to the appropriate category and usergroup (new method to ignore viewing access levels!).
	 * 	Note: Global Categories (0) can be either the related to the global_client_id or the client_id
	 *	Note twice:	If we get a loadGroup string, this take no effect to the returned object!! The appropriated plugins are loaded, but the returned list is NOT the same!
	 *			This is ok, becuase we want to load the plugins, because we need them load for further processing.
	 *
	 * @return		Object		With informations from tabApp config and joined extensions (folder)
	 * 					id, appNames (plugin element name), folder to perform the NFactory::getPermissions(), catid, config (JSON)
	 */
	public function getPlugins($catid, $loadGroup = 'all', $client_id = false)
	{
		// Init checks
		if( !(int) $catid ) {
			return false;
		}

		// If we get no client_id, we use the client_id based on the current client session to perform our query
		if(!$client_id) {
			$client_id = self::getSession('client_id');
		}

		// Check if we could use a global_client_id, else use the client_id as global_client_id
		$global_client_id = self::getSession('global_client_id');
		if(!$global_client_id) {
			$global_client_id = $client_id;
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select(array('a.id', 'a.plugin', 'a.catid', 'a.config', 'b.folder'))
			->from('#__xiveirm_tabapps AS a')
			->join('LEFT', '#__extensions as b ON (a.plugin = b.element)')
			->where('b.enabled = 1 AND ((a.catid = 0 AND a.client_id IN (' . $db->quote($global_client_id) . ',' . $db->quote($client_id) . ')) OR (a.catid = ' . $db->quote($catid) . ' AND a.client_id = ' . $db->quote($client_id) . '))');

		$db->setQuery($query);

		$results = $db->loadObjectlist();

		// Load the corresponding TabApps and Widgets based on the $loadGroup
		if($loadGroup == 'all') {
			foreach($results as $result) {
				JPluginHelper::importPlugin( $result->folder, $result->plugin );
			}
		} else {
			foreach($results as $result) {
				JPluginHelper::importPlugin( $loadGroup, $result->plugin );
			}
		}

		// Return the results, we'll need to load the permissions based on the related assets/config // SEE NFactory::getPermissions
		// Based on this results we can get the permissions, we need to check for checkin/out, save, edit, create or view permissions.
		// NOTE: This is atypical to the normal viewing access levels and we need this for handle all client related stuff with this permissions/assets
		return $results;
	}
}