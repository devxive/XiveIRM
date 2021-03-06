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
class XiveirmModelplugins extends JModelList {

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
				'plugin', 'a.plugin',
				'catid', 'a.catid',
				'config', 'a.config',
				'state', 'a.state',
				'ordering', 'a.ordering',
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

		//Filtering client_id
		$this->setState('filter.client_id', $app->getUserStateFromRequest($this->context.'.filter.client_id', 'filter_client_id', '', 'string'));

		//Filtering plugin
		$this->setState('filter.plugin', $app->getUserStateFromRequest($this->context.'.filter.plugin', 'filter_plugin', '', 'string'));

		//Filtering catid
		$this->setState('filter.catid', $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid', '', 'string'));

		// Load the parameters.
		$params = JComponentHelper::getParams('com_xiveirm');
 		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.id', 'asc');
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

		$query->from('`#__xiveirm_plugins` AS a');

		// Join over the usergroup field 'client_id'
		$query->select('client_id.title AS client_id');
		$query->join('LEFT', '#__usergroups AS client_id ON client_id.id = a.client_id');

		// Join over the category 'catid'
		$query->select('catid.title AS catid');
		$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

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
				$query->where('( a.client_id LIKE '.$search.'  OR  a.plugin LIKE '.$search.'  OR  a.catid LIKE '.$search.' )');
			}
		}

		//Filtering client_id
		$filter_client_id = $this->state->get("filter.client_id");
		if ($filter_client_id) {
			$query->where("a.client_id = '".$db->escape($filter_client_id)."'");
		}

		//Filtering plugin
		$filter_plugin = $this->state->get("filter.plugin");
		if ($filter_plugin) {
			$query->where("a.plugin = '".$db->escape($filter_plugin)."'");
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