<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

// Import HTML and Helper Classes
// nimport('NItem.Helper', false);
// nimport('NUser.Access', false);

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
		$result = NFWItemHelper::checkOut( 'xiveirm_' . $data['coreapp'], $data['id'], NFWItemHelper::getDate('MySQL'), NFWUser::getId() );

		$return = array();

		if($result) {
			$return["status"] = true;
			$return["id"] = $data['id'];
			$return["message"] = '';
		} else {
			$return["status"] = false;
			$return["id"] = $data['id'];
			$return["code"] = 1000;
			$return["message"] = 'An Error occured in the Nawala Framework API-Processor. Please contact the support!';
		}

		echo json_encode($return);

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
		$result = NFWItemHelper::checkIn('xiveirm_' . $data['coreapp'], $data['id']);

		$return = array();

		if($result) {
			$return["status"] = true;
			$return["id"] = $data['id'];
			$return["message"] = '';
		} else {
			$return["status"] = false;
			$return["id"] = $data['id'];
			$return["code"] = 1000;
			$return["message"] = 'An Error occured in the Nawala Framework API-Processor. Please contact the support!';
		}

		echo json_encode($return);

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
				$catId = (int) $dataCore['catid'];
				$coreId = (int) $dataCore['id'];
				// TODO: Could be removed if we add in the contacts app an empty field for that. We then only have to check if we have an contact_id. Since we use the new NFWDatabase::save() method, it only stores vars that fields exist in database!
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
		$data->api['coretable'] = 'xiveirm_' . $coreApp;
		$data->api['apptable'] = 'xiveirm_' . $coreApp . '_appvalues';
		$data->api['apptableidname'] = $coreApp . '_id';

		// Go to models and try to save the contacts datas.
		// In all cases (new, update), if return isn't false, we got the item row id for further processing the TabApp datas.

		// Get Permissions based on category id, else for component
		if ( !$catId ) {
			// We have no category id and use the components acl as set in the form
			$acl = NFWAccessHelper::getActions($coreComponent);
		} else {
			// We have a category id and use the category acl, since all categories are handled in com_xiveirm
			$acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $catId);
		}

		// If it is an existing item, get the created by id
		$createdBy = isset($dataCore['created_by']) ? $dataCore['created_by'] : 0;

		// Check if the user have the rights to save the data for current item. Note, we have to check against the created_by id because, we're setting the ACL for the contacts ONLY via the categories inherited by the component!
		// TODO: Check followed situation: If its a new contact -> close with error, because the user can't create a core app item. If it is an exising contact, and the user can't save this item, we have to jump to plugins, where we check if the user can save the app values ?!?! NOTE: We do also a check against the plugin assets
		if( ($coreId == 0 && $acl->get('core.create')) || ($coreId > 0 && $acl->get('core.edit')) || ($coreId > 0 && $acl->get('core.edit.own') && NFWUser::getId() == $createdBy) ) {
			$return = $model->savecore($data);
		} else {
			self::closeOnError(1100, 'Controller: Please Note: You have insufficient permissions to save this. Please contact the support or your administrator to get further informations!');
		}

		// Import Plugins (only those that are set / configured in the #__xiveirm_apps table. Category 0 and its related global_client-id (usergroup) always load, because its global (means for all)
		// There is an extra check in, where we check also against the client_id (component global usergroup and client related usergroup)
		// Because we use the returned values from the getPlugin, we have all informations on tabs that are loaded in the form.
		// Widgets do not have form values, therefore we do not load them! Even there is no need to load the event dispatcher! (((At this time!!!!)))
		// Pro for use the getPlugins Method: We'll get the tabconfig row id, we need for the permission checks below!!!!

		// Import all plugins based on the XiveIRM plugin configs and the related catid!
		IRMAppHelper::importPlugins('com_xiveirm', $catId);

		// Get apps that register form fields
		$plugins = IRMAppHelper::getRegisteredForms(); // Array with all available plugins, that should load as set in the com_xiveirm app configuration!!!
		if($plugins) {
			// Check first if we get a positive response and a valid contact id from the savecore method
			if ( $return->status == true && (int) $return->id > 0 )
			{
				// Unset core data from object
				unset($data->core);

				foreach($plugins as $regApp)
				{
					// Plugin permission checks!
					$appAcl = NFWAccessHelper::getActions( 'com_xiveirm', 'plugin', IRMAppHelper::getId($regApp, $catId) );

					// Check if the user have the rights to save the app values for the current item.
					if( ($coreId == 0 && $appAcl->get('core.create')) || ($coreId > 0 && $appAcl->get('core.edit')) || ($coreId > 0 && $appAcl->get('core.edit.own') && NFWUser::getId() == $createdBy) ) {
				 		// Get the app values data.
						$dataApp = JFactory::getApplication()->input->get($regApp, array(), 'array');

						// Set dataTabs to data object
						unset($data->tab);
						$data->tab = $dataApp;

						// Attempt to save the plugin values, if we got any, bound to the appropriate plugin.
						if( $data->tab ) {
							$return = $model->saveAppData($data, $return->id, $regApp);
						} else {
							// Nothing in here. May we could delete app value item from db
						}
					} else {
						self::closeOnError(1100, 'Controller: Please Note: You have insufficient permissions to save the <strong>' . $coreApp . ' (' . $tabApp->plugin . '-datas)</strong>! Please contact the support or your administrator to get further informations!');
					}
				}
			} else {
				self::closeOnError(1600, 'Controller: There is an error in the response (Code: ' . $return->code . '). Message: ' . $return->message . '. Please contact the devXive support team or your administrator to get further informations!');
			}
		} else {
			// Nothing in here ( No plugins loaded, see app config )
		}

		// If all done, check in the core item
		NFWItemHelper::checkIn($data->api['coretable'], $coreId);

		echo json_encode($return);

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
		// TODO FIND A WAY TO FLUSH THE DATA FROM THE userSTATE. AS WE'RE IN AJAX API, WE ONLY NEED THIS FOR THE CANCEL BUTTONS, BECAUSE WE DO NOT HAVE A PAGERELOAD AFTER
		// WE HAVE TO AUTODETECT OR FIND ANY OTHER WAY TO DETERMINE EVERY PART FROM THE STATE ( com_xiveirm + edit + contact OR com_xivetranscorder + edit + transcorder ) !!!
//		$dataAPI = $this->app->input->get('irmapi', array(), 'array');
//		$singleState = $dataAPI['singlestate'];
//		$this->app->->getUserState('com_xiveirm.edit');
		// Flush the data from the session.
//		$this->app->setUserState('com_xiveirm.edit.contact', null);
//		$this->app->setUserState('com_xiveirm.edit.api', null);

		$id = $this->app->input->get('id', '', 'INT');
		$return = NFWItemHelper::checkIn('xiveirm_contacts', $id);

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
		 * NOTE: we do a different token check, because the old one is not working at the moment.
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
		$return = array();
		$return["status"] = false;
		$return["id"] = $errorId;
		$return["code"] = $errorCode;
		$return["message"] = $errorMessage;

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