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

/**
 * Xivetranscorder helper.
 */
class XivetranscorderHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_TRANSCORDERS'),
			'index.php?option=com_xivetranscorder&view=transcorders',
			$vName == 'transcorders'
		);
		JHtmlSidebar::addEntry(
			'Categories (Transcorders - Category)',
			"index.php?option=com_categories&extension=com_xivetranscorder.transcorders.catid",
			$vName == 'categories.transcorders'
		);
		
if ($vName=='categories.transcorders.catid') {			
JToolBarHelper::title('XiveTC - Transport Coordination: Categories (Transcorders - Category)');		
}		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_TABAPPVALUES'),
			'index.php?option=com_xivetranscorder&view=tabappvalues',
			$vName == 'tabappvalues'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_RELATEDBILLINGS'),
			'index.php?option=com_xivetranscorder&view=relatedbillings',
			$vName == 'relatedbillings'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_RELATEDSTATISTICS'),
			'index.php?option=com_xivetranscorder&view=relatedstatistics',
			$vName == 'relatedstatistics'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_RELATEDREVISIONS'),
			'index.php?option=com_xivetranscorder&view=relatedrevisions',
			$vName == 'relatedrevisions'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_XIVETRANSCORDER_TITLE_TABAPPS'),
			'index.php?option=com_xivetranscorder&view=tabapps',
			$vName == 'tabapps'
		);
		JHtmlSidebar::addEntry(
			'Categories (Tabapps - Category)',
			"index.php?option=com_categories&extension=com_xivetranscorder.tabapps.catid",
			$vName == 'categories.tabapps'
		);
		
if ($vName=='categories.tabapps.catid') {			
JToolBarHelper::title('XiveTC - Transport Coordination: Categories (Tabapps - Category)');		
}
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

		$assetName = 'com_xivetranscorder';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
