<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

// Import HTML and Helper Classes
nimport('NItem.Helper', false);
nimport('NUser.Access', false);

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

		$data = $this->app->input->get('irmapi', array(), 'array');
		$return = NItemHelper::checkOut('xiveirm_' . $data['coreapp'], $data['id'], NItemHelper::getDate('MySQL'), JFactory::getUser()->id);

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

		$data = $this->app->input->get('irmapi', array(), 'array');
		$return = NItemHelper::checkIn('xiveirm_' . $data['coreapp'], $data['id']);

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

//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

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
		// Get the dataAPI fields to check what app to process further
		$dataAPI = $this->app->input->get('irmapi', array(), 'array');
		if( $dataAPI && isset($dataAPI['coreapp']) && isset($dataAPI['component']) ) {
			$coreApp = $dataAPI['coreapp'];
			$coreComponent = $dataAPI['component'];

			$dataCore = $this->app->input->get($coreApp, array(), 'array');

			if(isset($dataCore['catid']) && isset($dataCore['id'])) {
				$catId = $dataCore['catid'];
				$coreId = (int) $dataCore['id'];
				if($coreApp != 'contacts') {
					if(isset($dataCore['contact_id'])) {
						$contactId = (int) $dataCore['contact_id'];
					} else {
						self::closeOnError(999, 'Controller: Get no contact_id');
					}
				}
			} else {
				self::closeOnError(999, 'Controller: Get either no catid or id');
			}
		} else {
			self::closeOnError(999, 'Controller: Get no Coreapp ID');
		}

		// Build the data object
		$data = new stdClass;
		$data->core = $dataCore;
		$data->api = $dataAPI;

		// Set some vars -- Strip the last letter from $coreApp to get the correct string within the tabs/widgets and table which we're store in
		$data->api['tablenamewithoutprefix'] = 'xiveirm_' . $coreApp;
		$data->api['coretable'] = '#__xiveirm_' . $coreApp;
		$data->api['tabapptable'] = '#__xiveirm_' . substr($coreApp, 0, -1) . '_tabappvalues';
		$data->api['tabapptableidname'] = substr($coreApp, 0, -1) . '_id';

		// Go to models and try to save the contacts datas.
		// In all cases (new, update), if return isn't false, we got the item row id for further processing the TabApp datas.
		// Check if the user have the rights to save the data for the contacts by checking the components ACL.
		$permissionsCore = NUserAccess::getPermissions($coreComponent, false, false, $data->api['tablenamewithoutprefix'] . '.' . $coreId);
		if( ($coreId == 0 && $permissionsCore->get('core.create')) || ($coreId > 0 && ($permissionsCore->get('core.edit') || $permissionsCore->get('core.edit.own'))) ) {
			$return = $model->savecore($data);
		} else {
			self::closeOnError(1100, 'Controller: Please Note: You have no rights edit or save the core datas. Please contact the support or your administrator to get further informations!');
		}

		/**
		 * Example of doing Permission Checks
		 * 
		 * $permissionsCore = NUserAccess::getPermissions('com_xiveirm');
		 * $permissionsTab = NUserAccess::getPermissions('com_xiveirm', 'tabapp', $tabApp->id);
		 * 
		 * $canView		= $this->user->authorise('core.view',		'com_xiveirm.tabapp.2');
		 * $canCreate		= $this->user->authorise('core.create',		'com_xiveirm');
		 * $canDelete		= $this->user->authorise('core.delete',		'com_xiveirm');
		 * $canEdit		= $this->user->authorise('core.edit',		'com_xiveirm');
		 * $canChange		= $this->user->authorise('core.edit.state',	'com_xiveirm');
		 * $canEditOwn	= $this->user->authorise('core.edit.own',		'com_xiveirm');
		 * 
		 */

		// Import Plugins (only those that are set / configured in the #__xiveirm_tabapps table. Category 0 and its related global_client-id (usergroup) always load, because its global (means for all)
		// There is an extra check in, where we check also against the client_id (component global usergroup and client related usergroup)
		// Because we use the returned values from the getPlugin, we have all informations on tabs that are loaded in the form.
		// Widgets do not have form values, therefore we do not load them! Even there is no need to load the event dispatcher! (((At this time!!!!)))
		// Pro for use the getPlugins Method: We'll get the tabconfig row id, we need for the permission checks below!!!!
		$plugins = IRMSystem::getPlugins($catId, $coreApp); // Array with all available plugins, that should load as set in the tabapp configuration!!!


		if($plugins) {
			// ok we have installed and enabled TabApps, lets play. Check first if we get a positive response and a valid contact id from the savecore method
			if( ($return["apiReturnCode"] == 'UPDATED' || $return["apiReturnCode"] == 'SAVED') && $return["apiReturnId"] > 0 && (int)$return["apiReturnId"] )
			{
				foreach($plugins as $tabApp)
				{
					// Check permissions based on the TabApp config with extra permission if the user can edit its own contact and related tabapps (we use the $coreId in the if core.create condition, to check if we have a new contact and the user is able to create.)
					// XiveTODO: We should check if it make sense to add a userid (created_by) column in the tabappvalue table
					$permissionsTab = NUserAccess::getPermissions($coreComponent, 'tabapp', $tabApp->id, $data->api['tablenamewithoutprefix'] . '.' . $return["apiReturnId"]);

					// Check permissions and save related data in the tabappvalue db table
					if( $permissionsTab->get('core.edit') || $permissionsTab->get('core.edit.own') ) {
				 		// Get the tabForm data.
						$dataTabs = JFactory::getApplication()->input->get($tabApp->plugin, array(), 'array');

						// Build new data object
						unset($data->core);
						unset($data->tab);
						$data->tab = $dataTabs;

						// Attempt to save the tabdata, if we got any, bound to the appropriate TabApp.
						if($data->tab) {
							$return = $model->savetab($data, $return["apiReturnId"], $tabApp->plugin); // Return goes directly to echo json_encode (after checkIn the coreId)
						}
					} else {
						$return_arr = array();
						$return_arr["apiReturnId"] = $return["apiReturnId"];
						$return_arr["apiReturnCode"] = 'NOTICE';
						$return_arr["apiReturnMessage"] = 'Controller: Please note that you have no rights to edit or save the <strong>' . $coreApp . ' (' . $tabApp->plugin . '-datas)</strong>! Please contact the support or your administrator to get further informations!';

						$return = $return_arr;
					}
				}
			} else {
				// Perform the return array. In this condition, the contacts returned no error but an id lover 0, or an correct id but an ERROR in the returnCode
				$return_arr = array();
				$return_arr["apiReturnId"] = 0;
				$return_arr["apiReturnCode"] = 1600;
				$return_arr["apiReturnMessage"] = 'Controller: Get an invalid row id from the savecore model process or we get an error in the model itself.';

				$return = $return_arr;
			}
		} else {
			// we have no apps, therefore we use the return code from the save/update process from core contacts table
//			echo 'Controller: No Plugins Load because there are no active';
		}

		// If all done, check in the core item
		NItemHelper::checkIn($data->api['tablenamewithoutprefix'], $coreId);

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
		$return = NItemHelper::checkIn('xiveirm_contacts', $id);

		$menu = & JSite::getMenu();
		$item = $menu->getActive();
		$this->setRedirect(JRoute::_($item->link, false));
	}

	/**
	 * Method to get a list for the jdatatable
	 *
	 * @since	4.4
	 */
	public function getlist()
	{
		/*
		 * NOTE: we do a different token check at the moment, because the old is not working at the moment.
		 */
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		if(!$sessionToken = $app->input->getInt(JFactory::getSession()->get('session.token'), null, 'array'))
		{
			return false;
		}
		

		$model = $this->getModel('Api', 'XiveirmModel');

		$cache_timeout = $this->input->getInt('cache_timeout', 0);
		if ($cache_timeout == 0)
		{
			$component = JComponentHelper::getComponent('com_xiveirm');
			$params = $component->params;
			$cache_timeout = $params->get('cachetimeout', 6, 'int');
			$cache_timeout = 3600 * $cache_timeout;
		}

		$return = $model->prepareList();

		echo json_encode($return);

		$this->app->close();
	}

