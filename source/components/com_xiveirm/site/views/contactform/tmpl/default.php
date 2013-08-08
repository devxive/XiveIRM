<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

NFWHtmlJavascript::setToggle('extended', 'toggleExtend');
NFWHtmlJavascript::setTextLimit('.limited', 250);
NFWHtmlJavascript::setTextAutosize('.autosize');
NFWHtmlJavascript::setTooltip('.xtooltip');
NFWHtmlJavascript::setPopover('.xpopover');
NFWHtmlJavascript::setPreventFormSubmitByKey();
NFWHtmlJavascript::loadGritter();
NFWHtmlJavascript::loadAlertify();
NFWHtmlJavascript::setPreventFormLeaveIfChanged('#form-contact');
NFWHtmlJavascript::loadEasyPie('.ep-chart', false, false);
NFWHtmlJavascript::loadBootbox('.bootbox');
// NFWPluginsSHA256::loadSHA256('js.sha256');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_ADMINISTRATOR);

// Load the XiveIRMSystem Session Data
$session = IRMSessionHelper::getValues();

// If it's a new contact and we have set the catid in the prevoius link!
if(!$this->item->catid) {
	$this->item->catid = JFactory::getApplication()->getUserState('com_xiveirm.edit.contact.catid');
}

// Get Permissions based on category
if ( !$this->item->catid ) {
	// We have no category id and use the components acl
	$acl = NFWAccessHelper::getActions('com_xiveirm');
} else {
	// We have a category id and use the category acl
	$acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $this->item->catid);
}

// Import all TabApps based on the XiveIRM TabApp configs and the related catid!
// IRMSystem::getPlugins($this->item->catid, 'contacts');
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
$checkedOut = NFWHtmlJavascript::getCheckoutMessage($this->item->checked_out, $this->item->checked_out_time, '#checkout-message', $checkoutParams);

