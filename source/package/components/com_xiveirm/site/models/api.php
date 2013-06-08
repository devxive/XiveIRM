<?php
/**
 * @version     3.3.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Xiveirm model.
 */
class XiveirmModelApi extends JModelForm
{
	var $_item = null;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		echo '<h1>YOU ARE HERE => model->populateState</h1>';
	}

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getData($id = null)
	{
		echo '<h1>YOU ARE HERE => model->getData</h1>';
	}

	public function getTable($type = 'Api', $prefix = 'XiveirmTable', $config = array())
	{
		echo '<h1>YOU ARE HERE => model->getTable</h1>';
	}

	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
			if (method_exists($table, 'checkin')) {
				if (!$table->checkin($id)) {
					$this->setError($table->getError());
					return false;
				}
			}
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');

		if ($id)
		{
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
			if (method_exists($table, 'checkout')) {
				if (!$table->checkout($user->get('id'), $id)) {
					$this->setError($table->getError());
					return false;
				}
			}
		}

		return true;
	}    
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
	 * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		echo '<h1>YOU ARE HERE => model->getForm</h1>';
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		echo '<h1>YOU ARE HERE => model->loadFormData</h1>';
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function savetab($data)
	{
		// Now we get raw $data from the controller and have to perform the save request
		// first we have to check if we got any datas and split the data into separate values

		//check if we get any data
		if(!$data)
		{
			// Perform the return array
			$return_arr = array();
			$return_arr["apiReturnCode"] = 1000;
			$return_arr["apiReturnMessage"] = 'The form is completely empty: Neither an appId, a tabId nor a masterdataId is given!';

			return $return_arr;
		}

		// Split and store the identifier vars
		$tab_key	= $data['tabkey']; // This is the tab identifier (a lowercase name => the same as in plugins/masterdatatabs)
		$customer_cid	= $data['customercid']; // This is the item dbId from the #__xiveirm_customer table

		// exclude system based form infos for the new array
		unset($data['tabkey']);
		unset($data['customercid']);

		// Check if in all the datas are values and/or nested arrays from multiselect and reinject the val as key, save all as a clean new array
		$newDataArray = array();
		foreach($data as $key => $val)
		{
			if(!empty($val))
			{
				if(is_array($val))
				{
					$newKey = $key . '_set';
					foreach($val as $newVal)
					{
						$newDataArray[$newKey][$newVal] = true;
					}
				}
				else
				{
					$newDataArray[$key] = $val;
				}
			}
		}

		// JSONize the new array
		$newData = json_encode($newDataArray);

		// Init database vars
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Lets have a look first if we have already a tab with that tab_key saved for this customer! Happens if the user attemp to click more than once on save
		$query
			->select('*')
			->from('#__xiveirm_customer_add')
			->where('customer_cid = ' . $db->quote($customer_cid) . '')
			->where('tab_key = ' . $db->quote($tab_key) . '');

		$db->setQuery($query);
		$result = $db->loadObject();

		if($result) {
			$tab_exist = true;

			// Ok, tab exist, if the array is empty, the user try to delete all values. Lets kill the row!
			// XiveIRM-TODO: Delete the row!
			
		} else {
			$tab_exist = false;
		}

		if(!$tab_exist && $customer_cid != 0)
		{
			// Set the columns
			$columns = array('customer_cid', 'tab_key', 'tab_value', 'ordering');

			// Set the values
			$values = array($db->quote($customer_cid), $db->quote($tab_key), $db->quote($newData), 0);

			$query
				->insert($db->quoteName('#__xiveirm_customer_add'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnCode = 'SAVED';
				$apiReturnMessage = 'Succesfully saved';
			} catch (Exception $e) {
				$apiReturnCode = (int)$e->getCode();
				$apiReturnMessage = $e->getMessage();
			}
		}
		else if($tab_exist && $customer_cid != 0)
		{
			// Set the fields
			$fields = array(
				'customer_cid = ' . $db->quote($customer_cid) . '',
				'tab_key = ' . $db->quote($tab_key) . '',
				'tab_value = ' . $db->quote($newData) . '',
				'ordering = 0');

			$query
				->update($db->quoteName('#__xiveirm_customer_add'))
				->set($fields)
				->where('customer_cid = ' . $db->quote($customer_cid) . '')
				->where('tab_key = ' . $db->quote($tab_key) . '');

			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnCode = 'UPDATED';
				$apiReturnMessage = 'Succesfully updated';
			} catch (Exception $e) {
				$apiReturnCode = (int)$e->getCode();
				$apiReturnMessage = $e->getMessage();
			}
		}
		else
		{
			$apiReturnCode = '1666';
			$apiReturnMessage = 'Unbekannter Fehler';
		}

		// Perform the return array
		$return_arr = array();
		$return_arr["apiReturnCode"] = $apiReturnCode;
		$return_arr["apiReturnMessage"] = $apiReturnMessage;

		return $return_arr;
	}

	function delete($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('api.id');

		if(JFactory::getUser()->authorise('core.delete', 'com_xiveirm') !== true){
			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
			return false;
		}

		$table = $this->getTable();

		if ($table->delete($data['id']) === true) {
			return $id;
		} else {
			return false;
		}

		return true;
	}

	function getCategoryName($id)
	{
		echo '<h1>YOU ARE HERE => model->delete</h1>';
	}
}