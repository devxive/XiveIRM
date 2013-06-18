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

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);

	$search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
	$this->setState('filter.search', preg_replace('/\s+/', ' ', $search));

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

		// Join over the category 'catid'
		$query->select('catid.title AS catid_title');
		$query->join('LEFT', '#__categories AS catid ON catid.id = a.catid');

		// Join over the foreign key 'gender'
		$query->select('#__xiveirm_options_556902.opt_name AS options_opt_name_556902');
		$query->join('LEFT', '#__xiveirm_options AS #__xiveirm_options_556902 ON #__xiveirm_options_556902.opt_value = a.gender');
        
		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = ' . (int) substr($search, 3));
			} else if ($search === '09') {
				$query->where('a.last_name NOT REGEXP \'^[[:alpha:]]\'');
			} else {
				$search = $db->Quote($db->escape($search, true) . '%');
//				$query->where('( a.company LIKE '.$search.'  OR  a.last_name LIKE '.$search.'  OR  a.first_name LIKE '.$search.' )');
				$query->where('a.last_name LIKE ' . $search);
			}
		}

		//Filtering catid
		$filter_catid = $this->state->get("filter.catid");
		if ($filter_catid) {
			$query->where("a.catid = '".$filter_catid."'");
		}

        return $query;
    }
}