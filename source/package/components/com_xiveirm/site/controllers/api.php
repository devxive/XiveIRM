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
	}

	/**
	 * Method to save a user's profile data.
	 *
	 * @return	void
	 * @since	1.6
	 */
	public function save()
	{
		$data = array('direction' => 'ajax');

		// Check for request forgeries.
		if($data['direction'] == 'ajax')
		{
			// This is the api direction (where the data come from "ajax" or "in future whatever" we use at this time only the ajax variant)
			// The problem with JSession token check have to be resolved!
		}
		else
		{
			JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		}

		// Initialise variables.
		$app = JFactory::getApplication();
		$model = $this->getModel('Api', 'XiveirmModel');

 		// Get the tabForm data.
		$data = JFactory::getApplication()->input->get('tabForm', array(), 'array');

		// Attempt to save the data.
		$return = $model->save($data);

//		echo json_encode($return);

//		// XAP-TODO: Not so nice and have to be updated to the right things
		$returnUrl = '/components/com_xiveirm/helpers/api_return.php?string=' . json_encode($return);
		header('Location: ' . $returnUrl);

//		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.api.data', null);
	}
    
    
	function cancel()
	{
	}

	public function remove()
	{
	}
}