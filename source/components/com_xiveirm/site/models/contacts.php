<?php
/**
 * @version     6.0.0
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
class XiveirmModelContacts extends JModelList {

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
    protected function populateState($ordering = null, $direction = null) {

	// Initialise variables.
	$app = JFactory::getApplication();
	$session = JFactory::getSession();

	// List state information
	$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
	$this->setState('list.limit', $limit);

	$limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
	$this->setState('list.start', $limitstart);

	// Set state for client_id (usergroup)
	if ($irmSession = $session->get('XiveIRMSystem')) {
		$this->setState('filter.client_id', (int) $irmSession->client_id);
	}

	// Set the filter states
	$filter = $app->getUserState('com_xiveirm.contacts.filter');

	// Set state for global search phrase
	$filter_global = isset($filter['global']) ? preg_replace('/\s+/', ' ', $filter['global']) : null;
	$this->setState('filter.global', preg_replace('/\s+/', ' ', $filter_global));

	// Set state for category search
	$filter_catid = isset($filter['catid']) ? $filter['catid'] : null;
	$this->setState('filter.catid', $filter_catid);

	// Set state for task search
	$filter_pdk = isset($filter['pdk']) ? $filter['pdk'] : null;
	$this->setState('filter.pdk', $filter_pdk);

	// Set state for global search by phrase
//	$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
//	$this->setState('filter.search', preg_replace('/\s+/', ' ', $search));

	// List state information.
	parent::populateState($ordering, $direction);
    }

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
	// Create a new query object.
	$db = $this->getDbo();
	$query = $db->getQuery(true);

	// Select the required fields from the table.
	$query->select(
		$this->getState(
			'list.select', 'a.*'
		)
	);

	$query->from('`#__xiveirm_contacts` AS a');

	// Join over the users for the checked out user.
	$query->select('uc.name AS editor');
	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

	// Join over the created by field 'created_by'
	$query->select('created_by.name AS created_by');
	$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

	// Join over the modified by field 'modified_by'
	$query->select('modified_by.name AS modified_by');
	$query->join('LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by');

	// Join over the category 'catid'
	$query->select('catid.title AS catid_title');
	$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

	// Join over the category 'gender'
	$query->select('gender.title AS gender_title');
	$query->join('LEFT', '#__categories AS gender ON gender.id = a.gender');

	// Join over the flags
	$query->select('flags.flag AS flagged');
	$query->join('LEFT', '#__xiveirm_flags AS flags ON flags.item = CONCAT(\'contacts.\', a.id)');

	// Join over the verified contacts
	$query->select( array('verified.system_checked AS system_checked', 'verified.client_checked AS client_checked') );
	$query->join('LEFT', '#__xiveirm_contacts_verified AS verified ON verified.contacts_id = a.id');

	// Set the client_id (usergroup)
	if ($filter_client_id = $this->state->get("filter.client_id")) {
		$query->where('a.client_id = ' . $filter_client_id . '');
	}

	// Filtering catid
	if ($filter_catid = $this->state->get("filter.catid")) {
		$query->where("a.catid = '".$filter_catid."'");
	}

	// Filtering by global search phrase
	if ($filter_global = $this->state->get("filter.global")) {
		$filter_global = $db->Quote($db->escape($filter_global, true) . '%');
		$query->where('( a.company LIKE '.$filter_global.'  OR  a.last_name LIKE '.$filter_global.'  OR  a.first_name LIKE '.$filter_global.' )');
//			$filter_global = $db->Quote($db->escape($filter_global, true));
//			$query->where('a.id = ' . (int) substr($filter_global, 3));
	}

	// If the filter_pdk (pre defined key) is set, use the filter_pdk query
	if ($filter_pdk = $this->state->get("filter.pdk")) {
		if($filter_pdk == 'pdk_flagged') {
			$query->where('flags.flag = 1');
		} else if($filter_pdk == 'pdk_incomplete_address') {
			$query->where('a.address_street = \'\' OR a.address_houseno = \'\' OR a.address_zip = \'\' OR a.address_city = \'\' OR a.address_country = \'\'');
		} else if($filter_pdk == 'pdk_no_phone') {
			$query->where('a.phone = \'\' AND a.mobile = \'\'');
		} else if($filter_pdk == 'pdk_checked_out') {
			$userId = JFactory::getUser()->id;
			$query->where('a.checked_out = ' . $userId . '');
		} else if($filter_pdk == 'pdk_special') {
			$query->where('(a.last_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.last_name != \'\') OR (a.first_name NOT REGEXP \'^[[:alpha:]]*$\' AND a.first_name != \'\') OR (a.company NOT REGEXP \'^[[:alpha:]]\' AND a.company != \'\')');
		} else if($filter_pdk == 'pdk_not_in_country') {
			$query->where('a.address_country NOT IN (\'Deutschland\', \'Germany\')');
		} else if($filter_pdk == 'pdk_in_country') {
			$query->where('a.address_country IN (\'Deutschland\', \'Germany\')');
		} else if($filter_pdk == 'pdk_no_customer_id') {
			$query->where('a.customer_id = \'\'');
		} else {
			// What? Nothing!
		}
	}

	return $query;
    }
}