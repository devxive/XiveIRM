<?php
/**
 * @version     6.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Xivetranscorder records.
 */
class XivetranscorderModelTranscorders extends JModelList
{
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
		$filter = $app->getUserState('com_xivetranscorder.transcorders.filter');

		// Set state for global search phrase
		$filter_global = isset($filter['global']) ? preg_replace('/\s+/', ' ', $filter['global']) : null;
		$this->setState('filter.global', preg_replace('/\s+/', ' ', $filter_global));

		// Set state for category search
		$filter_catid = isset($filter['catid']) ? $filter['catid'] : null;
		$this->setState('filter.catid', $filter_catid);

		// Set state array for daterange
		$filter_daterange = isset($filter['daterange']) ? $filter['daterange'] : null;
		$this->setState('filter.daterange', $filter_daterange);

		// Set state for task search
		$filter_pdk = isset($filter['pdk']) ? $filter['pdk'] : null;
		$this->setState('filter.pdk', $filter_pdk);

		// Set state for contact search
		$filter_contact = isset($filter['contact']) ? $filter['contact'] : null;
		$this->setState('filter.contact', $filter_contact);

		// List state information.
		parent::populateState($ordering, $direction);
	}


	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return	JDatabaseQuery
	 * @since	1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select', 'a.*'
			)
		);

		$query->from( '`#__xiveirm_transcorders` AS a' );

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join( 'LEFT', '#__users AS uc ON uc.id=a.checked_out' );

		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join( 'LEFT', '#__users AS created_by ON created_by.id = a.created_by' );

		// Join over the modified by field 'modified_by'
		$query->select('modified_by.name AS modified_by');
		$query->join( 'LEFT', '#__users AS modified_by ON modified_by.id = a.modified_by' );
    
		// Join over the category 'catid'
		$query->select('catid.title AS catid_title');
		$query->join( 'LEFT', '#__categories AS catid ON catid.id = a.catid' );

		// Join over the flags
		$query->select('flags.flag AS flagged');
		$query->join( 'LEFT', '#__xiveirm_flags AS flags ON flags.item = CONCAT(\'transcorders.\', a.id)' );

		// Set the client_id (usergroup)
		if ($filter_client_id = $this->state->get("filter.client_id")) {
			$query->where( 'a.client_id = ' . $filter_client_id . '' );
		}

		// Filtering catid
		if ($filter_catid = $this->state->get("filter.catid")) {
			$query->where( "a.catid = '" . $filter_catid . "'" );
		}

		// Filtering contact_id
		if ($filter_contact = $this->state->get("filter.contact")) {
			$query->where( "a.contact_id = '" . $filter_contact . "'" );
		}

		// Filtering daterange
		if ($filter_daterange = $this->state->get("filter.daterange")) {
			$query->where("a.transport_timestamp >= '".$filter_daterange[0]."' AND a.transport_timestamp <= '".$filter_daterange[1]."'");
		} else {
			// Use Todays values if nothing is set
			$startTime = strtotime( date('d.m.Y') . ' 00:00:00' );
			$endTime = strtotime( date('d.m.Y') . ' 23:59:59' );

			$query->where("a.transport_timestamp >= '" . $startTime . "' AND a.transport_timestamp <= '" . $endTime . "'");
		}

		// If the filter_pdk (pre defined key) is set, use the filter_pdk query
		if ($filter_pdk = $this->state->get("filter.pdk")) {
			if($filter_pdk == 'pdk_flagged') {
				$query->where('flags.flag = 1');
			} else if($filter_pdk == 'pdk_checked_out') {
				$userId = JFactory::getUser()->id;
				$query->where('a.checked_out = ' . $userId . '');
			} else if($filter_pdk == 'pdk_no_order_id') {
				$query->where('a.order_id = \'\'');
			} else {
				// What? Nothing!
			}
		}

		return $query;
	}
}