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
nimport('NUser.Access', false);

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
	public function getListOptions($ext, $alias = null, $app = 'com_xiveirm.contacts')
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


	/*
	 * Global Method to load all TabApps and Widgets related to the appropriate category and usergroup (new method to ignore viewing access levels!).
	 * 	Note: Global Categories (0) can be either the related to the global_client_id or the client_id
	 *	Note twice:	If we get a loadGroup string, this take no effect to the returned object!! The appropriated plugins are loaded, but the returned list is NOT the same!
	 *			This is ok, becuase we want to load the plugins, because we need them load for further processing.
	 *
	 * @return		Object		With informations from tabApp config and joined extensions (folder)
	 * 					id, appNames (plugin element name), folder to perform the NUserAccess::getPermissions(), catid, config (JSON)
	 */
	public function getPlugins($catid, $coreApp = null, $loadGroup = 'all', $client_id = false)
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

		$table = '#__xiveirm_tabapps';
		$appName = substr($coreApp, 0, -1);

		$widgets = 'irmwidgets' . $appName;
		$tabs = 'irmtabs' . $appName;
		

		$query
			->select(array('a.id', 'a.plugin', 'a.catid', 'a.config', 'b.folder'))
			->from('' . $table . ' AS a')
			->join('LEFT', '#__extensions as b ON (a.plugin = b.element)')
			->where('b.folder = ' . $db->quote($widgets) . ' || b.folder = ' . $db->quote($tabs) . '')
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

		if($loadGroup != 'all') {
			$reindexedResults = array();

			foreach($results as $key => $result) {
				if($result->folder == $loadGroup) {
					$reindexedResults[] = $result;
				}
			}

			return $reindexedResults;
		}

		// Return the results, we'll need to load the permissions based on the related assets/config // SEE NUserAccess::getPermissions
		// Based on this results we can get the permissions, we need to check for checkin/out, save, edit, create or view permissions.
		// NOTE: This is atypical to the normal viewing access levels and we need this for handle all client related stuff with this permissions/assets
		return $results;
	}

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
		$db = JFactory::getDBO();
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

	/**
	 * Add javascript function to copy the value of form fields pused via tabApp into the main form
	 *
	 * @param	array		$formFields     Common id to identify the input fields
	 *
	 * @return  html
	 *
	 * @since   5.0
	 */
	public function cloneTabFormFields($formFields = null, $tabKey = null)
	{
		// Check if we get formFields and the tabKey
		if(empty($formFields) || empty($tabKey)) {
			return false;
		}

		// Include JS framework
		NHtml::loadJsFramework();

		$return_html = '';
		$return_jsdec = "jQuery(document).ready(function() {";

		foreach($formFields as $formField => $placeholder) {
			// Build the html part
			$return_html .= '<input id="' . $tabKey . '[' . $formField . ']clone" type="text" class="input-control span6" placeholder="' . JText::_($placeholder) . '" />';

			// Build the JavaScript part
			$return_jsdec .=
					"
					var intval_" . $tabKey . "_" . $formField . " = $('[name=" . $tabKey . "\\\\[" . $formField . "\\\\]]').val();
					$('#" . $tabKey . "\\\\[" . $formField . "\\\\]clone').val(intval_" . $tabKey . "_" . $formField . ");

					$('#" . $tabKey . "\\\\[" . $formField . "\\\\]clone').keyup(function() {
						var val_" . $tabKey . "_" . $formField . "_clone = $(this).val();
						$('[name=" . $tabKey . "\\\\[" . $formField . "\\\\]]').val(val_" . $tabKey . "_" . $formField . "_clone);
					});
					$('[name=" . $tabKey . "\\\\[" . $formField . "\\\\]]').keyup(function() {
						var val_" . $tabKey . "_" . $formField . " = $(this).val();
						$('#" . $tabKey . "\\\\[" . $formField . "\\\\]clone').val(val_" . $tabKey . "_" . $formField . ");
					});
					";
		}

		// Close the .ready function and attach to the document
		$return_jsdec .= "});\n";
		JFactory::getDocument()->addScriptDeclaration($return_jsdec);

		// Return the html build
		return $return_html;
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