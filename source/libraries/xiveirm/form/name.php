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
 * Form or table helper class to format names
 */
class IRMFormName
{
	/*
	 * Method to format names
	 *
	 * @param     array      $values       The name of the component as stored in components folder (com_mycomponent)
	 * @param     boolean    $checkmark    Show (true) or hide (false) checkmarks in the name string
	 *
	 * @return    string
	 */
	public static function formatPoiName( $values, $checkmark = true )
	{
		// Preformat in the IRMHtmlBuilder class similar to client id method
		$id                  = isset($values->id) || isset($values->contact_id)    ? $values->id                  : false;
		$address_name        = isset($values->address_name)                        ? $values->address_name        : false;
		$address_name_add    = isset($values->address_name_add)                    ? $values->address_name_add    : false;
		$company             = isset($values->company)                             ? $values->company             : false;
		$last_name           = isset($values->last_name)                           ? $values->last_name           : false;
		$first_name          = isset($values->first_name)                          ? $values->first_name          : false;
		$system_checked      = isset($values->system_checked)                      ? $values->system_checked      : false;
		$client_checked      = isset($values->client_checked)                      ? $values->client_checked      : false;

		$opt_name = '';

		// If only company or company and name
		if ( $company && (!$last_name || !$first_name) ) {
			$opt_name .= $company;
			$check_before = true;
		} else if ( $company && ($last_name || $first_name) ) {
			$opt_name .= '<small>' . $company . '</small><br>';
			if ( $last_name ) {
				$opt_name .= $last_name . ', (****)';
			} else {
				$opt_name .= '(****), ' . $first_name;
			}
			$check_before = true;
		} else if ( ($last_name || $first_name) && !$company ) {
			if ( $last_name && $first_name ) {
				$opt_name .= $last_name . ', ' . $first_name;
			} else if ( $last_name ) {
				$opt_name .= $last_name . ', (****)';
			} else {
				$opt_name .= '(****), ' . $first_name;
			}
			$check_before = true;
		} else {
			$opt_name .= '';
			$check_before = false;
		}

		// If block 1 and block 2, add new line
		if ( $check_before && ($address_name || $address_name_add) ) {
			$opt_name .= '<br>';
		}

		// If only address_name or address_name and address_name_add
		if ( $address_name && !$address_name_add ) {
			$opt_name .= $address_name;
			$check_after = true;
		} else if ( $address_name && $address_name_add ) {
			$opt_name .= $address_name . ' ( ' . $address_name_add . ' )';
			$check_after = true;
		} else if ( !$address_name && $address_name_add ) {
			$opt_name .= $address_name_add;
			$check_after = true;
		} else {
			$opt_name .= '';
			$check_after = false;
		}

		if ( !$check_before && !$check_after ) {
			$opt_name = '*** Undefined name for ID: ' . $id . ' ***';
		}

		// Adding checkmark icon/sign
		if ( $checkmark ) {
			if ( $system_checked ) {
				$opt_name .= ' <i class="icon-ok-sign"></i>';
			} else if ( $client_checked ) {
				$opt_name .= ' *';
			}
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


	/*
	 * Method to format names
	 *
	 * @param     array      $values       The name of the component as stored in components folder (com_mycomponent)
	 * @param     boolean    $checkmark    Show (true) or hide (false) checkmarks in the name string
	 *
	 * @return    string
	 */
	public static function formatListName( $values, $checkmark = true )
	{
		// Preformat in the IRMHtmlBuilder class similar to client id method
		$id                  = isset($values->id) || isset($values->contact_id)    ? $values->id                  : false;
		$address_name        = isset($values->address_name)                        ? $values->address_name        : false;
		$address_name_add    = isset($values->address_name_add)                    ? $values->address_name_add    : false;
		$company             = isset($values->company)                             ? $values->company             : false;
		$last_name           = isset($values->last_name)                           ? $values->last_name           : false;
		$first_name          = isset($values->first_name)                          ? $values->first_name          : false;
		$system_checked      = isset($values->system_checked)                      ? $values->system_checked      : false;
		$client_checked      = isset($values->client_checked)                      ? $values->client_checked      : false;

		$opt_name = '';

		// If only company or company and name
		if ( $company && (!$last_name || !$first_name) ) {
			$opt_name .= $company;
			$check_before = true;
		} else if ( $company && ($last_name || $first_name) ) {
			$opt_name .= '<small>' . $company . '</small><br>';
			if ( $last_name ) {
				$opt_name .= $last_name . ', (****)';
			} else {
				$opt_name .= '(****), ' . $first_name;
			}
			$check_before = true;
		} else if ( ($last_name || $first_name) && !$company ) {
			if ( $last_name && $first_name ) {
				$opt_name .= $last_name . ', ' . $first_name;
			} else if ( $last_name ) {
				$opt_name .= $last_name . ', (****)';
			} else {
				$opt_name .= '(****), ' . $first_name;
			}
			$check_before = true;
		} else {
			$opt_name .= '';
			$check_before = false;
		}

		// If block 1 and block 2, add new line
		if ( $check_before && ($address_name || $address_name_add) ) {
			$opt_name .= '<br>';
		}

		// If only address_name or address_name and address_name_add
		if ( $address_name && !$address_name_add ) {
			$opt_name .= $address_name;
			$check_after = true;
		} else if ( $address_name && $address_name_add ) {
			$opt_name .= $address_name . ' ( ' . $address_name_add . ' )';
			$check_after = true;
		} else if ( !$address_name && $address_name_add ) {
			$opt_name .= $address_name_add;
			$check_after = true;
		} else {
			$opt_name .= '';
			$check_after = false;
		}

		if ( !$check_before && !$check_after ) {
			$opt_name = '*** Undefined name for ID: ' . $id . ' ***';
		}

		// Adding checkmark icon/sign
		if ( $checkmark ) {
			if ( $system_checked ) {
				$opt_name .= ' <i class="icon-ok-sign"></i>';
			} else if ( $client_checked ) {
				$opt_name .= ' *';
			}
		}

		return $opt_name;
	}
}