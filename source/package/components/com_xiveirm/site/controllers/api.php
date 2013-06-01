<?php
/**
 * @version     3.1.0
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
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function edit()
	{
		$app			= JFactory::getApplication();

		// Get the previous edit id (if any) and the current edit id.
		$previousId = (int) $app->getUserState('com_xiveirm.edit.api.id');
		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');

		// Set the user id for the user to edit in the session.
		$app->setUserState('com_xiveirm.edit.api.id', $editId);

		// Get the model.
		$model = $this->getModel('Api', 'XiveirmModel');

		// Check out the item
		if ($editId) {
            $model->checkout($editId);
		}

		// Check in the previous user.
		if ($previousId) {
            $model->checkin($previousId);
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=apiform&layout=edit', false));
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save()
	{
// 
// 		// Check for request forgeries.
// 		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
// 
// 		// Initialise variables.
// 		$app	= JFactory::getApplication();
// 		$model = $this->getModel('Api', 'XiveirmModel');
// 
// 		// Get the user data.
//		$data = JFactory::getApplication()->input->get('tabForm', array(), 'array');

		$data = array('itemid' => '1', 'tabid' => 'medicaltesttab', 'direction' => 'api', 'format' => 'json', 'first_name' => 'Max', 'last_name' => 'Mustermann');
		// Store the identifier vars
		$itemId = $data['itemid'];
		$tabId = $data['tabid'];
		$direction = $data['direction'];
		$format = $data['format'];
		
		// Build a new array if stored in a single row as json encoded values
		if($format == 'json') {
			$newArray = array();
			foreach($data as $key => $val)
			{
				if($key != 'itemid' || $key != 'tabid' || $key != 'direction' || $key != 'format' || $key != '')
				{
					$newArray[$key] = $val;
				}
			}
		}

		$dbValue = json_encode($newArray);

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		// check if there are already datas from the specific tabid related to customers itemId
		$query
			->select('*')
			->from('#__xiveirm_masterdata_add')
			->where('item_id = ' . $itemId)
			->where('tab_name = ' . $tabId);
		$db->setQuery($query);

		if($db->loadObjectList()) { echo 'NIX DAAAAAAAAAAAAA'; }

		// Validate the posted data and check what method we have to use to store values in the database
		if($direction == 'api') {
			$return_arr = array();
			$return_arr["code"] = '200';
			$return_arr["message"] = 'Everything ok';
			echo json_encode($return_arr);
		} else if($direction == 'app') {
			echo '<p class="alert alert-notice">Single Values</p>';
		} else {
//			JError::raiseError(500, 'We cant provide a query!');
//			return false;
		}



// 
// 		// Validate the posted data.
// 		$form = $model->getForm();
// 		if (!$form) {
// 			JError::raiseError(500, $model->getError());
// 			return false;
// 		}
// 
// 		// Validate the posted data.
// 		$data = $model->validate($form, $data);
// 
// 		// Check for errors.
// 		if ($data === false) {
// 			// Get the validation messages.
// 			$errors	= $model->getErrors();
// 
// 			// Push up to three validation messages out to the user.
// 			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
// 				if ($errors[$i] instanceof Exception) {
// 					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
// 				} else {
// 					$app->enqueueMessage($errors[$i], 'warning');
// 				}
// 			}
// 
// 			// Save the data in the session.
// 			$app->setUserState('com_xiveirm.edit.api.data', JRequest::getVar('jform'),array());
// 
// 			// Redirect back to the edit screen.
// 			$id = (int) $app->getUserState('com_xiveirm.edit.api.id');
// 			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
// 			return false;
// 		}
// 
// 		// Attempt to save the data.
// 		$return	= $model->save($data);
// 
// 		// Check for errors.
// 		if ($return === false) {
// 			// Save the data in the session.
// 			$app->setUserState('com_xiveirm.edit.api.data', $data);
// 
// 			// Redirect back to the edit screen.
// 			$id = (int)$app->getUserState('com_xiveirm.edit.api.id');
// 			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
// 			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
// 			return false;
// 		}
// 
//             
//         // Check in the profile.
//         if ($return) {
//             $model->checkin($return);
//         }
//         
//         // Clear the profile id from the session.
//         $app->setUserState('com_xiveirm.edit.api.id', null);
// 
//         // Redirect to the list screen.
//         $this->setMessage(JText::_('COM_XIVEIRM_ITEM_SAVED_SUCCESSFULLY'));
//         $menu = & JSite::getMenu();
//         $item = $menu->getActive();
//         $this->setRedirect(JRoute::_($item->link, false));
// 
// 		// Flush the data from the session.
// 		$app->setUserState('com_xiveirm.edit.api.data', null);
	}
    
    
    function cancel() {
		$menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));
    }
    
	public function remove()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Api', 'XiveirmModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');

		// Validate the posted data.
		$form = $model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}

		// Validate the posted data.
		$data = $model->validate($form, $data);

		// Check for errors.
		if ($data === false) {
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++) {
				if ($errors[$i] instanceof Exception) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				} else {
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_xiveirm.edit.api.data', $data);

			// Redirect back to the edit screen.
			$id = (int) $app->getUserState('com_xiveirm.edit.api.id');
			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
			return false;
		}

		// Attempt to save the data.
		$return	= $model->delete($data);

		// Check for errors.
		if ($return === false) {
			// Save the data in the session.
			$app->setUserState('com_xiveirm.edit.api.data', $data);

			// Redirect back to the edit screen.
			$id = (int)$app->getUserState('com_xiveirm.edit.api.id');
			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=api&layout=edit&id='.$id, false));
			return false;
		}

            
        // Check in the profile.
        if ($return) {
            $model->checkin($return);
        }
        
        // Clear the profile id from the session.
        $app->setUserState('com_xiveirm.edit.api.id', null);

        // Redirect to the list screen.
        $this->setMessage(JText::_('COM_XIVEIRM_ITEM_DELETED_SUCCESSFULLY'));
        $menu = & JSite::getMenu();
        $item = $menu->getActive();
        $this->setRedirect(JRoute::_($item->link, false));

		// Flush the data from the session.
		$app->setUserState('com_xiveirm.edit.api.data', null);
	}
    
    
}