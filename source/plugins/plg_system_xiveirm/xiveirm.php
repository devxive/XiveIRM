<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	Nawala Framework - A PHP and Javascript framework
 *
 * @package		XAP.plugin
 * @subPackage	System.xiveirm
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		1.0
 */

defined('_JEXEC') or die();

require_once JPATH_SITE.'/components/com_xiveirm/helpers/irmsystem.php';

/**
 * Do checks to get the right coice of everything. Stores essential things in the session if a user login!
 *
 * @package     XiveIRM.Plugin
 * @subpackage  System.System
 * @since       3.0
 */
class PlgSystemXiveIrm extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @access protected
	 * @param object $subject The object to observe
	 * @param array   $config  An array that holds the plugin configuration
	 * @since 1.0
	 */
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );

		// Do some extra initialisation in this constructor if required
	}

	/**
	 * Method to register the library.
	 *
	 * return  void
	 */
	public function onAfterInitialise()
	{
		JLoader::registerPrefix('IRM', JPATH_LIBRARIES . '/xiveirm');

		// Define version
		if (!defined('IRMVERSION')) {
			$irmversion = new IRMVersion();
			define('IRMVERSION', $irmversion->getShortVersion());
		}
	}

 	/**
	 * Every App, have access to the XiveIRM System session t get the client_id from the user.
	 * If the Client ability is disabled, the system uses the global client_id "0"
	 * 
	 * @since   3.4
	 */
	public function onAfterInitialise()
	{
		$user = JFactory::getUser()->id;

		// If we are in admin area we have to check if the logged in user is in the group of the given minimum user group (future version)
		// at this time, all users with admin access become the client_id = 0, access = 1 (Public) in the session

		// Check if we have a logged in user and a valid user id to check if we have a valid session or not
		if($user != 0 && (int) $user)
		{
			$session = JFactory::getSession();

			// Create the XiveIRM Session if no exist and store the required informations in the $clientObject.
			if (!$session->get('XiveIRMSystem')) {
				$clientObject = new JObject;

				$app = JFactory::getApplication();

				// Get and store user informations in $clientObject
				$userObject = JFactory::getUser($user);
				$clientObject->id = $userObject->id;
				$clientObject->username = $userObject->username;
				$clientObject->name = $userObject->name;
				$clientObject->email = $userObject->email;

				// Get user profile data and store in $clientObject
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);

				$query
					->select(array('profile_key', 'profile_value'))
					->from('#__user_profiles')
					->where('profile_key LIKE \'xiveirmclientprofile.%\'')
					->where('user_id = '.$user.'');

				$db->setQuery($query);
				$results = $db->loadObjectList();

				foreach($results as $result) {
					$key = str_replace('xiveirmclientprofile.', '', $result->profile_key);
					$clientObject->$key = json_decode($result->profile_value, true);
				}

				// Check if we have a client_id from the users profile, if not, then use the global client_id from the component
				if(!isset($clientObject->client_id)) {
					$clientObject->client_id = IRMSystem::getGlobalClientId();
				}

				// Check if user want to get global list options. If yes, store the global_client_id, else unset this option
				if(isset($clientObject->show_globals) && $clientObject->show_globals == 1) {
					$clientObject->global_client_id = IRMSystem::getGlobalClientId();
					unset($clientObject->show_globals);
				} else {
					unset($clientObject->show_globals);
				}

				// Override informations if we're in admin
				if ($app->isAdmin()) {
					$clientObject->client_id = IRMSystem::getGlobalClientId();
					$clientObject->jobtitle = 'System Administrator';

					$session->set('XiveIRMSystem', $clientObject);
				}

				// Check if we have the minimum required fields and push the final object to session. If not, close the $app with an error message!
				if (isset($clientObject->id) && isset($clientObject->client_id)) {
						$session->set('XiveIRMSystem', $clientObject);
				} else {
					JError::raiseError( 403, 'You\'re not authorized to work with this system at present. Please check back again later!' );
//					$session->destroy();
//					$app->close();
				}

			} else {
				// Session exist, all ok
			}
			// End of session exist check
		}
		// End of user check
	}
}








//			try
//			{
//				$db = JFactory::getDbo();
//
//				// Try to update existing profile fields. If nothing is there to update, we create a new entry
//				$order  = 1;
//
//				foreach ($data['xiveirmclientprofile'] as $k => $v)
//				{
//					$profile_key = $db->quote('xiveirmclientprofile.'.$k);
//
//					// Check if row (field) exist for that user
//					$db->setQuery('SELECT * FROM #__user_profiles WHERE user_id = '.$userId.' AND profile_key = '.$profile_key.'');
//					if (!$db->loadObjectList())
//					{
//						$query = $db->getQuery(true);
//						$columns = array('user_id', 'profile_key', 'profile_value', 'ordering');
//						$values = array($userId, $profile_key, $db->quote(json_encode($v)), $order++);
//						$query
//							->insert($db->quoteName('#__user_profiles'))
//							->columns($db->quoteName($columns))
//							->values(implode(', ', $values));
//						$db->setQuery($query);
//						$db->query();
//					} else {
//						$db->setQuery('UPDATE #__user_profiles SET profile_value = '.$db->quote(json_encode($v)).' WHERE user_id = '.$userId.' AND profile_key = '.$profile_key.'');
//						if (!$db->query())
//						{
//							throw new Exception($db->getErrorMsg());
//						}
//					}
//				}
//			}
//			catch (JException $e)
//			{
//				$this->_subject->setError($e->getMessage());
//				return false;
//			}
//		}
