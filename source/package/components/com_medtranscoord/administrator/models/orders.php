<?php
/**
 * @version     3.0.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Medtranscoord records.
 */
class MedtranscoordModelorders extends JModelList
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
                'state', 'a.state',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'modified', 'a.modified',
                'access_id', 'a.access_id',
                'client_id', 'a.client_id',
                'customer_cid', 'a.customer_cid',
                'order_id', 'a.order_id',
                'customer_fullname', 'a.customer_fullname',
                'transport_timestamp', 'a.transport_timestamp',
                'f_poi_id', 'a.f_poi_id',
                'f_address_name', 'a.f_address_name',
                'f_address_name_add', 'a.f_address_name_add',
                'f_address_street', 'a.f_address_street',
                'f_address_houseno', 'a.f_address_houseno',
                'f_address_zip', 'a.f_address_zip',
                'f_address_city', 'a.f_address_city',
                'f_address_country', 'a.f_address_country',
                'f_address_lat', 'a.f_address_lat',
                'f_address_long', 'a.f_address_long',
                'f_address_hash', 'a.f_address_hash',
                't_poi_id', 'a.t_poi_id',
                't_address_name', 'a.t_address_name',
                't_address_name_add', 'a.t_address_name_add',
                't_address_street', 'a.t_address_street',
                't_address_houseno', 'a.t_address_houseno',
                't_address_zip', 'a.t_address_zip',
                't_address_city', 'a.t_address_city',
                't_address_country', 'a.t_address_country',
                't_address_lat', 'a.t_address_lat',
                't_address_long', 'a.t_address_long',
                't_address_hash', 'a.t_address_hash',
                'distcalc_device', 'a.distcalc_device',
                'estimated_distance', 'a.estimated_distance',
                'estimated_time', 'a.estimated_time',

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
		$search = $app->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_published', '', 'string');
		$this->setState('filter.state', $published);
        
        
		//Filtering distcalc_device
		$this->setState('filter.distcalc_device', $app->getUserStateFromRequest($this->context.'.filter.distcalc_device', 'filter_distcalc_device', '', 'string'));

        
		// Load the parameters.
		$params = JComponentHelper::getParams('com_medtranscoord');
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
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from('`#__medtranscoord_order` AS a');


    // Join over the users for the checked out user.
    $query->select('uc.name AS editor');
    $query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    
		// Join over the user field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');


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
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('( a.client_id LIKE '.$search.'  OR  a.customer_cid LIKE '.$search.'  OR  a.order_id LIKE '.$search.' )');
			}
		}
        


		//Filtering distcalc_device
		$filter_distcalc_device = $this->state->get("filter.distcalc_device");
		if ($filter_distcalc_device != '') {
			$query->where("a.distcalc_device = '".$db->escape($filter_distcalc_device)."'");
		}        
        
        
		// Add the list ordering clause.
        $orderCol	= $this->state->get('list.ordering');
        $orderDirn	= $this->state->get('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order($db->escape($orderCol.' '.$orderDirn));
        }

		return $query;
	}
}
