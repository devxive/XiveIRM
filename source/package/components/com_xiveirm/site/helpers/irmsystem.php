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
	public function getTabData($customer_cid, $tab_key)
	{
		if(!$customer_cid || !$tab_key)
		{
			return false;
		}

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$query
			->select('*')
			->from('#__xiveirm_customer_add')
			->where('customer_cid = ' . $db->quote($customer_cid) . '')
			->where('tab_key = ' . $db->quote($tab_key) . '');
		$db->setQuery($query);

		// Try to get the data or the error code for debugging
		try
		{
			$result = $db->loadObject();

			if($result) {
				$tab_value = json_decode($result->tab_value);
				$result->tab_value = $tab_value;
			} else {
				$result = new stdClass;
			}

			return $result;
		} catch (Exception $e) {
			$error = array();
			$error['code'] = (int)$e->getCode();
			$error['message'] = $e->getMessage();

			return $error;
		}
		
	}


	/*
	 * return the current date, based on the timezone, given either in the user or the system config object.
	 * @format	switch the format, sql datetime format, unix timestamp, date, datetime
	 * @value	default now, other formats not supportet at this time
	 * @mode	switch the mode (default: USER_UTC): SERVER_UTC, USER_UTC (USER_UTC with fallback to system, if the timezone is set to Global)
	 */
	public function getDate($format = 'UNIX', $value = 'now', $mode = 'USER_UTC')
	{
		// Get some system objects.
		$config = JFactory::getConfig();
		$user = JFactory::getUser();

		$date = JFactory::getDate($value, 'UTC');

		// Set the timezone
		switch ($mode)
		{
			case 'SERVER_UTC':
				// Convert a date to UTC based on the server timezone.
				$date->setTimezone(new DateTimeZone($config->get('offset')));
				break;

			case 'USER_UTC':
				// Convert a date to UTC based on the user timezone (Fallback, system config timezome, if user tz is set to global).
				$date->setTimezone(new DateTimeZone($user->getParam('timezone', $config->get('offset'))));
				break;
		}

		// Transform the date string
		switch ($format)
		{
			case 'MySQL':
				$value = $date->format('Y-m-d H:i:s', true, false);
				break;

			case 'UNIX':
				$value = strtotime($date->format('Y-m-d H:i:s', true, false));
				break;

			case 'TIME':
				$value = $date->format('H:i', true, false);
				break;

			case 'TIMES':
				$value = $date->format('H:i:s', true, false);
				break;

			case 'LC':
			case 'LC1':
			case 'JLC':
			case 'JLC1': // Wednesday, 12 June 2013 
				$value = $date->format('l, d F Y', true, false);
				break;

			case 'LC2':
			case 'JLC2': // Wednesday, 12 June 2013 15:20
				$value = $date->format('l, d F Y H:i', true, false);
				break;

			case 'LC3':
			case 'JLC3':
				$value = $date->format('d F Y', true, false); // 12 June 2013
				break;

			case 'DATE':
			case 'LC4':
			case 'JLC4':
				$value = $date->format('Y-m-d', true, false); // 2013-06-12
				break;
		}

		return $value;
	}
}

