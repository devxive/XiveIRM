<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	IRM Library - The XiveIRM Library
 *
 * @package		XiveIRM.Library
 * @subPackage	Framework
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

// Define version
if ( !defined('IRMVERSION') ) {
	$irmversion = new IRMVersion();
	define( 'IRMVERSION', $irmversion->getShortVersion() );
}

// Set the XiveIRM Library root path as a constant if necessary.
if (!defined('IRMPATH_LIBRARY'))
{
	define('IRMPATH_LIBRARY', JPATH_SITE . '/libraries/xiveirm');
}

// Set the XiveIRM Library media path as a constant if necessary.
if (!defined('IRMPATH_MEDIA'))
{
	define('IRMPATH_MEDIA', JPATH_SITE . '/media/xiveirm');
}

// Define legacy directory separator as a constant if not exist
if(!defined('DS')) {
	define('DS', '/');
}

// Init the factory if necessary.
if (!class_exists('IRMFactory'))
{
	require_once ( __DIR__ . '/factory.php');
}