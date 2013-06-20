<?php
/**
 * @package     XiveIRM.Plugin
 * @subpackage  System.xiveirm
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

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

		// make sure the user is logged in to check if we have a session or not
		if($user != 0)
		{
			$session = JFactory::getSession();

			// Create the XiveIRM Session Array if no exist and store the required informations.
			if (!$session->get('XiveIRMSystem')) {
				$xiveIrmSystemObject = new stdClass;

				$app = JFactory::getApplication();

				if ($app->isSite()) {
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);

					$query
						->select(array('profile_key', 'profile_value'))
						->from('#__user_profiles')
						->where('profile_key LIKE \'xiveirmclientprofile.%\'')
						->where('user_id = '.$user.'');

					$db->setQuery($query);
					$results = $db->loadRowList();

					//Build an easy User Profile Array
					$newProfileArray = array();
					foreach($results as $result) {
						$newProfileArray[str_replace('xiveirmclientprofile.', '', $result[0])] = json_decode($result[1], true);
					}

					// Check if we have the required fields and store the final object for pushing to session. If not, close the $app with an error message!
					if (isset($newProfileArray['groupid']) && isset($newProfileArray['access'])) {
						$xiveIrmSystemObject->client_id = (int) $newProfileArray['groupid'];
						$xiveIrmSystemObject->access = (int) $newProfileArray['access'];

						// Try to store additional fields
						if (isset($newProfileArray['jobtitle'])) {
							$xiveIrmSystemObject->jobtitle = $newProfileArray['jobtitle'];
						}

						$session->set('XiveIRMSystem', $xiveIrmSystemObject);
					} else {
						JError::raiseError( 403, 'You\'re not authorized to work with this system at present. Please check back again later!' );
//						$session->destroy();
//						$app->close();
					}
				} else {
					$xiveIrmSystemObject->client_id = 0;
					$xiveIrmSystemObject->access = 1;
					$xiveIrmSystemObject->jobtitle = 'System Administrator';

					$session->set('XiveIRMSystem', $xiveIrmSystemObject);
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
