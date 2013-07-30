<?php
 /**
 * @version   $Id: requirements.php 10887 2013-05-30 06:31:57Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2013 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
$errors = array();
if (version_compare(PHP_VERSION, '5.2.8', 'gt')) {
    $errors[] = 'Needs a minimum PHP version of 5.2.8. You are running PHP version ' . PHP_VERSION;
}

$nfwversion = new NFWVersion();
if (!NFWVERSION) {
	$errors[] = 'The Nawala Framework have to be installed first!';
} else {
	if (version_compare(NFWVERSION, '6.0.0', 'gt')) {
		$errors[] = 'Please update Nawala Framework to the latest version. You are running ' . nfwversion->getLongVersion();
	}
}

$jversion = new JVersion();
if (!$jversion->isCompatible('3.1')) {
	$errors[] = 'XiveIRM will only run on XAP 13.6+, MOOTOMBO 3+ or Joomla! 3.1+ ';
}

if (!function_exists('gd_info'))
    $errors[] = 'The PHP GD2 module is needed but not installed.';

if (!phpversion('PDO'))
    $errors[] = 'The PHP PDO module is needed but not installed.';

if (!phpversion('pdo_mysql'))
    $errors[] = 'The PHP MySQL PDO driver is needed but not installed.';

if (!empty($errors)) return $errors;

return true;
