<?php
/**
 * @version     3.0.0
 * @package     com_mc3prm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Mc3prm helper.
 */
class Mc3prmHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_MC3PRM_TITLE_PRMMASTERDATAS'),
			'index.php?option=com_mc3prm&view=prmmasterdatas',
			$vName == 'prmmasterdatas'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MC3PRM_TITLE_EVENTS'),
			'index.php?option=com_mc3prm&view=events',
			$vName == 'events'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MC3PRM_TITLE_PHYSICALSTATES'),
			'index.php?option=com_mc3prm&view=physicalstates',
			$vName == 'physicalstates'
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

		$assetName = 'com_mc3prm';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
