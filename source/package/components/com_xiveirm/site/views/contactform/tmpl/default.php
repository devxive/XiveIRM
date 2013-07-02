<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

// JHtml::_('behavior.keepalive');
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');

// Import HTML and Helper Classes
nimport('NHtml.JavaScript');
nimport('NItem.Helper', false);
nimport('NUser.Access', false);

NHtmlJavaScript::setToggle('extended', 'toggleExtend');
NHtmlJavaScript::setTextLimit('.limited', 250);
NHtmlJavaScript::setTextAutosize('.autosize');
NHtmlJavaScript::setTooltip('.xtooltip');
NHtmlJavaScript::setPopover('.xpopover');
NHtmlJavaScript::setPreventFormSubmitByKey();
NHtmlJavaScript::loadGritter();
NHtmlJavaScript::setPreventFormLeaveIfChanged();

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_ADMINISTRATOR);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Get Permissions
$permissions = NUserAccess::getPermissions('com_xiveirm', false, false, 'xiveirm_contacts.' . $this->item->id);

// If it's a new contact and we have set the catid in the prevoius link!
if(!$this->item->catid) {
	$this->item->catid = JFactory::getApplication()->getUserState('com_xiveirm.edit.contact.catid');
}

// Import all TabApps based on the XiveIRM TabApp configs and the related catid!
IRMSystem::getPlugins($this->item->catid);
$dispatcher = JDispatcher::getInstance();

// Check for checked out item
$checkoutParams = array(
	'checkoutByOtherTitle' => 'COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_BY_OTHER_ALERT_ERROR_TITLE',
	'checkoutByOtherMessage' => 'COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_BY_OTHER_ALERT_ERROR_BODY',
	'checkoutByUserTitle' => 'COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_BY_USER_ALERT_ERROR_TITLE',
	'checkoutByUserMessage' => 'COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_BY_USER_ALERT_ERROR_BODY',
	'checkinMessage' => 'COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_BY_OTHER_ALERT_ERROR_CHECKIN_MESSAGE',
	'checkinTime' => 10,
	'userlink' => '#'
);
$checkedOut = NHtmlJavaScript::getCheckoutMessage($this->item->checked_out, $this->item->checked_out_time, '#checkout-message', $checkoutParams);

// used for javascript processed messages
$full_name = $this->item->first_name . ' ' . $this->item->last_name;
?>
<!--
<script type="text/javascript">
	function getScript(url,success) {
		var script = document.createElement('script');
		script.src = url;
		var head = document.getElementsByTagName('head')[0],
			done = false;

		// Attach handlers for all browsers
		script.onload = script.onreadystatechange = function() {
			if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete'))
			{
				done = true;
				success();
				script.onload = script.onreadystatechange = null;
				head.removeChild(script);
			}
		};

		head.appendChild(script);
	}

	getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
		js = jQuery.noConflict();
		js(document).ready(function(){
			js('#form-contact').submit(function(event){
			});

		});
	});
