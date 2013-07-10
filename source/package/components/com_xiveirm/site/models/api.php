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
nimport('NItem.Helper', false);

/**
 * Xiveirm model.
 */
class XiveirmModelApi extends JModelForm
{
	var $_item = null;

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
			$return_arr["apiReturnId"] = 0;
			$return_arr["apiReturnCode"] = 2000;
			$return_arr["apiReturnMessage"] = 'The form is completely empty: Neither an appId, a tabId nor a masterdataId is given!';

			return $return_arr;
		}

//		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('contacts.id');
//		$state = (!empty($data['state'])) ? 1 : 0;
//		$user = JFactory::getUser();
//
//		if($id)
//		{
//			//Check the user can edit this item
//			$authorised = $user->authorise('core.edit', 'com_xiveirm.contacts.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_xiveirm.contacts.'.$id);
//			if($user->authorise('core.edit.state', 'com_xiveirm.contacts.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
//				$data['state'] = 0;
//			}
//		} else {
//			//Check the user can create new items in this section
//			$authorised = $user->authorise('core.create', 'com_xiveirm');
//			if($user->authorise('core.edit.state', 'com_xiveirm.contacts.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
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
				|| !isset($data['parent_id'])
				|| !isset($data['catid'])
				|| !isset($data['customer_id'])
				|| !isset($data['company'])
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
				|| !isset($data['address_region'])
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
					'client_id',
					'parent_id',
					'created',
					'created_by',
					'catid',
					'customer_id',
					'company',
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
					'address_region',
					'address_country',
					'phone',
					'fax',
					'mobile',
					'email',
					'web',
					'remarks');
	
				// Set the values
				$values = array(
					$db->quote($data['client_id']),
					$db->quote($data['parent_id']),
					$db->quote(date('Y-m-d H:i:s')),
					$db->quote($data['created_by']),
					$db->quote($data['catid']),
					$db->quote($data['customer_id']),
					$db->quote($data['company']),
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
					$db->quote($data['address_region']),
					$db->quote($data['address_country']),
					$db->quote($data['phone']),
					$db->quote($data['fax']),
					$db->quote($data['mobile']),
					$db->quote($data['email']),
					$db->quote($data['web']),
					$db->quote($data['remarks']));
	
				$query
					->insert($db->quoteName('#__xiveirm_contacts'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
				$db->setQuery($query);
	
				// Try to store or get the error code for debugging
				$apiReturn = array();
	
				try
				{
					$db->execute();
					$apiReturnId = (int)$db->insertid();
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
				|| !isset($data['parent_id'])
				|| !isset($data['catid'])
				|| !isset($data['customer_id'])
				|| !isset($data['company'])
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
				|| !isset($data['address_region'])
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
					'parent_id = ' . $db->quote($data['parent_id']) . '',
					'catid = ' . $db->quote($data['catid']) . '',
					'customer_id = ' . $db->quote($data['customer_id']) . '',
					'company = ' . $db->quote($data['company']) . '',
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
					'address_region = ' . $db->quote($data['address_region']) . '',
					'address_country = ' . $db->quote($data['address_country']) . '',
					'phone = ' . $db->quote($data['phone']) . '',
					'fax = ' . $db->quote($data['fax']) . '',
					'mobile = ' . $db->quote($data['mobile']) . '',
					'email = ' . $db->quote($data['email']) . '',
					'web = ' . $db->quote($data['web']) . '',
					'remarks = ' . $db->quote($data['remarks']) . '',
					'modified = ' . $db->quote(NItemHelper::getDate('MySQL')) . '');

				$query
					->update($db->quoteName('#__xiveirm_contacts'))
					->set($fields)
					->where('id = ' . $db->quote($id) . '');

				$db->setQuery($query);

				// Try to store or get the error code for debugging
				try
				{
					$db->execute();
					$apiReturnId = (int)$id;
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
			$apiReturnCode = 2066;
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
	public function savetab($data, $contact_id, $tab_key)
	{
		// Now we get raw $data from the controller and have to perform the save request
		// first we have to check if we got any datas and split the data into separate values

		//check if we get any data
		if(!$data)
		{
			// Perform the return array
			$return_arr = array();
			$return_arr["apiReturnId"] = (int)$contact_id;
			$return_arr["apiReturnCode"] = 2500;
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
			->from('#__xiveirm_contact_tabappvalues')
			->where('contact_id = ' . $db->quote($contact_id) . '')
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

		if(!$tab_exist && $contact_id != 0)
		{
			// Set the columns
			$columns = array('contact_id', 'tab_key', 'tab_value');

			// Set the values
			$values = array($db->quote($contact_id), $db->quote($tab_key), $db->quote($newData));

			$query
				->insert($db->quoteName('#__xiveirm_contact_tabappvalues'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnId = (int)$contact_id;
				$apiReturnCode = 'SAVED';
				$apiReturnMessage = 'Succesfully saved';
			} catch (Exception $e) {
				$apiReturnId = null;
				$apiReturnCode = (int)$e->getCode();
				$apiReturnMessage = $e->getMessage();
			}
		}
		else if($tab_exist && $contact_id != 0)
		{
			// Set the fields
			$fields = array(
				'contact_id = ' . $db->quote($contact_id) . '',
				'tab_key = ' . $db->quote($tab_key) . '',
				'tab_value = ' . $db->quote($newData) . '');

			$query
				->update($db->quoteName('#__xiveirm_contact_tabappvalues'))
				->set($fields)
				->where('contact_id = ' . $db->quote($contact_id) . '')
				->where('tab_key = ' . $db->quote($tab_key) . '');

			$db->setQuery($query);

			// Try to store or get the error code for debugging
			try
			{
				$db->execute();
				$apiReturnId = (int)$contact_id;
				$apiReturnCode = 'UPDATED';
				$apiReturnMessage = 'Succesfully updated';
			} catch (Exception $e) {
				$apiReturnId = null;
				$apiReturnCode = (int)$e->getCode();
				$apiReturnMessage = $e->getMessage();
			}
		}
		else
		{
			$apiReturnId = (int)$contact_id;
			$apiReturnCode = 2566;
			$apiReturnMessage = 'Unbekannter Fehler';
		}

		// Perform the return array
		$return_arr = array();
		$return_arr["apiReturnId"] = (int)$contact_id;
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

	/**
	 * Method to get the data that should be injected in the list.
	 *
	 * @return	mixed	The data for the list.
	 * @since	4.3
	 */
	public function prepareList()
	{
		/*
		 * TODO: We have to build a dataTable class in the nawala framework, where we call the jQuery dataTable functionality
		 * 	- What we can try is, that we may pass some functions for the dataTable via an $params array
		 * 	- 2 options: First, we have a table and whish to extend with normal features.
		 * 
		 */
		$app = JFactory::getApplication();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$columns = array( 'last_name', 'first_name', 'gender', 'phone', 'remarks' );

		$limitStart = $app->input->getInt('iDisplayStart', null, 'array');
		$limitEnd = $app->input->getInt('iDisplayLength', null, 'array');
		$iSortCol_0 = $app->input->getInt('iSortCol_0', null, 'array');
		$search = $app->input->getInt('sSearch', null, 'array');


		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$columns = array( 'id', 'last_name', 'first_name', 'gender', 'phone', 'remarks' );
	
		/* Indexed column (used for fast and accurate table cardinality) */
		$sIndexColumn = "id";
	
		/* DB table to use */
		$sTable = "mc3_patient_base_data";
	
		/* Database connection information */
		$gaSql['user']       = "dbo_lst-14673146";
		$gaSql['password']   = "tJ*BdpiT33AZFwpRtJ*BdpiT33AZFwpR";
		$gaSql['db']         = "db0_lst-14673146";
		$gaSql['server']     = "localhost";
	
		/* 
		 * MySQL connection
		 */
		$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
			die( 'Could not open connection to server' );
	
		mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
			die( 'Could not select database '. $gaSql['db'] );
	
		// Paging -- gecheckt!
		$sLimit = "";
		if(isset($limitStart) && $limitEnd != '-1')
		{
			$sLimit = "LIMIT ".intval($limitStart).", ".intval($limitEnd);
		}
	
		/*
		 * Ordering
		 */
		$sOrder = "";
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= "`".$columns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
						($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited
		 */
		$sWhere = "";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($columns) ; $i++ )
			{
				$sWhere .= "`".$columns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */
		for ( $i=0 ; $i<count($columns) ; $i++ )
		{
			if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= "`".$columns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $columns))."`
			FROM   $sTable
			$sWhere
			$sOrder
			$sLimit
			";
		$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS()
		";
		$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(`".$sIndexColumn."`)
			FROM   $sTable
		";
		$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultTotal = mysql_fetch_array($rResultTotal);
		$iTotal = $aResultTotal[0];
		
		
		/*
		 * Output
		 */
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array();
			for ( $i=0 ; $i<count($columns) ; $i++ )
			{
				$row[ $columns[$i] ] = $aRow[ $columns[$i] ];
			}

			// Adding
			$row[ 'DT_RowId' ] = $aRow[ 'id' ];

			$output['aaData'][] = $row;
		}

		return $output;
	}





// ------------------- BEGIN OF ORIGINAL


//    var $_item = null;
//    
//	/**
//	 * Method to auto-populate the model state.
//	 *
//	 * Note. Calling getState in this method will result in recursion.
//	 *
//	 * @since	1.6
//	 */
//	protected function populateState()
//	{
//		$app = JFactory::getApplication('com_xiveirm');
//
//		// Load state from the request userState on edit or from the passed variable on default
//        if (JFactory::getApplication()->input->get('layout') == 'edit') {
//            $id = JFactory::getApplication()->getUserState('com_xiveirm.edit.api.id');
//        } else {
//            $id = JFactory::getApplication()->input->get('id');
//            JFactory::getApplication()->setUserState('com_xiveirm.edit.api.id', $id);
//        }
//		$this->setState('api.id', $id);
//
//		// Load the parameters.
//		$params = $app->getParams();
//        $params_array = $params->toArray();
//        if(isset($params_array['item_id'])){
//            $this->setState('api.id', $params_array['item_id']);
//        }
//		$this->setState('params', $params);
//
//	}
//        
//
//	/**
//	 * Method to get an ojbect.
//	 *
//	 * @param	integer	The id of the object to get.
//	 *
//	 * @return	mixed	Object on success, false on failure.
//	 */
//	public function &getData($id = null)
//	{
//		if ($this->_item === null)
//		{
//			$this->_item = false;
//
//			if (empty($id)) {
//				$id = $this->getState('api.id');
//			}
//
//			// Get a level row instance.
//			$table = $this->getTable();
//
//			// Attempt to load the row.
//			if ($table->load($id))
//			{
//				// Check published state.
//				if ($published = $this->getState('filter.published'))
//				{
//					if ($table->state != $published) {
//						return $this->_item;
//					}
//				}
//
//				// Convert the JTable to a clean JObject.
//				$properties = $table->getProperties(1);
//				$this->_item = JArrayHelper::toObject($properties, 'JObject');
//			} elseif ($error = $table->getError()) {
//				$this->setError($error);
//			}
//		}
//
//		return $this->_item;
//	}
//    
//	public function getTable($type = 'Api', $prefix = 'XiveirmTable', $config = array())
//	{   
//        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
//        return JTable::getInstance($type, $prefix, $config);
//	}     
//
//    
//	/**
//	 * Method to check in an item.
//	 *
//	 * @param	integer		The id of the row to check out.
//	 * @return	boolean		True on success, false on failure.
//	 * @since	1.6
//	 */
//	public function checkin($id = null)
//	{
//		// Get the id.
//		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');
//
//		if ($id) {
//            
//			// Initialise the table
//			$table = $this->getTable();
//
//			// Attempt to check the row in.
//            if (method_exists($table, 'checkin')) {
//                if (!$table->checkin($id)) {
//                    $this->setError($table->getError());
//                    return false;
//                }
//            }
//		}
//
//		return true;
//	}
//
//	/**
//	 * Method to check out an item for editing.
//	 *
//	 * @param	integer		The id of the row to check out.
//	 * @return	boolean		True on success, false on failure.
//	 * @since	1.6
//	 */
//	public function checkout($id = null)
//	{
//		// Get the user id.
//		$id = (!empty($id)) ? $id : (int)$this->getState('api.id');
//
//		if ($id) {
//            
//			// Initialise the table
//			$table = $this->getTable();
//
//			// Get the current user object.
//			$user = JFactory::getUser();
//
//			// Attempt to check the row out.
//            if (method_exists($table, 'checkout')) {
//                if (!$table->checkout($user->get('id'), $id)) {
//                    $this->setError($table->getError());
//                    return false;
//                }
//            }
//		}
//
//		return true;
//	}    
//    
//	/**
//	 * Method to get the profile form.
//	 *
//	 * The base form is loaded from XML 
//     * 
//	 * @param	array	$data		An optional array of data for the form to interogate.
//	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
//	 * @return	JForm	A JForm object on success, false on failure
//	 * @since	1.6
//	 */
//	public function getForm($data = array(), $loadData = true)
//	{
//		// Get the form.
//		$form = $this->loadForm('com_xiveirm.api', 'api', array('control' => 'jform', 'load_data' => $loadData));
//		if (empty($form)) {
//			return false;
//		}
//
//		return $form;
//	}
//
//	/**
//	 * Method to get the data that should be injected in the form.
//	 *
//	 * @return	mixed	The data for the form.
//	 * @since	1.6
//	 */
//	protected function loadFormData()
//	{
//		$data = $this->getData(); 
//        
//        return $data;
//	}
//
//	/**
//	 * Method to save the form data.
//	 *
//	 * @param	array		The form data.
//	 * @return	mixed		The user id on success, false on failure.
//	 * @since	1.6
//	 */
//	public function save($data)
//	{
//		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('api.id');
//        $state = (!empty($data['state'])) ? 1 : 0;
//        $user = JFactory::getUser();
//
//        if($id) {
//            //Check the user can edit this item
//            $authorised = $user->authorise('core.edit', 'com_xiveirm.tabapp.'.$id) || $authorised = $user->authorise('core.edit.own', 'com_xiveirm.tabapp.'.$id);
//            if($user->authorise('core.edit.state', 'com_xiveirm.tabapp.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
//                $data['state'] = 0;
//            }
//        } else {
//            //Check the user can create new items in this section
//            $authorised = $user->authorise('core.create', 'com_xiveirm');
//            if($user->authorise('core.edit.state', 'com_xiveirm.tabapp.'.$id) !== true && $state == 1){ //The user cannot edit the state of the item.
//                $data['state'] = 0;
//            }
//        }
//
//        if ($authorised !== true) {
//            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
//            return false;
//        }
//        
//        $table = $this->getTable();
//        if ($table->save($data) === true) {
//            return $id;
//        } else {
//            return false;
//        }
//        
//	}
//    
//     function delete($data)
//    {
//        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('api.id');
//        if(JFactory::getUser()->authorise('core.delete', 'com_xiveirm.tabapp.'.$id) !== true){
//            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
//            return false;
//        }
//        $table = $this->getTable();
//        if ($table->delete($data['id']) === true) {
//            return $id;
//        } else {
//            return false;
//        }
//        
//        return true;
//    }
//    
//    function getCategoryName($id){
//        $db = JFactory::getDbo();
//        $query = $db->getQuery(true);
//        $query 
//            ->select('title')
//            ->from('#__categories')
//            ->where('id = ' . $id);
//        $db->setQuery($query);
//        return $db->loadObject();
//    }




// ------------------- END OF ORIGINAL


}