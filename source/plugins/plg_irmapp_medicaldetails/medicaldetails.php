<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.medicaldetails
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     IRM.Plugin
 * @subpackage  IRMApp.medicaldetails
 * @since       6.0
 *
 *
 *
 *
 * The IRMApp should follow the following:
 *	- Widgets, Tabs, Buttons, etc... are all now in one plugin!
 *	- The main events have to be understand as positions and should follow this naming convention:
 *		* registerApp							- Where the app is registered to the IRMPlugins::get() library class
 *										  This should also include where the app is for, eg. contact or transcorder (could be a string or an array of apps)
 *		* htmlBuildTab_contact					- Where the tab is rendered in. The _contact as example stands for the single view of a contact.
 *										  For the XiveTranscorder App use _transcorder and so on.
 *		* htmlBuildAction_contact
 *
 */
class PlgIrmAppMedicaldetails extends JPlugin
{
	/**
	 * Stores the app name
	 * @var	appKey
	 * @since	6.0
	 */
	public $appKey;

	private $item;
	private $params;

	/**
	 * Stores the tab values
	 * @var	tabData
	 * @since	3.5
	 */
	var $tabData;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->appKey = 'medicaldetails';
		$this->loadLanguage();

		// Get the userState Object
//		$userState = (int) $app->getUserState('com_xiveirm.edit.contact');
		$contactId = (int) JFactory::getApplication()->getUserState('com_xiveirm.edit.contact.id');

		// Get the values from database
		$this->tabData = IRMSystem::getTabData($contactId, $this->tab_key);
	}


	/**
	 * Register App to know which tabApp to be load. Should be used for later check if clients have paid for it.
	 * This is also used in the controller to know which additional datas (tabs) we have to save.
	 * Since all form fields are used in one form we have to identify the form fields by using <input name="<?php echo $this->appKey; ?>[FORMNAME]" />
	 */
	public function IRMregisterApp(&$item = null, &$params = null)
	{
		return $this->tab_key;

		// Init the object
		$register = new JObject();

		// Register the app/plugin key
		$register->key = $this->appKey;

		// Register the components the app/plugin is used for (use the single view of that component)
		$register->components = array('contact', 'transcorder');
	}


	/**
	 * Method to inject a tab with form fields to extend the core form. The position of this tab is the same as the buttons, based on the plugin position.
	 *
	 * @param     object    &$item      The item referenced object which includes the system id of this contact
	 * @param     object    &$params    The params referenced object
	 *
	 * @return    array                 Array with the $appKey, the $html data for rendering and the $tabButton to show at the top.
	 *                                  tabButton - Use either a name and/or a translatable string with or without an icon
	 *
	 * @since     6.0
	 */
	public function htmlBuildTab(&$item = null, &$params = null)
	{
		ob_start();
		?>



		<?php
		$html = ob_get_clean();

		// Create the tabbed button
		$tabButton = <i class="icon-arrow-down"></i> ' . JText::_('TABBUTTON');

		$eventArray = array(
			'appKey' => $this->appKey,
			'tabButton' => $tabButton,
			'html' => $html
		);

		return $eventArray;
	}


	/**
	 * Method to clone form fields to the front (core form). Useful for recommended fields. The position of this widget is the same as the buttons or the tab, based on the plugin position.
	 * Put also a JS function in, where the input fields were cloned in realtime between the pseudo and the real form field.
	 *
	 * @param     object    &$item      The item referenced object which includes the system id of this contact
	 * @param     object    &$params    The params referenced object
	 *
	 * @return    array                 Array with the $formLabel and the $formFields for output rendering in a new form control group
	 *
	 * @since     6.0
	 *
	 * @usage                           Put the form field name (inner brackets) in an array.
	 *                                  Example: <?php echo $this->appKey; ?>[example1] -> use example1 as name in the array
	 *                                  NOTE: The form field must exist in your app/plugin!
	 */
	public function htmlBuildPseudoForms()
	{
		$inputFields = array(
			'example1' => JText::_('PLACEHOLDER_EXAMPLE1'),
			'example2' => JText::_('PLACEHOLDER_EXAMPLE2')
		);

		$html = IRMSystem::cloneTabFormFields($inputFields, $this->tab_key);

		$eventArray = array(
			'formLabel' => $this->appKey,
			'formFields' => $html
		);

		return $eventArray;
	}


	/**
	 * Method to inject a button in the action toolbar. The position of this button is the same as the tab, based on the plugin position.
	 *
	 * @param     object    &$item      The item referenced object which includes the system id of this contact
	 * @param     object    &$params    The params referenced object
	 *
	 * @return    array                 Array with the $appKey and the $html data for rendering in that position
	 *
	 * @since     6.0
	 */
	public function htmlBuildAction(&$item = null, &$params = null)
	{
		ob_start();
		?>



		<?php
		$html = ob_get_clean();

		$eventArray = array(
			'appKey' => $this->appKey,
			'html' => $html
		);

		return $eventArray;
	}


	/**
	 * Method to inject a widget on the right view. The position of this widget is the same as the buttons or the tab, based on the plugin position.
	 *
	 * @param     object    &$item      The item referenced object which includes the system id of this contact
	 * @param     object    &$params    The params referenced object
	 *
	 * @return    array                 Array with the $appKey and the $html data for output rendering
	 *
	 * @since     6.0
	 */
	public function htmlBuildWidget(&$item = null, &$params = null)
	{
		ob_start();
		?>



		<?php
		$html = ob_get_clean();

		$eventArray = array(
			'appKey' => $this->appKey,
			'html' => $html
		);

		return $eventArray;
	}
}