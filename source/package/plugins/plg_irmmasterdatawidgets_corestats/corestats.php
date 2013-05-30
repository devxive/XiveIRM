<?php
/**
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataWidgets.corestats
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataWidgets.corestats
 * @since       3.0
 */
class PlgIrmmasterdatawidgetsCorestats extends JPlugin
{
	/**
	 * Stores the tab name
	 * @var	tabId
	 * @since	3.1
	 */
	var $tabId;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->tabId = 'corestats';
		$this->loadLanguage();
	}

	/**
	 * @param   integer    $itemId     The id from #__xiveirm_masterdata
	 *
	 * @return  array			tabId = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainer($itemId = null)
	{
		$tabContent = 'This is a demo Content';

		$inMasterContainer = array(
			'tabId' => $this->tabId,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}
}
?>