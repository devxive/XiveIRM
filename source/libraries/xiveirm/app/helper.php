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
class IRMAppHelper
{
	/*
	 * Method to get all registered Apps ( as loaded in IRMAppHelper::loadApp() )
	 *
	 * @return    array    Array with all Apps that are registered on the appropriate site where method is called.
	 *                     Return false if nothing is registered
	 */
	public function getRegistered()
	{
		// Get the JDispatcher instance
		$dispatcher = JDispatcher::getInstance();

		// Get the registered apps by the IRMregisterApp event
		$registered = $dispatcher->trigger( 'IRMregisterApp' );

		if ( empty($registered) ) {
			$registered = false;
		}

		return $registered;
	}


	/*
	 * Method to get a list of plugins / apps for the related category, based and as set in the "XiveIRM - App Config"
	 * NOTE: WORKS ALREADY WITH THE NEW SITUATION THAT ONE USER COULD HAVE MORE THAN ONE CLIENT_ID (USERGROUPS)
	 *
	 * @param     string    $app      The app where the method is called (eg. com_xiveirm, com_xivetranscorder, etc...)
	 * @param     int       $catid    The category id to get the related plugins / apps
	 *
	 * @return    array               Return an array with appropriate plugins
	 */
	public function getPlugins($app, $catid)
	{
		if ( !$app || !$catid ) {
			return false;
		}

		$catIds = array();
		$catIds[$catid] = $catid;

		// Get all relevant category id's and build the sql prepared string
		$appCatId = IRMComponentHelper::getConfigValue($app, 'parent_app_category');
		if ( $appCatId > 0 ) {
			$catIds[$appCatId] = $appCatId;
		}

		// All Categories ( eg for system based stuff )
		$catIds[0] = 0;

		$catIdString = implode(',', $catIds);

		// Get usergroups as implode string ($implode = true) and injected usergroup as global (injectGroup = 0)
		$usergroups = NFWUserGroup::getParents( true, array(0 => 'System Global') );

		// Prepare database query
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Get active and configurated plugins, join extended with plugin folder from #__extensions
		// TODO: remove plugins from tabapps table, because we get it from extensions
		$query
			->select( array('a.id', 'a.client_id', 'a.plugin', 'a.catid', 'a.config', 'b.folder') )
			->from( '#__xiveirm_tabapps AS a' )
			->join( 'LEFT', '#__extensions as b ON (a.plugin = b.element)' )
			->where( 'b.enabled = 1' )
			->where( 'a.catid IN (' . $catIdString . ')' )
			->where( 'a.client_id IN (' . $usergroups . ')' );

		$db->setQuery($query);

		$results = $db->loadObjectlist();

		return $results;
	}


	/*
	 * Method to import plugins, based on the getPlugins method
	 * NOTE: WORKS ALREADY WITH THE NEW SITUATION THAT ONE USER COULD HAVE MORE THAN ONE CLIENT_ID (USERGROUPS)
	 *
	 * @param     string    $app      The app where the method is called (eg. com_xiveirm, com_xivetranscorder, etc...)
	 * @param     int       $catid    The category id to get the related plugins / apps
	 *
	 * @return    void
	 */
	public function importPlugins($app, $catid)
	{
		if ( !$app || !$catid ) {
			return false;
		}

		$plugins = self::getplugins($app, $catid);

		foreach( $plugins as $plugin ) {
			JPluginHelper::importPlugin( $plugin->folder, $plugin->plugin );
		}
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
}