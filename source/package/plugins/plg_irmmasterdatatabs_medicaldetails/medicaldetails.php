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
		$this->tabId = 'medicaldetails';
		$this->loadLanguage();
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabId = The tab identification, tabName = Translateable string from .ini file
	 *
	 * @since   3.0
	 */
	public function loadTabButton(&$item = null)
	{
		$tabName = '<i class="icon-medkit red"></i> ' . JText::_('PLG_IRMMASTERDATATABS_MEDICALDETAILS_TABNAME');

		$tabButton = array(
			'tabId' => $this->tabId,
			'tabName' => $tabName
		);

		return $tabButton;
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabId = The tab identification, tabContent = Summary of the tabForms
	 *
	 * @since   3.0
	 */
	public function loadTabContainer(&$item = null)
	{
		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabId; ?> ---------->
		<style>
			#chzn-select .chzn-container, #chzn-select .chzn-container-multi, #chzn-select .chzn-drop {width: 99.6% !important;}
			.widget-toolbar .popover {width: 220px;}
			.widget-toolbar .popover .popover-content {line-height: 15px;}
		</style>

		<form id="form-tab-<?php echo $this->tabId; ?>" class="form-horizontal">
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
										<input name="" type="text" placeholder="Enter Informations here to activate!" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Transportart</label>
									<div class="controls">
										<input name="" type="text" placeholder="Enter Informations here to activate!" required>
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Additional Persons</label>
									<div class="controls">
										<input name="add_person" type="text" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Mobile Oxigen</label>
									<div class="controls">
										<input name="" type="text" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Vacuum Mattress</label>
									<div class="controls">
										<input name="" type="text" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Other Helpmittel</label>
									<div class="controls">
										<input name="" type="text" placeholder="Enter Informations here to activate!">
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
									<label class="control-label">MRE <i class="icon-tags red"></i></label>
									<div id="chzn-select" class="controls">
										<select multiple data-placeholder="Select Informations here to activate!" class="chzn-select">
											<option value=""></option>
											<option value="mrsa">MRSA</option>
											<option value="vre">VRE</option>
											<option value="esbl">ESBL</option>
										</select>
									</div>
								</div>

								<div class="control-group">
									<label class="control-label">HEP <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="mre" type="text" class="span12" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">HIV <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="mre" type="text" class="span12" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Umkehrisolation <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="mre" type="text" class="span12" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag red"></i></label>
									<div class="controls">
										<input name="mre" type="text" class="span12" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Obese 120KG+ <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input name="120kg" type="text" placeholder="Enter Informations here to activate!">
									</div>
								</div>
								<div class="control-group">
									<label class="control-label">Sonstiges <i class="icon-tag orange"></i></label>
									<div class="controls">
										<input name="120kg" type="text" placeholder="Enter Informations here to activate!">
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
									<label class="control-label">Additional Persons</label>
									<div class="controls">
										<input name="add_person" type="text" placeholder="Enter Informations here">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
			<div id="message_post"></div>
			<div class="form-actions">
				<input type="hidden" name="tabForm[direction]" value="api">
				<input type="hidden" name="tabForm[format]" value="json">
				<input type="hidden" name="tabForm[itemid]" value="<?php echo $item->id; ?>">
				<input type="hidden" name="tabForm[tabid]" value="<?php echo $this->tabId; ?>">
				<?php echo JHtml::_('form.token'); ?>
				<button class="btn btn-info" type="submit"><i class="icon-ok"></i> Submit</button>
				&nbsp; &nbsp; &nbsp;
				<button class="btn" type="reset"><i class="icon-undo"></i> Reset</button>
			</div>
		</form>

		<div class="hr"></div>
		<center>
			<span class="help-button ace-popover" data-trigger="hover" data-placement="top" data-content="Informations given here are used in other applications, such as the despatching app => order form. Use this as help to minimize inputs during remaining phone orders." data-original-title="Info about cross referencing!"><i class="icon-random"></i></span>
		</center>
<?php echo JRoute::_('index.php?option=com_xiveirm&task=api.save'); ?>
		<script>
			jQuery(function(){
				$("#form-tab-<?php echo $this->tabId; ?>").submit(function(e){
					e.preventDefault();

					$.post("/plugins/irmmasterdatatabs/medicaldetails/process.php", $("#form-tab-<?php echo $this->tabId; ?>").serialize(),
					function(data){
						if(data.email_check == 'invalid'){
							$("#message_post").html("<div class='errorMessage'>Sorry " + data.name + ", " + data.email + " is NOT a valid e-mail address. Try again.</div>");
						} else {
							$("#message_post").html("<div class='successMessage'>" + data.email + " is a valid e-mail address. Thank you, " + data.name + ".</div>");
						}
					}, "json");
				});
			});
		</script>

		<!---------- End output buffering: <?php echo $this->tabId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$tabContainer = array(
			'tabId' => $this->tabId,
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