</script>
-->
<div class="row-fluid">
	<!-- ---------- ---------- ---------- ---------- ---------- BEGIN PAGE HEADER ---------- ---------- ---------- ---------- ---------- -->

	<div class="row-fluid header smaller lighter blue">
		<h1>
			<span class="span7">
				<i class="icon-user"></i>
				<?php if (!empty($this->item->id)): ?>
					<?php echo ' ' . $this->item->last_name; ?>, <?php echo $this->item->first_name; ?> <?php if($this->item->customer_id): echo '<small><i class="icon-double-angle-right"></i> (#' . $this->item->customer_id . ')</small>'; endif; ?>
				<?php else: ?>
					<?php echo ' ' . JText::_('COM_XIVEIRM_CONTACT_FORM_ADD_NEW_CONTACT'); ?>
				<?php endif; ?>
			</span>
			<span class="span5">
				<div class="btn-group pull-right inline">
					<?php if( ($this->item->id && $checkedOut['by'] != 'other') && ($permissions->get('core.edit') || $permissions->get('core.edit.own')) ): ?>
						<a onClick="enableEdit()" id="loading-btn-edit" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="btn btn-warning btn-mini edit-form-button"><i class="icon-edit"></i> <?php echo JText::_('COM_XIVEIRM_EDIT_ITEM'); ?></a>
					<?php endif; ?>
					<?php if($checkedOut['by'] == 'other'): ?>
						<a class="btn btn-danger btn-mini" href="<?php echo JRoute::_('index.php?option=com_xiveirm'); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_CANCEL_ITEM'); ?></a>
					<?php else: ?>
						<a class="btn btn-danger btn-mini" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=api.cancel&id=' . $this->item->id); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_CANCEL_ITEM'); ?></a>
					<?php endif; ?>
				</div>
			</span>
		</h1>
	</div><!--/header-->
	<!-- ---------- ---------- ---------- ---------- ---------- END PAGE HEADER ---------- ---------- ---------- ---------- ---------- -->
	<!-- ---------- ---------- ---------- ---------- ---------- BEGIN CHECK_OUT MESSAGE ---------- ---------- ---------- ---------- ---------- -->
	<?php
		echo $checkedOut['message'];
	?>
	<!-- ---------- ---------- ---------- ---------- ---------- END CHECK_OUT MESSAGE ---------- ---------- ---------- ---------- ---------- -->

	<form id="form-contact" class="form-validate form-horizontal">

		<!-- ---------- ---------- ---------- ---------- ---------- BEGIN MASTER_TAP_PANE_PLUGINSTYLED ---------- ---------- ---------- ---------- ---------- -->
		<div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> <?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TAB_BASICDATA'); ?></a></li>
				<!-- TAB.PLUGIN_BUTTON -->
				<?php
					foreach($dispatcher->trigger( 'loadTabButton', array(&$this->item) ) as $tabButton)
					{
						echo '<li><a data-toggle="tab" href="#' . $tabButton['tab_key'] . '">';
						echo $tabButton['tabButtonName'];
						echo '</a></li>';
					}
				?>
				<!-- TAB.PLUGIN_BUTTON -->
				<li class="dropdown">
					<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TAB_MORE'); ?> <b class="caret"></b></a>
					<ul class="dropdown-menu dropdown-info">
						<li><a data-toggle="tab" href="#dropdown1">@Anwendung 4</a></li>
						<li><a data-toggle="tab" href="#dropdown2">@Anwendung 5</a></li>
						<li><a data-toggle="tab" href="#">@Anwendung 6</a></li>
					</ul>
				</li>
			</ul>
	
			<!-- ---------- ---------- ---------- ---------- ---------- BEGIN master-tab-pane-container ---------- ---------- ---------- ---------- ---------- -->
			<div class="tab-content">
				<!-- ---------- ---------- ---------- ---------- ---------- BEGIN BASE-DATA_TAB_CORE ---------- ---------- ---------- ---------- ---------- -->
				<div id="base-data" class="tab-pane active">
					<div class="row-fluid">
						<div class="span7">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FORM_LBL_CONTACT_CATEGORY'); ?></label>
								<div class="controls controls-row">
									<div class="span6">
										<?php if($this->item->catid && !$this->item->id) { ?>
											<input type="hidden" name="coreform[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="coreform[catid]" class="chzn-select input-control" style="width: 362px;" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
											<option value=""></option>
											<?php
												$options = IRMSystem::getListOptions('categories', false);
												if($options->client) {
													echo '<optgroup label="' . JText::sprintf('COM_XIVEIRM_SELECT_CATEGORY_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
														foreach ($options->client as $key => $val) {
															if($this->item->catid == $key) {
																echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
															} else {
																echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
															}
														}
													echo '</optgroup>';
												}
												if($options->global) {
													echo '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
														foreach ($options->global as $key => $val) {
															if($this->item->catid == $key) {
																echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
															} else {
																echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
															}
														}
													echo '</optgroup>';
												}
											?>
										</select>
										<?php } ?>
									</div>
									<div class="span6">
										<a id="toggleExtend" class="btn btn-small btn-primary pull-right"><i class="icon-double-angle-down"></i><span class="hidden-phone"> Additional fields</span></a>
									</div>
								</div>
							</div>
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CUSTOMER_ID_LABEL'); ?></label>
								<div class="controls controls-row">
									<input type="text" name="coreform[customer_id]" class="input-control span6" id="prependedInput" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CUSTOMER_ID'); ?>" value="<?php echo $this->item->customer_id; ?>">
									<div class="span6">
										<select name="coreform[parent_id]" class="chzn-select input-control" style="width: 220px;" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_PARENT'); ?>" required>
											<?php
												if(!$this->item->parent_id) {
													echo '<option value="0" selected>' . JText::_('COM_XIVEIRM_SELECT_NO_PARENT') . '</option>';
												}

												$options_parent_id = IRMSystem::getListOptions('contacts', $xsession->client_id);
												foreach($options_parent_id->categories as $catid => $name) {
													echo '<optgroup label="' . $name . '">';
														foreach($options_parent_id->contacts as $contactgroupid => $contactgroup) {
															if($catid == $contactgroupid) {
																foreach($contactgroup as $contact) {
																	if($this->item->parent_id == $contact['id']) {
																		echo '<option value="' . $contact['id'] . '" selected>#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
																	} else {
																		echo '<option value="' . $contact['id'] . '">#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
																	}
																}
																unset($options_parent_id->contacts[$contactgroupid]);
															}
														}
													echo '</optgroup>';
												}
												unset($options_parent_id->categories, $options_parent_id->contacts);
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_NAME_LABEL'); ?></label>
								<div id="name-ext" class="controls controls-row extended">
									<input type="text" name="coreform[company]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_COMPANY'); ?>" value="<?php echo $this->item->company; ?>">
									<input type="text" name="coreform[title]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_NAME_TITLE'); ?>" value="<?php echo $this->item->title; ?>">
								</div>
								<div class="controls controls-row">
									<input type="text" name="coreform[last_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_LAST_NAME'); ?>" value="<?php echo $this->item->last_name; ?>" <?php echo empty($this->item->id) ? 'autofocus' : ''; ?>>
									<input type="text" name="coreform[first_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_FIRST_NAME'); ?>" value="<?php echo $this->item->first_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">
									<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_LABEL'); ?>
									<span class="help-button xpopover" data-trigger="hover" data-placement="top" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_DESC'); ?>" data-original-title="The Gender take over effects!">?</i>
								</label>
								<div class="controls controls-row">
									<select name="coreform[gender]" class="chzn-selectXXX input-control span6" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
										<option value=""><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_SELECT'); ?></option>
										<?php
											$options = IRMSystem::getListOptions('options', 'gender');
											if($options->client) {
												echo '<optgroup label="' . JText::sprintf('COM_XIVEIRM_SELECT_TRAITS_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
													foreach ($options->client as $key => $val) {
														if($this->item->gender == $key) {
															echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
														} else {
															echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
														}
													}
												echo '</optgroup>';
											}
											if($options->global) {
												echo '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
													foreach ($options->global as $key => $val) {
														if($this->item->gender == $key) {
															echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
														} else {
															echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
														}
													}
												echo '</optgroup>';
											}
										?>
									</select>
									<input type="date" name="coreform[dob]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_DOB'); ?>" value="<?php echo $this->item->dob; ?>" required>
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_LABEL'); ?></label>
								<div class="controls extended">
									<input type="text" name="coreform[address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->address_name; ?>">
								</div>
								<div class="controls extended">
									<input type="text" name="coreform[address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->address_name_add; ?>">
								</div>
								<div class="controls controls-row">
									<input type="text" name="coreform[address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->address_street; ?>">
									<input type="text" name="coreform[address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->address_houseno; ?>">
								</div>
								<div class="controls controls-row">
									<input type="text" name="coreform[address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->address_zip; ?>">
									<input type="text" name="coreform[address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->address_city; ?>">
								</div>
								<div class="controls controls-row extended">
									<input type="text" name="coreform[address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->address_region; ?>">
									<input type="text" name="coreform[address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->address_country; ?>">
								</div>
							</div>
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN INCORE-FORM RECOMMENDED FORMFIELDS ---------- ---------- ---------- ---------- ---------- -->

							<?php
								foreach($dispatcher->trigger( 'loadInCoreformForm', array() ) as $formFields)
								{
							?>
							<div class="control-group">
								<label class="control-label"><?php echo $formFields['formLabel']; ?></label>
								<div class="controls controls-row">
									<?php echo $formFields['formFields']; ?>
								</div>
							</div>
							<?php
								}
							?>

							<!-- ---------- ---------- ---------- ---------- ---------- END INCORE-FORM RECOMMENDED FORMFIELDS ---------- ---------- ---------- ---------- ---------- -->

							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_PHONE_NUMBERS_LABEL'); ?></label>
								<div class="controls controls-row">
									<input type="text" name="coreform[phone]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_PHONE'); ?>" value="<?php echo $this->item->phone; ?>">
									<input type="text" name="coreform[fax]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_FAX'); ?>" value="<?php echo $this->item->fax; ?>">
								</div>
								<div class="controls">
									<input type="text" name="coreform[mobile]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_MOBILE'); ?>" value="<?php echo $this->item->mobile; ?>">
								</div>
							</div>
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_WEB_LABEL'); ?></label>
								<div class="controls controls-row">
									<input type="text" name="coreform[email]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_EMAIL'); ?>" value="<?php echo $this->item->email; ?>">
									<input type="text" name="coreform[web]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_WEB'); ?>" value="<?php echo $this->item->web; ?>">
								</div>
							</div>
							
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_INTERNAL_REMARKS'); ?></label>
								<div class="controls">
									<textarea name="coreform[remarks]" class="input-control span12 limited autosize" max-data-length="250" maxlength="250" rows="2" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_INTERNAL_REMARKS_DESC'); ?>"><?php echo $this->item->remarks; ?></textarea>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="well">
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							<?php
								foreach($dispatcher->trigger( 'loadInBasedataContainerFirst', array(&$this->item) ) as $inBaseWidget)
								{
									echo '<div id="' . $inBaseWidget['tab_key'] . '">';
									echo $inBaseWidget['tabContent'];
									echo '</div>';
								}
								foreach($dispatcher->trigger( 'loadInBasedataContainer', array(&$this->item) ) as $inBaseWidget)
								{
									echo '<div id="' . $inBaseWidget['tab_key'] . '">';
									echo $inBaseWidget['tabContent'];
									echo '</div>';
								}
								foreach($dispatcher->trigger( 'loadInBasedataContainerLast', array(&$this->item) ) as $inBaseWidget)
								{
									echo '<div id="' . $inBaseWidget['tab_key'] . '">';
									echo $inBaseWidget['tabContent'];
									echo '</div>';
								}
							?>
							<!-- ---------- ---------- ---------- ---------- ---------- END TAB.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							
							</div>
						</div>
					</div>
				</div>
				<!-- ---------- ---------- ---------- ---------- ---------- END BASE-DATA_TAB_CORE ---------- ---------- ---------- ---------- ---------- -->


				<!-- ---------- ---------- ---------- ---------- ---------- BEGIN DROPDOWN TAB PANE TEST ---------- ---------- ---------- ---------- ---------- -->
				<div id="dropdown1" class="tab-pane">
					<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
				</div>
				<div id="dropdown2" class="tab-pane">
					<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin.</p>
				</div>
				<!-- ---------- ---------- ---------- ---------- ---------- END DROPDOWN TAB PANE TEST ---------- ---------- ---------- ---------- ---------- -->

				<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB.PLUGINS_CONTENT ---------- ---------- ---------- ---------- ---------- -->
				<?php
					foreach($dispatcher->trigger( 'loadTabContainer', array(&$this->item) ) as $tabContainer)
					{
						echo '<div id="' . $tabContainer['tab_key'] . '" class="tab-pane">';
						echo $tabContainer['tabContent'];
						echo '</div>';
					}
				?>
				<!-- ---------- ---------- ---------- ---------- ---------- END TAB.PLUGINS_CONTENT ---------- ---------- ---------- ---------- ---------- -->

				<input type="hidden" name="coreform[id]" id="customer_cid" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />

<!-- Könnte raus, weil ich in den models das datum eintrage und es hier erstmal unrelevant ist, kann in das core widget rein, wann der kunde das erste mal angelegt wurde!!!! -->
<input type="hidden" name="coreform[created]" value="<?php echo $this->item->created; ?>" />

				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="coreform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
					<input type="hidden" name="coreform[client_id]" value="<?php echo $xsession->client_id; ?>" maxlength="50" />
				<?php } else { ?>
					<input type="hidden" name="coreform[created_by]" value="<?php echo $this->item->created_by; ?>" />
					<input type="hidden" name="coreform[client_id]" value="<?php echo $this->item->client_id; ?>" maxlength="50" />
				<?php } ?>
				<input type="hidden" name="coreform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="coreform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
				<input type="hidden" name="coreform[modified]" value="<?php echo $this->item->modified; ?>" />
				<input type="hidden" name="coreform[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" id="checkEditForm" name="checkEditForm" value="0" />
				<?php echo JHtml::_('form.token'); ?>

				<div class="form-actions">
					<span id="form-buttons" class="<?php echo empty($this->item->id) ? '' : 'hidden'; ?>">
						<button id="loading-btn-save" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-complete-text="<?php echo JText::_('COM_XIVEIRM_API_SAVED_BUTTON'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="validate btn btn-info" type="submit"><i class="icon-ok"></i> <?php echo isset($this->item->id) ? JText::_('COM_XIVEIRM_UPDATE_ITEM') : JText::_('COM_XIVEIRM_SAVE_ITEM'); ?></button>
						&nbsp; &nbsp; &nbsp;
						<button class="btn" type="reset" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_RESET_TIP'); ?>"><i class="icon-undo"></i> <?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_RESET'); ?></button>
						&nbsp; &nbsp; &nbsp;
					</span>
					<?php if($checkedOut['by'] == 'other'): ?>
						<a class="btn btn-danger" href="<?php echo JRoute::_('index.php?option=com_xiveirm'); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_CANCEL_ITEM'); ?></a>
					<?php else: ?>
						<a class="btn btn-danger" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=api.cancel&id=' . $this->item->id); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_CANCEL_ITEM'); ?></a>
					<?php endif; ?>
				</div>
			</div>
			<!-- ---------- ---------- ---------- ---------- ---------- END master-tab-pane-container ---------- ---------- ---------- ---------- ---------- -->
		</div>
		<!-- ---------- ---------- ---------- ---------- ---------- END MASTER_TAP_PANE_PLUGINSTYLED ---------- ---------- ---------- ---------- ---------- -->

	</form>
	<form id="form-contact-cica">
		<input type="hidden" name="cica[id]" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>

