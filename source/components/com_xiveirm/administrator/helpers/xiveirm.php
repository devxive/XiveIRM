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

/**
 * Xiveirm helper.
 */
class XiveirmHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_CONTACTS'),
			'index.php?option=com_xiveirm&view=contacts',
			$vName == 'contacts'
		);
		
		if ($vName=='contacts' || $vName=='categories.contacts') {
			JHtmlSidebar::addEntry(
				JText::_('COM_XIVEIRM_CONTACTS_CATEGORIES'),
				"index.php?option=com_categories&extension=com_xiveirm.contacts",
				$vName == 'categories.contacts'
			);
		}

		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_OPTIONS'),
			'index.php?option=com_xiveirm&view=options',
			$vName == 'options'
		);

		if ($vName=='options' || $vName=='categories.options') {
			JHtmlSidebar::addEntry(
				JText::_('COM_XIVEIRM_OPTIONS_CATEGORIES'),
				"index.php?option=com_categories&extension=com_xiveirm.options",
				$vName == 'categories.options'
			);
		}

		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_TABAPPS'),
			'index.php?option=com_xiveirm&view=tabapps',
			$vName == 'tabapps'
		);
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_xiveirm';

		$actions = array(
			'core.admin',
			'core.manage',
			'core.create',
			'core.edit',
			'core.edit.own',
			'core.edit.state',
			'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}