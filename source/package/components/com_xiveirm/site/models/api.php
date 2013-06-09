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
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
//		// Get the id.
//		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');
//
//		if ($id)
//		{
//			// Initialise the table
//			$table = $this->getTable();
//
//			// Attempt to check the row in.
//			if (method_exists($table, 'checkin')) {
//				if (!$table->checkin($id)) {
//					$this->setError($table->getError());
//					return false;
//				}
//			}
//
//		}
//
//		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null, $row)
	{
//		// Get the user id.
//		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');
//
//		if (empty($row)) {
//			return false;
//		}
//
//	// Set the fields
//	$fields = array(
//		'customer_cid = ' . $db->quote($customer_cid) . '',
//		'tab_key = ' . $db->quote($tab_key) . '');
//
//	$query
//		->update($db->quoteName('#__xiveirm_customer'))
//		->set($fields)
//		->where('id = ' . $db->quote($row) . '');
//
//	$db->setQuery($query);
//
//	// Try to store or get the error code for debugging
//	try
//	{
//		$db->execute();
//		$apiReturnCode = 'OUT';
//		$apiReturnMessage = 'Succesfully updated';
//	} catch (Exception $e) {
//		$apiReturnCode = (int)$e->getCode();
//		$apiReturnMessage = $e->getMessage();
//	}
//
//		if ($id)
//		{
//			// Initialise the table
//			$table = $this->getTable();
//
//			// Init database object.
//			$db = JFactory::getDBO();
//			$query = $db->getQuery(true);
//
//			// Get the current user object.
//			$user = JFactory::getUser();
//
//			// Attempt to check the row out.
//			if (method_exists($table, 'checkout')) {
//				if (!$table->checkout($user->get('id'), $id)) {
//					$this->setError($table->getError());
//					return false;
//				}
//			}
//		}
//
//		return true;
	}    
    
	/**
	 * Method to save the coreform data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function savecore($data)
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

//		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('irmcustomer.id');
//		$state = (!empty($data['state'])) ? 1 : 0;
//		$user = JFactory::getUser();
//
//		if($id)
//		{
//			//Check the user can edit this item
//			$authorised = $user->authorise('core.edit', 'com_xiveirm.irmcustomer.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_xiveirm.irmcustomer.'.$id);
//			if($user->authorise('core.edit.state', 'com_xiveirm.irmcustomer.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
//				$data['state'] = 0;
//			}
//		} else {
//			//Check the user can create new items in this section
//			$authorised = $user->authorise('core.create', 'com_xiveirm');
//			if($user->authorise('core.edit.state', 'com_xiveirm.irmcustomer.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
//				$data['state'] = 0;
//			}
//		}
//
//		if ($authorised !== true) {
//			JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
//			return false;
//		}

		// Check if we have an id for an update, else we have to create
		isset($data['id']) ? $id = (int)$data['id'] : $id = 0;

		// Init database object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
				
		if($id == 0)
		{
			// Do the create new contact check if all needed datas are in there, else return false with error codes
			if (!isset($data['created_by'])
				|| !isset($data['client_id'])
				|| !isset($data['customer_id'])
				|| !isset($data['company_name'])
				|| !isset($data['title'])
				|| !isset($data['last_name'])
				|| !isset($data['first_name'])
				|| !isset($data['gender'])
				|| !isset($data['dob'])
				|| !isset($data['address_name'])
				|| !isset($data['address_name_add'])
				|| !isset($data['address_street'])
				|| !isset($data['address_houseno'])
				|| !isset($data['address_zip'])
				|| !isset($data['address_city'])
				|| !isset($data['address_country'])
				|| !isset($data['phone'])
				|| !isset($data['fax'])
				|| !isset($data['mobile'])
				|| !isset($data['email'])
				|| !isset($data['web'])
				|| !isset($data['remarks']))
			{
				$apiReturnId = null;
				$apiReturnCode = 'ERROR';
				$apiReturnMessage = 'An Error occured while checking if all form fields are send correctly';
			} else {
				// Set the columns
				$columns = array(
					'created',
					'created_by',
					'client_id',
					'customer_id',
					'company_name',
					'title',
					'last_name',
					'first_name',
					'gender',
					'dob',
					'address_name',
					'address_name_add',
					'address_street',
					'address_houseno',
					'address_zip',
					'address_city',
					'address_country',
					'phone',
					'fax',
					'mobile',
					'email',
					'web',
					'remarks');
	
				// Set the values
				$values = array(
					$db->quote(date('Y-m-d H:i:s')),
					$db->quote($data['created_by']),
					$db->quote($data['client_id']),
					$db->quote($data['customer_id']),
					$db->quote($data['company_name']),
					$db->quote($data['title']),
					$db->quote($data['last_name']),
					$db->quote($data['first_name']),
					$db->quote($data['gender']),
					$db->quote($data['dob']),
					$db->quote($data['address_name']),
					$db->quote($data['address_name_add']),
					$db->quote($data['address_street']),
					$db->quote($data['address_houseno']),
					$db->quote($data['address_zip']),
					$db->quote($data['address_city']),
					$db->quote($data['address_country']),
					$db->quote($data['phone']),
					$db->quote($data['fax']),
					$db->quote($data['mobile']),
					$db->quote($data['email']),
					$db->quote($data['web']),
					$db->quote($data['remarks']));
	
				$query
					->insert($db->quoteName('#__xiveirm_customer'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
				$db->setQuery($query);
	
				// Try to store or get the error code for debugging
				$apiReturn = array();
	
				try
				{
					$db->execute();
					$apiReturnId = $db->insertid();
					$apiReturnCode = 'SAVED';
					$apiReturnMessage = 'Succesfully saved';
				} catch (Exception $e) {
					$apiReturnId = null;
					$apiReturnCode = (int)$e->getCode();
					$apiReturnMessage = $e->getMessage();
				}
			}

		}
		else if($id > 0)
		{
			// Do the update contact check if all needed datas are in there, else return false with error codes
			if (!isset($data['client_id'])
				|| !isset($data['customer_id'])
				|| !isset($data['company_name'])
				|| !isset($data['title'])
				|| !isset($data['last_name'])
				|| !isset($data['first_name'])
				|| !isset($data['gender'])
				|| !isset($data['dob'])
				|| !isset($data['address_name'])
				|| !isset($data['address_name_add'])
				|| !isset($data['address_street'])
				|| !isset($data['address_houseno'])
				|| !isset($data['address_zip'])
				|| !isset($data['address_city'])
				|| !isset($data['address_country'])
				|| !isset($data['phone'])
				|| !isset($data['fax'])
				|| !isset($data['mobile'])
				|| !isset($data['email'])
				|| !isset($data['web'])
				|| !isset($data['remarks']))
			{
				$apiReturnId = null;
				$apiReturnCode = 'ERROR';
				$apiReturnMessage = 'An Error occured while checking if all form fields are send correctly';
			}
			else
			{
				// Set the fields
				$fields = array(
					'client_id = ' . $db->quote($data['client_id']) . '',
					'customer_id = ' . $db->quote($data['customer_id']) . '',
					'company_name = ' . $db->quote($data['company_name']) . '',
					'title = ' . $db->quote($data['title']) . '',
					'last_name = ' . $db->quote($data['last_name']) . '',
					'first_name = ' . $db->quote($data['first_name']) . '',
					'gender = ' . $db->quote($data['gender']) . '',
					'dob = ' . $db->quote($data['dob']) . '',
					'address_name = ' . $db->quote($data['address_name']) . '',
					'address_name_add = ' . $db->quote($data['address_name_add']) . '',
					'address_street = ' . $db->quote($data['address_street']) . '',
					'address_houseno = ' . $db->quote($data['address_houseno']) . '',
					'address_zip = ' . $db->quote($data['address_zip']) . '',
					'address_city = ' . $db->quote($data['address_city']) . '',
					'address_country = ' . $db->quote($data['address_country']) . '',
					'phone = ' . $db->quote($data['phone']) . '',
					'fax = ' . $db->quote($data['fax']) . '',
					'mobile = ' . $db->quote($data['mobile']) . '',
					'email = ' . $db->quote($data['email']) . '',
					'web = ' . $db->quote($data['web']) . '',
					'remarks = ' . $db->quote($data['remarks']) . '');
	
				$query
					->update($db->quoteName('#__xiveirm_customer'))
					->set($fields)
					->where('id = ' . $db->quote($id) . '');
	
				$db->setQuery($query);
	
				// Try to store or get the error code for debugging
				try
				{
					$db->execute();
					$apiReturnId = $id;
					$apiReturnCode = 'UPDATED';
					$apiReturnMessage = 'Succesfully updated';
				} catch (Exception $e) {
					$apiReturnId = null;
					$apiReturnCode = (int)$e->getCode();
					$apiReturnMessage = $e->getMessage();
				}
			}
		}
		else
		{
			$apiReturnId = null;
			$apiReturnCode = '1666';
			$apiReturnMessage = 'Unbekannter Fehler';
		}
	
		// Perform the return array
		$return_arr = array();
		$return_arr["apiReturnId"] = (int)$apiReturnId;
		$return_arr["apiReturnCode"] = $apiReturnCode;
		$return_arr["apiReturnMessage"] = $apiReturnMessage;

		return $return_arr;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function savetab($data, $customer_cid, $tab_key)
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

		// Init database object.
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

	function getCategoryName($id)
	{
		echo '<h1>YOU ARE HERE => model->delete</h1>';
	}








}