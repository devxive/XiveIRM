<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

class IRMSystem
{
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
	 * Method to get an array of options. Used for select lists, radio and checkbox sets
	 * return array if success, else return false
	 * $table without prefix, option key, client id (return always 0 because 0 is used as global)
	 */
	public function getOptionArray($table, $opt_key, $client_id = 0)
	{
		if(!$table && !$opt_key && (int) $client_id) {
			return false;
		}

		// Init database object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		$dbTable = '#__' . $table;
		$global_id = 0;
		$key = $sl_key . '.%';

		$query
			->select(array('client_id', 'sl_value', 'sl_string'))
			->from($db->quoteName($dbTable))
			->where('opt_key LIKE ' . $db->quote($key) . '')
			->where('client_id = ' . $db->quote($global_id) . ' OR client_id = ' . $db->quote($client_id) . '')
			->order('ordering ASC');

		$db->setQuery($query);

		// Try to get or return false
		try
		{
			$results = $db->loadObjectList();

			$superglobal = new stdClass;
			$client = array();
			$global = array();

			foreach ($results as $result) {
				if($result->client_id != 0) {
					$client[$result->sl_value] = $result->sl_string;
				} else {
					$global[$result->sl_value] = $result->sl_string;
				}
			}

			$superglobal->client = $client;
			$superglobal->global = $global;

			return $superglobal;
		} catch (Exception $e) {
			return false;
		}
	}



}

