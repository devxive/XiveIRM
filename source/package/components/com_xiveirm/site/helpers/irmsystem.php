<?php
/**
 * @version     3.1.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

class IRMSystem
{
	public function getUserName($id)
	{
		return JFactory::getUser($id)->name;
	}

	/*
	 * 
	 * returns a prepared array
	 */
	public function getTabData($tabAppId, $masterDataItemId, $json = false)
	{
		if(!$tabAppId || !$masterDataItemId)
		{
			return false;
		}

		// set the tab_id for the where clause
		$tabId = $tabAppId . '.' . $masterDataItemId;

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__xiveirm_masterdata_add')
			->where('tab_id = ' . $db->quote($tabId) . '');
		$db->setQuery($query);

		// Try to get the data or the error code for debugging
		try
		{
			$result = $db->loadObject();

			if($json == false && $result)
			{
				$tab_value = json_decode($result->tab_value);
				$result->tab_value = $tab_value;
			}			

			return $result;
		} catch (Exception $e) {
			$error = array();
			$error['code'] = (int)$e->getCode();
			$error['message'] = $e->getMessage();

			return $error;
		}
		
	}
}

