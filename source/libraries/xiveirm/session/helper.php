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
class IRMSessionHelper
{
	/*
	 * Method to check if the XiveIRMSystem session exist
	 *
	 * @param     string     If set, only a single value is returned.
	 *
	 * @return    boolean    Return true if the ckeck is positive, else the check returns false
	 * TODO: Integrate checks to prevent session highjacking
	 */
	public function check()
	{
		// Init vars
		$session = JFactory::getSession()->get('XiveIRMSystem');
		$user = JFactory::getUser();

		if( $session ) {
			// Session exist, check now for a valid user_id in the session
			if ( $session->user_id > 0 ) {
				// There is a valid user_id, check now if the session user id is the same as the logged in user id
				if ( $user->id == $session->user_id ) {
					return true;
				} else {
					self::error('Potential Session Highjacking detected! Session closed!');
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
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
		// If check is false then return false
		if ( !self::check() ) {
			return false;
		}

		$result = JFactory::getSession()->get('XiveIRMSystem');

		return $result;
	}


	/*
	 * Method to save values in the XiveIRMSystem session
	 *
	 * @param     object    $values    If set, only a single value is returned.
	 *
	 * @return    mixed                 Return an object with all values from the session or if a value is set, only the value
	 */
	protected function setValues($values = false)
	{
		if ( !$values ) {
			self::error('Potential Session Highjacking detected! Could not initate session vars => Session closed!');
		}

		// Get the session object
		$session = JFactory::getSession();

		$sessionHelper = new JObject;

		// Build the object to store in session
		foreach ($values as $key => $value) {
			$sessionHelper->$key = $value;
		}

		$session->set('XiveIRMSystem', $sessionHelper);

		return true;
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
		// If check true, return false, because we have a valid session, else fwd to register app and user values
		if ( !self::check() ) {
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
			'user_id'     => $user->id,
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

		// Override informations if we're in admin
		if ($app->isAdmin()) {
			// If we are in admin area we have to check if the logged in user is in the group of the given minimum user group (future version)
			$values['client_id'] = 8;
			$values['jobtitle'] = 'System Administrator';
		}

		// Check if we have the minimum required fields and push the final object to session. If not, close the $app with an error message!
		if ( isset($values['user_id']) && isset($values['jobtitle']) ) {
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
	public function error($msg = false)
	{
		// Get the session object
		$session = JFactory::getSession();

		if ( $msg ) {
			JError::raiseError( 409, $msg );
		} else {
			JError::raiseError( 403, 'You\'re not authorized to work with this system at present. Please check back again later or feel free to contact the support!' );
		}

		$session->destroy();
		JFactory::getApplication()->close();
	}
}