// used for Javascript processed messages
$full_name = $this->item->first_name . ' ' . $this->item->last_name;
?>
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
					<?php if( ($this->item->id && $checkedOut['by'] != 'other') && ($acl->get('core.edit') || $acl->get('core.edit.own')) ): ?>
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
									<?php NFWHtmlJavascript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span6">
										<?php if($this->item->catid && !$this->item->id) { ?>
											<input type="hidden" name="contacts[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NFWItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="contacts[catid]" class="chzn-select-category input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
											<option value=""></option>
											<?php
												$options = IRMFormList::getCategoryOptions('com_xiveirm');
												if($options) {
													foreach ($options as $key => $val) {
														echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
													}
												}
											?>
										</select>
										<?php } ?>
									</div>
									<div class="span6">
										<a id="toggleExtend" class="btn btn-small pull-right"><i class="icon-double-angle-down"></i><span class="hidden-phone"> Additional fields</span></a>
									</div>
								</div>
							</div>
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CUSTOMER_ID_LABEL'); ?></label>
								<div class="controls controls-row">
									<input type="text" name="contacts[customer_id]" class="input-control span6" id="prependedInput" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CUSTOMER_ID'); ?>" value="<?php echo $this->item->customer_id; ?>">
									<?php NFWHtmlJavascript::setChosen('.chzn-select-parent', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span6">
										<select name="contacts[parent_id]" class="chzn-select-parent input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_PARENT'); ?>" required>
											<?php
												if(!$this->item->parent_id) {
													echo '<option value="0" selected>' . JText::_('COM_XIVEIRM_SELECT_NO_PARENT') . '</option>';
												}

//												$options_parent_id = IRMSystem::getListOptions('parents', $xsession->client_id);
//												foreach($options_parent_id->categories as $catid => $name) {
//													echo '<optgroup label="' . $name . '">';
//														foreach($options_parent_id->contacts as $contactgroupid => $contactgroup) {
//															if($catid == $contactgroupid) {
//																foreach($contactgroup as $contact) {
//																	if($this->item->parent_id == $contact['id']) {
//																		echo '<option value="' . $contact['id'] . '" selected>#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
//																	} else {
//																		echo '<option value="' . $contact['id'] . '">#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
//																	}
//																}
//																unset($options_parent_id->contacts[$contactgroupid]);
//															}
//														}
//													echo '</optgroup>';
//												}
//												unset($options_parent_id->categories, $options_parent_id->contacts);
											?>
										</select>
									</div>
								</div>
							</div>
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_NAME_LABEL'); ?></label>
								<div id="name-ext" class="controls controls-row extended">
									<input type="text" name="contacts[company]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_COMPANY'); ?>" value="<?php echo $this->item->company; ?>">
									<input type="text" name="contacts[title]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_NAME_TITLE'); ?>" value="<?php echo $this->item->title; ?>">
								</div>
								<div class="controls controls-row">
									<input type="text" name="contacts[last_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_LAST_NAME'); ?>" value="<?php echo $this->item->last_name; ?>" <?php echo empty($this->item->id) ? 'autofocus' : ''; ?>>
									<input type="text" name="contacts[first_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_FIRST_NAME'); ?>" value="<?php echo $this->item->first_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label">
									<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_LABEL'); ?>
									<span class="help-button xpopover" data-trigger="hover" data-placement="top" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_DESC'); ?>" data-original-title="The Gender take over effects!">?</span>
								</label>
								<div class="controls controls-row">
									<?php NFWHtmlJavascript::setChosen('.chzn-select-gender', false, array('width' => '100%', 'disable_search' => true)); ?>
									<div class="span6">
										<select name="contacts[gender]" class="chzn-select-gender input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
											<option value=""><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_SELECT'); ?></option>
											<?php
												$options = IRMFormList::getGenderOptions();
												foreach ($options as $key => $val) {
													if($this->item->gender == $key) {
														echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
													} else {
														echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
													}
												}
											?>
										</select>
									</div>
									<input type="date" name="contacts[dob]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_DOB'); ?>" value="<?php echo $this->item->dob; ?>" />
								</div>
							</div>
							
							<div id="address-block" class="control-group">
								<label class="control-label">
									<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_LABEL'); ?>
									<span class="help-button xpopover btn-danger" data-trigger="hover" data-placement="top" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CLEAN_ADDRESS_DESC'); ?>" data-original-title="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CLEAN_ADDRESS_TITLE'); ?>" onClick="clearAddress()">!</span>
									<div id="geocode-progress" class="ep-chart" data-percent="100" data-size="23" data-line-width="4" data-animate="1500" data-color="#EBA450" style="display: inline-block; vertical-align: middle;"></div>
								</label>
								<div class="controls">
									<div class="alert center" style="padding: 8px !important;">
										<input type="text" id="address_auto_geocoder" class="input-control span12 red" placeholder="Type in: Street HouseNo, City, State, Country" onFocus="geocodeInputHelper()" onBlur="geocodeInputHelper()" style="margin-bottom: 0 !important;;">
										<p id="geocode-input-helper" style="margin-top: 10px; display: none;">
											<small>
												This is a helper input field. Type in here the address and let the Geocoder find the right one for you!<br>
												<em><strong>Please note that this field will not save its value!</strong></em>
											</small>
										</p>
									</div>
								</div>
								<div id="inner-address-block">
									<div class="controls extended">
										<input type="text" id="address_name" name="contacts[address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->address_name; ?>">
									</div>
									<div class="controls extended">
										<input type="text" id="address_name_add" name="contacts[address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->address_name_add; ?>">
									</div>
									<div class="controls controls-row">
										<input type="text" id="address_street" name="contacts[address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->address_street; ?>">
										<input type="text" id="address_houseno" name="contacts[address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->address_houseno; ?>">
									</div>
									<div class="controls controls-row">
										<input type="text" id="address_zip" name="contacts[address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->address_zip; ?>">
										<input type="text" id="address_city" name="contacts[address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->address_city; ?>">
									</div>
									<div class="controls controls-row extended">
										<input type="text" id="address_region" name="contacts[address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->address_region; ?>">
										<input type="text" id="address_country" name="contacts[address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->address_country; ?>">
									</div>
									<input type="text" placeholder="lat" class="purple span4" id="address_lat" name="contacts[address_lat]" value="<?php echo $this->item->address_lat; ?>" />
									<input type="text" placeholder="lng" class="purple span4" id="address_lng" name="contacts[address_lng]" value="<?php echo $this->item->address_lng; ?>" />
									<input type="text" placeholder="hash" class="purple span4" id="address_hash" name="contacts[address_hash]" value="<?php echo $this->item->address_hash; ?>" />
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
									<input type="text" name="contacts[phone]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_PHONE'); ?>" value="<?php echo $this->item->phone; ?>">
									<input type="text" name="contacts[fax]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_FAX'); ?>" value="<?php echo $this->item->fax; ?>">
								</div>
								<div class="controls">
									<input type="text" name="contacts[mobile]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_MOBILE'); ?>" value="<?php echo $this->item->mobile; ?>">
								</div>
							</div>
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_WEB_LABEL'); ?></label>
								<div class="controls controls-row">
									<input type="text" name="contacts[email]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_EMAIL'); ?>" value="<?php echo $this->item->email; ?>">
									<input type="text" name="contacts[web]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_WEB'); ?>" value="<?php echo $this->item->web; ?>">
								</div>
							</div>
							
							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_INTERNAL_REMARKS'); ?></label>
								<div class="controls">
									<textarea name="contacts[remarks]" class="input-control span12 limited autosize" max-data-length="250" maxlength="250" rows="2" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_INTERNAL_REMARKS_DESC'); ?>"><?php echo $this->item->remarks; ?></textarea>
								</div>
							</div>
						</div>
						<div class="span5">
							<div class="well">
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							<?php
								echo '<style>';
									echo '.widget-box .btn-app.btn-mini span { font-size: 11px; }';
								echo '</style>';
								echo '<div class="widget-box light-border" style="margin-top: -10px;">';
									echo '<div class="widget-header red">';
										echo '<h5 class="smaller">Actiontoolbar</h5>';
										echo '<div class="widget-toolbar">';
											echo '<label>';
												if(!empty($this->item->address_street) && !empty($this->item->address_houseno) && !empty($this->item->address_zip) && !empty($this->item->address_city) && !empty($this->item->address_country)) {
													echo '<a class="xpopover link-control btn btn-mini btn-light" href="http://google.com/maps/preview#!q=' . $this->item->address_street . '+' . $this->item->address_houseno . '+' . $this->item->address_zip . '+' . $this->item->address_city . '" target="_blank" data-placement="bottom" data-content="Show the address with a map marker in the new Google Maps" title="Google Maps 2.0"><img src="http://www.zdnet.de/wp-content/uploads/2012/11/googlemaps-icon.png" style="height: 15px; margin-top: -2px;"></a>';
												}

												echo '<a href="javascript:alert(\'PrintPDF: In sandbox not available at present\');" class="link-control btn btn-mini btn-light"><i class="icon-print icon-only"></i></a>';
												echo '<a href="javascript:alert(\'DocUpload: In sandbox not available at present\');" class="link-control btn btn-mini btn-light"><i class="icon-cloud-upload icon-only"></i></a>';
												echo '<a href="javascript:alert(\'ShareIt: In sandbox not available at present\');" class="link-control btn btn-mini btn-light"><i class="icon-share-alt icon-only"></i></a>';
											echo '</label>';
										echo '</div>';
									echo '</div>';
									echo '<div class="widget-body">';
										echo '<div class="widget-main padding-5">';
											foreach($dispatcher->trigger( 'loadActionButton', array(&$this->item) ) as $inBaseWidget)
											{
												echo '<span id="' . $inBaseWidget['tab_key'] . '_button">';
												echo $inBaseWidget['tabContent'];
												echo '</span>';
											}
										echo '</div>';
									echo '</div>';
								echo '</div>';

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

				<input type="hidden" name="contacts[id]" id="customer_cid" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />

				<?php echo IRMHtmlBuilder::getClientId($this->item->client_id, $options = array('name' => 'contacts[client_id]')); ?>

				<input type="hidden" name="contacts[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" id="checkEditForm" name="checkEditForm" value="0" />
				<input type="hidden" name="irmapi[coreapp]" value="contacts" />
				<input type="hidden" name="irmapi[component]" value="com_xiveirm" />
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
		<input type="hidden" name="irmapi[id]" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />
		<input type="hidden" name="irmapi[coreapp]" value="contacts" />
		<?php echo JHtml::_('form.token'); ?>
	</form>

	<!-- ---------- ---------- ---------- ---------- ---------- BEGIN EXTERN FORMS ---------- ---------- ---------- ---------- ---------- -->
	<?php
		foreach($dispatcher->trigger( 'loadExternForms', array(&$this->item) ) as $externForm)
		{
			echo '<span id="' . $externForm['tab_key'] . '_form">';
			echo $externForm['tabContent'];
			echo '</span>';
		}
	?>
	<!-- ---------- ---------- ---------- ---------- ---------- END EXTERN FORMS ---------- ---------- ---------- ---------- ---------- -->

