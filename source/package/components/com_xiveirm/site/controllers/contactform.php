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

/**
 * Contact controller class.
 */
class XiveirmControllerContactForm extends XiveirmController
{

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function edit()
	{
		$app			= JFactory::getApplication();

		// Get the previous edit id (if any) and get and set the current edit id for the user to edit in the session.
//		$previousId = (int) $app->getUserState('com_xiveirm.edit.contact.id');
		$editId = $app->input->getInt('id', null, 'array');
		$app->setUserState('com_xiveirm.edit.contact.id', $editId);

		// Get and set the category id for the user if its a new contact to edit in the session if we get one, else return to list
		if($editId == 0) {
			$catId = $app->input->getInt('catid', null, 'array');
			if($catId >= 0 && (int) $catId) {
				$app->setUserState('com_xiveirm.edit.contact.catid', $catId);
			} else {
				// Redirect to the list.
				$this->setRedirect(JRoute::_('index.php?option=com_xiveirm', false));
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
		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contactform&layout=edit', false));
	}

	/**
	 * Method to check out an item for editing and redirect to the edit form.
	 *
	 * @since	1.6
	 */
	public function flag()
	{
		$app = JFactory::getApplication();

		// Get the the current edit id.
		$id = JFactory::getApplication()->input->getInt('id', null, 'array');

		$flag = IRMSystem::flagIt($id);

		$name = '';

		// Set the name for the respone message
		if($flag->first_name && $flag->last_name)
		{
			$name .= $flag->first_name . ' ' . $flag->last_name;
		} else {
			if($flag->last_name) { $name .= $flag->last_name; }
			if($flag->first_name) { $name .= $flag->first_name; }
		}

		if($name != '' && $flag->company)
		{
			$name .= ' - '. $flag->company;
		} else {
			if($flag->company) {
				$name .= $flag->company;
			} else {
				if($name == '') {
					$name .= 'ID ' . $id;
				}
			}
		}

		if($flag->action)
		{
			$app->enqueueMessage(JText::_('COM_XIVEIRM_CONTACT_LIST_FLAGGED') . $name, 'success');
		} else {
			$app->enqueueMessage(JText::_('COM_XIVEIRM_CONTACT_LIST_UNFLAGGED') . $name, 'notice');
		}

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contacts', false));
	}

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
//		$model = $this->getModel('ContactForm', 'XiveirmModel');
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
//			$app->setUserState('com_xiveirm.edit.contact.data', JRequest::getVar('jform'),array());
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xiveirm.edit.contact.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contactform&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->save($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.contact.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xiveirm.edit.contact.id');
//			$this->setMessage(JText::sprintf('Save failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contactform&layout=edit&id='.$id, false));
//			return false;
//		}
//
//            
//		// Check in the profile.
//		if ($return) {
//			$model->checkin($return);
//		}
//
//		// Clear the profile id from the session.
//		$app->setUserState('com_xiveirm.edit.contact.id', null);
//
//		// Redirect to the list screen.
//		$this->setMessage(JText::_('COM_XIVEIRM_ITEM_SAVED_SUCCESSFULLY'));
//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.contact.data', null);
//	}

//	function cancel()
//	{
//		$id = JFactory::getApplication()->input->get('id', '', 'INT');
//		$return = NItemHelper::checkIn('xiveirm_customer', $id);
//
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
//		$model = $this->getModel('ContactForm', 'XiveirmModel');
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
//			$app->setUserState('com_xiveirm.edit.contact.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int) $app->getUserState('com_xiveirm.edit.contact.id');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contact&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Attempt to save the data.
//		$return	= $model->delete($data);
//
//		// Check for errors.
//		if ($return === false) {
//			// Save the data in the session.
//			$app->setUserState('com_xiveirm.edit.contact.data', $data);
//
//			// Redirect back to the edit screen.
//			$id = (int)$app->getUserState('com_xiveirm.edit.contact.id');
//			$this->setMessage(JText::sprintf('Delete failed', $model->getError()), 'warning');
//			$this->setRedirect(JRoute::_('index.php?option=com_xiveirm&view=contact&layout=edit&id='.$id, false));
//			return false;
//		}
//
//		// Check in the profile.
//		if ($return) {
//			$model->checkin($return);
//		}
//
//		// Clear the profile id from the session.
//		$app->setUserState('com_xiveirm.edit.contact.id', null);
//
//		// Redirect to the list screen.
//		$this->setMessage(JText::_('COM_XIVEIRM_ITEM_DELETED_SUCCESSFULLY'));
//		$menu = & JSite::getMenu();
//		$item = $menu->getActive();
//		$this->setRedirect(JRoute::_($item->link, false));
//
//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.contact.data', null);
//	}
}