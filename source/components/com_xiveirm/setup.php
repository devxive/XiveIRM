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
 *
 * NOTE: Normally installer will look for setup.php in component folder during component install. Usage of z_setup.php will prevent the execution during component installation,
 *       remains directory consistency and will be included in the setup.php in the root folder of installation and executes in the postflight section of the install script.
 */

// Set variables
$message = array();

// Set the include path for the table class
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xiveirm/tables');

if ($package['installer']->getInstallType() == 'install') {
	// Runs on install

	// Restore assets from backup
	if (NFWInstallerHelper::restoreAssets($element)) {
		$message[] = '<i class="icon-ok"></i> Assets restored successfully';
	}

	/*
	 * Set the parent category for all categories for the XiveContacts App
	 */
	$parentCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_PARENT_XIVECONTACTS', 'access' => 2, 'language' => '*', 'alias' => 'com-xivecontacts'));
	if($parentCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set XiveContacts Core Category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set XiveContacts Core Category ... FAILED';
	}

	/*
	 * Set gender category as a subcategory of the XiveContacts Core Category
	 */
	$genderCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_GENDER', 'alias' => 'xivecontacts-gender', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if($genderCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set gender category ... OK';

		// Set the gender option values
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'unknown', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_UNKNOWN', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'female', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_FEMALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'male', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_MALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'company', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_COMPANY', 'access' => 2);

		$data = (object) $rows;
		$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);
		if($return) {
			$message[] = '<i class="icon-ok"></i> Form - Set gender values ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Form - Set gender values ... FAILED';
		}
	} else {
		$message[] = '<i class="icon-cancel"></i> Set gender category ... FAILED';
	}

	/*
	 * Set parent contacts category
	 */
	$contactsCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS', 'alias' => 'xivecontacts-contacts', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if($contactsCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set parent contacts category ... OK';

		// Set the contacts related categories
		// Kunde
		$cc1 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_CUSTOMER', 'alias' => 'contacts-customer', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Lieferant
		$cc2 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_SUPPLIER', 'alias' => 'contacts-supplier', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Subunternehmer
		$cc3 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_SUBCONTRACTOR', 'alias' => 'contacts-subcontractor', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Interessent
		$cc4 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_LEAD', 'alias' => 'contacts-lead', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Behörde
		$cc5 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_AGENCY', 'alias' => 'contacts-agency', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		if ($cc1 && $cc2 && $cc3 && $cc4 && $cc5 ) {
			$message[] = '<i class="icon-ok"></i> Set contacts related categories ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set contacts related categories ... FAILED!';
		}
	} else {
		$message[] = '<i class="icon-cancel"></i> Set parent contacts category ... FAILED';
	}
} else {
	// Runs on update
}

if (!empty($message)) {
	return $message;
} else {
	return true;
}