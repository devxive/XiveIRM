﻿<?php
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

$message[] = '<i class="icon-ok"></i> Prepared database tables';
$message[] = '<i class="icon-ok"></i> Added submenu items to XiveIRM main menu';
$message[] = '<i class="icon-ok"></i> Removed temporary files';

if (!empty($message)) {
	return $message;
} else {
	return true;
}