</div>

<script>

function clearAddress() {
	if(jQuery("#address_name").attr('disabled') != 'disabled') {
		jQuery('#address_name').val('');
		jQuery('#address_name_add').val('');
		jQuery('#address_street').val('');
		jQuery('#address_houseno').val('');
		jQuery('#address_zip').val('');
		jQuery('#address_city').val('');
		jQuery('#address_region').val('');
		jQuery('#address_country').val('');
		jQuery('#address_lat').val('');
		jQuery('#address_lng').val('');
		jQuery('#address_hash').val('');
	}
}

function geocodeInputHelper() {
	jQuery("#geocode-input-helper").fadeToggle('slow');
}

<?php
	// PHP OUT COMMENTS TO PREVENT SHOWING INFOS IN SOURCE CODE WHILE IN ALPHA/BETA
	/*
	 * Returns from API in json format
	 * example {"apiReturnCode":"SAVED","apiReturnRowId":"173","apiReturnMessage":"Successfully saved"}
	 * 
	 * apiReturnCode could be: SAVED, UPDATED or an Error Number ie. 666
	 * apiReturnMessage: returns a informal message, should be used for debugging and not in production use. returns the database or php errors
	 */
?>
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

					$("#form-contact .input-control").attr("disabled", true).trigger("liszt:updated");
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

					$("#form-contact .input-control").attr("disabled", true);
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
	jQuery("#form-contact .input-control").attr("disabled", true);

	<?php // Check if a link is disabled and prevent default action !! ?>
	jQuery('a').click(function(e) {
		linkvar = $(this).attr('disabled');
		if (linkvar === 'disabled') {
			e.preventDefault();
		}
	});

	<?php // XAP-TODO: Have to set more functions to the edit form, such as a DB-checkout on activate and checkin on save or check in on deactivate !!!! ?>
	function cancelEdit() {
	}

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

					<?php // Remove all disabled fields if we got a "TRUE" response from the api and set the id="ckeckeditform" to 1 for form checks on leaving site ?>
					if(inp.hasAttribute('disabled')) {
						// Remove disabled and fire trigger at same time
						$("#form-contact .input-control").attr("disabled", false).trigger("liszt:updated");

						$("#loading-btn-edit").addClass("hidden");
						$("#form-buttons").removeClass("hidden");
						$(".widget-box .link-control").attr("disabled", true);

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