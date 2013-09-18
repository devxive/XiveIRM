<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	XiveIRM - Interoperable Relationship Management System
 *
 * @package		XiveIRM
 * @subPackage	Library
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		3.2
 */

defined('_NFW_FRAMEWORK') or die();

/**
 * Component helper class
 */
class IRMFormList
{
	/*
	 * Method to get an array of categories to build a select list for the main category options based on ACL rules
	 *
	 * @param     string    $app       The name of the component as stored in components folder (com_mycomponent)
	 *
	 * @return    array
	 */
	public static function getCategoryOptions($app)
	{
		// Get the child categories without the parent
		$childs = IRMFormHelper::getChildCategories($app, false);

		$list = array();

		foreach ( $childs as $child ) {
			$actions = IRMAccessHelper::getCategoryActions($child->id);
			if ( $actions->get('core.view') ) {
				$list[$child->id] = JText::_($child->title);
			}
		}

		return $list;
	}


	/*
	 * Method to get an array of options based on a gender category as set in component settings
	 *
	 * @return    array    This will return an array with options for the gender select list
	 */
	public static function getGenderOptions()
	{
		// Get the gender category id
		$gender = IRMComponentHelper::getConfigValue('com_xiveirm', 'parent_gender_category');

		if ( !$gender ) {
			return false;
		}

		$list = array();

		// TODO ADD CLAUSE TO SET USERGROUP AND OR ACCESS LVLS
		$conditions = array(
			'catid' => $gender
		);
		$values = NFWDatabase::select('xiveirm_options', '*', $conditions);

		foreach ( $values as $value ) {
			$list[$value->opt_value] = JText::_($value->opt_name);
		}

		return $list;
	}


	/*
	 * Method to get an array of contacts to set as parent contact
	 *
	 * TODO: What type of contacts we should use as parent contact ?? in this case we use only contacts tagged as company
	 *
	 * @return    array    This will return an array with options for the gender select list
	 */
	public static function getParentContactOptions()
	{
		$list = array();

		$select = array(
			'id',
			'customer_id',
			'first_name',
			'last_name',
			'company',
			'dob'
		);
		// TODO add client id(s) to where clause // for each client id one new request to build the optgroups
		$conditions = array(
			'haschilds' => 1
		);
		$values = NFWDatabase::select('xiveirm_contacts', $select, $conditions);

		// return false if no results
		if ( !isset($values[0]) ) {
			return false;
		}

		// TODO: preformat the values as id => string ( $string =  1 optgroup per client_id, #customer_id - company - last_name, first_name (birthday) )
		// Preformat in the IRMHtmlBuilder class similar to client id method
		foreach ( $values as $value ) {
			$customer_id = isset($value->customer_id) ? $value->customer_id : false;
			$company     = isset($value->company)     ? $value->company     : false;
			$last_name   = isset($value->last_name)   ? $value->last_name   : false;
			$first_name  = isset($value->first_name)  ? $value->first_name  : false;
			$dob         = isset($value->dob)         ? $value->dob         : false;

			$opt_name = '';

//			$title = IRMFormName::formatPoiName($option);

			// Build the placeholder between
			if ( $company ) {
				if ( $customer_id ) {
					$opt_name .= $customer_id . ' - ' . $company;
				} else {
					$opt_name .= $company;
				}
			} else if ( $last_name && $first_name ) {
				if ( $customer_id ) {
					$opt_name .= $customer_id . ' - ';
				}
				if ( $dob ) {
					$opt_name .= $last_name . ', ' . $first_name . ' (*' . $dob . ')';
				} else {
					$opt_name .= $last_name . ', ' . $first_name;
				}
			} else if ( $last_name ) {
				if ( $customer_id ) {
					$opt_name .= $customer_id . ' - ';
				}
				if ( $dob ) {
					$opt_name .= $last_name . ' (*' . $dob . ')';
				} else {
					$opt_name .= $last_name;
				}
			} else if ( $first_name ) {
				if ( $customer_id ) {
					$opt_name .= $customer_id . ' - ';
				}
				if ( $dob ) {
					$opt_name .= $first_name . ' (*' . $dob . ')';
				} else {
					$opt_name .= $first_name;
				}
			} else {
				if ( $customer_id ) {
					$opt_name .= $customer_id . ' - ';
				}
				if ( $dob ) {
					$opt_name .= ' (*' . $dob . ')';
				} else {
					$opt_name = false;
				}
			}

			if ( $opt_name != false ) {
				$list[$value->id] = $opt_name;
			}
		}

		return $list;
	}


