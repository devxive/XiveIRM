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

		// TODO Add a field where to set if this contact could hace childs!
		$select = array(
			'id',
			'customer_id',
			'first_name',
			'last_name',
			'company'
		);
		// TODO add client id(s) to where clause
		$conditions = array(
			'haschilds' => 1
		);
		$values = NFWDatabase::select('xiveirm_options', $select, $conditions);

		// TODO: preformat the values as id => string ( $string =  1 optgroup per client_id, #customer_id - company - last_name, first_name (birthday) )
		// Preformat in the IRMHtmlBuilder class similar to client id method
//		foreach ( $values as $value ) {
//			$list[$value->opt_value] = JText::_($value->opt_name);
//		}
		$list = $values;

		return $list;
	}
}