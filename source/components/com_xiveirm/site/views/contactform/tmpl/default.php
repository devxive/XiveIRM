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
NFWHtmlJavascript::setTooltip( '.xtooltip' );
NFWHtmlJavascript::setPopover( '.xpopover', array('html' => true) );
NFWHtmlJavascript::setPreventFormSubmitByKey();
NFWHtmlJavascript::loadAlertify();
NFWHtmlJavascript::setPreventFormLeaveIfChanged('#form-contact');
NFWHtmlJavascript::loadBootbox('.bootbox');
IRMHtmlSelect2::init('.select2');

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
IRMAppHelper::importPlugins('com_xiveirm', $this->item->catid);
$dispatcher = JDispatcher::getInstance();

// Import all PluginApps with onBeforeContent
$dispatcher->trigger( 'onBeforeContent', array(&$this->item, &$this->params) );

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

// Prebuild the tabs
$appContainer = new stdClass();
foreach($dispatcher->trigger( 'htmlBuildTab', array(&$this->item, &$this->params) ) as $tab)
{
	$appHelper = new JObject();
	$appKey = $tab['appKey'];

	$appHelper->appKey     = $tab['appKey'];
	$appHelper->tabButton  = $tab['tabButton'];
	$appHelper->tabBody    = $tab['tabBody'];
	$appContainer->$appKey = $appHelper;
}
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
						<a id="loading-btn-edit" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="btn btn-warning btn-mini edit-form-button"><i class="icon-edit"></i> <?php echo JText::_('COM_XIVEIRM_EDIT_ITEM'); ?></a>
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
					$i = 0; $tabMax = 5; $appCount = count($appContainer);
					foreach( $appContainer as $irmApp )
					{
						$i++; // count right from beginning

						if ( $i < $tabMax ) {
							echo '<li><a data-toggle="tab" href="#' . $irmApp->appKey . '_tabbody" class="' . $irmApp->appKey . '_tabbutton">' . $irmApp->tabButton . '</a></li>';
						}

						if ( $i == $tabMax ) {
							echo '<li class="dropdown">';
								echo '<a data-toggle="dropdown" class="dropdown-toggle" href="#">' . JText::_('COM_XIVEIRM_CONTACT_FORM_TAB_MORE') . ' <b class="caret"></b></a>';
								echo '<ul class="dropdown-menu dropdown-info">';
									echo '<li><a data-toggle="tab" href="#' . $irmApp->appKey . '_tabbody" class="' . $irmApp->appKey . '_tabbutton">' . $irmApp->tabButton . '</a></li>';
						}

						if ( $i > $tabMax ) {
							echo '<li><a data-toggle="tab" href="#' . $irmApp->appKey . '_tabbody" class="' . $irmApp->appKey . '_tabbutton">' . $irmApp->tabButton . '</a></li>';
						}

						if ( $i >= $tabMax && $i == $appCount ) {
							echo '</ul></li>';
						}
					}
				?>
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
										<?php if ( ($this->item->catid && !$this->item->id) || ($this->item->catid && $appCount > 0) ) { ?>
											<input type="hidden" name="contacts[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NFWItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="contacts[catid]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
											<option></option>
											<?php
												$options = IRMFormList::getCategoryOptions('com_xiveirm');
												if($options) {
													foreach ($options as $key => $val) {
														if($this->item->catid == $key) {
															echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
														} else {
															echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
														}
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
									<div class="span6">
										<select name="contacts[parent_id]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_PARENT'); ?>">
											<?php
												echo '<option></option>';

												$options = IRMFormList::getParentContactOptions();
												foreach($options as $key => $val) {
													if($this->item->parent_id == $key) {
														echo '<option value="' . $key . '" selected>' . $val . '</option>';
													} else {
														echo '<option value="' . $key . '">' . $val . '</option>';
													}
												}
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
									<div class="span6">
										<select name="contacts[gender]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_SELECT'); ?>" required>
											<option></option>
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
							
							<div id="address-block-0" class="control-group address-block" data-direction="b" data-order="0">
								<label class="control-label">
									<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_LABEL'); ?>
									<div id="address-specific-options">
										<span id="clear-address-icon-helper" class="help-button xpopover btn-danger pull-right" data-placement="top" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CLEAN_ADDRESS_DESC'); ?>" data-original-title="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CLEAN_ADDRESS_TITLE'); ?>" onClick="clearAddress()"<?php echo empty($this->item->id) ? '' : ' style="display:none;"'; ?>>!</span>
										<span class="help-button btn-warning xpopover pull-right gverifier" data-trigger="hover" data-placement="top" data-content="Click here if you wish to check the address already filled out below" data-original-title="Geo Verification!"<?php echo empty($this->item->id) ? '' : ' style="display:none;"'; ?>>G</span>
									</div>
								</label>
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
									<div id="geo-coords">
										<input type="hidden" id="address_lat" name="contacts[address_lat]" value="<?php echo $this->item->address_lat; ?>" />
										<input type="hidden" id="address_lng" name="contacts[address_lng]" value="<?php echo $this->item->address_lng; ?>" />
									</div>
									<input type="hidden" id="address_hash" name="contacts[address_hash]" value="<?php echo $this->item->address_hash; ?>" />
								</div>
							</div>
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN INCORE-FORM RECOMMENDED FORMFIELDS ---------- ---------- ---------- ---------- ---------- -->

							<?php
								foreach($dispatcher->trigger( 'htmlBuildPseudoForms', array(&$this->item, &$this->params) ) as $formFields)
								{
									echo '<div class="control-group ' . $formFields['appKey'] . '_pseudoform">';
										echo '<label class="control-label">' . $formFields['formLabel'] . '</label>';
										echo '<div class="controls controls-row">';
											echo $formFields['formFields'];
										echo '</div>';
									echo '</div>';
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
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN APP.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
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
											foreach($dispatcher->trigger( 'htmlBuildAction', array(&$this->item, &$this->params) ) as $actionButton)
											{
												echo '<span id="' . $actionButton['appKey'] . '_actionbutton">';
												echo $actionButton['button'];
												echo '</span>';
											}
										echo '</div>';
									echo '</div>';
								echo '</div>';

								foreach( $dispatcher->trigger( 'htmlBuildWidgetTop', array(&$this->item, &$this->params) ) as $inWidget )
								{
									echo '<div id="' . $inWidget['appKey'] . '_widget-top">';
									echo $inWidget['html'];
									echo '</div>';
								}
								foreach( $dispatcher->trigger( 'htmlBuildWidget', array(&$this->item, &$this->params) ) as $inWidget )
								{
									echo '<div id="' . $inWidget['appKey'] . '_widget">';
									echo $inWidget['html'];
									echo '</div>';
								}
								foreach( $dispatcher->trigger( 'htmlBuildWidgetBottom', array(&$this->item, &$this->params) ) as $inWidget )
								{
									echo '<div id="' . $inWidget['appKey'] . '"_widget-bottom>';
									echo $inWidget['html'];
									echo '</div>';
								}
							?>
							<!-- ---------- ---------- ---------- ---------- ---------- END APP.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							
							</div>
						</div>
					</div>
				</div>
				<!-- ---------- ---------- ---------- ---------- ---------- END BASE-DATA_TAB_CORE ---------- ---------- ---------- ---------- ---------- -->

				<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB.PLUGINS_CONTENT ---------- ---------- ---------- ---------- ---------- -->
				<?php
					foreach( $appContainer as $irmApp )
					{
						echo '<div id="' . $irmApp->appKey . '_tabbody" class="tab-pane">';
						echo $irmApp->tabBody;
						echo '</div>';
					}
				?>
				<!-- ---------- ---------- ---------- ---------- ---------- END TAB.PLUGINS_CONTENT ---------- ---------- ---------- ---------- ---------- -->

				<input type="hidden" name="contacts[id]" id="customer_cid" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />
				<input type="hidden" name="contacts[created_by]" value="<?php echo isset($this->item->created_by) ? $this->item->created_by : NFWUser::getId(); ?>" />

				<?php echo IRMHtmlBuilder::getClientId($this->item->client_id, $options = array('name' => 'contacts[client_id]')); ?>

				<input type="hidden" name="contacts[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" id="checkEditForm" name="checkEditForm" value="0" />

				<input type="hidden" name="irmapi[coreapp]" value="contacts" />
				<input type="hidden" name="irmapi[component]" value="com_xiveirm" />

				<?php echo JHtml::_('form.token'); ?>

				<div class="form-actions">
					<span id="form-buttons"<?php echo empty($this->item->id) ? '' : ' style="display:none;"'; ?>>
						<button id="loading-btn-save" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-complete-text="<?php echo isset($this->item->id) ? JText::_('COM_XIVEIRM_UPDATE_ITEM') : JText::_('COM_XIVEIRM_SAVE_ITEM'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="validate btn btn-info" type="submit"><i class="icon-ok"></i> <?php echo isset($this->item->id) ? JText::_('COM_XIVEIRM_UPDATE_ITEM') : JText::_('COM_XIVEIRM_SAVE_ITEM'); ?></button>
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

	<!-- ---------- ---------- ---------- ---------- ---------- BEGIN EXTERN FORMS ---------- ---------- ---------- ---------- ---------- -->
	<?php
		foreach($dispatcher->trigger( 'loadExternForms', array(&$this->item, &$this->params) ) as $externForm)
		{
			echo '<span id="' . $externForm['appKey'] . '_form">';
			echo $externForm['appForm'];
			echo '</span>';
		}
	?>
	<!-- ---------- ---------- ---------- ---------- ---------- END EXTERN FORMS ---------- ---------- ---------- ---------- ---------- -->

</div>

<script>
	function clearAddress() {
		jQuery('#inner-address-block input').val('');
	}

	// Check if a link is disabled and prevent default action!!
	jQuery('a').click(function(e) {
		linkvar = $(this).attr('disabled');
		if (linkvar === 'disabled') {
			e.preventDefault();
		}
	});

	jQuery(function(){
		$("#form-contact").submit(function(e){
			e.preventDefault();

			// Add the button loading style to prevent clicks on save
			$("#loading-btn-save").addClass("btn-warning").button("loading");

			$.post('index.php?option=com_xiveirm&task=api.ajaxsave', $("#form-contact").serialize(),
			function(data){
				console.log(data);
				if(data.status === true){
					// Throw out the message
					alertify.success('<i class="icon-check"></i> <?php echo isset($this->item->id) ? JText::_('Successfully updated') : JText::_('Successfully saved'); ?>');

					// Set the id for the customer to work with further
					$("#customer_cid").val(data.id);

					// Activate the edit button, activate the .link-control, disable all input fields, disable save form buttons and icon address helper
					$("#loading-btn-edit").fadeIn().button("complete").button("reset");
					$(".link-control").attr("disabled", false);
					$("#form-contact .input-control").attr("disabled", true).trigger("liszt:updated");
					$("#form-buttons, #clear-address-icon-helper").hide();

					// Reset the save button for edit again
					$("#loading-btn-save").removeClass("btn-warning").button("complete").button("reset");

					<?php if(!$this->item->catid) { ?>
						window.location.href = "<?php echo JRoute::_('index.php?task=contactform.edit&id='); ?>" + data.id;
					<?php } ?>

				} else {
					alertify.error('<i class="icon-warning-sign"></i> Error code: ' + data.code + '<br><br>error message: ' + data.message + '<br><br>If this error persists, please contact the support immediately with the given error!');
					// Remove the warning and add the error style button
					$("#loading-btn-save").removeClass("btn-warning").button("error").addClass("btn-danger");
				}
			}, "json");
		});
	});

	$("#loading-btn-edit").click(function() {
		var editButton = this;

		$(editButton).addClass("btn-warning").button("loading");

		jQuery.post('index.php?option=com_xiveirm&task=api.ajaxcheckout', {'irmapi[id]': $("#customer_cid").val(), 'irmapi[coreapp]': "contacts", <?php echo NFWSession::getToken(); ?>: 1},
			function(data){
				// console.log(data);
				if(data.status === true){
					alertify.warning = alertify.extend('warning');
					alertify.warning('<i class="icon-signout"></i> <?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_TITLE'); ?> <br> <?php echo JText::sprintf('COM_XIVEIRM_CONTACT_FORM_CHECKED_OUT_INFO_BODY', $full_name); ?>');

					// Hide the edit button
					$(editButton).fadeOut();

					// Set a checkeditform value to get the prevent leave message even if nothing has changed, but user is now in edit screen
					$("#checkEditForm").val("1");

					// Show the form buttons, disable .link-control
					$("#form-buttons, #clear-address-icon-helper").fadeIn();
					$(".link-control").attr("disabled", true);

					// Remove all disabled from fields with .input-control
					$(".input-control").attr("disabled", false).trigger("liszt:updated");
				} else {
					alertify.error('<i class="icon-warning-sign"></i> An error occured: <br> Error code: ' + data.code + '<br><br>error message: ' + data.message + '<br><br>If this error persists, please contact the support immediately with the given error!');

					$("#loading-btn-edit").removeClass("btn-warning").button("error").addClass("btn-danger");
				}
			},
		"json");
	});

	<?php if($this->item->id): ?>
		jQuery("#form-contact .input-control").attr("disabled", true);
	<?php else: ?>
		// We're in new contact, therefore disable the btn class objects. Should be removed in edit function.
		jQuery(".widget-box .btn").attr("disabled", true);
	<?php endif; ?>
</script>