	/*
	 * Method to build a contact options list based on category id
	 * This will not produce an error message. If user dont want POI's the appripriate POI fields are hidden in form (example: com_xivetranscorder)
	 * @param     int    $catid    The component name / component folder name (com_mycomponent)
	 *
	 * @return    object           Return the list values
	 */
	public function getContactOptions( $catid )
	{
		// If no category id is set, try to get the catid.
		if ( !$catid ) {
			$catid = IRMComponentHelper::getConfigValue('com_xivetranscorder', 'poi_category');
		}

		$listOptions = IRMContactHelper::getList($catid);

		$contact = new stdClass;

		foreach ($listOptions as $option) {
			$title = IRMFormName::formatPoiName($option);
			$key = $option->id;

			$contact->$key = $title;
		}

		return $contact;
	}


	/*
	 * Method to build a transport device options list based on category id. The category id is required! If not set, it raise an error!
	 *
	 * @param     int    $catid    The id of the appropriate category
	 *
	 * @return    object           Return the list values
	 */
	public function getTransportDeviceOptions( $catid = false )
	{
		// If no category id is set, try to get the catid, else raise error
		if ( !$catid ) {
			$catid = IRMComponentHelper::getConfigValue('com_xivetranscorder', 'transport_device_category');
			if ( !$catid ) {
				JError::raiseError(500, 'Transport Device Category is not set in administrator! Please set this option!');
			}
		}

		$list = new stdClass();

		// TODO ADD CLAUSE TO SET USERGROUP AND OR ACCESS LVLS
		$conditions = array(
			'catid' => $catid
		);
		$values = NFWDatabase::select('xiveirm_options', '*', $conditions, 'ARRAY', 'ordering ASC');

		foreach ( $values as $value ) {
			$key = $value->id;
			$list->$key = JText::_($value->opt_name);
		}

		return $list;
	}


	/*
	 * Method to build a transport type options list based on category id. The category id is required! If not set, it raise an error!
	 *
	 * @param     int    $catid    The id of the appropriate category
	 *
	 * @return    object           Return the list values
	 */
	public function getTransportTypeOptions( $catid = false )
	{
		// If no category id is set, try to get the catid, else raise error
		if ( !$catid ) {
			$catid = IRMComponentHelper::getConfigValue('com_xivetranscorder', 'transport_type_category');
			if ( !$catid ) {
				JError::raiseError(500, 'Transport Type Category is not set in administrator! Please set this option!');
			}
		}

		$list = new stdClass();

		// TODO ADD CLAUSE TO SET USERGROUP AND OR ACCESS LVLS
		$conditions = array(
			'catid' => $catid
		);
		$values = NFWDatabase::select('xiveirm_options', '*', $conditions, 'ARRAY', 'ordering ASC');

		foreach ( $values as $value ) {
			$key = $value->id;
			$list->$key = JText::_($value->opt_name);
		}

		return $list;
	}


	/*
	 * Method to build a order type options list based on category id. The category id is required! If not set, it raise an error!
	 *
	 * @param     int    $catid    The id of the appropriate category
	 *
	 * @return    object           Return the list values
	 */
	public function getOrderTypeOptions( $catid = false )
	{
		// If no category id is set, try to get the catid, else raise error
		if ( !$catid ) {
			$catid = IRMComponentHelper::getConfigValue('com_xivetranscorder', 'order_type_category');
			if ( !$catid ) {
				JError::raiseError(500, 'Order Type Category is not set in administrator! Please set this option!');
			}
		}

		$list = new stdClass();

		// TODO ADD CLAUSE TO SET USERGROUP AND OR ACCESS LVLS
		$conditions = array(
			'catid' => $catid
		);
		$values = NFWDatabase::select('xiveirm_options', '*', $conditions, 'ARRAY', 'ordering ASC');

		foreach ( $values as $value ) {
			$key = $value->id;
			$list->$key = JText::_($value->opt_name);
		}

		return $list;
	}


	/*
	 * Method to get the id and formatted (contact) names for list usage, extracted from orders between the given daterange.
	 *
	 * @param     int    $catid    The id of the appropriate category
	 *
	 * @return    object           Return the list values
	 */
	public function getContactFilterOptions( $filter_daterange = false )
	{
		$returnObject = array();

		if ( !$filter_daterange ) {
			// Use Todays values for daterange if nothing is set
			$startTime = strtotime( date('d.m.Y') . ' 00:00:00' );
			$endTime = strtotime( date('d.m.Y') . ' 23:59:59' );
		} else {
			$startTime = $filter_daterange[0];
			$endTime = $filter_daterange[1];
		}

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query
			->select( array('a.contact_id', 'b.*') )
			->from( '#__xiveirm_transcorders AS a' )
			->join( 'INNER', '#__xiveirm_contacts AS b ON (a.contact_id = b.id)' )
			->where( 'a.transport_timestamp >= ' . $startTime . ' AND a.transport_timestamp <= ' . $endTime . '' );

		$db->setQuery($query);

		$results = $db->loadObjectList();

		foreach( $results as $result ) {
			$result->id = $result->contact_id;
			$returnObject[$result->id] = IRMFormName::formatContactName( $result );
		}

		return $returnObject;
	}
}