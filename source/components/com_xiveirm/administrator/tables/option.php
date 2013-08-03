<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// No direct access
defined('_JEXEC') or die;

/**
 * option Table class
 */
class XiveirmTableoption extends JTable
{
	/**
	 * Constructor
	 *
	 * @param JDatabase A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__xiveirm_options', 'id', $db);
	}


	/**
	 * Overloaded bind function for pre-processing
	 *
	 * @param     array    $array     Named array
	 * @param     mixed    $ignore    An optional array or space separated list of properties to ignore while binding.
	 *
	 * @see                           JTable:bind
	 * @return    mixed               Null if operation was satisfactory, otherwise returns an error
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new JRegistry();
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if(!JFactory::getUser()->authorise('core.admin', 'com_xiveirm.option.'.$array['id'])) {
//			$actions = JFactory::getACL()->getActions('com_xiveirm','option');
//			$default_actions = JFactory::getACL()->getAssetRules('com_xiveirm.option.'.$array['id'])->getData();
//			$array_jaccess = array();
//			foreach($actions as $action) {
//				$array_jaccess[$action->name] = $default_actions[$action->name];
//			}
//			$array['rules'] = $this->JAccessRulestoArray($array_jaccess);
		}

		//Bind the rules
		if (isset($array['rules']) && is_array($array['rules'])) {
//			$rules = new JRules($array['rules']);
//			$this->setRules($rules);
		}

		return parent::bind($array, $ignore);
	}


	/**
	 * Overloaded check function
	 *
	 * @return    boolean    True on success, false on failure
	 */
	public function check()
	{
		if (trim($this->client_id) == '') $this->client_id = $IRMSession::getClientId();

		// Check if a category is set
		if ((int) $this->catid <= 0) {
			$this->setError(JText::_('COM_XIVEIRM_WARNING_SELECT_CATEGORY'));
			return false;
		}

		if ( trim($this->opt_value) == '' ) {
			$this->setError(JText::_('COM_XIVEIRM_WARNING_PROVIDE_VALID_OPT_KEY'));
			return false;
		}

		if ( trim($this->opt_name) == '' ) {
			$this->setError(JText::_('COM_XIVEIRM_WARNING_PROVIDE_VALID_OPT_NAME'));
			return false;
		}

		// Check for selected access level
		if ($this->access <= 0) {
			$tableHelper = JTable::getInstance('Category');
			$table->load($this->catid, true);
			$this->access = $tableHelper->access;
		}

		//If there is an ordering column and this is a new row then get the next ordering value
		if ( property_exists($this, 'ordering') && $this->id == 0 ) $this->ordering = self::getNextOrder();

		// Verify that the option is unique
		$tableHelper = JTable::getInstance('Option', 'XiveirmTable');

		$data  = array('client_id' => $this->client_id, 'catid' => $this->catid, 'opt_value' => $this->opt_value);

		if ($tableHelper->load($data) && ($tableHelper->id != $this->id || $this->id == 0)) {
			$this->setError(JText::_('COM_XIVEIRM_ERROR_OPTION_UNIQUE_OPTION'));
			return false;
		}

		// Do this in admin since we use the XAP default admin panel to edit values
		// DEPRECATED IN 6.0
		if( JFactory::getApplication()->isAdmin() ) {
			$date = JFactory::getDate();
			$user = JFactory::getUser();

			if ($this->id) {
				// Existing item
//				$this->modified    = $date->toSql();
//				$this->modified_by = $user->get('id');
			}
			else {
				// New item. A project created_by field can be set by the user,
				// so we don't touch it if set.
//				$this->created = $date->toSql();
//				if ( empty($this->created_by) ) $this->created_by = $user->get('id');
			}
		}

		return true;
	}


	/**
	 * Overrides JTable::store to set modified data and user id.
	 *
	 * @param     boolean    True to update fields even if they are null.
	 *
	 * @return    boolean    True on success.
	 */
	public function store($updateNulls = false)
	{
		return parent::store($updateNulls);
	}


	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table.
	 *
	 * @param     mixed      $pks      An optional array of primary key values to update.
	 * @param     integer    $state    The publishing state
	 * @param     integer    $uid      The user id of the user performing the operation.
	 *
	 * @return    boolean              True on success.
	 */
	public function publish($pks = null, $state = 1, $uid = 0)
	{
		return $this->setState($pks, $state, $uid);
	}


	/**
	 * Method to set the state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param     mixed      $pks      An optional array of primary key values to update.
	 * @param     integer    $state    The state. eg. [0 = unpublished, 1 = published]
	 * @param     integer    $uid      The user id of the user performing the operation.
	 *
	 * @return    boolean              True on success.
	 */
	public function setState($pks = null, $state = 1, $uid = 0)
	{
		// Sanitize input.
		JArrayHelper::toInteger($pks);

		$k     = $this->_tbl_key;
		$uid   = (int) $uid;
		$state = (int) $state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			}
			else {
				// Nothing to set state on, return false.
				$this->setError(JText::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . '=' . implode(' OR ' . $k . '=', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		}
		else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $uid . ')';
		}

		// Update the state for rows with the given primary keys.
		$this->_db->setQuery(
			'UPDATE ' . $this->_db->quoteName($this->_tbl).
			' SET ' . $this->_db->quoteName('state').' = ' .(int) $state .
			' WHERE (' . $where . ')' .
			checkin
		);
		$this->_db->query();

		// Check for a database error.
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach($pks as $pk)
			{
				$this->checkin($pk);
			}
		}

		// If the JTable instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}


	/**
	 * This function convert an array of JAccessRule objects into an rules array.
	 * @param type $jaccessrules an arrao of JAccessRule objects.
	 */
	private function JAccessRulestoArray($jaccessrules)
	{
		$rules = array();
		foreach($jaccessrules as $action => $jaccess){
			$actions = array();
			foreach($jaccess->getData() as $group => $allow){
				$actions[$group] = ((bool)$allow);
			}
			$rules[$action] = $actions;
		}

		return $rules;
	}
}