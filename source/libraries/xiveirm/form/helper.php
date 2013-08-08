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
class IRMFormHelper
{
	/*
	 * Method to get a list of available child categories based on the parent id as set in component settings
	 *
	 * @param     string    $app           The name of the component as stored in components folder (com_mycomponent)
	 * @param     string    $showParent    If true, the parent item will be included in returned list
	 *
	 * @return    object                   This will return an object with categories which are plugins related to. Eg. for XiveContacts => Contact Types; XiveTranscorder => Transportation Types
	 */
	public static function getChildCategories($app, $showParent = false)
	{
		// Get the category as set in the component settings
		$parent = IRMComponentHelper::getConfig($app)->get('parent_app_category');

		// Get the child categories
		$categories = JTable::getInstance('Category');
		$childrens = $categories->getTree($parent);

		// Unset the parent item and reindex with array_values
		if ( !$showParent ) {
			unset($childrens[0]);
			array_values($childrens);
		}

		return $childrens;
	}


	/*
	 * Method to get an array of options. Used for select lists, radio and checkbox sets
	 * return array if success, else return false
	 * $table without prefix, category alias for the join left clause, client id (based on users usergroup and the xiveirm options where we declare which is the global group)
	 * the alias could also be a client_id id we want to get all contacts related to the client which is logged on. in this case alias have to be an integer
	 */
	public static function getListOptions($ext, $alias = null, $app = 'com_xiveirm')
	{
		// Create a new query object.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Get an array with authorized view levels
		$userId = (int) JFactory::getUser()->id;
		$viewlvlHelper = JAccess::getAuthorisedViewLevels($userId);

		// Get the component global viewlevel and inject in viewlvlArray to identify all categories with that view access level
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
			$extension = $app;

			// Prepare the query
			$query
				->select(array('id', 'title', 'access'))
				->from('#__categories')
				->where('extension = ' . $db->quote($extension) . '')
				->where('published = 1')
				->where('access IN (' . $viewlevels . ')')
				->where('parent_id = 423'); // ' . IRMComponentHelper($app) . '

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
				->select(array('a.id AS opt_id', 'a.opt_value', 'a.opt_name', 'a.access', 'b.id', 'b.alias'))
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

			// check for the gender clause (which is varchar, therefore we need thevalue itself) else the option id
			if($alias == 'gender') {
				$array_key = 'opt_value';
			} else {
				$array_key = 'opt_id';
			}

			foreach ($results as $result) {
				if($result->access != $globalAccessLvl) {
					$client[$result->$array_key] = $result->opt_name;
				} else {
					$global[$result->$array_key] = $result->opt_name;
				}
			}

			$superglobal->client = $client;
			$superglobal->global = $global;

			return $superglobal;
		} else if($ext == 'parents' && $alias != null && (int)$alias) {
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
		} else if($ext == 'contacts' && $alias != null && (int)$alias) {
			// Prepare the query
			$query
				->select(array('a.id', 'a.customer_id', 'a.last_name', 'a.first_name', 'a.company', 'a.catid', 'b.title', 'b.access'))
				->from('#__xiveirm_contacts AS a')
				->join('LEFT', '#__categories as b ON (a.catid = b.id)')
				->where('a.client_id = ' . $alias . '')
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
}