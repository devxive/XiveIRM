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
	var $app;
	var $model;
	var $user;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct()
	{
		// Initialise variables.
		$this->app = JFactory::getApplication();
		$this->model = $this->getModel('Api', 'XiveirmModel');
		$this->user = JFactory::getUser();

		parent::__construct();
	}

	/**
	 * Checkout an item if the edit button is clicked and report message in JSON format, for AJAX requests
	 *
	 * @return void
	 *
	 * @since 3.3
	 */
	public function ajaxcheckout()
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$data = $this->app->input->get('cica', array(), 'array');
		$return = NFactory::checkOut('xiveirm_customer', $data['id'], NFactory::getDate('MySQL'), JFactory::getUser()->id);

		if($return) {
			$return_arr = array();
			$return_arr["apiReturnId"] = $data['id'];
			$return_arr["apiReturnCode"] = 'TRUE';
		} else {
			$return_arr = array();
			$return_arr["apiReturnId"] = $data['id'];
			$return_arr["apiReturnCode"] = 1000;
			$return_arr["apiReturnMessage"] = 'An Error occured in the Nawala Framework API-Processor';
		}

		echo json_encode($return_arr);

		$this->app->close();
	}

	/**
	 * Checkin an item if the edit button is clicked again and report message in JSON format, for AJAX requests
	 *
	 * @return void
	 *
	 * @since 3.3
	 */
	public function ajaxcheckin()
	{
		/*
		 * Note: This is only an ajax checkin. The Checkin Processors are in the apropriate controllers
		 */

		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		$data = $this->app->input->get('cica', array(), 'array');
		$return = NFactory::checkIn('xiveirm_customer', $data['id']);

		if($return) {
			$return_arr = array();
			$return_arr["apiReturnId"] = $data['id'];
			$return_arr["apiReturnCode"] = TRUE;
		} else {
			$return_arr = array();
			$return_arr["apiReturnId"] = $data['id'];
			$return_arr["apiReturnCode"] = 1000;
			$return_arr["apiReturnMessage"] = 'An Error occured in the Nawala Framework API-Processor';
		}

		echo json_encode($return_arr);

		$this->app->close();
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

	$model = $this->getModel('Api', 'XiveirmModel');

		// Import plugins, set event dispatcher
		JPluginHelper::importPlugin( 'irmcustomertabs' ); // returned 1 if get successfully loaded
		$dispatcher = JDispatcher::getInstance();

		$cache_timeout = $this->input->getInt('cache_timeout', 0);
		if ($cache_timeout == 0)
		{
			$component = JComponentHelper::getComponent('com_xiveirm');
			$params = $component->params;
			$cache_timeout = $params->get('cachetimeout', 6, 'int');
			$cache_timeout = 3600 * $cache_timeout;
		}

		// ------------------------------------------------------------- GET AND PROCESS THE CORE FORM DATAS
		$data = $this->app->input->get('coreform', array(), 'array');
		$return = $model->savecore($data); // If return isn't false, we got the item row id for further processing the tab datas

		$customerItemId = $data['id'];

		if($return["apiReturnCode"] != 'ERROR' && $return["apiReturnId"] > 0)
		{
			// Check if we have tabApps and perform processing for each tabApp
			foreach($dispatcher->trigger( 'registerApp', array() ) as $tabApp)
			{
		 		// Get the tabForm data.
				$data = JFactory::getApplication()->input->get($tabApp, array(), 'array');
	
				// Attempt to save the tabdata.
				$return = $model->savetab($data, $return["apiReturnId"], $tabApp);

				// If all ok, check in the parent item
				NFactory::checkIn('xiveirm_customer', $customerItemId);

			}
		} else {
			// Perform the return array
			$return_arr = array();
			$return_arr["apiReturnId"] = 0;
			$return_arr["apiReturnCode"] = ERROR;
			$return_arr["apiReturnMessage"] = 'Cant get a row id from the customer db table to perform tabApp save/create processing';

			$return = $return_arr;
		}

		echo json_encode($return);

//		// Flush the data from the session.
//		$this->app->setUserState('com_xiveirm.edit.api.data', null);

		$this->app->close();
	}

	public function ajaxcancel()
	{
		/*
		 * Note: we don't do a token check as we're fetching information
		 * asynchronously. This means that between requests the token might
		 * change, making it impossible for AJAX to work.
		 */

//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));


		$model = $this->getModel('Api', 'XiveirmModel');

		// ------------------------------------------------------------- GET AND PROCESS THE CORE FORM DATAS
		$data = $this->app->input->get('cica', array(), 'array');

		// table_id, user_id
		$return = $model->checkin($data['id'], $this->user->id);

//		echo json_encode($return);

//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=irmcustomers', false));
	}

	public function ajaxremove()
	{
	}

	function cancel()
	{
		$id = JFactory::getApplication()->input->get('id', '', 'INT');
		$return = NFactory::checkIn('xiveirm_customer', $id);

		$menu = & JSite::getMenu();
		$item = $menu->getActive();
		$this->setRedirect(JRoute::_($item->link, false));
	}

}