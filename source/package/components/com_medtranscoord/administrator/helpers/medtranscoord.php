<?php
/**
 * @version     3.1.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * Medtranscoord helper.
 */
class MedtranscoordHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_MEDTRANSCOORD_TITLE_ORDERS'),
			'index.php?option=com_medtranscoord&view=orders',
			$vName == 'orders'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MEDTRANSCOORD_TITLE_ADDITIONALINFORMATIONS'),
			'index.php?option=com_medtranscoord&view=additionalinformations',
			$vName == 'additionalinformations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MEDTRANSCOORD_TITLE_ADDITIONALBILLINGINFORMATIONS'),
			'index.php?option=com_medtranscoord&view=additionalbillinginformations',
			$vName == 'additionalbillinginformations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MEDTRANSCOORD_TITLE_ADDITIONALSTATISTICINFORMATIONS'),
			'index.php?option=com_medtranscoord&view=additionalstatisticinformations',
			$vName == 'additionalstatisticinformations'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MEDTRANSCOORD_TITLE_SELECTLISTS'),
			'index.php?option=com_medtranscoord&view=selectlists',
			$vName == 'selectlists'
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

		$assetName = 'com_medtranscoord';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
