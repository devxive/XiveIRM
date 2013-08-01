<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	Nawala Framework - A PHP and Javascript framework
 *
 * @package		NFW.Installer
 * @subPackage	Framework
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		5.0
 */

// Set variables
$message = array();
$element = 'com_xivetranscorder';
$parent_menu = 'com_xiveirm';

if ($package['installer']->getInstallType() == 'install') {
	// Restore assets from backup
	if (NFWInstallerHelper::restoreAssets($element)) {
		$message[] = '<i class="icon-ok"></i> Assets restored successfully';
	}

	// Make the admin menu item a child of the $parent_menu (element, parent)
	if (NFWInstallerHelper::setComponentChildMenuItem($element, $parent_menu)) {
		$message[] = '<i class="icon-ok"></i> Added submenu items to ' . $parent_menu . ' main menu';
	} else {
		$message[] = '<i class="icon-cancel red"></i> Adding submenu item to ' . $parent_menu . ' main menu failed';
	}

	// Fill up the database with core values
	$db = JFactory:: getDBO();
	$query = $db->getQuery(true);

		/*
		 * Set the xiverim_options
		 *
		 */
		$query->clear();

		// Set the columns
		$columns = array('id', 'client_id', 'catid', 'opt_key', 'opt_value', 'opt_name', 'access', 'ordering');

		// Prepare the query
		$query
			->insert($db->quoteName('#__xiveirm_options'))
			->columns($db->quoteName($columns));
			->values(implode(',', $values));

		$query->values(implode(',', array('1', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_UNKNOWN', 'unknown', '2', ''));
		$query->values(implode(',', array('2', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_FEMALE', 'female', '2', ''));
		$query->values(implode(',', array('3', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_MALE', 'male', '2', ''));
		$query->values(implode(',', array('4', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_COMPANY', 'company', '2', ''));

		// Set the query string
		$query->setQuery($query);

		try {
			$db->execute();
			// $insertedId = (int)$db->insertid();
			$message[] = '<i class="icon-ok"></i> Added core values to the database';
		} catch (Exception $e) {
			$message[] = '<i class="icon-cancel"></i> Error ' . (int)$e->getCode() . ' ends up wit message: ' . $e->getMessage();
		}
} else {
	// Make the admin menu item a child of the $parent_menu (element, parent)
	if (NFWInstallerHelper::setComponentChildMenuItem($element, $parent_menu)) {
		$message[] = '<i class="icon-ok"></i> Added submenu items to ' . $parent_menu . ' main menu';
	} else {
		$message[] = '<i class="icon-cancel red"></i> Adding submenu item to ' . $parent_menu . ' main menu failed';
	}
}
	



// Set the option values
// $columns = array('id', 'client_id', 'catid', 'opt_key', 'opt_value', 'opt_name', 'access', 'ordering');
// $values_1 = array('1', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_UNKNOWN', 'unknown', '2', '');
// $values_2 = array('2', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_FEMALE', 'female', '2', '');
// $values_3 = array('3', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_MALE', 'male', '2', '');
// $values_4 = array('4', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_COMPANY', 'company', '2', '');
// 
// $query
// 	->insert($db->quoteName('#__xiveirm_options'))
// 	->columns($db->quoteName($columns))
// 	->values(implode(',', $values));
// 
// $query->setQuery($query);




$message[] = '<i class="icon-ok"></i> Removed temporary files';

if (!empty($message)) {
	return $message;
} else {
	return true;
}