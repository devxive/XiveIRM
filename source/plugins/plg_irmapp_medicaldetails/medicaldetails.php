<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.medicaldetails
 *
 * @since       6.0
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
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

	/**
	 * Stores the app values
	 * @var	appData
	 * @since	6.0
	 */
	var $appData;

	/**
	 * Stores the id of the selected contact
	 * @var      contactId
	 * @since    6.1
	 */
	public static $contactId;

	/**
	 * Stores the XiveIRM Session
	 * @var	xsession
	 * @since	5.0
	 */
	var $xsession;

	var $acl;
	var $appAcl;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->appKey = 'medicaldetails';
		$this->loadLanguage();

		//Load admin language file
		$lang = JFactory::getLanguage();
		$lang->load('com_xivetranscorder', JPATH_SITE);

		// Get the compoment registry object
//		$registry = JFactory::getSession()->get('registry')->get('com_xiveirm');

		// Get the userState Object
//		$userState = (int) $app->getUserState('com_xiveirm.edit.contact');
		$contactId = (int) JFactory::getApplication()->getUserState('com_xiveirm.edit.contact.id');
		$this->contactId = $contactId;

		// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
		$this->xsession = JFactory::getSession()->get('XiveIRMSystem');

		// Get the values from database
		$this->appData = IRMAppHelper::getTabData('contacts', $contactId, $this->appKey);
	}


	/**
	 * Register App to know which tabApp to be load. Should be used for later check if clients have paid for it.
	 * This is also used in the controller to know which additional datas (tabs) we have to save.
	 * Since all form fields are used in one form we have to identify the form fields by using <input name="<?php echo $this->appKey; ?>[FORMNAME]" />
	 */
	public function registerIrmAppForm()
	{
		return $this->appKey;

// TODO: see below !!
		// Init the object
//		$register = new JObject();

		// Register the app/plugin key
//		$register->key = $this->appKey;

		// Register the components this app/plugin is used for (use the single view of that component)
//		$register->components = array('contact', 'transcorder');
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
	public function htmlBuildTab( &$item = null, &$params = null )
	{
		IRMHtmlSelect2::init('.select2-clearable');

		// Get available transport device, transport type and order type options
		$transportDeviceOptions = IRMFormList::getTransportDeviceOptions();
		$transportTypeOptions = IRMFormList::getTransportTypeOptions();

		ob_start();
		?>

		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->
		<style>
			.widget-toolbar .popover {width: 220px;}
			.widget-toolbar .popover .popover-content {line-height: 15px;}
		</style>

		<div class="row-fluid">
			<div class="span6">
				<div class="widget-box">
					<div class="widget-header">
						<h4><i class="icon-puzzle-piece green"></i> Transportation info</h4>
						<span class="widget-toolbar">
							<span class="help-button"><i class="icon-random"></i></span>
						</span>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">
										<?php echo JText::_('Transportmittel'); ?>
									</label>
									<div class="controls controls-row">
										<select name="<?php echo $this->appKey; ?>[transport_device]" class="select2-clearable input-control span12" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_DEVICE_PLEASE_SELECT'); ?>">
											<option></option>
											<?php
												foreach ( $transportDeviceOptions as $key => $value ) {
													if ( $this->appData->app_value->transport_device == $key ) {
														echo '<option value="' . $key . '" selected>' . $value . '</option>';
													} else {
														echo '<option value="' . $key . '">' . $value . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">
										<?php echo JText::_('Transportart'); ?>
									</label>
									<div class="controls controls-row">
										<select name="<?php echo $this->appKey; ?>[transport_type]" class="select2-clearable input-control span12" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_TYPE_PLEASE_SELECT'); ?>">
											<option></option>
											<?php
												foreach ( $transportTypeOptions as $key => $value ) {
													if ( $this->appData->app_value->transport_type == $key ) {
														echo '<option value="' . $key . '" selected>' . $value . '</option>';
													} else {
														echo '<option value="' . $key . '">' . $value . '</option>';
													}
												}
											?>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Accompaniment</label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[companion]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->companion) ? 'value="' . $this->appData->app_value->companion . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Mobile Oxigen</label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[oxygen]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->oxygen) ? 'value="' . $this->appData->app_value->oxygen . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Vacuum Mattress</label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[vacuum_mattress]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->vacuum_mattress) ? 'value="' . $this->appData->app_value->vacuum_mattress . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Other Aids</label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[other_aids]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->other_aids) ? 'value="' . $this->appData->app_value->other_aids . '"' : ''; ?>>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="span6">
				<div class="widget-box">
					<div class="widget-header">
						<h4><i class="icon-puzzle-piece red"></i>Infects, Illness & Adipositas</h4>
						<span class="widget-toolbar">
							<span class="help-button"><i class="icon-random"></i></span>
						</span>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">Infections <i class="icon-tags red"></i></label>
									<div class="controls">
										<select multiple name="<?php echo $this->appKey; ?>[infections][]" data-placeholder="Click here to select your choice!" class="select2-clearable input-control">
											<option></option>
											<option value="mrsa" <?php echo isset($this->appData->app_value->infections_set->mrsa) ? 'selected' : ''; ?>>MRSA</option>
											<option value="vre" <?php echo isset($this->appData->app_value->infections_set->vre) ? 'selected' : ''; ?>>VRE</option>
											<option value="esbl" <?php echo isset($this->appData->app_value->infections_set->esbl) ? 'selected' : ''; ?>>ESBL</option>
											<option value="hepa" <?php echo isset($this->appData->app_value->infections_set->hepa) ? 'selected' : ''; ?>>HEP A</option>
											<option value="hepb" <?php echo isset($this->appData->app_value->infections_set->hepb) ? 'selected' : ''; ?>>HEP B</option>
											<option value="hepc" <?php echo isset($this->appData->app_value->infections_set->hepc) ? 'selected' : ''; ?>>HEP C</option>
											<option value="hepd" <?php echo isset($this->appData->app_value->infections_set->hepd) ? 'selected' : ''; ?>>HEP D</option>
											<option value="hepe" <?php echo isset($this->appData->app_value->infections_set->hepe) ? 'selected' : ''; ?>>HEP E</option>
											<option value="hiv" <?php echo isset($this->appData->app_value->infections_set->hiv) ? 'selected' : ''; ?>>HIV</option>
											<option value="clostdiff" <?php echo isset($this->appData->app_value->infections_set->clostdiff) ? 'selected' : ''; ?>>Clostr. Difficile</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Umkehrisolation <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="<?php echo $this->appKey; ?>[reserve_isolation]" type="checkbox" class="ace-switch ace-switch-6 input-control" <?php echo isset($this->appData->app_value->reserve_isolation) ? 'checked' : ''; ?>>
										<span class="lbl"> </span>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag red"></i></label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[other_infects]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->other_infect) ? 'value="' . $this->appData->app_value->other_infect . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Obese 120KG+ <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[obese_120]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->obese_120) ? 'value="' . $this->appData->app_value->obese_120 . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input class="input-control span12" name="<?php echo $this->appKey; ?>[obese_other]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($this->appData->app_value->obese_other) ? 'value="' . $this->appData->app_value->obese_other . '"' : ''; ?>>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
	
				<div class="widget-box small-margin-top">
					<div class="widget-header">
						<h4><i class="icon-puzzle-piece orange"></i>Insurance info</h4>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">Insurance</label>
									<div class="controls">
										<input class="input-control span6" name="<?php echo $this->appKey; ?>[insurance]" type="text" placeholder="Insurance" <?php echo isset($this->appData->app_value->insurance) ? 'value="' . $this->appData->app_value->insurance . '"' : ''; ?> />
										<input class="input-control span6" name="<?php echo $this->appKey; ?>[insurance_no]" type="text" placeholder="Insurance Number" <?php echo isset($this->appData->app_value->insurance_no) ? 'value="' . $this->appData->app_value->insurance_no . '"' : ''; ?> />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div><!-- end .span6 -->
		</div><!-- end .row-fluid -->

		<div class="hr"></div>
		<center>
			<span class="help-button xpopover" data-trigger="hover" data-placement="top" data-content="Informations given here are used in other applications, such as the despatching app => order form. Use this as help to minimize inputs during remaining phone orders." data-original-title="Info about cross referencing!"><i class="icon-random"></i></span>
		</center>

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->

		<?php
		$html = ob_get_clean();

		// Create the tabbed button
		$tabButton = '<i class="icon-medkit red"></i> ' . JText::_('PLG_IRMAPP_MEDICALDETAILS_TABNAME');

		$eventArray = array(
			'appKey'    => $this->appKey,
			'tabButton' => $tabButton,
			'tabBody'   => $html
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
	public function htmlBuildPseudoForms( &$item = null, &$params = null )
	{
		$inputFields = array(
			'insurance'    => 'Krankenkasse',
			'insurance_no' => 'Versicherungsnummer'
		);

		$html = IRMHtmlBuilder::cloneAppFormFields($inputFields, $this->appKey);

		$eventArray = array(
			'appKey' => $this->appKey,
			'formLabel' => JText::_('PLG_IRMAPP_MEDICALDETAILS_FORM_LBL_INSURANCE'),
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
	public function htmlBuildAction( &$item = null, &$params = null )
	{
		// Get Permissions based on category or component or plugin/app
		$appAcl = NFWAccessHelper::getActions('com_xivetranscorder');

		// Get options for the select list (ACL is already included in the requested list options!)
		$options = IRMFormList::getCategoryOptions('com_xivetranscorder');

		if ( $this->contactId == 0 ) {
			// Set the script
			$script = "
				jQuery(document).ready(function() {
					// If user click update/save button, we have to hide the input field and graphical stuff
					$('#loading-btn-save').click(function() {
						$(document).ajaxSuccess(function() {
							$('#medical-action-buttons-new-creation').hide();
							$('#medical-action-buttons').fadeIn('slow');
						});
					});
				});
			";
			JFactory::getDocument()->addScriptDeclaration($script);
		}

		ob_start();
		?>
		<!---------- Start output buffering: <?php echo $this->appKey; ?> ---------->

		<?php if( $appAcl->get('core.create') ): ?>
		<div id="medical-action-buttons" class="row-fluid large-margin-top" <?php echo $this->contactId != 0 ? '' : 'style="display:none;"'; ?>>
			<div class="control-group">
				<label class="control-label"><?php echo JText::_('PLG_IRMAPP_MEDICALDETAILS_FORM_LBL_NEW_ORDER'); ?></label>
				<div class="controls">
					<div class="span11">
						<select id="selectCatId" class="select2-clearable" data-placeholder="<?php echo JText::_('PLG_IRMAPP_MEDICALDETAILS_FORM_PLEASE_SELECT_TYPE'); ?>" onchange="setCategoryId()">
							<option></option>
							<?php
								if($options) {
									foreach ($options as $key => $val) {
										echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
									}
								}
							?>
						</select>
					</div>
				</div>
			</div>
			<script>
				function setCategoryId() {
					// Get value from select list and set catid in form "frm-new-order"
					var catId = jQuery("#selectCatId").val();
					jQuery("#setInputCatId").val(catId);
					document.getElementById("frm-new-order").submit();
				};
			</script>
		</div>
		<div id="medical-action-buttons-new-creation" class="alert alert-error center" <?php echo $this->contactId != 0 ? 'style="display:none;"' : ''; ?>>
			<p>You have to save this first before you can play with the action!</p>
		</div>
		<?php endif; ?>

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->
		<?php
		$html = ob_get_clean();

		$eventArray = array(
			'appKey' => $this->appKey,
			'button' => $html
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
	public function htmlBuildWidget( &$item = null, &$params = null )
	{
		ob_start();
		?>

		<!---------- Begin output buffering: <?php echo $this->tab_key; ?> ---------->
		<?php if( isset($this->appData->app_value->infections_set)  || isset($this->appData->app_value->reserve_isolation) ) { ?>
			<div class="widget-box light-border small-margin-top">
				<div class="widget-header red">
					<h5 class="smaller"><?php echo JText::_('PLG_IRMAPP_MEDICALDETAILS_TABNAME'); ?> Widget</h5>
					<?php if(isset($this->appData->app_value->infections_set)): ?>
						<div class="widget-toolbar">
							<span class="badge badge-important" data-rel="tooltip" data-placement="bottom" data-original-title="Patient hat akute Infektionen!">Achtung Infektionsgefahr!</span>
						</div>
					<?php endif; ?>
					<?php if(isset($this->appData->app_value->reserve_isolation)): ?>
						<div class="widget-toolbar">
							<span class="label label-large label-info arrowed-in-right arrowed" data-rel="tooltip" data-placement="bottom" data-original-title="Bitte Hinweise zur Umkehrisolation beachten!"><i class="icon-refresh"></i> Achtung Umkehrisolation!</span>
						</div>
					<?php endif; ?>
				</div>
				<div class="widget-body">
					<div class="widget-main padding-5">
						<?php if(isset($this->appData->app_value->infections_set)) { ?>
							<p>
								<center class="alert alert-info">Basierend auf den uns vorliegenden Informationen zu den angegebenen Infektionskrankheiten haben wir folgende Artikel rescherchiert!</center>
								<ul style="list-style: none;">
								<?php
									if(isset($this->appData->app_value->infections_set->mrsa))
									{
										echo '
											<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-mrsa.html" target="_blank">Informationen zur MRSA-Infektion bei GEFA</a>
											</li>
										';
									}
									if(isset($this->appData->app_value->infections_set->vre))
									{
										echo '
											<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-vre.html" target="_blank">Informationen zur VRE-Infektion bei GEFA</a>
											</li>
										';
									}
									if(isset($this->appData->app_value->infections_set->esbl))
									{
										echo '
											<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-esbl.html" target="_blank">Informationen zur ESBL-Infektion/Besiedelung bei GEFA</a>
											</li>
										';
									}
									if(isset($this->appData->app_value->infections_set->hepa) || isset($this->appData->app_value->infections_set->hepb) || isset($this->appData->app_value->infections_set->hepc) || isset($this->appData->app_value->infections_set->hepd) || isset($this->appData->app_value->infections_set->hepe))
									{
										echo '
											<li style="background: url(http://www.onmeda.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://www.onmeda.de/krankheiten/hepatitis-vorbeugen-1319-10.html" target="_blank">Ma&szlig;nahmen zur Vorbeugung einer Hepatitis bei Onmeda</a><br>
												<div class="btn-group">
												' .
												(isset($this->appData->app_value->infections_set->hepa) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_a.html" target="_blank">Typ A</a>' : '')
												. (isset($this->appData->app_value->infections_set->hepb) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_b.html" target="_blank">Typ B</a>' : '')
												. (isset($this->appData->app_value->infections_set->hepc) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_c.html" target="_blank">Typ C</a>' : '')
												. (isset($this->appData->app_value->infections_set->hepd) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_d.html" target="_blank">Typ D</a>' : '')
												. (isset($this->appData->app_value->infections_set->hepe) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_e.html" target="_blank">Typ E</a>' : '')
												. ' </div>
											</li>
										';
									}
									if(isset($this->appData->app_value->infections_set->hiv))
									{
										echo '
											<li style="background: url(http://upload.wikimedia.org/wikipedia/commons/9/9e/Wikipedia-logo-v2-de.svg) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://de.wikipedia.org/w/index.php?search=hiv#.C3.9Cbertragung" target="_blank">Hinweise zu HIV-Infektionen bei Wikipedia</a>
											</li>
										';
									}
									if(isset($this->appData->app_value->infections_set->clostdiff))
									{
										echo '
											<li style="background: url(http://www.berlin.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
												<a href="http://www.berlin.de/ba-charlottenburg-wilmersdorf/org/gesundheit/clostridium_difficile.html#j" target="_blank">Pr&auml;vention und Hygienema&szlig;nahmen bei einer Chlost. Difficilum-Infektionen<br>Gesundheitsamt Charlottenburg-Wilmersdorf</a>
											</li>
										';
									}
								?>
								</ul>
							</p>
						<?php } ?>
						<?php if(isset($this->appData->app_value->reserve_isolation)): ?>
							<p>
								<ul style="list-style: none;">
									<li style="background: url(http://www.pflegewiki.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
										<a href="http://www.pflegewiki.de/wiki/Infektionspflege#Umkehrisolation" target="_blank">Hinweise zur Infektionspflege => Umkehrisolation - Pflegewiki</a>
									</li>
								</ul>
							</p>
						<?php endif; ?>
					</div>
					<div class="widget-toolbox padding-5 clearfix">
						<div class="center">
							<small>
								<small>Die hier dargestellten Informationen stellen keine rechtliche Grundlage dar und k&ouml;nnen regional abweichen!</small><br>
								Mehr kostenlose Widgets auf unserer Webseite: <a href="#">Medical Marketing GmbH</a>
							</small>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>
		<!---------- End output buffering: <?php echo $this->tab_key; ?> ---------->


		<?php
		$html = ob_get_clean();

		$eventArray = array(
			'appKey' => $this->appKey,
			'html' => $html
		);

		return $eventArray;
	}


	/**
	 * Load extern forms (after the origin to prevent form in form)
	 *
	 *
	 */
	public function loadExternForms( &$item = null, &$params = null )
	{
		ob_start();
		?>

		<?php if(JFactory::getUser()->authorise('core.create','com_xivetranscorder')): ?>
			<form id="frm-new-order" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorderform.edit'); ?>">
				<input type="hidden" name="id" value="0">
				<input type="hidden" name="catid" id="setInputCatId" value="0">
				<input type="hidden" name="contactid" value="<?php echo $this->contactId; ?>">
			</form>
		<?php endif; ?>

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->
		<?php

		$html = ob_get_clean();

		$inMasterContainer = array(
			'appKey' => $this->appKey,
			'appForm' => $html
		);

		return $inMasterContainer;
	}
}