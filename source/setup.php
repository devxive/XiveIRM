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

$message = array();

/*
 * Set all rows (if set), collected from all setup files for single process in postflight, due to the fact, that NFWTable can be processing only once
 * TODO: Use the new Class NFWDatabase::save() to perform first a check and then a update/save process based on the settings that will be passed
 */
if ( !empty($this->tableQueryHelper) ) {
	$data = (object) $this->tableQueryHelper;
	$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);
	if($return) {
		$message[] = '<i class="icon-ok"></i> Form - Published option values ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Form - Published option values ... FAILED';
	}
}

if ( NFWSystemFolder::delete('plugins/system/XiveIRMinstaller') ) {
	$message[] = '<i class="icon-ok"></i> Removed installation files ... OK';
} else {
	$message[] = '<i class="icon-cancel"></i> Removed installation files ... FAILED';
}

if (!empty($message)) {
	return $message;
} else {
	return true;
}