<script>
	/*
	 * Returns from API in json format
	 * example {"apiReturnCode":"SAVED","apiReturnRowId":"173","apiReturnMessage":"Successfully saved"}
	 * 
	 * apiReturnCode could be: SAVED, UPDATED or an Error Number ie. 666
	 * apiReturnMessage: returns a informal message, should be used for debugging and not in production use. returns the database or php errors
	 */
	jQuery(function(){
		$("#form-contact").submit(function(e){
			e.preventDefault();

			$("#loading-btn-save").addClass("btn-warning");
			$("#loading-btn-save").button("loading");

			$.post('index.php?option=com_xiveirm&task=api.ajaxsave', $("#form-contact").serialize(),
			function(data){
				if(data.apiReturnCode === 'SAVED'){
					$.gritter.add({
						title: 'Successfully saved',
						text: 'You have successfully saved all items for the customer <?php echo $this->item->first_name . ' ' . $this->item->last_name; ?>',
						icon: 'icon-check',
						class_name: 'alert-success'
					});
					$("#customer_cid").val(data.apiReturnId);

					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("complete");
					$("#loading-btn-save").button("reset");

					<?php if(!$this->item->catid) { ?>
						var cId = $("#customer_cid").val();
						window.location.href = "<?php echo JRoute::_('index.php?task=contact.edit&id='); ?>" + cId;
					<?php } ?>

				} else if(data.apiReturnCode === 'UPDATED') {
					$.gritter.add({
						title: 'Successfully updated',
						text: 'You have successfully saved all items for the customer <?php echo $this->item->first_name . ' ' . $this->item->last_name; ?>',
						icon: 'icon-globe',
						class_name: 'alert-info'
					});
					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("complete");
					$("#loading-btn-save").button("reset");

					$("#loading-btn-edit").removeClass("hidden");
					$("#loading-btn-edit").button("complete");
					$("#loading-btn-edit").button("reset");

					$("#form-contact .input-control").attr("readonly", true);
					$("#form-buttons").addClass("hidden");
					$(".widget-box .btn").attr("disabled", false);

				} else if(data.apiReturnCode === 'NOTICE') {
					$.gritter.add({
						title: 'Successfully updated',
						text: 'You have successfully saved all core items for the customer <?php echo $this->item->first_name . ' ' . $this->item->last_name; ?><br><br>' + data.apiReturnMessage,
						icon: 'icon-info',
						sticky: true,
						class_name: 'alert-warning'
					});
					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("complete");
					$("#loading-btn-save").button("reset");

					$("#loading-btn-edit").removeClass("hidden");
					$("#loading-btn-edit").button("complete");
					$("#loading-btn-edit").button("reset");

					$("#form-contact .input-control").attr("readonly", true);
					$("#form-buttons").addClass("hidden");
					$(".widget-box .btn").attr("disabled", false);

				} else {
					$.gritter.add({
						title: 'An error occured',
						text: 'An error occured while trying to save or update. <br><br>Error code: ' + data.apiReturnCode + '<br><br>error message: ' + data.apiReturnMessage + '<br><br>If this error is persistant, please contact the support immediately with the given error!',
						icon: 'icon-warning-sign',
						sticky: true,
						class_name: 'alert-error'
					});
					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("error");
					$("#loading-btn-save").addClass("btn-danger");
				}
			}, "json");
		});
	});

	<?php if($this->item->id): ?>
	/*
	 *
	 *
	 * Item exist and we set now all input fields to readonly. Hit the edit button to remove all and check out the item!
	 * Note:	that we have added the disabled attribute to all .btn classes in widget boxes. May we do not need them anymore,
	 * 		because we have a function that prevent to leave the form if anything has changed!
	 *
	 */
	jQuery("#form-contact .input-control").attr("readonly", true);

	// XAP-TODO: Have to set more functions to the edit form, such as a DB-checkout on activate and checkin on save or check in on deactivate !!!!
	function cancelEdit() {
	}

	// XAP-TODO: Have to set more functions to the edit form, such as a DB-checkout on activate and checkin on save or check in on deactivate !!!!
	function enableEdit() {
		var inp = $('.input-control').get(0);

		$("#loading-btn-edit").addClass("btn-warning");
		$("#loading-btn-edit").button("loading");

		jQuery.post('index.php?option=com_xiveirm&task=api.ajaxcheckout', $("#form-contact-cica").serialize(),
			function(data){
				if(data.apiReturnCode === 'TRUE'){
					$.gritter.add({
						title: '<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_TITLE'); ?>',
						text: '<?php echo JText::sprintf('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_BODY', $full_name); ?>',
						icon: 'icon-signout',
						class_name: 'alert-warning'
					});

					// Remove all readonly fields if we got a "TRUE" response from the api and set the id="ckeckeditform" to 1 for form checks on leaving site
					if(inp.hasAttribute('readonly')) {
						$("#form-contact .input-control").attr("readonly", false);
						$("#loading-btn-edit").addClass("hidden");
						$("#form-buttons").removeClass("hidden");
						$(".widget-box .btn").attr("disabled", true);

						$("#checkEditForm").val("1");
					}
				} else {
					$.gritter.add({
						title: 'An error occured',
						text: 'An error occured while trying to check out for editing. <br><br>Error code: ' + data.apiReturnCode + '<br><br>Error message: ' + data.apiReturnMessage + '<br><br>If this error is persistant, please contact the support immediately with the given error!',
						icon: 'icon-warning-sign',
						sticky: true,
						class_name: 'alert-error'
					});

					$("#loading-btn-edit").removeClass("btn-warning");
					$("#loading-btn-edit").button("error");
					$("#loading-btn-edit").addClass("btn-danger");
				}
			},
		"json");
	}
	<?php else: ?>
		jQuery(".widget-box .btn").attr("disabled", true);
	<?php endif; ?>
</script>






























<!--









<div class="contact-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-contact" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contact.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
            			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
				<input type="hidden" name="jform[client_id]" value="<?php echo $this->item->client_id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('parent_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('parent_id'); ?></div>
			</div>
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

				<?php echo $this->form->getInput('created'); ?>
				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('modified'); ?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('customer_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('customer_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('company'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('company'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('last_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('last_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('first_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('first_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('gender'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('gender'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->gender as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="gender" name="jform[genderhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('dob'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('dob'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_name_add'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_name_add'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_street'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_street'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_houseno'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_houseno'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_zip'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_zip'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_city'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_city'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_region'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_region'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_country'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('phone'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('fax'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('fax'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('mobile'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('web'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('web'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('remarks'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('remarks'); ?></div>
			</div>

        </ul>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contact.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_xiveirm" />
            <input type="hidden" name="task" value="contactform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>



-->