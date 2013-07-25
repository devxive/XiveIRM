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
NHtmlJavaScript::setPreventFormLeaveIfChanged('#form-transcorder');
NHtmlJavaScript::loadTimepicker('#timepicker_1', 15, false, false);
NHtmlJavaScript::loadDatepicker();

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_SITE);
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Get Permissions
$permissions = NUserAccess::getPermissions('com_xiveirm', false, false, 'xiveirm_contacts.' . $this->item->id);

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
$full_name = 'Full Name PLACEHOLDER';
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
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
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
            js('#form-transcorder').submit(function(event){
                 
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

	<form id="form-transcorder" class="form-validate">

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
									<?php NHtmlJavaScript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span6">
										<?php if($this->item->catid && !$this->item->id) { ?>
											<input type="hidden" name="transcorder[catid]" value="<?php echo $this->item->catid; ?>">
											<a class="btn btn-small btn-warning disabled" disabled="disabled"><i class="icon-double-angle-left"></i> <?php echo NItemHelper::getTitleById('category', $this->item->catid); ?></a>
										<?php } else { ?>
										<select name="transcorder[catid]" class="chzn-select-category input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" required>
											<option value=""></option>
											<?php
												$options = IRMSystem::getListOptions('categories', false, 'com_xivetranscorder.transcorders');
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
										<a id="toggleExtend" class="btn btn-small pull-right"><i class="icon-double-angle-down"></i><span class="hidden-phone"> Additional fields</span></a>
									</div>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_CONTACT'); ?></label>
								<div class="controls controls-row">
									<?php NHtmlJavaScript::setChosen('.chzn-select-parent', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span6">
										<select name="contacts[parent_id]" class="chzn-select-parent input-control" data-placeholder="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_CONTACT'); ?>" required>
											<?php
												if(!$this->item->parent_id) {
													echo '<option value="0" selected>' . JText::_('COM_XIVETRANSCORDER_FORM_SELECT_CONTACT') . '</option>';
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
									<div class="span6">
									</div>
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

							<div class="control-group extended">
								<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_FORM_PUBLIC_REMARKS'); ?></label>
								<div class="controls">
									<textarea name="transcorder[remarks]" class="input-control span12 limited autosize" max-data-length="250" maxlength="250" rows="2" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_INTERNAL_REMARKS_DESC'); ?>"><?php echo $this->item->remarks; ?></textarea>
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
										<input name="transcorder[distcalc_device]" id="distcalc-device" type="hidden" value="drive" />

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
<?php NHtmlJavaScript::setToggle('extended-0', 'toggleEdit-0'); ?>
<?php NHtmlJavaScript::setToggle('extended-1', 'toggleEdit-1'); ?>
<?php NHtmlJavaScript::setToggle('extended-2', 'toggleEdit-2'); ?>
<?php NHtmlJavaScript::setToggle('extended-3', 'toggleEdit-3'); ?>
<?php NHtmlJavaScript::setToggle('extended-4', 'toggleEdit-4'); ?>
<?php NHtmlJavaScript::setToggle('extended-5', 'toggleEdit-5'); ?>
				<div id="torder-0" class="clonedTransport">
					<div class="widget-box transparent">
						<div class="widget-header">
							<h4 class="lighter">
								<div id="transport-header" class="input-medium">Transport: 1</div>
								<input name="transcorder[order_id]" type="hidden" value />
							</h4>
							<div class="widget-toolbar no-border">
								<div class="btn-group">
									<a class="btn btn-mini btn-inverse xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ORDER_ID'); ?>"
										data-placement="top">
										<span class="<?php echo $this->item->order_id ? 'green' : 'red'; ?>">#</span> <?php echo $this->item->order_id ? $this->item->order_id : 'N/A'; ?>
									</a>
									<input name="transcorder[order_id]" type="hidden" value />
									<a class="btn btn-mini btn-light xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_TIME'); ?>"
										data-placement="top">
										<i class="icon-time <?php echo $this->item->estimated_distance ? 'green' : 'red'; ?>"></i> <?php echo $this->item->estimated_distance ? '~' . $this->item->estimated_time : 'N/A'; ?>
									</a>
									<input name="transcorder[estimated_time]" type="hidden" value />
									<a class="btn btn-mini btn-light xtooltip"
										data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_DISTANCE'); ?>"
										data-placement="top">
										<i class="icon-road <?php echo $this->item->estimated_distance ? 'green' : 'red'; ?>"></i> <?php echo $this->item->estimated_distance ? '~' . $this->item->estimated_distance . ' KM' : 'N/A'; ?>
									</a>
									<input name="transcorder[estimated_distance]" type="hidden" value />
								</div>
							</div>
						</div>
						<div class="widget-body">
							<div class="widget-main padding-6 no-padding-left no-padding-right">
								<div class="row-fluid">
									<?php NHtmlJavaScript::setChosen('.chzn-select-poi-address', false, array('allow_single_deselect' => true, 'disable_search_threshold' => '10', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
									<div class="span8">
										<div class="well center inverse-extended-0">
											<span>
												<i class="icon-chevron-sign-up"></i> 
												Frankfurter Str. 122, 60388 Frankfurt am Main
											</span>
											<span class="margin-left margin-right"><i class="icon-exchange"></i></span>
											<span>
												<i class="icon-chevron-sign-down"></i> 
												Marbachweg 405, 61447 Offenbach am Main
											</span>
										</div>
										<div class="controls controls-row extended-0">
											<div class="span6">
												<div class="well">
													<div class="control-group">
														<label class="control-label"><i class="icon-chevron-sign-up"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_FROM'); ?></label>
														<div class="controls">
															<span class="span12">
																<?php echo $this->form->getInput('f_poi_id'); ?>
															</span>
														</div>
														<div class="controls">
															<input type="text" name="transcorder[f_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->f_address_name; ?>">
														</div>
														<div class="controls">
															<input type="text" name="transcorder[f_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->f_address_name_add; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[f_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->f_address_street; ?>">
															<input type="text" name="transcorder[f_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->f_address_houseno; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[f_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->f_address_zip; ?>">
															<input type="text" name="transcorder[f_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->f_address_city; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[f_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->f_address_region; ?>">
															<input type="text" name="transcorder[f_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->f_address_country; ?>">
														</div>
														<input type="hidden" name="transcorder[f_address_lat]" value="<?php echo $this->item->t_address_lat; ?>" />
														<input type="hidden" name="transcorder[f_address_long]" value="<?php echo $this->item->t_address_long; ?>" />
														<input type="hidden" name="transcorder[f_address_hash]" value="<?php echo $this->item->t_address_hash; ?>" />
													</div>
												</div>
											</div>
											<div class="span6">
												<div class="well">
													<div class="control-group">
														<label class="control-label"><i class="icon-chevron-sign-down"></i> <?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ADDRESS_TO'); ?></label>
														<div class="controls">
															<span class="span12">
																<?php echo $this->form->getInput('t_poi_id'); ?>
															</span>
														</div>
														<div class="controls">
															<input type="text" name="transcorder[t_address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->t_address_name; ?>">
														</div>
														<div class="controls">
															<input type="text" name="transcorder[t_address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->t_address_name_add; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[t_address_street]" class="input-control span9" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->t_address_street; ?>">
															<input type="text" name="transcorder[t_address_houseno]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->t_address_houseno; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[t_address_zip]" class="input-control span4" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->t_address_zip; ?>">
															<input type="text" name="transcorder[t_address_city]" class="input-control span8" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->t_address_city; ?>">
														</div>
														<div class="controls controls-row">
															<input type="text" name="transcorder[t_address_region]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_REGION'); ?>" value="<?php echo $this->item->t_address_region; ?>">
															<input type="text" name="transcorder[t_address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_CONTACT_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->t_address_country; ?>">
														</div>
														<input type="hidden" name="transcorder[t_address_lat]" value="<?php echo $this->item->t_address_lat; ?>" />
														<input type="hidden" name="transcorder[t_address_long]" value="<?php echo $this->item->t_address_long; ?>" />
														<input type="hidden" name="transcorder[t_address_hash]" value="<?php echo $this->item->t_address_hash; ?>" />
													</div>
												</div>
											</div>
										</div>
									</div><!-- /.span8 -->
									<div class="span4">
										<div class="well">
											<div class="extended-0">
												<div class="row-fluid">
													<span class="input-icon input-icon-right span12 bootstrap-timepicker">
														<input id="timepicker_1" type="text" class="span12 center" style="font-size: 25px; height: 45px;" />
														<i class="icon-time"></i>
													</span>
												</div>
												<div class="row-fluid">
													<span class="input-icon input-icon-right span12 bootstrap-datepicker">
														<input id="datepicker_1" type="text" class="span12 center" style="font-size: 25px; height: 45px;" />
														<i class="icon-calendar"></i>
													</span>
												</div>
											</div>
											<div class="inverse-extended-0">
												<div class="row-fluid">
													<span class="input-icon input-icon-right span6 bootstrap-timepicker">
														<input id="timepicker_1" type="text" class="span12 center" style="font-size: 25px; height: 45px;" />
														<i class="icon-time"></i>
													</span>
													<span class="input-icon input-icon-right span6 bootstrap-datepicker">
														<input id="datepicker_1" type="text" class="span12 center" style="font-size: 25px; height: 45px;" />
														<i class="icon-calendar"></i>
													</span>
												</div>
											</div>

											<input type="hidden" name="transcorder[transport_timestamp]" value="" />

											<div class="extended-0">
												<?php NHtmlJavaScript::setChosen('.chzn-select-trans', false, array('width' => '100%', 'disable_search' => true)); ?>
												<div class="control-group">
													<label class="control-label">
														<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSPORT_DEVICE_AND_TYPE'); ?>
													</label>
													<div class="controls controls-row">
														<div class="span6">
															<select name="transcorder[gender]" class="chzn-select-trans input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
																<option value=""><?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_TRANSPORT_DEVICE'); ?></option>
																<?php
																	$options = IRMSystem::getListOptions('options', 'transdevice');
																	if($options->client) {
																		echo '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
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
														</div>

														<div class="span6">
															<select name="transcorder[gender]" class="chzn-select-trans input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
																<option value=""><?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_TRANSPORT_DEVICE'); ?></option>
																<?php
																	$options = IRMSystem::getListOptions('options', 'transtype');
																	if($options->client) {
																		echo '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
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
														</div>
													</div>
												</div><!-- #end control group -->

												<div class="control-group">
													<label class="control-label">
														<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_ORDER_TYPE'); ?>
													</label>
													<div class="controls controls-row">
														<?php NHtmlJavaScript::setChosen('.chzn-select-ordertype', false, array('width' => '100%', 'disable_search' => true)); ?>
														<div class="span12">
															<select name="transcorder[gender]" class="chzn-select-ordertype input-control" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" style="" required>
																<option value=""><?php echo JText::_('COM_XIVETRANSCORDER_FORM_SELECT_ORDER_TYPE'); ?></option>
																<?php
																	$options = IRMSystem::getListOptions('options', 'ordertype');
																	if($options->client) {
																		echo '<optgroup label="' . JText::sprintf('COM_XIVETRANSCORDER_FORM_SELECT_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
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
														</div>
													</div>
												</div><!-- #end control group -->
											</div><!-- /.extended-0 -->
											<div class="control-group">
												<div class="controls controls-row center">
													<div class="btn-group">
														<a id="toggleEdit-0" class="btn btn-warning xtooltip"
															data-action="collaps"
															data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_EDIT_ITEM'); ?>"
															data-placement="top">
															<i class="icon-edit icon-only"></i> Bearbeiten
														</a>
														<a class="btn btn-success xtooltip trans-copy"
															data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_ADD_ITEM'); ?>"
															data-placement="top">
															<i class="icon-plus icon-only"></i> Hinzufügen
														</a>
														<a class="btn btn-danger xtooltip trans-remove"
															data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_DELETE_ITEM'); ?>"
															data-placement="top">
															<i class="icon-remove icon-only"></i> Löschen
														</a>
													</div>
												</div>
											</div><!-- #end control group -->
										</div><!-- /.well -->
									</div><!-- /.span4 -->
								</div><!-- /.row-fluid .extended-1 -->
							</div><!-- /.widget-main padding-6 no-padding-left no-padding-right -->
						</div><!-- /.widget-body -->
					</div><!-- /.widget-box .transparent -->
				</div><!-- /.torder-0 -->
				<!-- ########## ########## ########## ########## ##########   END FIRST TRANSPORT   ########## ########## ########## ########## ########## -->



				<div id="tcopycontainer"><hr></div>



				<input type="hidden" name="transcorder[id]" id="order_cid" value="<?php echo isset($this->item->id) ? $this->item->id : '0'; ?>" />

				<?php if(empty($this->item->client_id)){ ?>
					<input type="hidden" name="transcorder[client_id]" value="<?php echo $xsession->client_id; ?>" maxlength="50" />
				<?php } else { ?>
					<input type="hidden" name="transcorder[client_id]" value="<?php echo $this->item->client_id; ?>" maxlength="50" />
				<?php } ?>
				<input type="hidden" name="transcorder[state]" value="<?php echo $this->item->state; ?>" />
				<input type="hidden" id="checkEditForm" name="checkEditForm" value="0" />
				<input type="hidden" name="irmapi[coreapp]" value="transcorder" />
				<input type="hidden" name="irmapi[component]" value="com_xivetranscorder" />
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
		<input type="hidden" name="irmapi[coreapp]" value="transcorder" />
		<?php echo JHtml::_('form.token'); ?>
	</form>
</div>







<script>
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
						window.location.href = "<?php echo JRoute::_('index.php?task=contact.edit&id='); ?>" + cId;
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


	// TRANSPORT COPY SCRIPT ************************************************************************
	jQuery(document).ready(function() {

		var regex = /^(.*)(\d)+$/i;
		var cloneIndex = $(".clonedTransport").length;

		$(".trans-copy").click(function() {
			$(this).parents(".clonedTransport").clone()
				.appendTo("#tcopycontainer")
				.attr("id", "torder-" + cloneIndex)
				.find("*").each(function() {
					var id = this.id || "";
					var match = id.match(regex) || [];
					if (match.length == 3) {
						this.id = match[1] + (cloneIndex);
					}
					$("#transport-header").html("Transport: " + cloneIndex);
				});
			cloneIndex++;

			console.log(cloneIndex);

		});

		$(".trans-remove").click(function() {
			confirm('wirklich löschen?');
			$(this).parents(".clonedTransport").remove();
		});
	});

</script>

















<div class="transcorder-edit front-end-edit" id="form-transcorder" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
			<div class="control-group">
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
					<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
				<?php else: ?>
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
				<?php endif; ?>
				</div>

				<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('transport_timestamp'); ?></div>
				<div class="controls"><?php echo $this->form->getInput(''); ?></div>
			</div>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_xivetranscorder" />
            <input type="hidden" name="task" value="transcorderform.save" />
        </div>
</div>
