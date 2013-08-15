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
abstract class IRMContactHelper
{
	/*
	 * Method to get the component config
	 *
	 * @param     int    $catid    The component name / component folder name (com_mycomponent)
	 *
	 * @return    object           Return the component config object
	 */
	public function getList($catid)
	{
		$select     = array('id', 'last_name', 'first_name', 'company', 'address_name', 'address_name_add', 'address_system_checked');
		$conditions = array('catid' => $catid);
		$result = NFWDatabase::select('xiveirm_contacts', $select, $conditions, 'OBJECT');

		return $result;
	}
}