<?php
/**
 * @version     3.3.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Api controller class.
 */
class XiveirmControllerApi extends XiveirmController
{
	/**
	 * Checkout an item if the edit button is clicked and report message in JSON format, for AJAX requests
	 *
	 * @return void
	 *
	 * @since 3.1
	 */
	public function ajaxcheckout()
	{
	}

	/**
	 * Checkin an item if the edit button is clicked again and report message in JSON format, for AJAX requests
	 *
	 * @return void
	 *
	 * @since 3.1
	 */
	public function ajaxcheckin()
	{
	}

	/**
	 * Fetch form data push to model and report message in JSON format, for AJAX requests
	 *
	 * XiveIRM-TODO: Check the checked_out state before!
	 *	Description: 	If the item has a checked_out state: within the last 10 minutes, report to the customer, that he can't save the item, unless the item is checked in. The core app already give some notice about the item and the other user, who checked out the item
	 *			If the item has a checked_out state: over 10 minutes ad ago, give the customer the opportunity to check in (with the task=api.ajaxcheckin call) In this case the user itself take care of correct datas ;)
	 *
	 * @return void
	 *
	 * @since 3.1
	 */
	public function ajaxsave()
	{
		/*
		 * Note: we don't do a token check as we're fetching information
		 * asynchronously. This means that between requests the token might
		 * change, making it impossible for AJAX to work.
		 */

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Import plugins, set event dispatcher
		JPluginHelper::importPlugin( 'irmcustomertabs' ); // returned 1 if get successfully loaded
		$dispatcher = JDispatcher::getInstance();

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Api', 'XiveirmModel');

		$cache_timeout = $this->input->getInt('cache_timeout', 0);
		if ($cache_timeout == 0)
		{
			$component = JComponentHelper::getComponent('com_xiveirm');
			$params = $component->params;
			$cache_timeout = $params->get('cachetimeout', 6, 'int');
			$cache_timeout = 3600 * $cache_timeout;
		}

		// ------------------------------------------------------------- GET AND PROCESS THE CORE FORM DATAS
		$data = $app->input->get('coreform', array(), 'array');
		$return = $model->savecore($data); // If return isn't false, we got the item row id for further processing the tab datas

		if($return["apiReturnCode"] != 'ERROR' && $return["apiReturnId"] > 0)
		{
			// Check if we have tabApps and perform processing for each tabApp
			foreach($dispatcher->trigger( 'registerApp', array() ) as $tabApp)
			{
		 		// Get the tabForm data.
				$data = JFactory::getApplication()->input->get($tabApp, array(), 'array');
	
				// Attempt to save the tabdata.
				$return = $model->savetab($data, $return["apiReturnId"], $tabApp);
			}
		} else {
			return false; // We got no row id from customer table

			// Perform the return array
			$return_arr = array();
			$return_arr["apiReturnId"] = 0;
			$return_arr["apiReturnCode"] = ERROR;
			$return_arr["apiReturnMessage"] = 'Cant get a row id from the customer db table to perform tabApp save/create processing';

			$return = $return_arr;
		}

		echo json_encode($return);

//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.api.data', null);

		JFactory::getApplication()->close();
	}

	function ajaxcancel()
	{
	}

	public function ajaxremove()
	{
	}
}