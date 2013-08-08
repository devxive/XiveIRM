<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	Nawala Framework - A PHP and Javascript framework
 *
 * @package		XAP.plugin
 * @subPackage	System.xiveirm
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		1.0
 */

defined('_JEXEC') or die();

/**
 * Do checks to get the right coice of everything. Stores essential things in the session if a user login!
 *
 * @package     XiveIRM.Plugin
 * @subpackage  System.System
 * @since       3.0
 */
class PlgSystemXiveIrm extends JPlugin
{
	/**
	 * Constructor.
	 *
	 * @access protected
	 * @param object $subject The object to observe
	 * @param array   $config  An array that holds the plugin configuration
	 * @since 1.0
	 */
	public function __construct( &$subject, $config )
	{
		parent::__construct( $subject, $config );

		// Add Include Paths to the table classes of the components
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xiveirm/tables');
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xivetranscorder/tables');

		// Register the library and define the version first!
		JLoader::registerPrefix('IRM', JPATH_LIBRARIES . '/xiveirm');

		// Do some extra initialisation in this constructor if required
	}

 	/**
	 * @since   3.4
	 */
	public function onAfterInitialise()
	{
		// Initialise the XiveIRM Session
		IRMSessionHelper::init();
	}

	public function onBeforeCompileHead()
	{
		// Remove JUI stuff for frontend
		NFWHtml::removeJUI(true, false);
	}
}