//	/**
//	 * Method to check out an item for editing and redirect to the edit form.
//	 *
//	 * @since	1.6
//	 */
//	public function edit()
//	{
//		$app			= JFactory::getApplication();
//
//		// Get the previous edit id (if any) and the current edit id.
//		$previousId = (int) $app->getUserState('com_xiveirm.edit.api.id');
//		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');
//
//		// Set the user id for the user to edit in the session.
//		$app->setUserState('com_xiveirm.edit.api.id', $editId);
//
//		// Get the model.
//		$model = $this->getModel('Api', 'XiveirmModel');
//
//		// Check out the item
//		if ($editId) {
//            $model->checkout($editId);
//		}
//
//		// Check in the previous user.
//		if ($previousId) {
//            $model->checkin($previousId);
//		}
//
//		// Redirect to the edit screen.
//		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=apiform&layout=edit', false));
//	}
//
//	/**
//	 * Method to save a user's profile data.
//	 *
//	 * @return	void
//	 * @since	1.6
//	 */
//	public function save()
//	{
//		// Check for request forgeries.
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
//
//		// Initialise variables.
//		$app	= JFactory::getApplication();
//		$model = $this->getModel('Api', 'XiveirmModel');
//
//		// Get the user data.
//		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
//
//		// Validate the posted data.
//		$form = $model->getForm();
//		if (!$form) {
//			JError::raiseError(500, $model->getError());
//			return false;
//		}
//
//		// Validate the posted data.
//		$data = $model->validate($form, $data);
//
//		// Check for errors.
//		if ($data === false) {
//			// Get the validation messages.
//			$errors	= $model->getErrors();
//
//			// Push up to three validation messages out to the user.
//			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
//				if ($errors[$i] instanceof Exception) {
//					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
//				} else {
//					$app->enqueueMessage($errors[$i], 'warning');
//				}
//			}
//
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.api.data', JRequest::getVar('jform'),array());
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xiveirm.edit.api.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->save($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.api.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xiveirm.edit.api.id');
//			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
//			return false;
//		}
//
//            
//        // Check in the profile.
//        if ($return) {
//            $model->checkin($return);
//        }
//        
//        // Clear the profile id from the session.
//        $app->setUserState('com_xiveirm.edit.api.id', null);
//
//        // Redirect to the list screen.
//        $this->setMessage(JText::_('COM_XIVEIRM_ITEM_SAVED_SUCCESSFULLY'));
//        $menu = & JSite::getMenu();
//        $item = $menu->getActive();
//        $this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.api.data', null);
//	}
//    
//    
//    function cancel() {
//		$menu = & JSite::getMenu();
//        $item = $menu->getActive();
//        $this->setRedirect(JRoute::_($item->link, false));
//    }
//    
//	public function remove()
//	{
//		// Check for request forgeries.
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
//
//		// Initialise variables.
//		$app	= JFactory::getApplication();
//		$model = $this->getModel('Api', 'XiveirmModel');
//
//		// Get the user data.
//		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
//
//		// Validate the posted data.
//		$form = $model->getForm();
//		if (!$form) {
//			JError::raiseError(500, $model->getError());
//			return false;
//		}
//
//		// Validate the posted data.
//		$data = $model->validate($form, $data);
//
//		// Check for errors.
//		if ($data === false) {
//			// Get the validation messages.
//			$errors	= $model->getErrors();
//
//			// Push up to three validation messages out to the user.
//			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
//				if ($errors[$i] instanceof Exception) {
//					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
//				} else {
//					$app->enqueueMessage($errors[$i], 'warning');
//				}
//			}
//
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.api.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xiveirm.edit.api.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->delete($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.api.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xiveirm.edit.api.id');
//			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
//			return false;
//		}
//
//            
//        // Check in the profile.
//        if ($return) {
//            $model->checkin($return);
//        }
//        
//        // Clear the profile id from the session.
//        $app->setUserState('com_xiveirm.edit.api.id', null);
//
//        // Redirect to the list screen.
//        $this->setMessage(JText::_('COM_XIVEIRM_ITEM_DELETED_SUCCESSFULLY'));
//        $menu = & JSite::getMenu();
//        $item = $menu->getActive();
//        $this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.api.data', null);
//	}
//    
//    

	public function closeOnError($errorCode = 999, $errorMessage = 'ERROR', $errorId = 0) {
		$return_arr = array();
		$return_arr["apiReturnId"] = $errorId;
		$return_arr["apiReturnCode"] = $errorCode;
		$return_arr["apiReturnMessage"] = $errorMessage;

		$return = $return_arr;

		echo json_encode($return);

		$this->app->close();
	}

	public function test($ttt = '')
	{
		$return = $ttt . ' \n ' . json_encode($_POST);

		$file = 'test.txt';
		file_put_contents($file, $return);
		self::closeOnError('ERROR', 'ERROR');
	}


}