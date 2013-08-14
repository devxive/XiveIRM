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
abstract class IRMComponentHelper
{
	/*
	 * Method to get the component config
	 *
	 * @param     string    The component name / component folder name (com_mycomponent)
	 *
	 * @return    object    Return the component config object
	 */
	public function getConfig($app = 'com_xiveirm')
	{
		if( $appObject = JComponentHelper::getParams($app) ) {
			return $appObject;
		} else {
			return false;
		}
	}


	/*
	 * Method to get a single config value as set in the component settings
	 *
	 * @param     string    $app      The name of the component (eg. com_mycomponent)
	 * @param     mixed     $value    The value as set in the components config
	 *
	 * @return    mixed               Return the accessed value
	 */
	public function getConfigValue($app = 'com_xiveirm', $value = false)
	{
		if ( !$value ) {
			return false;
		}

		if( $appValue = JComponentHelper::getParams($app)->get($value) ) {
			return $appValue;
		} else {
			return false;
		}
	}


	/*
	 * Method to load the core language files (mostly used in other components, plugins or anything else)
	 *
	 * @param     string    $app    The name of the component (eg. com_mycomponent)
	 *
	 * @return    void
	 */
	public function loadLanguage($app = 'com_xiveirm')
	{
		$lang = JFactory::getLanguage();
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($app, $base_dir, $language_tag, $reload);
	}
}