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
// JHtml::_('behavior.tooltip');
// JHtml::_('behavior.formvalidation');

JHtml::_('stylesheet', 'nawala/nawala.import.css', false, true, false, false);

// Import HTML and Helper Classes
// nimport('NHtml.JavaScript');
// nimport('NItem.Helper', false);
// nimport('NUser.Access', false);
// nimport('NPlugins.Sha256');

NFWHtmlJavascript::setToggle('extended', 'toggleExtend');
NFWHtmlJavascript::setTooltip('.xtooltip');
NFWHtmlJavascript::setPopover('.xpopover');
NFWHtmlJavascript::setPreventFormSubmitByKey();
NFWHtmlJavascript::loadGritter();
NFWHtmlJavascript::loadAlertify();
// NFWHtmlJavascript::setPreventFormLeaveIfChanged('#form-transcorder');
NFWHtmlJavascript::setChosen('.chzn-select-poi-address', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%'));
NFWHtmlJavascript::loadMomentOnly();
// NFWPluginsSha256::loadSHA256('jquery.hash.sha256');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_SITE);
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Get Permissions
// $permissions = NUserAccess::getPermissions('com_xiveirm', false, false, 'xiveirm_transcorders.' . $this->item->id);

// If it's a new order and we have set the catid in the prevoius link!
if(!$this->item->catid) {
	$this->item->catid = JFactory::getApplication()->getUserState('com_xivetranscorder.edit.transcorder.catid');
}

// If it's a new order and we have set the contactid in the prevoius link!
if(!$this->item->contact_id) {
	$this->item->contact_id = JFactory::getApplication()->getUserState('com_xivetranscorder.edit.transcorder.contactid');
}

// Import all TabApps based on the XiveIRM TabApp configs and the related catid!
// IRMSystem::getPlugins($this->item->catid, 'transcorders');
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

// Get and build the name if id, used for javascript processed messages and title header
if($this->item->contact_id) {
	$full_name = NFWItemHelper::getNameById($this->item->contact_id, 'xiveirm_contacts', true);
} else {
	$full_name = 'Full Name PLACEHOLDER';
}

// Get the contact Object
if($this->item->contact_id) {
	$contactObject = IRMItemHelper::getContactObject($this->item->contact_id);
}

$menu = JSite::getMenu();
// echo '<pre>';
// print_r($menu->getActive());
// echo '</pre>';

// Get the POI Category from com_xivetranscorder config options and build the options list
$poiCat = IRMComponentHelper::getConfigValue('com_xivetranscorder', 'poi_category');
$poiOptions = IRMFormList::getContactOptions( $poiCat );

// Get transport device, transport type and order type options
$transportDeviceOptions = IRMFormList::getTransportDeviceOptions();
$transportTypeOptions = IRMFormList::getTransportTypeOptions();
$orderTypeOptions = IRMFormList::getOrderTypeOptions();

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
					<?php if( ($this->item->id && $checkedOut['by'] != 'other') && ($permissions->get('core.edit') || $permissions->get('core.edit.own')) ): ?>
						<a onClick="enableEdit()" id="loading-btn-edit" data-loading-text="<?php echo JText::_('COM_XIVEIRM_API_PLEASE_WAIT_BUTTON'); ?>" data-error-text="<?php echo JText::_('COM_XIVEIRM_API_ERROR_TRY_AGAIN_BUTTON'); ?>" class="btn btn-warning btn-mini edit-form-button"><i class="icon-edit"></i> <?php echo JText::_('COM_XIVEIRM_EDIT_ITEM'); ?></a>
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

	<form id="form-transcorder" class="form-validate"  enctype="multipart/form-data">

		<!-- ---------- ---------- ---------- ---------- ---------- BEGIN MASTER_TAP_PANE_PLUGINSTYLED ---------- ---------- ---------- ---------- ---------- -->
		<div class="tabbable">
			<ul class="nav nav-tabs" id="myTab">
				<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_FORM_TAB_BASICDATA'); ?></a></li>
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
					<div class="row-fluid form-horizontal">
						<div class="span7">
							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ORDER_CATEGORY'); ?></label>
								<div class="controls controls-row">
									<?php NFWHtmlJavascript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span6">
										<?php if($this->item->catid && !$this->item->id) { ?>
											<input type="hidden" name="transcorders[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NFWItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="transcorders[catid]" class="chzn-select-category input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
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
								<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_CONTACT'); ?></label>
								<div class="controls controls-row">
									<?php NFWHtmlJavascript::setChosen('.chzn-select-parent', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span12">
										<?php if($this->item->contact_id) { ?>
										<div class="widget-box">
											<div class="widget-header">
												<h5>
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
													<?php echo $contactObject->contact->flagged ? '<i class="icon-flag orange xtooltip" title="Contact is flagged!"></i>' : ''; ?>
													<?php echo $contactObject->contact->checked_out ? '<i class="icon-lock red xtooltip" title="Contact is checked out!"></i>' : ''; ?>
													<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit&id=' . $this->item->contact_id); ?>"><i class="icon-edit"></i></a>
													<a><i class="icon-refresh"></i></a>
													<a><i class="icon-chevron-up"></i></a>
													<a><i class="icon-remove"></i></a>
												</div>
											</div>
											<div class="widget-body">
												<div class="widget-main">
													<div class="row-fluid">
														<div class="span6">
															<address>
																<strong><?php echo $full_name; ?></strong><br>
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
										<select name="transcorders[contact_id]" class="chzn-select-parent input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_CONTACT'); ?>" required>
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

							<div class="control-group extended">
								<?php $canState = false; ?>
								<?php $canState = $canState = JFactory::getUser()->authorise('core.edit.state','com_xivetranscorder'); ?>
								<?php if(!$canState): ?>
								<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
									<?php
										$state_string = 'Unpublish';
										$state_value = 0;
										if($this->item->state == 1):
											$state_string = 'Publish';
											$state_value = 1;
										endif;
									?>
									<div class="controls"><?php echo $state_string; ?></div>
									<input type="hidden" name="transcorders[state]" value="<?php echo $state_value; ?>" />
								<?php else: ?>
									<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
									<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
								<?php endif; ?>
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

						</div>
						<div class="span5">
							<div class="well">
							
							<!-- ---------- ---------- ---------- ---------- ---------- BEGIN TAB.PLUGIN_MAIN-WIDGETS ---------- ---------- ---------- ---------- ---------- -->
							<?php
								echo '<style>';
									echo '.widget-box .btn-app.btn-mini span { font-size: 11px; }';
								echo '</style>';
								echo '<div class="widget-box widget-box-tabapps light-border" style="margin-top: -10px;">';
									echo '<div class="widget-header red">';
										echo '<h5 class="smaller">Actiontoolbar</h5>';
									echo '</div>';
									echo '<div class="widget-body">';
										echo '<div class="widget-main padding-5">';
										?>

										<?php
											if(!$device = $this->item->distcalc_device) {
												$device = 'drive';
											}
										?>
										<?php if($this->item->id) { ?>
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
												<hr>
											</div>
										<?php } ?>
										<input name="transcorders[distcalc_device]" id="distcalc-device" type="hidden" value="drive" />

										<?php
											echo '<center>';
												if(!empty($this->item->address_street) && !empty($this->item->address_houseno) && !empty($this->item->address_zip) && !empty($this->item->address_city) && !empty($this->item->address_country)) {
													echo '<a class="xpopover link-control" href="http://google.com/maps/preview#!q=' . $this->item->address_street . '+' . $this->item->address_houseno . '+' . $this->item->address_zip . '+' . $this->item->address_city . '" target="_blank" data-placement="bottom" data-content="New Google Maps" title="Google Maps 2.0"><img src="http://www.zdnet.de/wp-content/uploads/2012/11/googlemaps-icon.png" style="height: 63px; margin-right: 5px;"></a>';
												}
												echo '<a class="btn btn-app btn-mini btn-info link-control"><i class="icon-eye-open"></i> <span>StreetView</span></a>';
												echo '<a class="btn btn-app btn-mini btn-light link-control"><i class="icon-print"></i> <span>Print</span></a>';
												echo '<a class="btn btn-app btn-mini btn-purple link-control"><i class="icon-cloud-upload"></i> <span>DocUpload</span></a>';
												echo '<a class="btn btn-app btn-mini btn-pink link-control"><i class="icon-share-alt"></i> <span>ShareIt</span></a>';

												foreach($dispatcher->trigger( 'loadActionButton', array(&$this->item) ) as $inBaseWidget)
												{
													echo '<span id="' . $inBaseWidget['tab_key'] . '_button">';
													echo $inBaseWidget['tabContent'];
													echo '</span>';
												}

											echo '</center>';
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

				<!-- ########## ########## ########## ########## ##########  BEGIN FIRST TRANSPORT  ########## ########## ########## ########## ########## -->

				<div id="torder-1" class="clonedTransport">
					<div class="widget-box transparent">
						<div class="widget-header">
							<h4 class="lighter">
								<div id="transport-header" class="input-medium">Transport: 1</div>
							</h4>
							<div class="widget-toolbar no-border">
								<div class="btn-group">
									<a class="btn btn-mini btn-inverse xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ORDER_ID'); ?>"
										data-placement="top">
										<span class="<?php echo $this->item->order_id ? 'green' : 'red'; ?>">#</span> <?php echo $this->item->order_id ? $this->item->order_id : 'N/A'; ?>
									</a>
									<a class="btn btn-mini btn-light xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_TIME'); ?>"
										data-placement="top">
										<i class="icon-time <?php echo $this->item->estimated_distance ? 'green' : 'red'; ?>"></i> <?php echo $this->item->estimated_distance ? '~' . $this->item->estimated_time : 'N/A'; ?>
									</a>
									<a class="btn btn-mini btn-light xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_DISTANCE'); ?>"
										data-placement="top">
										<i class="icon-road <?php echo $this->item->estimated_distance ? 'green' : 'red'; ?>"></i> <?php echo $this->item->estimated_distance ? '~' . $this->item->estimated_distance . ' KM' : 'N/A'; ?>
									</a>
								</div>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main padding-6 no-padding-left no-padding-right">
								<div class="row-fluid">
									<?php NFWHtmlJavascript::setChosen('.chzn-select-poi-address', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span8">
										<div class="controls controls-row">
											<div class="span6">
												<div class="well">
													<div class="control-group">
														<label class="control-label"><i class="icon-chevron-sign-up"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_FROM'); ?></label>
														<div class="controls">
															<span class="span12">
																<select name="transcorders[multiform][1][f_poi_id]" class="chzn-select-poi-address" id="f_poi_id-1" data-placeholder="<?php echo JText::_('COM_XIVEIRM_FORM_SELECT_POI'); ?>">
																	<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_CONTACTLIST_PLEASE_SELECT'); ?></option>
																	<?php
																		foreach ( $poiOptions as $key => $value ) {
																			echo '<option value="' . $key . '">' . $value . '</option>';
																		}
																	?>
																</select>
															</span>
														</div>
														<div id="f_address_block-1">
															<div class="controls">
																<input type="text" id="f_address_name-1" name="transcorders[multiform][1][f_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->f_address_name; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls">
																<input type="text" id="f_address_name_add-1" name="transcorders[multiform][1][f_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->f_address_name_add; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="f_address_street-1" name="transcorders[multiform][1][f_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->f_address_street; ?>" onBlur="reCalc(1)" />
																<input type="text" id="f_address_houseno-1" name="transcorders[multiform][1][f_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->f_address_houseno; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="f_address_zip-1" name="transcorders[multiform][1][f_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->f_address_zip; ?>" onBlur="reCalc(1)" />
																<input type="text" id="f_address_city-1" name="transcorders[multiform][1][f_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->f_address_city; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="f_address_region-1" name="transcorders[multiform][1][f_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->f_address_region; ?>" onBlur="reCalc(1)" />
																<input type="text" id="f_address_country-1" name="transcorders[multiform][1][f_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->f_address_country; ?>" onBlur="reCalc(1)" />
															</div>
														</div>
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="well">
													<div class="control-group">
														<label class="control-label"><i class="icon-chevron-sign-down"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_TO'); ?></label>
														<div class="controls">
															<span class="span12">
																<select name="transcorders[multiform][1][t_poi_id]" class="chzn-select-poi-address" id="t_poi_id-1" data-placeholder="<?php echo JText::_('COM_XIVEIRM_FORM_SELECT_POI'); ?>">
																	<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_CONTACTLIST_PLEASE_SELECT'); ?></option>
																	<?php
																		foreach ( $poiOptions as $key => $value ) {
																			echo '<option value="' . $key . '">' . $value . '</option>';
																		}
																	?>
																</select>
															</span>
														</div>
														<div id="f_address_block-1">
															<div class="controls">
																<input type="text" id="t_address_name-1" name="transcorders[multiform][1][t_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->t_address_name; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls">
																<input type="text" id="t_address_name_add-1" name="transcorders[multiform][1][t_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->t_address_name_add; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="t_address_street-1" name="transcorders[multiform][1][t_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->t_address_street; ?>" onBlur="reCalc(1)" />
																<input type="text" id="t_address_houseno-1" name="transcorders[multiform][1][t_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->t_address_houseno; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="t_address_zip-1" name="transcorders[multiform][1][t_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->t_address_zip; ?>" onBlur="reCalc(1)" />
																<input type="text" id="t_address_city-1" name="transcorders[multiform][1][t_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->t_address_city; ?>" onBlur="reCalc(1)" />
															</div>
															<div class="controls controls-row">
																<input type="text" id="t_address_region-1" name="transcorders[multiform][1][t_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->t_address_region; ?>" onBlur="reCalc(1)" />
																<input type="text" id="t_address_country-1" name="transcorders[multiform][1][t_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->t_address_country; ?>" onBlur="reCalc(1)" />
															</div>
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
													<input id="timepicker-1" type="time" class="span12 center" onKeyUp="setTimestamp(1,0)" onClick="setTimestamp(1,0)" onBlur="setTimestamp(1,0)" onChange="setTimestamp(1,0)" style="font-size: 23px; height: 40px;" <?php echo $this->item->transport_timestamp ? 'value="' . date('H:i', $this->item->transport_timestamp . '"') : ''; ?>" maxlength="5" required />
												</span>
												<span class="span6">
													<input id="datepicker-1" type="date" class="span12 center" onKeyUp="setTimestamp(1,0)" onClick="setTimestamp(1,0)" onBlur="setTimestamp(1,0)" onChange="setTimestamp(1,0)" style="font-size: 23px; height: 40px;" <?php echo $this->item->transport_timestamp ? 'value="' . date('Y-m-d', $this->item->transport_timestamp) . '"' : ''; ?> maxlength="10" required />
												</span>
												<span id="transport-date-time-1" class="span12 center"></span>
											</div>

											<?php NFWHtmlJavascript::setChosen('.chzn-select-trans', false, array('width' => '100%', 'disable_search' => true)); ?>
											<div class="control-group">
												<label class="control-label">
													<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSPORT_DEVICE_AND_TYPE'); ?>
												</label>
												<div class="controls controls-row">
													<div class="span6">
														<select id="transport_device-1" name="transcorders[multiform][1][transport_device]" class="chzn-select-trans input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
															<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_OPTIONLIST_TRANSPORT_DEVICE_PLEASE_SELECT'); ?></option>
															<?php
																foreach ( $transportDeviceOptions as $key => $value ) {
																	echo '<option value="' . $key . '">' . $value . '</option>';
																}
															?>
														</select>
													</div>
														<div class="span6">
														<select id="transport_type-1" name="transcorders[multiform][1][transport_type]" class="chzn-select-trans input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
															<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_OPTIONLIST_TRANSPORT_TYPE_PLEASE_SELECT'); ?></option>
															<?php
																foreach ( $transportTypeOptions as $key => $value ) {
																	echo '<option value="' . $key . '">' . $value . '</option>';
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
													<?php NFWHtmlJavascript::setChosen('.chzn-select-ordertype', false, array('width' => '100%', 'disable_search' => true)); ?>
													<div class="span12">
														<select id="order_type-1" name="transcorders[multiform][1][order_type]" class="chzn-select-ordertype input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
															<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_OPTIONLIST_ORDER_TYPE_PLEASE_SELECT'); ?></option>
															<?php
																foreach ( $orderTypeOptions as $key => $value ) {
																	echo '<option value="' . $key . '">' . $value . '</option>';
																}
															?>
														</select>
													</div>
												</div>
											</div><!-- #end control group -->

											<?php if(!$this->item->id) { ?>
											<div class="control-group">
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
										</div><!-- /.well -->
									</div><!-- /.span4 -->
								</div><!-- /.row-fluid -->
							</div><!-- /.widget-main padding-6 no-padding-left no-padding-right -->
						</div><!-- /.widget-body -->
					</div><!-- /.widget-box .transparent -->

					<input type="text" name="transcorders[multiform][1][order_id]" value="<?php echo $this->item->order_id; ?>" />
					<input type="text" name="transcorders[multiform][1][estimated_time]" id="estimated_time-1" value="<?php echo $this->item->estimated_time; ?>" />
					<input type="text" name="transcorders[multiform][1][estimated_distance]" id="estimated_distance-1" value="<?php echo $this->item->estimated_distance; ?>" />
					<input type="text" name="transcorders[multiform][1][f_address_lat]" id="f_address_lat-1" value="<?php echo $this->item->t_address_lat; ?>" />
					<input type="text" name="transcorders[multiform][1][f_address_lng]" id="f_address_lng-1" value="<?php echo $this->item->t_address_lng; ?>" />
					<input type="text" name="transcorders[multiform][1][f_address_hash]" id="f_address_hash-1" value="<?php echo $this->item->t_address_hash; ?>" />
					<input type="text" name="transcorders[multiform][1][t_address_lat]" id="t_address_lat-1" value="<?php echo $this->item->t_address_lat; ?>" />
					<input type="text" name="transcorders[multiform][1][t_address_lng]" id="t_address_lng-1" value="<?php echo $this->item->t_address_lng; ?>" />
					<input type="text" name="transcorders[multiform][1][t_address_hash]" id="t_address_hash-1" value="<?php echo $this->item->t_address_hash; ?>" />
					<input type="text" name="transcorders[multiform][1][transport_timestamp]" id="transport_timestamp-1" value="<?php echo $this->item->transport_timestamp; ?>" required />

				</div><!-- /.torder-1 -->
				<!-- ########## ########## ########## ########## ##########   END FIRST TRANSPORT   ########## ########## ########## ########## ########## -->



				<div id="tcopycontainer"><hr></div>



				<input type="hidden" name="transcorders[id]" id="order_cid" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />

				<?php echo IRMHtmlBuilder::getClientId($this->item->client_id, $options = array('name' => 'transcorders[client_id]')); ?>

				<input type="hidden" name="irmapi[coreapp]" value="transcorders" />
				<input type="hidden" name="irmapi[component]" value="com_xivetranscorder" />

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
	<form id="form-transcorder-cica">
		<input type="hidden" name="irmapi[id]" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />
		<input type="hidden" name="irmapi[coreapp]" value="transcorders" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>







<script>
<?php
	// PHP OUT COMMENTS TO PREVENT SHOWING INFOS IN SOURCE CODE WHILE IN ALPHA/BETA
	/**
	 * Returns from API in json format
	 * example {"apiReturnCode":"SAVED","apiReturnRowId":"173","apiReturnMessage":"Successfully saved"}
	 * 
	 * apiReturnCode could be: SAVED, UPDATED or an Error Number ie. 666
	 * apiReturnMessage: returns a informal message, should be used for debugging and not in production use. returns the database or php errors
	 **/
?>
	jQuery(function(){
		$("#form-transcorder").submit(function(e){
			e.preventDefault();

			$("#loading-btn-save").addClass("btn-warning");
			$("#loading-btn-save").button("loading");

			$.post('index.php?option=com_xiveirm&task=api.ajaxsave', $("#form-transcorder").serialize(),
			function(data){
				if(data.apiReturnCode === 'SAVED'){
					$.gritter.add({
						title: 'Successfully saved',
						text: 'You have successfully saved all items for this order',
						icon: 'icon-check',
						class_name: 'alert-success'
					});
					$("#order_cid").val(data.apiReturnId);

					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("complete");
					$("#loading-btn-save").button("reset");

					<?php if(!$this->item->catid) { ?>
						var cId = $("#order_cid").val();
						window.location.href = "<?php echo JRoute::_('index.php?task=transcorderform.edit&id='); ?>" + cId;
					<?php } ?>

				} else if(data.apiReturnCode === 'UPDATED') {
					$.gritter.add({
						title: 'Successfully updated',
						text: 'You have successfully saved all items for this order',
						icon: 'icon-globe',
						class_name: 'alert-info'
					});
					$("#loading-btn-save").removeClass("btn-warning");
					$("#loading-btn-save").button("complete");
					$("#loading-btn-save").button("reset");

					$("#loading-btn-edit").removeClass("hidden");
					$("#loading-btn-edit").button("complete");
					$("#loading-btn-edit").button("reset");

					$("#form-transcorder .input-control").attr("disabled", true).trigger("liszt:updated");
					$("#form-buttons").addClass("hidden");
					$(".widget-box-tabapps .btn").attr("disabled", false);

				} else if(data.apiReturnCode === 'NOTICE') {
					$.gritter.add({
						title: 'Successfully updated',
						text: 'You have successfully saved all core items for this order.<br><br>' + data.apiReturnMessage,
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

					$("#form-transcorder .input-control").attr("disabled", true);
					$("#form-buttons").addClass("hidden");
					$(".widget-box-tabapps .btn").attr("disabled", false);

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
	jQuery("#form-transcorder .input-control").attr("disabled", true);

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

	// Function to toggle radio buttons for directions block
	jQuery("div.btn-group button.device").click(function() {
		var distcalc_value = $(this).attr("data-val");
		$("#distcalc-device").val(distcalc_value);
	});

	function enableEdit() {
		var inp = $('.input-control').get(0);

		$("#loading-btn-edit").addClass("btn-warning");
		$("#loading-btn-edit").button("loading");

		jQuery.post('index.php?option=com_xiveirm&task=api.ajaxcheckout', $("#form-transcorder-cica").serialize(),
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
						$("#form-transcorder .input-control").attr("disabled", false).trigger("liszt:updated");

						$("#loading-btn-edit").addClass("hidden");
						$("#form-buttons").removeClass("hidden");
						$(".widget-box-tabapps .link-control").attr("disabled", true);

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
		jQuery(".widget-box-tabapps .btn").attr("disabled", true);
	<?php endif; ?>
</script>





<?php
// ########################################### T E S T A R E A ######################################################################

	// TRANSPORT COPY SCRIPT ************************************************************************
//	jQuery(document).ready(function() {
//		var regex = /^(.*)(\d)+$/i;
//		var cloneIndex = $(".clonedTransport").length;

//		$(".trans-copy_XXXXX_ALT").click(function() {
//			$(this).parents(".clonedTransport").clone()
//				.appendTo("#tcopycontainer")
//				.attr("id", "torder-" + cloneIndex)
//				.find("*").each(function() {
//					var id = this.id || "";
//					var match = id.match(regex) || [];
//					if (match.length == 3) {
//						this.id = match[1] + (cloneIndex);
//					}
//					$("#transport-header").html("Transport: " + cloneIndex);
//				});
//			cloneIndex++;
//
//			console.log(cloneIndex);
//
//		});
//
//		$(".trans-remove_XXXXX_ALT").click(function() {
//			confirm('wirklich löschen?');
//			$(this).parents(".clonedTransport").remove();
//		});
//	});

// ----------------------------------------

//		var torderId = 2;

//		var regex = /^(.*)(\d)+$/i;
//		var cloneIndex = $(".clonedTransport").length + 1;
//
//		$(".trans-copy").click(function() {
//			var divContainer = buildContainer( cloneIndex );
//			$(divContainer).appendTo("#tcopycontainer");
//
//			cloneIndex++;
//
//			console.log(cloneIndex);
//		});

// ########################################### T E S T A R E A ######################################################################
?>







<script>
// jQuery(document).ready(function() {
		var regex = /^(.*)(\d)+$/i;
		var cloneIndex = $(this).length + 1;

		/*
		 * Method to add a new container with no prefilled values
		 *
		 */
		function trans_add() {
			divContainer = buildContainer( cloneIndex );
			jQuery(divContainer).appendTo("#tcopycontainer");

			torderIdHelper = '#torder-' + cloneIndex;
			jQuery(torderIdHelper).fadeIn('slow');

			torderIdHelper = '.inverse-extended-' + cloneIndex;
			jQuery(torderIdHelper).hide();

			// Print out he Message what we've done
			alertify.success('<i class="icon-plus"></i> Created new <strong>Transport ' + cloneIndex + '</strong>');

			// Remove the edit because we add an empty order
			var editButtonHelper = '#toggleEdit-' + cloneIndex;
			jQuery(editButtonHelper).hide();

			// Set chosen on the new select list element
			jQuery('#torder-' + cloneIndex + ' select').chosen({allow_single_deselect: true, disable_search_threshold: 10, no_results_text: "Oops, nothing found", width: "100%"});

			// Count index for next action
			cloneIndex++;
		}

		/*
		 * Method to copy the selected container with all the prefilled values
		 *
		 */
		function trans_copy( torderId ) {
			divContainer = buildContainer( cloneIndex );
			jQuery(divContainer).appendTo("#tcopycontainer");

			torderIdHelper = '#torder-' + cloneIndex;
			jQuery(torderIdHelper).fadeIn('slow');

			torderIdHelper = '.extended-' + cloneIndex;
			jQuery(torderIdHelper).hide();

			// Get the values from the Container which is copied
			var inputValues = getValues( torderId );
			jQuery.each(inputValues, function(i, val) {
// debug				console.log(i + ' - ' + val);
				jQuery('#' + i + '-' + cloneIndex).val(val);
			});

			// Process and set the shortInfoBar
			var htmlValues = setShortInfoBar( inputValues );
			jQuery('#torder-sum-left-' + cloneIndex).html(htmlValues.from);
			jQuery('#torder-sum-right-' + cloneIndex).html(htmlValues.to);

			console.log(htmlValues);


			// Print out he Message what we've done
			alertify.log('<i class="icon-copy"></i> <strong>Transport ' + torderId + '</strong> copied to <strong>Transport ' + cloneIndex + '</strong>');

			// Count index for next action
			cloneIndex++;
		}

		/*
		 * Method to edit the selected container
		 *
		 */
		function trans_edit( torderId ) {
			torderIdHelper1 = '.extended-' + torderId;
			torderIdHelper2 = '.inverse-extended-' + torderId;
			torderIdHelper3 = '#toggleEdit-' + torderId;

			jQuery(torderIdHelper2).hide();
			jQuery(torderIdHelper3).hide();
			jQuery(torderIdHelper1).slideToggle('fast', 'linear');

			// Set chosen on the new select list element
			jQuery('#torder-' + torderId + ' select').chosen({allow_single_deselect: true, disable_search_threshold: 10, no_results_text: "Oops, nothing found", width: "100%"});

			// Print out he Message what we've done
			alertify.warning = alertify.extend('warning');
			alertify.warning('<i class="icon-edit"></i> Edit <strong> Transport ' + torderId + '</strong>');
		}

		/*
		 * Method to remove the selected container
		 *
		 */
		function trans_remove( torderId ) {
			// extend confirm function with modal (bootstrap or alertify)
			alertify.set({
				buttonFocus : 'none'
			});
			alertify.confirm('<div class="modal-header"><h3>Confirm the deletion of Transport ' + torderId + '</h3></div><div class="modal-body">Do you really want to remove <strong>Transport ' + torderId + '</strong>?</div>', function (e) {
				if (e) {
					// user klicked ok
					torderIdHelper = '#torder-' + torderId;
					jQuery(torderIdHelper).fadeOut('slow', function() {
						jQuery(torderIdHelper).remove();
					});

					// Print out he Message what we've done
					alertify.error('<i class="icon-remove"></i> <strong>Transport ' + torderId + '</strong> successfully removed');
				} else {
					// user clicked cancel
					alertify.success('<i class="icon-lightbulb"></i> <strong>Abort action for Transport ' + torderId + '</strong>');
				}
			});
		}

		/*
		 * Method to get the values from given torderId
		 * Using in seperate to determine what fields we want to have for the copy process
		 */
		function getValues( torderId ) {
			inputValues = new Object();
			// catch base values
				inputValues.transport_device = jQuery('#transport_device-' + torderId).val();
				inputValues.transport_type = jQuery('#transport_type-' + torderId).val();
				inputValues.order_type = jQuery('#order_type-' + torderId).val();
			// catch from values
				inputValues.f_address_name = jQuery('#f_address_name-' + torderId).val();
				inputValues.f_address_name_add = jQuery('#f_address_name_add-' + torderId).val();
				inputValues.f_address_street = jQuery('#f_address_street-' + torderId).val();
				inputValues.f_address_houseno = jQuery('#f_address_houseno-' + torderId).val();
				inputValues.f_address_zip = jQuery('#f_address_zip-' + torderId).val();
				inputValues.f_address_city = jQuery('#f_address_city-' + torderId).val();
				inputValues.f_address_region = jQuery('#f_address_region-' + torderId).val();
				inputValues.f_address_country = jQuery('#f_address_country-' + torderId).val();
				inputValues.f_address_lat = jQuery('#f_address_lat-' + torderId).val();
				inputValues.f_address_lng = jQuery('#f_address_lng-' + torderId).val();
				inputValues.f_address_hash = jQuery('#f_address_hash-' + torderId).val();
			// catch to values
				inputValues.t_address_name = jQuery('#t_address_name-' + torderId).val();
				inputValues.t_address_name_add = jQuery('#t_address_name_add-' + torderId).val();
				inputValues.t_address_street = jQuery('#t_address_street-' + torderId).val();
				inputValues.t_address_houseno = jQuery('#t_address_houseno-' + torderId).val();
				inputValues.t_address_zip = jQuery('#t_address_zip-' + torderId).val();
				inputValues.t_address_city = jQuery('#t_address_city-' + torderId).val();
				inputValues.t_address_region = jQuery('#t_address_region-' + torderId).val();
				inputValues.t_address_country = jQuery('#t_address_country-' + torderId).val();
				inputValues.t_address_lat = jQuery('#t_address_lat-' + torderId).val();
				inputValues.t_address_lng = jQuery('#t_address_lng-' + torderId).val();
				inputValues.t_address_hash = jQuery('#t_address_hash-' + torderId).val();

			return inputValues;
		}

		/*
		 * Method to set the shortInfoBar
		 *
		 */
		function setShortInfoBar( inputValues ) {
			var htmlValues = new Object();

			var orderFrom = '',
			orderTo = '';

			orderFrom += '<small>' + inputValues.f_address_name;
			orderFrom += ' (' + inputValues.f_address_name_add + ')</small><br>';
			orderFrom += inputValues.f_address_street;
			orderFrom += ' ' + inputValues.f_address_houseno + ',';
			orderFrom += ' ' + inputValues.f_address_zip;
			orderFrom += ' ' + inputValues.f_address_city + ',';
			orderFrom += ' ' + inputValues.f_address_region;
			orderFrom += ' ' + inputValues.f_address_country;

			orderTo += '<small>' + inputValues.t_address_name;
			orderTo += ' (' + inputValues.t_address_name_add + ')</small><br>';
			orderTo += inputValues.t_address_street;
			orderTo += ' ' + inputValues.t_address_houseno + ',';
			orderTo += ' ' + inputValues.t_address_zip;
			orderTo += ' ' + inputValues.t_address_city + ',';
			orderTo += ' ' + inputValues.t_address_region;
			orderTo += ' ' + inputValues.t_address_country;

			htmlValues.from = orderFrom;
			htmlValues.to = orderTo;

// debug			console.log( htmlValues );

			return htmlValues;
		}

		/*
		 * Method to switch the hidden values and the shortInfoBar
		 *
		 */
		function switchValues( torderId ) {
			
		}

		/*
		 * Method to permaRecalculate the values for hash, geocoding, etc...
		 *
		 */
		function reCalc( torderId ) {
			var inputValues = getValues( torderId );

			// Update hash values for from, to
			var hashValues = hashSHA256(inputValues);
			jQuery('#f_address_hash-' + torderId).html(hashValues.from);
			jQuery('#t_address_hash-' + torderId).html(hashValues.to);

			
		}

		/*
		 * Method to calculate the hash
		 *
		 */
		function hashSHA256( inputValues ) {
			var hashValues = new Object();

			var hashFrom = '',
			hashTo = '';
alertify.log('in hash');
			hashFrom = inputValues.f_address_name + inputValues.f_address_name_add + inputValues.f_address_street + inputValues.f_address_houseno + inputValues.f_address_zip + inputValues.f_address_city + inputValues.f_address_region + inputValues.f_address_country;
			hashTo = inputValues.t_address_name + inputValues.t_address_name_add + inputValues.t_address_street + inputValues.t_address_houseno + inputValues.t_address_zip + inputValues.t_address_city + inputValues.t_address_region + inputValues.t_address_country;

			hashValues.from = sha256(hashFrom);
			hashValues.to = sha256(hashTo);
			
			return hashValues;
		}

		/*
		 * Method to get an OrderId/EmergencyId based and related to the timestamp and may unique in database
		 *
		 */
		function getOrderIdFromDB( torderId ) {
			
		}

		/*
		 * Method to build the container
		 *
		 */
		function buildContainer( torderId ) {
			var htmlOut = '<div id=\"torder-' + torderId + '\" class=\"clonedTransport\" style="display: none;">';
				htmlOut += '<div class=\"widget-box transparent\">';

					htmlOut += '<div class=\"widget-header\">';
						htmlOut += '<h4 class=\"lighter green\"><div class=\"input-medium\">Transport: ' + torderId + '</div></h4>';
						htmlOut += '<div class=\"widget-toolbar no-border\">';
							htmlOut += '<div class=\"btn-group\">';
								htmlOut += '<a class=\"btn btn-mini btn-inverse xtooltip\" title=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ORDER_ID'); ?>\" data-placement=\"top\">';
									htmlOut += '<span id=\"torder-orderid-color-' + torderId + '\" class=\"red\">#</span> <span id=\"torder-orderid-value-' + torderId + '\">N/A</span>';
								htmlOut += '</a>';
								htmlOut += '<a class=\"btn btn-mini xtooltip\" title=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_TIME'); ?>\" data-placement=\"top\">';
									htmlOut += '<i id=\"torder-esttime-color-' + torderId + '\" class=\"icon-time red\"></i> <span id=\"torder-esttime-value-' + torderId + '\">N/A</span>';
								htmlOut += '</a>';
								htmlOut += '<a class=\"btn btn-mini xtooltip\" title=\"<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_DISTANCE'); ?>\" data-placement=\"top\">';
									htmlOut += '<i id=\"torder-estdistance-color-' + torderId + '\" class=\"icon-road red\"></i> <span id=\"torder-estdistance-value-' + torderId + '\">N/A</span>';
								htmlOut += '</a>';
							htmlOut += '</div>';
						htmlOut += '</div>';
					htmlOut += '</div>';

					htmlOut += '<div class=\"widget-body\">';
						htmlOut += '<div class=\"widget-main padding-6 no-padding-left no-padding-right\">';
							htmlOut += '<div class=\"row-fluid\">';

								htmlOut += '<div class=\"span8\">';
									htmlOut += '<div class=\"controls controls-row well center inverse-extended-' + torderId + '\">';
										htmlOut += '<div class=\"span5\" id=\"torder-sum-left-' + torderId + '\">N/A</div>';
										htmlOut += '<div class=\"span2\" id=\"torder-sum-change-' + torderId + '\" style=\"vertical-align: middle; font-size: 30px; line-height: 40px;\">';
											htmlOut += '<span class=\"hidden-phone\"><i class=\"icon-exchange\"></i></span>';
											htmlOut += '<span class=\"visible-phone\"><i class=\"icon-exchange icon-rotate-90\"></i></span>';
										htmlOut += '</div>';
										htmlOut += '<div class=\"span5\" id=\"torder-sum-right-' + torderId + '\">N/A</div>';
									htmlOut += '</div>';
									htmlOut += '<div class=\"controls controls-row extended-' + torderId + '\">';
										htmlOut += '<div class=\"span6\">';
											htmlOut += '<div class=\"well\">';
												htmlOut += '<div class=\"control-group\">';
													htmlOut += '<label class=\"control-label\"><i class=\"icon-chevron-sign-up\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_FROM'); ?></label>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<span class=\"span12\">';
															htmlOut += '<select name="transcorders[multiform][' + torderId + '][f_poi_id]" class="chzn-select-poi-address" id="f_poi_id-' + torderId + '" data-placeholder="<?php echo JText::_('COM_XIVEIRM_FORM_SELECT_POI'); ?>">';
																htmlOut += '<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_CONTACTLIST_PLEASE_SELECT'); ?></option>';
																<?php
																	foreach ( $poiOptions as $key => $value ) {
																		echo 'htmlOut += \'<option value="' . $key . '">' . $value . '</option>\';';
																	}
																?>
															htmlOut += '</select>';
														htmlOut += '</span>';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<span class=\"span12 alert\">';
															htmlOut += '<input type="text" class="span12 f_address_helper-' + torderId + '" id="f_address_helper-' + torderId + '" data-placeholder="<?php echo JText::_('COM_XIVEIRM_ADDRESS_HELPER'); ?>">';
														htmlOut += '</span>';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"f_address_name-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_name]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>\" maxlength=\"150\" value />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"f_address_name_add-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_name_add]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_street-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_street]\" class=\"input-control span9\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>\" maxlength=\"100\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_houseno-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_houseno]\" class=\"input-control span3\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>\" maxlength=\"10\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_zip-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_zip]\" class=\"input-control span4\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>\" maxlength=\"10\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_city-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_city]\" class=\"input-control span8\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"f_address_region-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_region]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>\" />';
														htmlOut += '<input type=\"text\" id=\"f_address_country-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_country]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>\" />';
													htmlOut += '</div>';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div>';
										htmlOut += '<div class=\"span6\">';
											htmlOut += '<div class=\"well\">';
												htmlOut += '<div class=\"control-group\">';
													htmlOut += '<label class=\"control-label\"><i class=\"icon-chevron-sign-down\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_TO'); ?></label>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<span class=\"span12\">';
															htmlOut += '<select name="transcorders[multiform][' + torderId + '][t_poi_id]" class="chzn-select-poi-address" id="t_poi_id-' + torderId + '" data-placeholder="<?php echo JText::_('COM_XIVEIRM_FORM_SELECT_POI'); ?>">';
																htmlOut += '<option value=""><?php echo JText::_('COM_XIVEIRM_FORM_CONTACTLIST_PLEASE_SELECT'); ?></option>';
																<?php
																	foreach ( $poiOptions as $key => $value ) {
																		echo 'htmlOut += \'<option value="' . $key . '">' . $value . '</option>\';';
																	}
																?>
															htmlOut += '</select>';
														htmlOut += '</span>';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<span class=\"span12 alert\">';
															htmlOut += '<input type="text" class="span12 t_address_helper-' + torderId + '" id="t_address_helper-' + torderId + '" data-placeholder="<?php echo JText::_('COM_XIVEIRM_ADDRESS_HELPER'); ?>">';
														htmlOut += '</span>';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"t_address_name-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_name]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_NAME'); ?>\" maxlength=\"150\" value />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls\">';
														htmlOut += '<input type=\"text\" id=\"t_address_name_add-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_name_add]\" class=\"input-control span12\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_NAME_ADD'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_street-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_street]\" class=\"input-control span9\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_STREET'); ?>\" maxlength=\"100\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_houseno-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_houseno]\" class=\"input-control span3\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_HOUSENO'); ?>\" maxlength=\"10\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_zip-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_zip]\" class=\"input-control span4\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_ZIP'); ?>\" maxlength=\"10\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_city-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_city]\" class=\"input-control span8\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_CITY'); ?>\" maxlength=\"100\" />';
													htmlOut += '</div>';
													htmlOut += '<div class=\"controls controls-row\">';
														htmlOut += '<input type=\"text\" id=\"t_address_region-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_region]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_REGION'); ?>\" />';
														htmlOut += '<input type=\"text\" id=\"t_address_country-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_country]\" class=\"input-control span6\" placeholder=\"<?php echo JText::_('COM_XIVEIRM_CONTACT_TO_ADDRESS_COUNTRY'); ?>\" />';
													htmlOut += '</div>';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div>';
									htmlOut += '</div>'; // <!-- /.controls .controls-row .extended- -->
								htmlOut += '</div><!-- /.span8 -->';

								htmlOut += '<div class=\"span4\">';
									htmlOut += '<div class=\"well\">';
										htmlOut += '<div class=\"controls controls-row\">';
											htmlOut += '<span id=\"torder-input-time-' + torderId + '\" class=\"span6\">';
												htmlOut += '<input id=\"timepicker-' + torderId + '\" type=\"time\" class=\"span12 center\" onKeyUp=\"setTimestamp(' + torderId + ', 0)\" style=\"font-size: 23px; height: 40px;\" required />';
											htmlOut += '</span>';
											htmlOut += '<span id=\"torder-input-date-' + torderId + '\" class=\"span6\">';
												htmlOut += '<input id=\"datepicker-' + torderId + '\" type=\"date\" class=\"span12 center\" onKeyUp=\"setTimestamp(' + torderId + ', 0)\" style=\"font-size: 23px; height: 40px;\" required />';
											htmlOut += '</span>';
										htmlOut += '</div>';

										htmlOut += '<div class=\"extended-' + torderId + '\">';
											htmlOut += '<div class=\"control-group\">';
												htmlOut += '<label class=\"control-label\"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSPORT_DEVICE_AND_TYPE'); ?></label>';
												htmlOut += '<div class=\"controls controls-row\">';
													htmlOut += '<div class=\"span6\">';
														htmlOut += '<?php //  id=\"transport_device-' + torderId + '\" [multiform][' + torderId + '] echo getTransDevice(); ?>';
													htmlOut += '</div>';
													htmlOut += '<div class=\"span6\">';
														htmlOut += '<?php // id=\"transport_type-' + torderId + '\" [multiform][' + torderId + '] echo getTransType(); ?>';
													htmlOut += '</div>';
												htmlOut += '</div>';
											htmlOut += '</div><!-- #end control group -->';

											htmlOut += '<div class=\"control-group\">';
												htmlOut += '<label class=\"control-label\"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ORDER_TYPE'); ?></label>';
												htmlOut += '<div class=\"controls controls-row\">';
													htmlOut += '<div class=\"span12\">';
														htmlOut += '<?php // id=\"order_type-' + torderId + '\" [multiform][' + torderId + '] echo getOrderType(); ?>';
													htmlOut += '</div>';
												htmlOut += '</div>';
											htmlOut += '</div><!-- #end control group -->';
										htmlOut += '</div><!-- /.extended- -->';

										htmlOut += '<div class=\"control-group\">';
											htmlOut += '<div class=\"controls controls-row center\">';
												htmlOut += '<div class=\"btn-group\">';
													htmlOut += '<a id=\"toggleEdit-' + torderId + '\" class=\"btn btn-warning\" onClick=\"trans_edit(' + torderId + ')\">';
														htmlOut += '<i class=\"icon-edit\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_EDIT_ITEM'); ?>';
													htmlOut += '</a>';
													htmlOut += '<a class=\"btn btn-success xtooltip trans-add\" onClick=\"trans_add(' + torderId + ')\">';
														htmlOut += '<i class=\"icon-plus\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_ADD_EMPTY_ITEM'); ?>';
													htmlOut += '</a>';
													htmlOut += '<a class=\"btn btn-primary trans-copy\" onClick=\"trans_copy(' + torderId + ')\">';
														htmlOut += '<i class=\"icon-copy\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_COPY_THIS_ITEM'); ?>';
													htmlOut += '</a>';
													htmlOut += '<a class=\"btn btn-danger trans-remove\" onClick=\"trans_remove(' + torderId + ')\">';
														htmlOut += '<i class=\"icon-remove\"></i> <?php echo JText::_('COM_XIVETRANSCORDER_DELETE_ITEM'); ?>';
													htmlOut += '</a>';
												htmlOut += '</div>';
											htmlOut += '</div>';
										htmlOut += '</div><!-- #end control group -->';
									htmlOut += '</div><!-- /.well -->';
								htmlOut += '</div><!-- /.span4 -->';
							htmlOut += '</div><!-- /.row-fluid -->';
						htmlOut += '</div><!-- /.widget-main padding-6 no-padding-left no-padding-right -->';
					htmlOut += '</div><!-- /.widget-body -->';
				htmlOut += '</div><!-- /.widget-box .transparent -->';

				htmlOut += '<input type=\"text\" id=\"f_address_lat-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_lat]\" />';
				htmlOut += '<input type=\"text\" id=\"f_address_lng-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_lng]\" />';
				htmlOut += '<input type=\"text\" id=\"f_address_hash-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][f_address_hash]\" />';
				htmlOut += '<input type=\"text\" id=\"t_address_lat-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_lat]\" />';
				htmlOut += '<input type=\"text\" id=\"t_address_lng-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_lng]\" />';
				htmlOut += '<input type=\"text\" id=\"t_address_hash-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][t_address_hash]\" />';

				htmlOut += '<input type=\"text\" id=\"order_id-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][order_id]\" value />';
				htmlOut += '<input type=\"text\" id=\"estimated_time-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][estimated_time]\" value />';
				htmlOut += '<input type=\"text\" id=\"estimated_distance-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][estimated_distance]\" value />';
				htmlOut += '<input type=\"text\" id=\"transport_timestamp-' + torderId + '\" name=\"transcorders[multiform][' + torderId + '][transport_timestamp]\" value required />';

			htmlOut += '</div><!-- /.torder-' + torderId + ' -->';

			return htmlOut;
		}

	console.log();

	/*
	 * Get and set the unix timestamp from separate time and date input fields
	 * only support the chrome browser at this time !!
	 */
	function setTimestamp ( torderId, addDays ) {
		var timeField = jQuery('#timepicker-' + torderId).val(),
		dateField = jQuery('#datepicker-' + torderId).val();

		if(!timeField && !dateField) {
			jQuery('#timepicker-' + torderId).val(moment().format('HH:mm'));
			jQuery('#datepicker-' + torderId).val(moment().format('YYYY-MM-DD'));
		} else {
			if( timeField && dateField ) {
				formatDateTime = dateField + ' ' + timeField + ':00';
				newDateTime = moment(formatDateTime).add('days', addDays);

				// do some things while date is invalid
				if( moment(newDateTime).isValid() === true ) {
					jQuery('#transport-date-time-' + torderId).html(moment(newDateTime).format('dddd, DD.MM.YYYY HH:mm') + ' <i class="icon-ok-sign"></a>');

					// Format the unix timestamp
					unixTimestamp = moment( newDateTime ).format('X');
					jQuery('#transport_timestamp-' + torderId).val(unixTimestamp);
				} else {
					alertify.error("invalid");
				}
			}
		}

		// test stuff
//		unixTimestampNow = Math.round( (new Date()).getTime() / 1000);
//		unixTimestampNow = moment().unix();
//		ts = moment( Date.parse("03/21/2013 14:00:00") ).format('X');
//		var numChars = "2013".match(/[a-zA-Z0-9]/g).length;
//		console.log(numChars);
	}
	setTimestamp ( 1, 0 );


// });
</script>
<?php
NFWHtmlJavascript::detectChanges();
//		#################################################### EXAMPLE #########################################################
//				// Triggered on every change in the auto geocoder block (this.value determines the actual field)
//				$('#torder-' + torderId).on('inputchange', function() {
//					// Detect Parent first! Then go step by step down to find and determine the fields!!!!!!!!
//					------------------------------------------------------------------------------------------
//					------------------------------------------------------------------------------------------
//					------------------------------------------------------------------------------------------
//					// Get and set the address vars to auto-geocoder and trigger onKeyUp
//					$('#location').val(this.value);
//				});
//		#################################################### EXAMPLE #########################################################
?>

<pre>
<?php

print_r($transportDeviceOptions);
print_r($transportTypeOptions);
print_r($orderTypeOptions);

?>
</pre>