<?php
/**
 * @version     5.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

// JHtml::_('behavior.keepalive');
// JHtml::_('behavior.formvalidation');

JHtml::_('stylesheet', 'nawala/nawala.import.css', false, true, false, false);

NFWHtmlJavascript::setToggle('extended', 'toggleExtend');
NFWHtmlJavascript::setTooltip('.xtooltip');
NFWHtmlJavascript::setPopover('.xpopover', array('html' => true) );
NFWHtmlJavascript::loadGritter();
NFWHtmlJavascript::loadAlertify();
IRMHtmlSelect2::init('.select2');

// NFWHtmlJavascript::setPreventFormLeaveIfChanged('#form-transcorder');
NFWHtmlJavascript::setPreventFormSubmitByKey();

NFWHtmlJavascript::loadMomentOnly();
NFWHtmlJavascript::detectChanges();

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_SITE);
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);

// Load the XiveIRMSystem Session Data
$session = IRMSessionHelper::getValues();

// If it's a new order and we have set the catid in the prevoius link!
if(!$this->item->catid) {
	$this->item->catid = JFactory::getApplication()->getUserState('com_xivetranscorder.edit.transcorder.catid');

	// Get the category acl
	$acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $this->item->catid);
} else {
	// We have no category id and use the components acl
	$acl = NFWAccessHelper::getActions('com_xiveirm');
}
$canState = $acl->get('core.edit.state');

// If it's a new order and we have set the contactid in the prevoius link!
if(!$this->item->contact_id) {
	$this->item->contact_id = JFactory::getApplication()->getUserState('com_xivetranscorder.edit.transcorder.contactid');
}
// POI STUFF (selector, url, params) based on contact id (The contact data are set as first option in select2) (passing by "&contact_id=...")
IRMHtmlSelect2::initAjaxPoi('.select2-poi', 'index.php?option=com_xivetranscorder&task=api.poilist&contact_id=' . $this->item->contact_id);

// Get the contact Object
$contactObject = IRMItemHelper::getContactObject($this->item->contact_id);

// used for Javascript processed messages
$full_name = IRMFormName::formatContactName($contactObject->contact);

// Import all TabApps based on the XiveIRM TabApp configs and the related catid!
IRMAppHelper::importPlugins('com_xivetranscorder', $this->item->catid);
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

// Get available transport device, transport type and order type options
$transportDeviceOptions = IRMFormList::getTransportDeviceOptions();
$transportTypeOptions = IRMFormList::getTransportTypeOptions();
$orderTypeOptions = IRMFormList::getOrderTypeOptions();

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

	<div class="row-fluid header smaller lighter green">
		<h1>
			<span class="span7">
				<i class="icon-road"></i>
				<?php if (!empty($this->item->id)): ?>
					<?php echo ' Order: #' . $this->item->order_id; ?>
				<?php else: ?>
					<?php echo ' ' . JText::_('COM_XIVETRANSCORDER_ORDER_FORM_ADD_NEW_ORDER'); ?>
				<?php endif; ?>
			</span>
			<span class="span5">
				<div class="btn-group pull-right inline">
					<?php if( ($this->item->id && $checkedOut['by'] != 'other') && ($acl->get('core.edit') || $acl->get('core.edit.own')) ): ?>
						<a id="loading-btn-edit" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="btn btn-warning btn-mini edit-form-button"><i class="icon-edit"></i> <?php echo JText::_('COM_XIVEIRM_EDIT_ITEM'); ?></a>
					<?php endif; ?>
					<?php if($checkedOut['by'] == 'other'): ?>
						<a class="btn btn-danger btn-mini" href="<?php echo JRoute::_('index.php?option=com_xivetranscorder'); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_CANCEL_ITEM'); ?></a>
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

	<form id="form-transcorder-core" class="form-validate" enctype="multipart/form-data">
		<div class="tabbable">
			<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB_BUTTONS ---------- ---------- ---------- ---------- ---------- -->
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_FORM_TAB_BASICDATA'); ?></a></li>
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
			<!-- ---------- ---------- ---------- ---------- ---------- END TAB_BUTTONS ---------- ---------- ---------- ---------- ---------- -->

			<div class="tab-content">
				<!-- ---------- ---------- ---------- ---------- ---------- BEGIN BASE-DATA_TAB_CORE ---------- ---------- ---------- ---------- ---------- -->
				<div id="base-data" class="tab-pane active">
					<div class="row-fluid">
						<div class="span6">
							<div class="control-group">
								<div class="controls controls-row">
									<div class="span6">
										<?php if( $this->item->catid ) { ?>
											<input type="hidden" name="transcorders[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NFWItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="transcorders[catid]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
											<option value=""></option>
											<?php
												$options = IRMFormList::getCategoryOptions('com_xivetranscorder');
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
	
							<div class="control-group">
								<div class="controls controls-row">
									<div class="span12">
										<?php if($this->item->contact_id) { ?>
										<div class="widget-box widget-box-tabapps light-border">
											<div class="widget-header">
												<h5 class="smaller">
													<?php
														if($contactObject->contact->gender == 'FEMALE') {
															echo '<i class="icon-female red"></i>';
														} else if($contactObject->contact->gender == 'MALE') {
															echo '<i class="icon-male blue"></i>';
														} else if($contactObject->contact->gender == 'COMPANY') {
															echo '<i class="icon-building green"></i>';
														} else {
															echo '<i class="icon-user black"></i>';
														}
														echo $contactObject->contact->catid_title ? ' ' . $contactObject->contact->catid_title : '';
													?>
												</h5>
												<div class="widget-toolbar">
													<label>
														<span class="btn-group">
															<?php echo $contactObject->contact->flagged ? '<i class="icon-flag orange xtooltip" title="Contact is flagged!"></i>' : ''; ?>
															<?php echo $contactObject->contact->checked_out ? '<i class="icon-lock red xtooltip" title="Contact is checked out!"></i>' : ''; ?>
															<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit&id=' . $this->item->contact_id); ?>" class="link-control btn btn-mini btn-light"><i class="icon-edit icon-only"></i></a>
														</span>
													</label>
												</div>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													<div class="row-fluid">
														<div class="span6">
															<address>
																<strong style="font-size:15px;"><?php echo $full_name; ?></strong><br>
																<?php echo $contactObject->contact->address_name ? $contactObject->contact->address_name . '<br>' : ''; ?>
																<?php echo $contactObject->contact->address_name_add ? $contactObject->contact->address_name_add . '<br>' : ''; ?>
																<?php echo $contactObject->contact->address_street ? $contactObject->contact->address_street . ' ' : ''; ?>	<?php echo $contactObject->contact->address_houseno ? $contactObject->contact->address_houseno . '<br>' : '<br>'; ?>
																<?php echo $contactObject->contact->address_zip ? $contactObject->contact->address_zip . ' ' : ''; ?><?php echo $contactObject->contact->address_city ? $contactObject->contact->address_city . '<br>' : '<br>'; ?>
																<?php echo $contactObject->contact->address_region ? $contactObject->contact->address_region . ', ' : ''; ?><?php echo $contactObject->contact->address_country ? $contactObject->contact->address_country . '<br>' : '<br>'; ?>
															</address>
														</div>
														<div class="span6">
															<?php
																if($contactObject->contact->customer_id && (int)$contactObject->contact->customer_id) {
																	echo '<i class="icon-barcode"></i> ' . $contactObject->contact->customer_id;
																} else if($contactObject->contact->customer_id && !(int)$contactObject->contact->customer_id) {
																	echo '<i class="icon-qrcode"></i> ' . $contactObject->contact->customer_id;
																} else {
																	echo '<i class="icon-code-fork"></i> ' . $contactObject->contact->id;
																}
															?><br>
															<br>
															<?php echo isset($contactObject->tabs->medicaldetails->insurance) ? 'Krankenkasse: ' . $contactObject->tabs->medicaldetails->insurance . '<br>' : '<span class="red">Krankenkasse: unbekannt</span>'; ?>
															<?php echo isset($contactObject->tabs->medicaldetails->insurance_no) ? 'Versicherungs-Nr.: ' . $contactObject->tabs->medicaldetails->insurance_no . '<br>' : ''; ?>
														</div>
													</div>
													<div class="row-fluid extended">
														<div class="span6">
															<?php echo $contactObject->contact->phone ? '<i class="icon-phone icon-shift-right"></i> ' . $contactObject->contact->phone . '<br>' : ''; ?>
															<?php echo $contactObject->contact->fax ? '<i class="icon-print icon-shift-right"></i> ' . $contactObject->contact->fax . '<br>' : ''; ?>
															<?php echo $contactObject->contact->mobile ? '<i class="icon-mobile-phone icon-shift-right"></i> ' . $contactObject->contact->mobile . '<br>' : ''; ?>
															<?php echo $contactObject->contact->email ? '<i class="icon-envelope icon-shift-right"></i> ' . $contactObject->contact->email . '<br>' : ''; ?>
														</div>
														<div class="span6">
															<?php echo $contactObject->contact->remarks ? '<blockquote><p>' . $contactObject->contact->remarks . '</p><small>Interne Bemerkungen</small></blockquote>' : ''; ?>
														</div>
													</div>
												</div>
											</div>
											<input type="hidden" name="transcorders[contact_id]" value="<?php echo $this->item->contact_id; ?>" />
										</div>
										<?php } else { ?>
										<select name="transcorders[contact_id]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_CONTACT'); ?>" required>
											<?php
												if(!$this->item->contact_id) {
													echo '<option value="0" selected>' . JText::_('COM_XIVETRANSCORDER_FORM_SELECT_CONTACT') . '</option>';
												}
	
												$options_contact_id = new stdClass; //IRMSystem::getListOptions('contacts', $xsession->client_id);
												foreach($options_contact_id->categories as $catid => $name) {
													echo '<optgroup label="' . $name . '">';
														foreach($options_contact_id->contacts as $contactgroupid => $contactgroup) {
															if($catid == $contactgroupid) {
																foreach($contactgroup as $contact) {
																	if($this->item->contact_id == $contact['id']) {
																		echo '<option value="' . $contact['id'] . '" selected>#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
																	} else {
																		echo '<option value="' . $contact['id'] . '">#' . $contact['customer_id'] . ' - ' . $contact['company'] . ' ( ' . $contact['last_name'] . ', ' . $contact['first_name'] . ' )</option>';
																	}
																}
																unset($options_contact_id->contacts[$contactgroupid]);
															}
														}
													echo '</optgroup>';
												}
												unset($options_contact_id->categories, $options_contact_id->contacts);
											?>
										</select>
										<?php } ?>
									</div>
								</div>
							</div>

							<?php if ( $canState || $this->item->state != 1 ): ?>
								<div class="control-group">
									<div class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_CURRENT_STATE'); ?></div>
									<?php
										if ( $this->item->state != 1 ) {
											if ( $this->item->state == -2 ) {
												// Trashed item
												echo '<span class="link-control btn btn-danger disabled" disabled="disabled"><i class="icon-trash"></i> ' . JText::_('COM_XIVETRANSCORDER_FORM_STATUS_TRASHED_TRANSPORT') . '</span>';
											} else if ( $this->item->state == 0 ) {
												// Inactive item
												echo '<span class="link-control btn btn-info disabled" disabled="disabled"><i class="icon-remove"></i> ' . JText::_('COM_XIVETRANSCORDER_FORM_STATUS_INACTIVE_TRANSPORT') . '</span>';
											} else if ( $this->item->state == 2 ) {
												// Archived item
												echo '<span class="link-control btn btn-info disabled" disabled="disabled"><i class="icon-folder-close-alt"></i> ' . JText::_('COM_XIVETRANSCORDER_FORM_STATUS_ARCHIVED_TRANSPORT') . '</span>';
											}
										} else {
											if($canState):
												echo '<a href="javascript:alert(\'Changing Contact: In sandbox not available at present\')" class="btn btn-info"><i class="icon-refresh"></i> ' . JText::_('COM_XIVETRANSCORDER_FORM_STATUS_CHANGE_CONTACT') . '</a>';
											else:
												echo '<span>' . JText::_('COM_XIVETRANSCORDER_FORM_STATUS_NO_STATE_ACCESS_TO_CHANGE_CONTACT') . '</span>';
											endif;
										}
									?>
								</div>
							<?php endif; ?>

							<div class="row-fluid extended margin-top center">
								<div data-toggle="buttons-radio" class="btn-group directions">
									<button class="device btn btn-small btn-light xtooltip <?php echo $device == 'drive' ? 'active' : ''; ?>" type="button" data-val="drive" title="Drive">
										<div class="mode-icon drive-icon"></div>
									</button>
									<button class="device btn btn-small btn-light xtooltip <?php echo $device == 'transit' ? 'active' : ''; ?>" type="button" data-val="transit" title="Transit">
										<div class="mode-icon transit-icon"></div>
									</button>
									<button class="device btn btn-small btn-light xtooltip <?php echo $device == 'walk' ? 'active' : ''; ?>" type="button" data-val="walk" title="Walk">
										<div class="mode-icon walk-icon"></div>
									</button>
									<button class="device btn btn-small btn-light xtooltip <?php echo $device == 'bicycle' ? 'active' : ''; ?>" type="button" data-val="bicycle" title="Bicycle">
										<div class="mode-icon bicycle-icon"></div>
									</button>
									<button class="device btn btn-small btn-light xtooltip <?php echo $device == 'airplane' ? 'active' : ''; ?>" type="button" data-val="airplane" title="Airplane">
										<div class="mode-icon airplane-icon"></div>
									</button>
								</div>
							</div>
							<input name="transcorders[distcalc_device]" id="distcalc-device" type="hidden" value="drive" />
	
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
	
						</div>
						<div class="span6">
							<div class="well">
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN APP.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							<?php
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
							<!-- ---------- ---------- ---------- ---------- ---------- END TAB.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							
							</div>
						</div>
					</div>

					<?php echo IRMHtmlBuilder::getClientId($this->item->client_id, $options = array('name' => 'transcorders[client_id]')); ?>

					<input type="hidden" name="irmapi[coreapp]" value="transcorders" />
					<input type="hidden" name="irmapi[component]" value="com_xivetranscorder" />

					<input type="hidden" id="checkEditForm" name="checkEditForm" value="0" />
					<?php echo JHtml::_('form.token'); ?>
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

			</div><!-- END .tab-content -->
		</div><!-- END .tabbable -->
	</form>

	<!-- ########## ########## ########## ########## ##########  BEGIN FIRST TRANSPORT  ########## ########## ########## ########## ########## -->
	<form id="form-transcorder-1" data-order="1">
		<div id="torder-1" class="clonedTransport">
			<div class="widget-box transparent">
				<div class="widget-header">
					<h4 class="lighter">
						<div id="transport-header" class="input-medium">Transport: 1</div>
					</h4>
					<div class="widget-toolbar no-border">
						<div class="btn-group">
							<?php
								// Check for geo coordinates
								if ( $this->item->f_address_lat && $this->item->f_address_lng && $this->item->t_address_lat && $this->item->t_address_lng ) {
									$geoClass = 'btn-success';
								} else if ( $this->item->f_address_lat || $this->item->f_address_lng || $this->item->t_address_lat || $this->item->t_address_lng ) {
									$geoClass = 'btn-warning';
								} else {
									$geoClass = 'btn-danger';
								}

								// Check for hash
								if ( $this->item->f_address_hash && $this->item->t_address_hash ) {
									$hashClass = 'btn-success';
								} else if ( $this->item->f_address_hash || $this->item->t_address_hash ) {
									$hashClass = 'btn-warning';
								} else {
									$hashClass = 'btn-danger';
								}
							?>
							<span class="geo-icon btn btn-mini <?php echo $geoClass; ?>">
								<i class="icon-globe icon-only"></i>
							</span>
							<span class="hash-icon btn btn-mini <?php echo $hashClass; ?>">
								<i class="icon-cny icon-only"></i>
							</span>
							<span class="btn btn-mini btn-inverse ordernumber">
								<i class="icon-ambulance"></i> <span><?php echo $this->item->order_id ? $this->item->order_id : 'N/A'; ?></span>
							</span>
							<span class="btn btn-mini btn-danger manurouter" <?php echo ($this->item->estimated_time && $this->item->estimated_distance) ? 'style="display: none;"' : ''; ?>>
								<i class="icon-compass icon-large icon-only"></i>
							</span>
							<span class="btn btn-mini btn-light duration" <?php echo !$this->item->estimated_time ? 'style="display: none;"' : ''; ?>>
								<i class="icon-time"></i> <span><?php echo $this->item->estimated_time ? IRMItemHelper::translateDuration($this->item->estimated_time) : 'N/A'; ?></span>
							</span>
							<span class="btn btn-mini btn-light distance" <?php echo !$this->item->estimated_distance ? 'style="display: none;"' : ''; ?>>
								<i class="icon-road"></i> <span><?php echo $this->item->estimated_distance ? IRMItemHelper::translateDistance($this->item->estimated_distance) : 'N/A'; ?></span>
							</span>
						</div>
					</div>
				</div>
				<div class="widget-body">
					<div class="widget-main padding-6 no-padding-left no-padding-right">
						<div class="row-fluid">
							<div class="span8">
								<div class="controls controls-row">
									<div class="span6">
										<div class="well">
											<div class="control-group address-block" data-direction="f">
												<label class="control-label contact-home"><i class="icon-map-marker icon-large green"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_FROM'); ?></label>
												<div class="controls poi-selects">
													<span class="span12">
														<input name="transcorders[f_poi_id]" class="select2-poi" id="f_poi_id-1" data-direction="f" data-order="1" data-placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_SELECT_POI'); ?>" />
													</span>
												</div>
												<div class="inner-address-block">
													<div class="controls">
														<input type="text" id="f_address_name-1" name="transcorders[f_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->f_address_name; ?>" />
													</div>
													<div class="controls">
														<input type="text" id="f_address_name_add-1" name="transcorders[f_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->f_address_name_add; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="f_address_street-1" name="transcorders[f_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->f_address_street; ?>" />
														<input type="text" id="f_address_houseno-1" name="transcorders[f_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->f_address_houseno; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="f_address_zip-1" name="transcorders[f_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->f_address_zip; ?>" />
														<input type="text" id="f_address_city-1" name="transcorders[f_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->f_address_city; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="f_address_region-1" name="transcorders[f_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->f_address_region; ?>" />
														<input type="text" id="f_address_country-1" name="transcorders[f_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->f_address_country; ?>" />
													</div>
													<div class="geo-coords">
														<input type="hidden" name="transcorders[f_address_lat]" id="f_address_lat-1" value="<?php echo $this->item->f_address_lat; ?>" />
														<input type="hidden" name="transcorders[f_address_lng]" id="f_address_lng-1" value="<?php echo $this->item->f_address_lng; ?>" />
													</div>
													<input type="hidden" name="transcorders[f_address_hash]" id="f_address_hash-1" value="<?php echo $this->item->f_address_hash; ?>" />
												</div>
											</div>
										</div>
									</div>
									<div class="span6">
										<div class="well">
											<div class="control-group address-block" data-direction="t">
												<label class="control-label contact-home"><i class="icon-map-marker icon-large red"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_TO'); ?></label>
												<div class="controls poi-selects">
													<span class="span12">
														<input name="transcorders[t_poi_id]" class="select2-poi" id="t_poi_id-1" data-direction="t" data-order="1" data-placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_SELECT_POI'); ?>" />
													</span>
												</div>
												<div class="inner-address-block">
													<div class="controls">
														<input type="text" id="t_address_name-1" name="transcorders[t_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->t_address_name; ?>" />
													</div>
													<div class="controls">
														<input type="text" id="t_address_name_add-1" name="transcorders[t_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->t_address_name_add; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="t_address_street-1" name="transcorders[t_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->t_address_street; ?>" />
														<input type="text" id="t_address_houseno-1" name="transcorders[t_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->t_address_houseno; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="t_address_zip-1" name="transcorders[t_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->t_address_zip; ?>" />
														<input type="text" id="t_address_city-1" name="transcorders[t_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->t_address_city; ?>" />
													</div>
													<div class="controls controls-row">
														<input type="text" id="t_address_region-1" name="transcorders[t_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->t_address_region; ?>" />
														<input type="text" id="t_address_country-1" name="transcorders[t_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->t_address_country; ?>" />
													</div>
													<div class="geo-coords">
														<input type="hidden" name="transcorders[t_address_lat]" id="t_address_lat-1" value="<?php echo $this->item->t_address_lat; ?>" />
														<input type="hidden" name="transcorders[t_address_lng]" id="t_address_lng-1" value="<?php echo $this->item->t_address_lng; ?>" />
													</div>
													<input type="hidden" name="transcorders[t_address_hash]" id="t_address_hash-1" value="<?php echo $this->item->t_address_hash; ?>" />
												</div>
											</div>
										</div>
									</div>
								</div>
							</div><!-- /.span8 -->
							<div class="span4">
								<div class="well">
									<div class="row-fluid">
										<span class="span6">
											<input name="transcorders[date]" type="date" class="input-control span12 center" style="font-size: 23px; height: 40px;" <?php echo $this->item->transport_timestamp ? 'value="' . date('Y-m-d', $this->item->transport_timestamp) . '"' : ''; ?> maxlength="10" required />
										</span>
										<span class="span6">
											<input name="transcorders[time]" type="time" class="input-control span12 center" style="font-size: 23px; height: 40px;" <?php echo $this->item->transport_timestamp ? 'value="' . date('H:i', $this->item->transport_timestamp) . '"' : ''; ?> maxlength="5" required />
										</span>
										<span class="datetimeconstruct center"></span>
									</div>
	
									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSPORT_DEVICE_AND_TYPE'); ?>
										</label>
										<div class="controls controls-row">
											<div class="span6">
												<select id="transport_device-1" name="transcorders[transport_device]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_DEVICE_PLEASE_SELECT'); ?>" <?php echo $this->item->transport_device ? 'value="' . $this->item->transport_device . '"' : ''; ?> required>
													<option></option>
													<?php
														foreach ( $transportDeviceOptions as $key => $value ) {
															if ( $this->item->transport_device == $key ) {
																echo '<option value="' . $key . '" selected>' . $value . '</option>';
															} else {
																echo '<option value="' . $key . '">' . $value . '</option>';
															}
														}
													?>
												</select>
											</div>
												<div class="span6">
												<select id="transport_type-1" name="transcorders[transport_type]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_TRANSPORT_TYPE_PLEASE_SELECT'); ?>" <?php echo $this->item->transport_type ? 'value="' . $this->item->transport_type . '"' : ''; ?> required>
													<option></option>
													<?php
														foreach ( $transportTypeOptions as $key => $value ) {
															if ( $this->item->transport_type == $key ) {
																echo '<option value="' . $key . '" selected>' . $value . '</option>';
															} else {
																echo '<option value="' . $key . '">' . $value . '</option>';
															}
														}
													?>
												</select>
											</div>
										</div>
									</div><!-- #end control group -->

									<div class="control-group">
										<label class="control-label">
											<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ORDER_TYPE'); ?>
										</label>
										<div class="controls controls-row">
											<div class="span12">
												<select id="order_type-1" name="transcorders[order_type]" class="select2 input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_OPTIONLIST_ORDER_TYPE_PLEASE_SELECT'); ?>" required>
													<option></option>
													<?php
														foreach ( $orderTypeOptions as $key => $value ) {
															if ( $this->item->order_type == $key ) {
																echo '<option value="' . $key . '" selected>' . $value . '</option>';
															} else {
																echo '<option value="' . $key . '">' . $value . '</option>';
															}
														}
													?>
												</select>
											</div>
										</div>
									</div><!-- #end control group -->

									<?php if(!$this->item->id) { ?>
										<div class="control-group actions">
											<div class="controls controls-row center">
												<div class="btn-group">
													<a class="btn btn-success" onClick="trans_add(1)">
														<i class="icon-plus icon-only"></i> <?php echo JText::_('COM_XIVETRANSCORDER_ADD_EMPTY_ITEM'); ?>
													</a>
													<a class="btn btn-primary" onClick="trans_copy(1)">
														<i class="icon-copy icon-only"></i> <?php echo JText::_('COM_XIVETRANSCORDER_COPY_THIS_ITEM'); ?>
													</a>
												</div>
											</div>
										</div><!-- #end control group -->
									<?php } ?>
										<div class="control-group loader" style="display: none;">
											<div class="controls controls-row margin-top">
												<div class="alert center">
													<img src="/media/nawala/images/loader.gif">
												</div>
											</div>
										</div>
								</div><!-- /.well -->
							</div><!-- /.span4 -->
						</div><!-- /.row-fluid -->
					</div><!-- /.widget-main padding-6 no-padding-left no-padding-right -->
				</div><!-- /.widget-body -->
			</div><!-- /.widget-box .transparent -->

			<input type="hidden" name="transcorders[id]" id="order_cid-1" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />
			<input type="hidden" name="transcorders[order_id]" id="order_id-1" value="<?php echo $this->item->order_id; ?>" />
			<input type="hidden" name="transcorders[transport_timestamp]" id="transport_timestamp-1" value="<?php echo $this->item->transport_timestamp; ?>" required />
			<input type="hidden" name="transcorders[estimated_time]" id="estimated_time-1" value="<?php echo $this->item->estimated_time; ?>" />
			<input type="hidden" name="transcorders[estimated_distance]" id="estimated_distance-1" value="<?php echo $this->item->estimated_distance; ?>" />
			<input type="hidden" name="transcorders[state]" value="1" />

		</div><!-- /#torder-1 -->
	</form><!-- /.torder-1 -->
	<!-- ########## ########## ########## ########## ##########   END FIRST TRANSPORT   ########## ########## ########## ########## ########## -->

	<!-- ########## ########## ########## ########## ##########  BEGIN COPY TRANSPORTS  ########## ########## ########## ########## ########## -->
	<div id="tcopycontainer"><hr></div>
	<!-- ########## ########## ########## ########## ##########  END COPY TRANSPORTS  ########## ########## ########## ########## ########## -->

	<div class="form-actions">
		<span id="form-buttons"<?php echo empty($this->item->id) ? '' : ' style="display:none;"'; ?>>
			<div class="btn-group">
				<button id="coreCheckSave" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-complete-text="<?php echo isset($this->item->id) ? JText::_('COM_XIVEIRM_UPDATE_ITEM') : JText::_('COM_XIVEIRM_SAVE_ITEM'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="validate btn btn-info" type="submit"><i class="icon-ok"></i> <?php echo isset($this->item->id) ? JText::_('COM_XIVEIRM_CHECKUPDATE_ITEM') : JText::_('COM_XIVETRANSCORDER_CHECKSAVE_ITEM'); ?></button>
				<button class="btn btn-info dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu">
					<li class="">
						<a id="coreSave" href="#"><?php echo JText::_('COM_XIVETRANSCORDER_SAVE_WITH_BASIC_CHECK_ITEM'); ?></a>
					</li>
					<li class="">
						<a id="coreSaveBackList" href=""><?php echo JText::_('COM_XIVETRANSCORDER_SAVE_WITHOUT_CHECK_ITEM'); ?></a>
					</li>
					<li class="">
						<a id="coreSaveCopy" href="">Save as Copy</a>
					</li>
				</ul>
			</div>
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
	<div class="clearfix"></div>
</div>




<script>
	<?php require_once(__DIR__ . '/tocajshelper.php'); ?>

	<?php if($this->item->id): ?>
		// Render the route for existing items
		renderRoute( 1 );

		// Disable all editable fields
		var initUsher = getUsher(1);
		jQuery(document).ready(function() {
			$(initUsher.order + ' select').select2('readonly', true);
		});
		jQuery(initUsher.order + ' .input-control').attr('readonly', true);
		jQuery(initUsher.order + ' .poi-selects').hide();

		// Function to toggle radio buttons for directions block
		jQuery("div.btn-group button.device").click(function() {
			var distcalc_value = $(this).attr("data-val");
			$("#distcalc-device").val(distcalc_value);
		});
	<?php else: ?>
		jQuery(".widget-box-tabapps .btn").attr("disabled", true);
	<?php endif; ?>

	// Set the contact address in the appropriate destination fields
	jQuery('.contact-home').click(function() {
		// Holds the contact address
		var contactHome = {};
		    contactHome.address_name     = '<?php echo $contactObject->contact->address_name; ?>';
		    contactHome.address_name_add = '<?php echo $contactObject->contact->address_name_add; ?>';
		    contactHome.address_street   = '<?php echo $contactObject->contact->address_street; ?>';
		    contactHome.address_houseno  = '<?php echo $contactObject->contact->address_houseno; ?>';
		    contactHome.address_zip      = '<?php echo $contactObject->contact->address_zip; ?>';
		    contactHome.address_city     = '<?php echo $contactObject->contact->address_city; ?>';
		    contactHome.address_region   = '<?php echo $contactObject->contact->address_region; ?>';
		    contactHome.address_country  = '<?php echo $contactObject->contact->address_country; ?>';
		    contactHome.address_lat      = '<?php echo $contactObject->contact->address_lat; ?>';
		    contactHome.address_lng      = '<?php echo $contactObject->contact->address_lng; ?>';
		    contactHome.address_hash     = '<?php echo $contactObject->contact->address_hash; ?>';

		var order = $(this).parents('form').attr('data-order');
		var direction = $(this).parents('.address-block').attr('data-direction');
		var usher = getUsher ( order, direction);

		setNewAddress( order, direction, contactHome );

		$(usher.deep + ' .input-control').attr('readonly', true);
	});


	// Function to save the transports with any available checks
	// MAY WE COULD ADD ONE MORE VAR TO PREVENT GEOCHECKS EVEN GEO IS ENABLED! IN THE autoCheckFUNCTION WE COULD ADD joinGeoCheck and in formatCheck we could check if function exist and if joinGeo is enabled
	jQuery("#coreCheckSave").click(function(e){
		e.preventDefault();
		autoCheck();
	});

	// Function to save the transports without any checks
	jQuery("#coreSave").click(function(e){
		e.preventDefault();
		saveLoop();
	});
</script>