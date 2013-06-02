<?php
/**
 * @version     3.1.0
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
		echo '<h1>YOU ARE HERE => model->checkin</h1>';
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
		echo '<h1>YOU ARE HERE => model->checkout</h1>';
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
	public function save($data)
	{
		// Now we get raw $data from the controller and have to perform the save request
		// first we have to split the data into separate values

		//check if we get any data
		if(!$data)
		{
			// Perform the return array
			$return_arr = array();
			$return_arr["apiReturnCode"] = 505;
			$return_arr["apiReturnRowId"] = null;
			$return_arr["apiReturnMessage"] = 'There is eiter an api nor an app request given!';

			return $return_arr;
		}

		// Store the identifier vars
		$dataTabId		= $data['id']; // This is the data tab id, the id from the masterdata_add db row
		$tabAppId		= $data['tabappid']; // This is the tab identifier (a lowercase name => the same as in plugins/masterdatatabs)
		$masterDataItemId	= $data['masterdataitemid']; // This is the item dbId from the #__xiveirm_masterdata table
		$direction		= $data['direction']; // This is the api direction (where the data come from "ajax" or "in future whatever" we use at this time only the ajax variant)
		$valueFormat		= $data['valueformat']; // The format we use to store the submitted values in the database

		// set the tab_id for the database
		$tabId = $tabAppId . '.' . $masterDataItemId;

		// exclude system based infos for the new array
		unset($data['id']);
		unset($data['tabappid']);
		unset($data['masterdataitemid']);
		unset($data['direction']);
		unset($data['valueformat']);

		// Check if in all the datas are values and/or nested arrays from multiselect and reinject the val as key
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

		// Build a new array if stored in a single row or as json encoded values || At this time json is the only way to store values in the database
		if($valueFormat == 'json')
		{
			$newData = json_encode($newDataArray);
		}

		// Lets save the data in the database
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Lets have a look if we have already a tab saved for this customer! Happens if the user attemp to click more than once on save
		if($dataTabId == '0')
		{
			$query
				->select('*')
				->from('#__xiveirm_masterdata_add')
				->where('tab_id = ' . $db->quote($tabId) . '');
			$db->setQuery($query);

			$dataTabId = $db->loadObject()->id;
		}

		if($dataTabId == '0' || !$dataTabId)
		{
			// Set the columns
			$columns = array('tab_id', 'tab_field_id', 'tab_value', 'state', 'ordering');

			// Set the values
			$values = array($db->quote($tabId), 0, $db->quote($newData), 1, 0);

			$query
				->insert($db->quoteName('#__xiveirm_masterdata_add'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnCode = 'SAVED';
				$apiReturnRowId = $db->insertid();
				$apiReturnMessage = 'Succesfully saved';
			} catch (Exception $e) {
				$apiReturnRowId = null;
				$apiReturnCode = (int)$e->getCode();
				$apiReturnMessage = $e->getMessage();
			}
		}
		else if($dataTabId > '0')
		{
			// Set the fields
			$fields = array(
				'tab_id = ' . $db->quote($tabId) . '',
				'tab_field_id = 0',
				'tab_value = ' . $db->quote($newData) . '',
				'state = 1',
				'ordering = 0');

			$query
				->update($db->quoteName('#__xiveirm_masterdata_add'))
				->set($fields)
				->where('id = ' . $db->quote($dataTabId) . '');

			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnCode = 'UPDATED';
				$apiReturnRowId = $dataTabId;
				$apiReturnMessage = 'Succesfully updated';
			} catch (Exception $e) {
				$apiReturnCode = (int)$e->getCode();
				$apiReturnRowId = null;
				$apiReturnMessage = $e->getMessage();
			}
		}
		else
		{
			$apiReturnRowId = null;
			$apiReturnCode = 666;
			$apiReturnMessage = 'The dataTabId is either 0 nor an integer greater than 0';

//			JError::raiseError(500, 'There is either an api nor an app request given!');
//			return false;
		}

		// Perform the return array
		$return_arr = array();
		$return_arr["apiReturnCode"] = $apiReturnCode;
		$return_arr["apiReturnRowId"] = $apiReturnRowId;
		$return_arr["apiReturnMessage"] = $apiReturnMessage;

		return $return_arr;
	}

	function delete($data)
	{
		echo '<h1>YOU ARE HERE => model->delete</h1>';
	}

	function getCategoryName($id)
	{
		echo '<h1>YOU ARE HERE => model->getCategoryName</h1>';
	}
}