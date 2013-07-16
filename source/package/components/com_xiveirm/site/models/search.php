<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Xiveirm records.
 */
class XiveirmModelSearch extends JModelList {

	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 * @see        JController
	 * @since    1.6
	 */
	public function __construct($config = array()) {
		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication();

		// List state information
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$this->setState('list.limit', $limit);

		$limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
		$this->SetState('list.start', $limitstart);

		// List state information.
		parent::populateState($ordering, $direction);
	}

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    public function searchbywords($data) {
	// Prepare the return object
	$return = new JObject();

	// Initialise variables.
//	$app = JFactory::getApplication();
	$irmSession = JFactory::getSession();

	// Create a new query object.
	$db = $this->getDbo();

	$query = $db->getQuery(true);

	// get the string
	$search = preg_replace('/\s+/', ' ', $db->escape($data, true));

	//explode the search terms
	$search_query_x = explode(' ', $search);

	// Remove empty string
	foreach($search_query_x as $key => $value) {
		if($value == '') {
			unset($search_query_x[$key]);
		}
	}

	// Check for data // if array empty
	if(empty($search_query_x)) {
		$return->query = array();
		$return->results = array();
		echo 'Nothing found. (This is hardcoded in search.php model class)';
		return $return;
	}

	// Select the required fields from the table.
	$query->select('a.*');
//	$query->select(
//		$this->getState(
//			'list.select', 'a.*'
//		)
//	);

	$query->from('`#__xiveirm_contacts` AS a');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

	// Join over the created by field 'created_by'
	$query->select('created_by.name AS created_by');
	$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

	// Join over the category 'catid'
	$query->select('catid.title AS catid_title');
	$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

	// Join over the category 'gender'
	$query->select('gender.title AS gender_title');
	$query->join('LEFT', '#__categories AS gender ON gender.id = a.gender');

	// Join over the flags
	$query->select('flags.flag AS flagged');
	$query->join('LEFT', '#__xiveirm_flags AS flags ON flags.item = CONCAT(\'contacts.\', a.id)');

	// Set the client_id (usergroup) if exist. Stores while check if it exist to minimize requests/processes. Its not a if comparison!
	if ($filter_client_id = (int) $irmSession->client_id) {
		$query->where('a.client_id = ' . $filter_client_id . '');
	}

	foreach($search_query_x as $search_each) {
//		$query->where('( a.company LIKE '.$filter_global.'  OR  a.last_name LIKE '.$filter_global.'  OR  a.first_name LIKE '.$filter_global.' )');
//		$query->where('STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0');
// war draussen, teste nur mal obs funzt mit explode, weil search each ein array ist
		$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.first_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.company), SOUNDEX(\'' . $search_each . '\')) = 0 ');
// test like 1,3
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) OR SUBSTRING(SOUNDEX(a.first_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) OR SUBSTRING(SOUNDEX(a.company) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) ');
// like
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.first_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.company) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) ');
// gleich
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) = SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.first_name) = SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.company) = SOUNDEX(\'' . $search_each . '\'),1,2) ');
		$query->where(' a.last_name LIKE \'%' . $search_each . '%\' OR a.first_name LIKE \'%' . $search_each . '%\' OR a.company LIKE \'%' . $search_each . '%\' ');
	}
//		$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 ');
//		$query->where('SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\')');
//	$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.first_name), SOUNDEX(\'' . $search_each . '\')) OR STRCMP(SOUNDEX(a.company), SOUNDEX(\'' . $search_each . '\')) ');
//		$query->where('SUBSTRING(SOUNDEX(a.first_name),1,2) LIKE SUBSTRING(SOUNDEX(\'' . $search_each . '\'),1,2)');
//		$query->where('SUBSTRING(SOUNDEX(a.company),1,2) LIKE SUBSTRING(SOUNDEX(\'' . $search_each . '\'),1,2)');

		$query->order('a.last_name ASC');

