<?php
/**
 * @version     5.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Transcorder controller class.
 */
class XivetranscorderControllerTranscorderForm extends XivetranscorderController
{
	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	5.0
	 */
	public function edit()
	{
		$app = JFactory::getApplication();

		// Get the previous edit id (if any) and get and set the current edit id for the user to edit in the session.
//		$previousId = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.id');
		$editId = $app->input->getInt('id', null, 'array');
		$app->setUserState('com_xivetranscorder.edit.transcorder.id', $editId);

		// Get and set the category id for the user if its a new contact to edit in the session if we get one, else return to list
		if($editId == 0) {
//			$catId = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.catid'); // getUserState is much better than getInt
			$catId = $app->input->getInt('catid', null, 'array');
			if($catId >= 0 && (int) $catId) {
				$app->setUserState('com_xivetranscorder.edit.transcorder.catid', $catId);
			} else {
				// Redirect to the list.
				$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder', false));
				return false;
			}
		}

		// Get and set the contactId for the order if its a new order to edit in the session if we get one, else return to list
		if($editId == 0) {
//			$contactId = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.catid'); // getUserState is much better than getInt
			$contactId = $app->input->getInt('contactid', null, 'array');
			if($catId >= 0 && (int) $catId) {
				$app->setUserState('com_xivetranscorder.edit.transcorder.contactid', $contactId);
			} else {
				// Redirect to the list.
				$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder', false));
				return false;
			}
		}

//		// Get the model.
//		$model = $this->getModel('ContactForm', 'XiveirmModel');

//		// Check out the item
//		if ($editId) {
//			$model->checkout($editId);
//		}

//		// Check in the previous user.
//		if ($previousId) {
//			$model->checkin($previousId);
//		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorderform&layout=edit', false));
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
//		$previousId = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.id');
//		$editId	= JFactory::getApplication()->input->getInt('id', null, 'array');
//
//		// Set the user id for the user to edit in the session.
//		$app->setUserState('com_xivetranscorder.edit.transcorder.id', $editId);
//
//		// Get the model.
//		$model = $this->getModel('TranscorderForm', 'XivetranscorderModel');
//
//		// Check out the item
//		if ($editId) {
//			$model->checkout($editId);
//		}
//
//		// Check in the previous user.
//		if ($previousId) {
//			$model->checkin($previousId);
//		}
//
//		// Redirect to the edit screen.
//		$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorder&layout=edit', false));
//	}

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
//		$model = $this->getModel('TranscorderForm', 'XivetranscorderModel');
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
//			$app->setUserState('com_xivetranscorder.edit.transcorder.data', JRequest::getVar('jform'),array());
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorderform&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->save($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xivetranscorder.edit.transcorder.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xivetranscorder.edit.transcorder.id');
//			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorderform&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Check in the profile.
//		if ($return) {
//			$model->checkin($return);
//		}
//
//		// Clear the profile id from the session.
//		$app->setUserState('com_xivetranscorder.edit.transcorder.id', null);
//
//		// Redirect to the list screen.
//		$this->setMessage(JText::_('COM_XIVETRANSCORDER_ITEM_SAVED_SUCCESSFULLY'));
//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xivetranscorder.edit.transcorder.data', null);
//	}

//	function cancel()
//	{
//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
//	}

//	public function remove()
//	{
//		// Check for request forgeries.
//		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
//
//		// Initialise variables.
//		$app	= JFactory::getApplication();
//		$model = $this->getModel('TranscorderForm', 'XivetranscorderModel');
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
//			$app->setUserState('com_xivetranscorder.edit.transcorder.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xivetranscorder.edit.transcorder.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorder&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->delete($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xivetranscorder.edit.transcorder.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xivetranscorder.edit.transcorder.id');
//			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder&view=transcorder&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Check in the profile.
//		if ($return) {
//			$model->checkin($return);
//		}
//
//		// Clear the profile id from the session.
//		$app->setUserState('com_xivetranscorder.edit.transcorder.id', null);
//
//		// Redirect to the list screen.
//		$this->setMessage(JText::_('COM_XIVETRANSCORDER_ITEM_DELETED_SUCCESSFULLY'));
//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xivetranscorder.edit.transcorder.data', null);
//	}
}