<?php
/**
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataTabs.medicaldetails
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataTabs.medicaldetails
 * @since       3.0
 */
class PlgIrmmasterdatatabsMedicaldetails extends JPlugin
{
	/**
	 * Stores the tab app name
	 * @var	tabAppId
	 * @since	3.1
	 */
	var $tabAppId;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->tabAppId = 'medicaldetails';
		$this->loadLanguage();
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabAppId = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainer(&$item = null, &$params = null)
	{
		$tabData = IRMSystem::getTabData($this->tabAppId, $item->id, false);

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabAppId; ?> ---------->
		<div class="widget-box light-border small-margin-top">
			<div class="widget-header red hidden-phone visible-desktop">
				<h5 class="smaller">Actiontoolbar</h5>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<center>
						<button class="btn btn-app btn-small btn-success"><i class="icon-road"></i> Order</button>
						<button class="btn btn-app btn-small btn-grey"><i class="icon-file-alt"></i> Template</button>
						<button class="btn btn-app btn-small btn-info"><i class="icon-eye-open"></i> StreetView</button>
						<button class="btn btn-app btn-small btn-light"><i class="icon-print"></i> Print</button>
						<button class="btn btn-app btn-small btn-purple"><i class="icon-cloud-upload"></i> Upload</button>
						<button class="btn btn-app btn-small btn-pink"><i class="icon-share-alt"></i> Share</button>
					</center>
				</div>
			</div>
		</div>

		<div class="widget-box light-border">
			<div class="widget-header red small-margin-top">
				<h5 class="smaller"><?php echo JText::_('PLG_IRMMASTERDATATABS_MEDICALDETAILS_TABNAME'); ?> Widget</h5>
				<?php if(isset($tabData->tab_value->infections_set)): ?>
					<div class="widget-toolbar">
						<span class="badge badge-important" data-rel="tooltip" data-placement="bottom" data-original-title="Patient hat akute Infektionen!">Achtung Infektionsgefahr!</span>
					</div>
				<?php endif; ?>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<?php if(isset($tabData->tab_value->infections_set)) { ?>
						<p>
							<center class="alert alert-info">Basierend auf den uns vorliegenden Informationen zu den angegebenen Infektionskrankheiten haben wir folgende Artikel rescherchiert!</center>
							<ul style="list-style: none;">
							<?php
								if(isset($tabData->tab_value->infections_set->mrsa))
								{
									echo '
										<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
											<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-mrsa.html" target="_blank">Informationen zur MRSA-Infektion bei GEFA</a>
										</li>
									';
								}
								if(isset($tabData->tab_value->infections_set->vre))
								{
									echo '
										<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
											<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-vre.html" target="_blank">Informationen zur VRE-Infektion bei GEFA</a>
										</li>
									';
								}
								if(isset($tabData->tab_value->infections_set->esbl))
								{
									echo '
										<li style="background: url(http://www.gefatex.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
											<a href="http://www.gefatex.de/de/tipps-a-infos/informationen-zu-esbl.html" target="_blank">Informationen zur ESBL-Infektion/Besiedelung bei GEFA</a>
										</li>
									';
								}
								if(isset($tabData->tab_value->infections_set->hepa) || isset($tabData->tab_value->infections_set->hepb) || isset($tabData->tab_value->infections_set->hepc) || isset($tabData->tab_value->infections_set->hepd) || isset($tabData->tab_value->infections_set->hepe))
								{
									echo '
										<li style="background: url(http://www.onmeda.de/favicon.ico) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
											<a href="http://www.onmeda.de/krankheiten/hepatitis-vorbeugen-1319-10.html" target="_blank">Ma&szlig;nahmen zur Vorbeugung einer Hepatitis bei Onmeda</a><br>
											<div class="btn-group">
											' .
											(isset($tabData->tab_value->infections_set->hepa) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_a.html" target="_blank">Typ A</a>' : '')
											. (isset($tabData->tab_value->infections_set->hepb) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_b.html" target="_blank">Typ B</a>' : '')
											. (isset($tabData->tab_value->infections_set->hepc) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_c.html" target="_blank">Typ C</a>' : '')
											. (isset($tabData->tab_value->infections_set->hepd) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_d.html" target="_blank">Typ D</a>' : '')
											. (isset($tabData->tab_value->infections_set->hepe) ? '<a class="btn btn-minier btn-purple" href="http://www.onmeda.de/krankheiten/hepatitis_e.html" target="_blank">Typ E</a>' : '')
											. ' </div>
										</li>
									';
								}
								if(isset($tabData->tab_value->infections_set->hiv))
								{
									echo '
										<li style="background: url(http://upload.wikimedia.org/wikipedia/commons/9/9e/Wikipedia-logo-v2-de.svg) 0% 50% no-repeat; background-size: 3%; padding-left: 25px;">
											<a href="http://de.wikipedia.org/w/index.php?search=hiv#.C3.9Cbertragung" target="_blank">Hinweise zu HIV-Infektionen bei Wikipedia</a>
										</li>
									';
								}
								if(isset($tabData->tab_value->infections_set->clostdiff))
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
				</div>
				<div class="widget-toolbox padding-5 clearfix">
					<div class="center">
						<small>Mehr kostenlose Widgets auf unserer Webseite: <a href="#">ZAD Northeim GmbH</a></small>
					</div>
				</div>
			</div>
		</div>

		<!---------- End output buffering: <?php echo $this->tabAppId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tabAppId' => $this->tabAppId . '-widget',
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabAppId = The tab identification, tabName = Translateable string from .ini file
	 *
	 * @since   3.0
	 */
	public function loadTabButton(&$item = null)
	{
		$tabButton = array(
			'tabAppId' => $this->tabAppId,
			'tabButtonName' => '<i class="icon-medkit red"></i> ' . JText::_('PLG_IRMMASTERDATATABS_MEDICALDETAILS_TABNAME')
		);

		return $tabButton;
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabAppId = The tab identification, tabContent = Summary of the tabForms
	 *
	 * @since   3.0
	 */
	public function loadTabContainer(&$item = null)
	{
		// Get the values from database
		$tabData = IRMSystem::getTabData($this->tabAppId, $item->id, false);

//		echo '<pre>';
//		print_r($tabData);
//		echo '</pre>';
		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabAppId; ?> ---------->
		<style>
			#chzn-select .chzn-container, #chzn-select .chzn-container-multi, #chzn-select .chzn-drop {width: 99.6% !important;}
			.widget-toolbar .popover {width: 220px;}
			.widget-toolbar .popover .popover-content {line-height: 15px;}
		</style>

		<form id="form-tab-<?php echo $this->tabAppId; ?>" class="form-horizontal">
			<div class="row-fluid">
			<div class="span6">
				<div class="widget-box">
					<div class="widget-header">
						<h4><i class="icon-random"></i> Transportation info</h4>
						<span class="widget-toolbar">
							<span class="help-button"><i class="icon-random"></i></span>
						</span>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">Transportmittel</label>
									<div class="controls">
										<input name="tabForm[transport_type]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->transport_type) ? 'value="' . $tabData->tab_value->transport_type . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Transportart</label>
									<div class="controls">
										<input name="tabForm[transport_properties]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->transport_properties) ? 'value="' . $tabData->tab_value->transport_properties . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Accompaniment</label>
									<div class="controls">
										<input name="tabForm[companion]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->companion) ? 'value="' . $tabData->tab_value->companion . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Mobile Oxigen</label>
									<div class="controls">
										<input name="tabForm[oxygen]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->oxygen) ? 'value="' . $tabData->tab_value->oxygen . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Vacuum Mattress</label>
									<div class="controls">
										<input name="tabForm[vacuum_mattress]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->vacuum_mattress) ? 'value="' . $tabData->tab_value->vacuum_mattress . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Other Aids</label>
									<div class="controls">
										<input name="tabForm[other_aids]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->other_aids) ? 'value="' . $tabData->tab_value->other_aids . '"' : ''; ?>>
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
						<h4><i class="icon-tags"></i>Infects, Illness & Adipositas</h4>
						<span class="widget-toolbar">
							<span class="help-button"><i class="icon-random"></i></span>
						</span>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">Infections <i class="icon-tags red"></i></label>
									<div id="chzn-select" class="controls">
										<select multiple name="tabForm[infections][]" data-placeholder="Select Informations here to activate!" class="chzn-select">
											<option value=""></option>
											<option value="mrsa" <?php echo isset($tabData->tab_value->infections_set->mrsa) ? 'selected' : ''; ?>>MRSA</option>
											<option value="vre" <?php echo isset($tabData->tab_value->infections_set->vre) ? 'selected' : ''; ?>>VRE</option>
											<option value="esbl" <?php echo isset($tabData->tab_value->infections_set->esbl) ? 'selected' : ''; ?>>ESBL</option>
											<option value="hepa" <?php echo isset($tabData->tab_value->infections_set->hepa) ? 'selected' : ''; ?>>HEP A</option>
											<option value="hepb" <?php echo isset($tabData->tab_value->infections_set->hepb) ? 'selected' : ''; ?>>HEP B</option>
											<option value="hepc" <?php echo isset($tabData->tab_value->infections_set->hepc) ? 'selected' : ''; ?>>HEP C</option>
											<option value="hepd" <?php echo isset($tabData->tab_value->infections_set->hepd) ? 'selected' : ''; ?>>HEP D</option>
											<option value="hepe" <?php echo isset($tabData->tab_value->infections_set->hepe) ? 'selected' : ''; ?>>HEP E</option>
											<option value="hiv" <?php echo isset($tabData->tab_value->infections_set->hiv) ? 'selected' : ''; ?>>HIV</option>
											<option value="clostdiff" <?php echo isset($tabData->tab_value->infections_set->clostdiff) ? 'selected' : ''; ?>>Clostr. Difficile</option>
										</select>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Umkehrisolation <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="tabForm[reserve_isolation]" type="checkbox" class="ace-switch ace-switch-6" <?php echo isset($tabData->tab_value->reserve_isolation) ? 'checked' : ''; ?>>
										<span class="lbl"> </span>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="tabForm[other_infect]" type="text" class="span12" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->other_infect) ? 'value="' . $tabData->tab_value->other_infect . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Obese 120KG+ <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input name="tabForm[obese_120]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->obese_120) ? 'value="' . $tabData->tab_value->obese_120 . '"' : ''; ?>>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input name="tabForm[obese_other]" type="text" placeholder="Enter Informations here to activate!" <?php echo isset($tabData->tab_value->obese_other) ? 'value="' . $tabData->tab_value->obese_other . '"' : ''; ?>>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="widget-box small-margin-top">
					<div class="widget-header">
						<h5><i class="icon-tag orange"></i>Transportation related Informations</h5>
					</div>
					<div class="widget-body">
						<div class="widget-body-inner">
							<div class="widget-main">
								<div class="control-group">
									<label class="control-label">Insurance</label>
									<div class="controls">
										<input name="tabForm[insurance]" type="text" placeholder="Enter Informations here" <?php echo isset($tabData->tab_value->insurance) ? 'value="' . $tabData->tab_value->insurance . '"' : ''; ?>>
										<input name="tabForm[supervisor_name]" type="text" placeholder="Supervisor Name" />
										<input name="tabForm[supervisor_phone]" type="text" placeholder="Supervisor Phone" />
										<input name="tabForm[supervisor_desc]" type="text" placeholder="Supervisor Description" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div class="form-actions">
				<input type="hidden" name="tabForm[id]" id="tabId" value="<?php echo isset($tabData->id) ? $tabData->id : '0'; ?>">
				<input type="hidden" name="tabForm[tabappid]" value="<?php echo $this->tabAppId; ?>">
				<input type="hidden" name="tabForm[masterdataitemid]" value="<?php echo $item->id; ?>">
				<input type="hidden" name="tabForm[direction]" value="ajax">
				<input type="hidden" name="tabForm[valueformat]" value="json">

				<input type="hidden" name="option" value="com_xiveirm" />
				<input type="hidden" name="task" value="api.save" />
				<?php echo JHtml::_('form.token'); ?>
				<button id="loading-btn" class="btn btn-info" data-loading-text="Please wait..." type="submit"><i class="icon-ok"></i> <?php echo isset($tabData->id) ? 'Update' : 'Submit'; ?></button>
				&nbsp; &nbsp; &nbsp;
				<button class="btn" type="reset"><i class="icon-undo"></i> Reset</button>
			</div>
		</form>

		<div class="hr"></div>
		<center>
			<span class="help-button ace-popover" data-trigger="hover" data-placement="top" data-content="Informations given here are used in other applications, such as the despatching app => order form. Use this as help to minimize inputs during remaining phone orders." data-original-title="Info about cross referencing!"><i class="icon-random"></i></span>
		</center>
		<script>
			jQuery(function(){
				$("#form-tab-<?php echo $this->tabAppId; ?>").submit(function(e){
					e.preventDefault();

					$.post('index.php?option=com_xiveirm&task=api.save&tmpl=component', $("#form-tab-<?php echo $this->tabAppId; ?>").serialize(),
					function(data){
						if(data.apiReturnCode === 'SAVED'){
							$.gritter.add({
								title: 'Successfully saved',
								text: 'You have successfully saved all items for the customer <?php echo $item->first_name . ' ' . $item->last_name; ?>',
								class_name: 'gritter-success'
							});
							$('#tabId').val(data.apiReturnRowId);
						} else if(data.apiReturnCode === 'UPDATED'){
							$.gritter.add({
								title: 'Successfully updated',
								text: 'You have successfully saved all items for the customer <?php echo $item->first_name . ' ' . $item->last_name; ?>',
								class_name: 'gritter-info'
							});
						} else {
							$.gritter.add({
								title: 'An error occured',
								text: 'An error occured while trying to save or update the data. If this error returns so far please let us now the following<br>error code: ' + data.apiReturnCode + '<br>and the following<br>error message: ' + data.apiReturnMessage,
								class_name: 'gritter-error'
							});
						}
					}, "json");
				});
			});
<?php
	/*
	 * Returns from API in json format
	 * example {"apiReturnCode":"SAVED","apiReturnRowId":"173","apiReturnMessage":"Successfully saved"}
	 * 
	 * apiReturnCode could be: SAVED, UPDATED or an Error Number ie. 666
	 * apiReturnRowId: returns the id from the database on which this tab entry is saved! this feature may deprecated in future versions, since we have already a unique tabId "medicaldetails.dbIdFromCustomer"
	 * apiReturnMessage: returns a informal message, should be used for debugging and not in production use. returns the database or php errors
	 */
?>
		</script>

		<!---------- End output buffering: <?php echo $this->tabAppId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$tabContainer = array(
			'tabAppId' => $this->tabAppId,
			'tabContent' => $tabContent
		);

		return $tabContainer;
	}

	/**
	 * @param   string     $context  The context for the data
	 * @param   integer    $data     The user id
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public function onContentPrepareData($context, $data)
	{
		// Check we are manipulating a valid form.
		if (!in_array($context, array('com_users.profile', 'com_users.registration', 'com_users.user', 'com_admin.profile')))
		{
			return true;
		}

		$userId = isset($data->id) ? $data->id : 0;

		// Load the profile data from the database.
		$db = JFactory::getDbo();
		$db->setQuery(
			'SELECT profile_key, profile_value FROM #__user_profiles' .
			' WHERE user_id = ' . (int) $userId .
			' AND profile_key LIKE \'xiveirmclientprofile.%\'' .
			' ORDER BY ordering'
		);
		$results = $db->loadRowList();

		// Check for a database error.
		if ($db->getErrorNum()) {
			$this->_subject->setError($db->getErrorMsg());
			return false;
		}

		// Merge the profile data.
		$data->xiveirmclientprofile = array();
		foreach ($results as $v) {
			$k = str_replace('xiveirmclientprofile.', '', $v[0]);
			$data->xiveirmclientprofile[$k] = json_decode($v[1], true);
		}

		return true;
	}

	/**
	 * @param   JForm    $form    The form to be altered.
	 * @param   array    $data    The associated data for the form.
	 *
	 * @return  boolean
	 * @since   3.0
	 */
	public function onContentPrepareForm($form, $data)
	{
		//Load user_xiveirmclientprofile plugin language
		$lang = JFactory::getLanguage();
		$lang->load('plg_user_xiveirmclientprofile', JPATH_ADMINISTRATOR);

		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Check we are manipulating a valid form.
		if (!in_array($form->getName(), array('com_admin.profile', 'com_users.registration', 'com_users.user', 'com_users.profile')))
		{
			return true;
		}

		if ($form->getName()=='com_users.profile')
		{
			// Add the profile fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the xiveirmclientid field is required.
			if ($this->params->get('profile-xiveirmclientid', 1) > 0)
			{
				$form->setFieldAttribute('xiveirmclientid', 'required', $this->params->get('profile-xiveirmclientid') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('xiveirmclientid', 'xiveirmclientprofile');
			}

			// Toggle whether the jobtitle field is required.
			if ($this->params->get('profile-jobtitle', 1) > 0)
			{
				$form->setFieldAttribute('jobtitle', 'required', $this->params->get('profile-jobtitle') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('jobtitle', 'xiveirmclientprofile');
			}
		}

		//In this example, we treat the frontend registration and the back end user create or edit as the same.
		elseif ($form->getName()=='com_users.registration' || $form->getName()=='com_users.user' )
		{
			// Add the registration fields to the form.
			JForm::addFormPath(dirname(__FILE__).'/profiles');
			$form->loadFile('profile', false);

			// Toggle whether the xiveirmclientid field is required.
			if ($this->params->get('register-xiveirmclientid', 1) > 0)
			{
				$form->setFieldAttribute('xiveirmclientid', 'required', $this->params->get('register-xiveirmclientid') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('xiveirmclientid', 'xiveirmclientprofile');
			}

			// Toggle whether the jobtitle field is required.
			if ($this->params->get('register-jobtitle', 1) > 0)
			{
				$form->setFieldAttribute('jobtitle', 'required', $this->params->get('register-jobtitle') == 2, 'xiveirmclientprofile');
			} else {
				$form->removeField('jobtitle', 'xiveirmclientprofile');
			}
		}
	}

	function onUserAfterSave($data, $isNew, $result, $error)
	{
		$userId = JArrayHelper::getValue($data, 'id', 0, 'int');

		if ($userId && $result && isset($data['xiveirmclientprofile']) && (count($data['xiveirmclientprofile'])))
		{
			try
			{
				$db = &JFactory::getDbo();
				$db->setQuery('DELETE FROM #__user_profiles WHERE user_id = '.$userId.' AND profile_key LIKE \'xiveirmclientprofile.%\'');
				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}

				$tuples = array();
				$order  = 1;
				foreach ($data['xiveirmclientprofile'] as $k => $v)
				{
					$tuples[] = '('.$userId.', '.$db->quote('xiveirmclientprofile.'.$k).', '.$db->quote(json_encode($v)).', '.$order++.')';
				}

				$db->setQuery('INSERT INTO #__user_profiles VALUES '.implode(', ', $tuples));
				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

	/**
	  * Remove all user profile information for the given user ID
	  *
	  * Method is called after user data is deleted from the database
	  *
	  * @param       array           $user           Holds the user data
	  * @param       boolean         $success        True if user was succesfully stored in the database
	  * @param       string          $msg            Message
	  */
	function onUserAfterDelete($user, $success, $msg)
	{
		if (!$success)
		{
			return false;
		}

		$userId = JArrayHelper::getValue($user, 'id', 0, 'int');

		if ($userId)
		{
			try
			{
				$db = JFactory::getDbo();
				$db->setQuery(
					'DELETE FROM #__user_profiles WHERE user_id = '.$userId .
					' AND profile_key LIKE \'profile5.%\''
				);

				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}

}
?>