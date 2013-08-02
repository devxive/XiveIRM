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
	// Set the Parent Category for all categories in the TOCA App
	$parentCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVECONTACTS_PARENT_CATEGORY', 'alias' => 'com-xivecontacts'));

	//Set gender category
	$genderId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_GENDER', 'alias' => 'gender', 'parent_id' => $parentCategoryId));

	// Set the gender option values
	if($genderId) {
		$rows[] = array('client_id' => 2, 'catid' => $genderId, 'opt_value' => 'unknown', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_GENDER_TRAIT_UNKNOWN', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderId, 'opt_value' => 'female', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_GENDER_TRAIT_FEMALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderId, 'opt_value' => 'male', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_GENDER_TRAIT_MALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderId, 'opt_value' => 'company', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_GENDER_TRAIT_COMPANY', 'access' => 2);

		$data = (object) $rows;
		$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);

		if($return) {
			$message[] = '<i class="icon-ok"></i> Form - Set gender values ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Form - Set gender values ... FAILED';
		}

		$message[] = '<i class="icon-ok"></i> Set gender category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set gender category ... FAILED';
	}

	// Set the core contacts categories
	
} else {
}

$message[] = '<i class="icon-ok"></i> Removed temporary files';

if (!empty($message)) {
	return $message;
} else {
	return true;
}