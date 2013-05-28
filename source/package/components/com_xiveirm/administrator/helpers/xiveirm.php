<?php
/**
 * @version     3.0.4
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
			JText::_('COM_XIVEIRM_TITLE_IRMMASTERDATAS'),
			'index.php?option=com_xiveirm&view=irmmasterdatas',
			$vName == 'irmmasterdatas'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_ADDITIONALINFORMATIONS'),
			'index.php?option=com_xiveirm&view=additionalinformations',
			$vName == 'additionalinformations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_SELECTLISTS'),
			'index.php?option=com_xiveirm&view=selectlists',
			$vName == 'selectlists'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVEIRM_TITLE_AUDITS'),
			'index.php?option=com_xiveirm&view=audits',
			$vName == 'audits'
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
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
