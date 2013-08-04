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
abstract class IRMSessionHelper
{
	/*
	 * Method to get access the XiveIRMSystem session
	 *
	 * @param     object    $values    If set, only a single value is returned.
	 *
	 * @return    mixed                 Return an object with all values from the session or if a value is set, only the value
	 */
	public function setValues($values = false)
	{
		// Get the session object
		$session = JFactory::getSession();

		// Check for an existing session object
		if ( !$session->get('XiveIRMSystem') ) {
			$sessionHelper = new JObject;
		} else {
			$sessionHelper = $session->get('XiveIRMSystem');
		}

		if ( $values ) {
			// Inject the values into the sessionHelper object
			foreach ($values as $key => $value) {
				$sessionHelper->$key = $value;
			}
		} else {
			return false;
		}

		// Pull back into the session
		$session->set('XiveIRMSystem', $sessionHelper);

		return true;
	}

	/*
	 * Method to get access the XiveIRMSystem session
	 *
	 * @param     string    If set, only a single value is returned.
	 *
	 * @return    mixed     Return an object with all values from the session or if a value is set, only the value
	 */
	public function getValues()
	{
		// Init core vars
		$user = JFactory::getUser();

		// Check if we have a logged in user and a valid user id to check if we have a valid session or not
		if( $user->id != 0  && (int) $user->id )
		{
			$result = JFactory::getSession()->get('XiveIRMSystem');

			if( !$result ) {
				return false;
			}

			return $result;
		}
	}

	/*
	 * Method to initialise the XiveIRMSystem session
	 *
	 * Every App, have access to the XiveIRM System session to get the client_id from the user and the id of the user group (global_client_id).
	 * If the Client ability is disabled, the system uses the global client_id as set in the component options
	 *
	 * @return    mixed     Return an object with all values from the session or if a value is set, only the value
	 * 
	 * @since   3.4
	 */
	public function init()
	{
		// Self check
		if ( self::getValues() ) {
			return false;
		}

		// Init core vars
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$db = JFactory::getDBO();

		// Get user profile data and store in $clientObject
		$query = $db->getQuery(true);

		// Set the vars based on the user object
		$values = array(
			'userId'      => $user->id,
			'username'    => $user->username,
			'name'        => $user->name,
			'email'       => $user->email,
		);

		$query
			->select(array('profile_key', 'profile_value'))
			->from('#__user_profiles')
			->where('profile_key LIKE \'xiveirmclientprofile.%\'')
			->where('user_id = ' . $user->id . '');

		$db->setQuery($query);
		$results = $db->loadObjectList();

		// Set the vars based on the user profile object
		foreach($results as $result) {
			$key = str_replace('xiveirmclientprofile.', '', $result->profile_key);
			$values[$key] = json_decode($result->profile_value, true);
		}

		// Check if we have a client_id from the users profile, if not, then use the global client_id as client_id for the user from the component
		if( !isset($values['client_id']) ) {
			$values['client_id'] = IRMComponentHelper::getGlobalClientId();
		}

		// Check if user want to get global list options. If yes, store the global_client_id, else unset this option
		if( isset($values['show_globals']) && $values['show_globals'] == 1 ) {
			$values['global_client_id'] = IRMComponentHelper::getGlobalClientId();
			unset($values['show_globals']);
		} else {
			unset($values['show_globals']);
		}

		// Override informations if we're in admin
		if ($app->isAdmin()) {
			// If we are in admin area we have to check if the logged in user is in the group of the given minimum user group (future version)
			$values['client_id'] = IRMComponentHelper::getGlobalClientId();
			$values['jobtitle'] = 'System Administrator';
		}

		// Check if we have the minimum required fields and push the final object to session. If not, close the $app with an error message!
		if ( isset($values['id']) && isset($values['client_id']) ) {
			self::setValues($values);
		} else {
			if ( !$app->isAdmin() && $user->id > 0 ) {
				self::error();
			}
		}
	}

	/*
	 *
	 */
	public function error()
	{
		// Get the session object
		$session = JFactory::getSession();

		JError::raiseError( 403, 'You\'re not authorized to work with this system at present. Please check back again later or feel free to contact the support!' );
		$session->destroy();
		JFactory::getApplication()->close();
	}
}