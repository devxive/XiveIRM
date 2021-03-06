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
		$lang = JFactory::getLanguage();
		$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select( array('*') )
			->from( '#__menu' )
			->where( 'menutype = \'main\' AND path LIKE \'com-xive%\' AND parent_id != \'1\' AND alias != \'com-xivetranscorder\'' )
			->order( 'rgt ASC' );

		$db->setQuery($query);
		$results = $db->loadObjectList();

		foreach($results AS $result) {
			$img = '<img src="' . $result->img . '">';
			JHtmlSidebar::addEntry(
				$img . ' ' . JText::_($result->title),
				$result->link,
				$vName == $result->alias
			);
		}

//		JHtmlSidebar::addEntry(
//			JText::_('COM_XIVEIRM_TITLE_CONTACTS'),
//			'index.php?option=com_xiveirm&view=contacts',
//			$vName == 'contacts'
//		);

//		JHtmlSidebar::addEntry(
//			JText::_('COM_XIVEIRM_TITLE_OPTIONS'),
//			'index.php?option=com_xiveirm&view=options',
//			$vName == 'options'
//		);

//		JHtmlSidebar::addEntry(
//			JText::_('COM_XIVEIRM_CATEGORIES'),
//			"index.php?option=com_categories&extension=com_xiveirm",
//			$vName == 'categories'
//		);

//		JHtmlSidebar::addEntry(
//			JText::_('COM_XIVEIRM_TITLE_PLUGINS'),
//			'index.php?option=com_xiveirm&view=plugins',
//			$vName == 'plugins'
//		);
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