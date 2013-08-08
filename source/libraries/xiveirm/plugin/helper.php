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
abstract class IRMPluginHelper
{
	/*
	 * Method to get a list of avialable plugins for the related app
	 *
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
}