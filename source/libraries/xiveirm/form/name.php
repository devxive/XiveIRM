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
class IRMFormName
{
	/*
	 * Method to format names
	 *
	 * @param     array    $values    The name of the component as stored in components folder (com_mycomponent)
	 *
	 * @return    string
	 */
	public static function formatPoiName( $values )
	{
		// Preformat in the IRMHtmlBuilder class similar to client id method
		$id                     = isset($values->id)                     ? $values->id                     : false;
		$address_name           = isset($values->address_name)           ? $values->address_name           : false;
		$address_name_add       = isset($values->address_name_add)       ? $values->address_name_add       : false;
		$company                = isset($values->company)                ? $values->company                : false;
		$last_name              = isset($values->last_name)              ? $values->last_name              : false;
		$first_name             = isset($values->first_name)             ? $values->first_name             : false;
		$address_system_checked = isset($values->address_system_checked) ? $values->address_system_checked : false;

		$opt_name = '';

		// Build the placeholder between
		if ( $address_system_checked ) {
			$opt_name .= '* ';
		}

		if ( $address_name && $address_name_add ) {
			$opt_name .= $address_name . ' (' . $address_name_add . ')';
		} else if ( $address_name ) {
			$opt_name .= $address_name;
		} else if ( !$address_name && !$address_name_add && $company ) {
			$opt_name .= $company;
		} else if ( !$address_name && $address_name_add && $company ) {
			$opt_name .= $company . ' (' . $address_name_add . ')';
		} else if ( !$address_name && !$address_name_add && $company && $last_name && $first_name ) {
			$opt_name .= $company . ' - ' . $last_name . ', ' . $first_name;
		} else if ( !$address_name && !$address_name_add && !$company && $last_name && $first_name ) {
			$opt_name .= $last_name . ', ' . $first_name;
		} else if ( !$address_name && !$address_name_add && !$company && $last_name ) {
			$opt_name .= $last_name;
		} else if ( !$address_name && !$address_name_add && !$company && $first_name ) {
			$opt_name .= $first_name;
		} else {
			$opt_name = false;
		}

		if ( !$opt_name ) {
			$opt_name = '*** Undefined name for ID: ' . $id . ' ***';
		}

		return $opt_name;
	}


	/*
	 * Method to format names
	 *
	 * @param     array    $values    The name of the component as stored in components folder (com_mycomponent)
	 *
	 * @return    string
	 */
	public static function formatContactName( $values )
	{
		// Preformat in the IRMHtmlBuilder class similar to client id method
		$id                     = isset($values->id)                     ? $values->id                     : false;
		$company                = isset($values->company)                ? $values->company                : false;
		$last_name              = isset($values->last_name)              ? $values->last_name              : false;
		$first_name             = isset($values->first_name)             ? $values->first_name             : false;

		$opt_name = '';

		// Build the placeholder between
		if ( $company && $last_name && $first_name ) {
			$opt_name .= $company . ' (' . $first_name . ' ' . $last_name . ')';
		} else if ( !$company && $last_name && $first_name ) {
			$opt_name .= $first_name . ' ' . $last_name;
		} else if ( $company && !$last_name && !$first_name ) {
			$opt_name .= $company;
		} else if ( $last_name ) {
			$opt_name .= '(****) ' . $last_name;
		} else if ( $first_name ) {
			$opt_name .= $first_name . ' (****)';
		} else {
			$opt_name = false;
		}

		if ( !$opt_name ) {
			$opt_name = '*** Undefined name for ID: ' . $id . ' ***';
		}

		return $opt_name;
	}
}