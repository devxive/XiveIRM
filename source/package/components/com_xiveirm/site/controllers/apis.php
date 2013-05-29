<?php
/**
 * @version     3.1.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Apis list controller class.
 */
class XiveirmControllerApis extends XiveirmController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Apis', $prefix = 'XiveirmModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}



	public function save()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Initialise variables.
		$app	= JFactory::getApplication();
		$model = $this->getModel('Additionaloperation', 'XiveirmModel');

		// Get the user data.
		$data = JFactory::getApplication()->input->get('jform', array(), 'array');
		
		return print_r($data);
		
		// Redirect to the list screen.           ***************** return daher wo de her kommst ;) **********************
		$this->setMessage(JText::_('COM_XIVEIRM_ITEM_SAVED_SUCCESSFULLY'));
		$menu = & JSite::getMenu();
		$item = $menu->getActive();
		$this->setRedirect(JRoute::_($item->link, false));

		// Flush the data from the session.
//		$app->setUserState('com_xiveirm.edit.additionaloperation.data', null);
	}




}