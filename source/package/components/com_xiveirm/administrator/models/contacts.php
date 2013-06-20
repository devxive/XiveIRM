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
class XiveirmModelcontacts extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param    array    An optional associative array of configuration settings.
	 * @see        JController
	 * @since    1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
				'id', 'a.id',
				'client_id', 'a.client_id',
				'parent_id', 'a.parent_id',
				'state', 'a.state',
				'created', 'a.created',
				'created_by', 'a.created_by',
				'modified', 'a.modified',
				'catid', 'a.catid',
				'customer_id', 'a.customer_id',
				'company', 'a.company',
				'title', 'a.title',
				'last_name', 'a.last_name',
				'first_name', 'a.first_name',
				'gender', 'a.gender',
				'dob', 'a.dob',
				'address_name', 'a.address_name',
				'address_name_add', 'a.address_name_add',
				'address_street', 'a.address_street',
				'address_houseno', 'a.address_houseno',
				'address_zip', 'a.address_zip',
				'address_city', 'a.address_city',
				'address_region', 'a.address_region',
				'address_country', 'a.address_country',
				'phone', 'a.phone',
				'fax', 'a.fax',
				'mobile', 'a.mobile',
				'email', 'a.email',
				'web', 'a.web',
				'remarks', 'a.remarks',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Initialise variables.
		$app = JFactory::getApplication('administrator');

		// Load the filter state.
		$search = $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context . '.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);

		//Filtering catid
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_xiveirm');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.client_id', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param	string		$id	A prefix for the store id.
	 * @return	string		A store id.
	 * @since	1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id.= ':' . $this->getState('filter.search');
		$id.= ':' . $this->getState('filter.state');

		return parent::getStoreId($id);
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

		$query->from('`#__xiveirm_contacts` AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor');
		$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');

		// Join over the category 'catid'
		$query->select('catid.title AS catid');
		$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

		// Join over the category 'gender'
		$query->select('gender.title AS gender');
		$query->join('LEFT', '#__categories AS gender ON gender.id = a.gender');

		// Filter by published state
		$published = $this->getState('filter.state');
		if (is_numeric($published)) {
			$query->where('a.state = '.(int) $published);
		} else if ($published === '') {
			$query->where('(a.state IN (0, 1))');
		}

		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else {
				$search = $db->Quote('%' . $db->escape($search, true) . '%');
				$query->where('( a.client_id LIKE '.$search.'  OR  a.company LIKE '.$search.'  OR  a.last_name LIKE '.$search.'  OR  a.first_name LIKE '.$search.' )');
			}
		}

		//Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid) {
			$query->where("a.catid = '".$db->escape($filter_catid)."'");
		}

		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering');
		$orderDirn = $this->state->get('list.direction');
		if ($orderCol && $orderDirn) {
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	public function getItems()
	{
		$items = parent::getItems();

		return $items;
	}
}