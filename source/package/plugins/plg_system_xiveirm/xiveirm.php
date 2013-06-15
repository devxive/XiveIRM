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

		// make sure the user is logged in
		if($user != 0) {
			$session = JFactory::getSession();

			// Create the XiveIRM Session Array if no exist and store the required informations.
			if (!$session->get('XiveIRMSystem')) {
				$xiveIrmSystemArray = array();

				if ($this->params->get('set-client-ability') == 0) {
					$xiveIrmSystemArray['client_id'] = 0;
				} else {
					$db = JFactory::getDBO();

		// GET INFORMATIONS FROM DB, WHAT CLIENT ID THE USER HAVE!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

					$client_id = '120700';
					$xiveIrmSystemArray['client_id'] = $client_id;
				}

				$session->set('XiveIRMSystem', $xiveIrmSystemArray);
			} else {
				// Try to get info why this could be an error!?
				return false;
			}
			// End of session exist check
		}
		// End of user check

//		$app = JFactory::getApplication();
	}
}