//	// If the filter_pdk (pre defined key) is set, use the filter_pdk query
//	if ($filter_pdk = $this->state->get("filter.pdk")) {
//		if($filter_pdk == 'pdk_flagged') {
//			$query->where('flags.flag = 1');
//		} else if($filter_pdk == 'pdk_incomplete_address') {
//			$query->where('a.address_street = \'\' OR a.address_houseno = \'\' OR a.address_zip = \'\' OR a.address_city = \'\' OR a.address_country = \'\'');
//		} else if($filter_pdk == 'pdk_no_phone') {
//			$query->where('a.phone = \'\' AND a.mobile = \'\'');
//		} else if($filter_pdk == 'pdk_checked_out') {
//			$userId = JFactory::getUser()->id;
//			$query->where('a.checked_out = ' . $userId . '');
//		} else if($filter_pdk == 'pdk_special') {
//			$query->where('(a.last_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.last_name != \'\') OR (a.first_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.first_name != \'\') OR (a.company NOT REGEXP \'^[[:alpha:]]\' AND a.company != \'\')');
//		} else if($filter_pdk == 'pdk_not_in_country') {
//			$query->where('a.address_country NOT IN (\'Deutschland\', \'Germany\')');
//		} else if($filter_pdk == 'pdk_in_country') {
//			$query->where('a.address_country IN (\'Deutschland\', \'Germany\')');
//		} else if($filter_pdk == 'pdk_no_customer_id') {
//			$query->where('a.customer_id = \'\'');
//		} else {
//			// What? Nothing!
//		}
//	}

	$return->query = $query;

	$db->setQuery($query);

	$return->results = $db->loadObjectList();

	return $return;
    }

    /**
     * Method to search for as much details are given
     *
     * @data array
     *
     * @since	5.0
     */
    public function searchdetails($data) {

	// Initialise variables.
//	$app = JFactory::getApplication();
	$irmSession = JFactory::getSession();

	// Create a new query object.
	$db = $this->getDbo();

	$query = $db->getQuery(true);

	// get the string
//	$search = preg_replace('/\s+/', ' ', $db->escape($data, true));

	//explode the search terms (NOTE we may explode the search terms later where we check what fields we have
	$search_query_x = explode(' ', $search);

	// Select the required fields from the table.
	$query->select('a.*');
//	$query->select(
//		$this->getState(
//			'list.select', 'a.*'
//		)
//	);

	$query->from('`#__xiveirm_contacts` AS a');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

	// Join over the created by field 'created_by'
	$query->select('created_by.name AS created_by');
	$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

	// Join over the category 'catid'
	$query->select('catid.title AS catid_title');
	$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

	// Join over the category 'gender'
	$query->select('gender.title AS gender_title');
	$query->join('LEFT', '#__categories AS gender ON gender.id = a.gender');

	// Join over the flags
	$query->select('flags.flag AS flagged');
	$query->join('LEFT', '#__xiveirm_flags AS flags ON flags.item = CONCAT(\'contacts.\', a.id)');

	// Set the client_id (usergroup) if exist. Stores while check if it exist to minimize requests/processes. Its not a if comparison!
	if ($filter_client_id = (int) $irmSession->client_id) {
		$query->where('a.client_id = ' . $filter_client_id . '');
	}

	foreach($search_query_x as $search_each) {
//		$query->where('( a.company LIKE '.$filter_global.'  OR  a.last_name LIKE '.$filter_global.'  OR  a.first_name LIKE '.$filter_global.' )');
//		$query->where('STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0');
// war draussen, teste nur mal obs funzt mit explode, weil search each ein array ist
		$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.first_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.company), SOUNDEX(\'' . $search_each . '\')) = 0 ');
// test like 1,3
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) OR SUBSTRING(SOUNDEX(a.first_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) OR SUBSTRING(SOUNDEX(a.company) LIKE SOUNDEX(\'' . $search_each . '\'),1,3) ');
// like
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.first_name) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.company) LIKE SOUNDEX(\'' . $search_each . '\'),1,2) ');
// gleich
//		$query->where(' SUBSTRING(SOUNDEX(a.last_name) = SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.first_name) = SOUNDEX(\'' . $search_each . '\'),1,2) OR SUBSTRING(SOUNDEX(a.company) = SOUNDEX(\'' . $search_each . '\'),1,2) ');
		$query->where(' a.last_name LIKE \'%' . $search_each . '%\' OR a.first_name LIKE \'%' . $search_each . '%\' OR a.company LIKE \'%' . $search_each . '%\' ');
	}
//		$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 ');
//		$query->where('SOUNDEX(a.last_name) LIKE SOUNDEX(\'' . $search_each . '\')');
//	$query->where(' STRCMP(SOUNDEX(a.last_name), SOUNDEX(\'' . $search_each . '\')) = 0 OR STRCMP(SOUNDEX(a.first_name), SOUNDEX(\'' . $search_each . '\')) OR STRCMP(SOUNDEX(a.company), SOUNDEX(\'' . $search_each . '\')) ');
//		$query->where('SUBSTRING(SOUNDEX(a.first_name),1,2) LIKE SUBSTRING(SOUNDEX(\'' . $search_each . '\'),1,2)');
//		$query->where('SUBSTRING(SOUNDEX(a.company),1,2) LIKE SUBSTRING(SOUNDEX(\'' . $search_each . '\'),1,2)');

		$query->order('a.last_name ASC');

//	// If the filter_pdk (pre defined key) is set, use the filter_pdk query
//	if ($filter_pdk = $this->state->get("filter.pdk")) {
//		if($filter_pdk == 'pdk_flagged') {
//			$query->where('flags.flag = 1');
//		} else if($filter_pdk == 'pdk_incomplete_address') {
//			$query->where('a.address_street = \'\' OR a.address_houseno = \'\' OR a.address_zip = \'\' OR a.address_city = \'\' OR a.address_country = \'\'');
//		} else if($filter_pdk == 'pdk_no_phone') {
//			$query->where('a.phone = \'\' AND a.mobile = \'\'');
//		} else if($filter_pdk == 'pdk_checked_out') {
//			$userId = JFactory::getUser()->id;
//			$query->where('a.checked_out = ' . $userId . '');
//		} else if($filter_pdk == 'pdk_special') {
//			$query->where('(a.last_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.last_name != \'\') OR (a.first_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.first_name != \'\') OR (a.company NOT REGEXP \'^[[:alpha:]]\' AND a.company != \'\')');
//		} else if($filter_pdk == 'pdk_not_in_country') {
//			$query->where('a.address_country NOT IN (\'Deutschland\', \'Germany\')');
//		} else if($filter_pdk == 'pdk_in_country') {
//			$query->where('a.address_country IN (\'Deutschland\', \'Germany\')');
//		} else if($filter_pdk == 'pdk_no_customer_id') {
//			$query->where('a.customer_id = \'\'');
//		} else {
//			// What? Nothing!
//		}
//	}

	$db->setQuery($query);

	$results = $db->loadObjectList();

	return $results;
    }
}