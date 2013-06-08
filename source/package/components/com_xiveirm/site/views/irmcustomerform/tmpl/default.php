<?php
/**
 * @version     3.3.0
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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xiveirm', JPATH_ADMINISTRATOR);

JPluginHelper::importPlugin( 'irmcustomertabs' ); // returned 1 if get successfully loaded
JPluginHelper::importPlugin( 'irmcustomerwidgets' ); // returned 1 if get successfully loaded
$dispatcher = JDispatcher::getInstance();
?>

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
			js('#form-irmcustomer').submit(function(event){
			}); 
		});
	});
</script>

<div class="row-fluid">
	<div class="row-fluid header smaller lighter blue">
		<h1>
			<span class="span7">
				<i class="icon-user"></i>
				<?php if (!empty($this->item->id)): ?>
					<?php echo ' ' . $this->item->last_name; ?>, <?php echo $this->item->first_name; ?> <?php if($this->item->customer_id): echo '<small><i class="icon-double-angle-right"></i> (#' . $this->item->customer_id . ')</small>'; endif; ?>
				<?php else: ?>
					<?php echo ' ' . JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADD_NEW_CONTACT'); ?>
				<?php endif; ?>
			</span>
			<span class="span5">
				<a class="btn btn-danger btn-mini pull-right inline" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.cancel'); ?>" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CANCEL_CHECKIN_TIP'); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CANCEL_CHECKIN'); ?></a>
			</span>
		</h1>
	</div><!--/page-header-->

	<!-- CHECK_OUT MESSAGE -->
	<?php if($this->item->checked_out): ?>
		<?php if(IRMSystem::getUserName($this->item->checked_out) == IRMSystem::getUserName(null)): ?>
			<div id="checkout-info" class="alert alert-info" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CHECKED_OUT_ALERT_INFO'); ?>
			</div>
		<?php else : ?>
			<div id="checkout-info" class="alert alert-error" style="display: none;">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<?php echo JText::printf('COM_XIVEIRM_IRMCUSTOMER_FORM_CHECKED_OUT_ALERT_ERROR', '#', IRMSystem::getUserName($this->item->checked_out), $this->item->checked_out_time, '5'); // 1. Link zum Benutzer, 2. Name, 3. Checkedout time, 4. wann wieder verfügbar ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<!-- CHECK_OUT MESSAGE -->

	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
	<div class="tabbable">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TAB_BASICDATA'); ?></a></li>
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
				<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TAB_MORE'); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-info">
					<li><a data-toggle="tab" href="#dropdown1">@Anwendung 4</a></li>
					<li><a data-toggle="tab" href="#dropdown2">@Anwendung 5</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 6</a></li>
				</ul>
			</li>
		</ul>
		<div class="tab-content">
	<!-- BASE-DATA_TAB_CORE -->
			<div id="base-data" class="tab-pane active">
				<div class="row-fluid">
					<div class="span7">
					<form id="form-irmcustomer" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
						<?php if($this->item->id): ?>
							<div class="control-group">
								<label id="input-control-button" class="control-label red"><b>Edit form</b></label>
								<div class="controls">
									<input id="id-disable-check" class="ace-switch ace-switch-7" type="checkbox"><span class="lbl"></span>
								</div>
							</div>
						<?php endif; ?>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CUSTOMER_ID_LABEL'); ?>, <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_NAME_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[customer_id]" class="input-control span6" id="prependedInput" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CUSTOMER_ID'); ?>" value="<?php echo $this->item->customer_id; ?>">
								<input type="text" name="jform[title]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_NAME_TITLE'); ?>" value="<?php echo $this->item->title; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[last_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_LAST_NAME'); ?>" value="<?php echo $this->item->last_name; ?>" autofocus>
								<input type="text" name="jform[first_name]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_FIRST_NAME'); ?>" value="<?php echo $this->item->first_name; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_GENDER_DESC'); ?>"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_LABEL'); ?></label>
							<div class="controls controls-row">
								<select name="jform[gender]" class="input-control span3" required>
									<option value=""<?php if(!$this->item->gender): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_SELECT'); ?></option>
									<option value="u"<?php if($this->item->gender == 'u'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_GENDER_UNKNOWN'); ?></option>
									<option value="f"<?php if($this->item->gender == 'f'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_GENDER_FEMALE'); ?></option>
									<option value="m"<?php if($this->item->gender == 'm'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_GENDER_MALE'); ?></option>
									<option value="c"<?php if($this->item->gender == 'c'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_GENDER_COMPANY'); ?></option>
								</select>
								<input type="date" name="jform[dob]" class="input-control span3" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_TRAIT_DOB'); ?>" value="<?php echo $this->item->dob; ?>" required>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_LABEL'); ?></label>
							<div class="controls">
								<input type="text" name="jform[address_name]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->address_name; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_name_add]" class="input-control span12" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->address_name_add; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_street]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->address_street; ?>">
								<input type="text" name="jform[address_houseno]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->address_houseno; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_zip]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->address_zip; ?>">
								<input type="text" name="jform[address_city]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->address_city; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_country]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->address_country; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_PHONE_NUMBERS_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[phone]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_PHONE'); ?>" value="<?php echo $this->item->phone; ?>">
								<input type="text" name="jform[fax]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_FAX'); ?>" value="<?php echo $this->item->fax; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[mobile]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_MOBILE'); ?>" value="<?php echo $this->item->mobile; ?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_WEB_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[email]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_EMAIL'); ?>" value="<?php echo $this->item->email; ?>">
								<input type="text" name="jform[web]" class="input-control span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_WEB'); ?>" value="<?php echo $this->item->web; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_INTERNAL_REMARKS'); ?></label>
							<div class="controls">
								<textarea name="jform[remarks]" class="input-control span12 limited" max-data-length="250" maxlength="250" rows="5" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_INTERNAL_REMARKS_DESC'); ?>"><?php echo $this->item->remarks; ?></textarea>
							</div>
						</div>
						
						<input type="hidden" name="jform[client_id]" value="<?php echo $this->item->client_id; ?>" maxlength="50" />

						<?php if(empty($this->item->id)){ ?>
							<input type="hidden" name="jform[created_by]" value="0" />
						<?php } else { ?>
							<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
						<?php } ?>
						<input type="hidden" name="jform[created]" value="<?php echo $this->item->created; ?>" />
						<?php if(empty($this->item->created_by)){ ?>
							<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
						<?php } else { ?>
							<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
						<?php } ?>
						<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
						<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
						<input type="hidden" name="jform[modified]" value="<?php echo $this->item->modified; ?>" />
						<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
						<input type="hidden" name="option" value="com_xiveirm" />
						<input type="hidden" name="task" value="irmcustomerform.save" />
						<?php echo JHtml::_('form.token'); ?>
						<div class="form-actions">
							<span id="form-buttons" class="<?php echo empty($this->item->id) ? '' : 'hidden'; ?>">
								<button class="validate btn btn-info" type="submit" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_SUBMIT_CHECKIN_TIP'); ?>"><i class="icon-ok"></i> <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_SUBMIT_CHECKIN'); ?></button>
								&nbsp; &nbsp; &nbsp;
								<button class="btn" type="reset" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_RESET_TIP'); ?>"><i class="icon-undo"></i> <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_RESET'); ?></button>
								&nbsp; &nbsp; &nbsp;
							</span>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.cancel'); ?>" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CANCEL_CHECKIN_TIP'); ?>" class="btn btn-danger"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_IRMCUSTOMER_FORM_CANCEL_CHECKIN'); ?></a>
						</div>
					</form>
					</div>
					<div class="span5">
						<div class="well">
							
						<!-- TAB.PLUGIN_MAIN-WIDGETS -->
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
						<!-- TAB.PLUGIN_MAIN-WIDGETS -->

						</div>
					</div>
				</div>
			</div>
	<!-- BASE-DATA_TAB_CORE -->
			<div id="dropdown1" class="tab-pane">
				<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
			</div>
			<div id="dropdown2" class="tab-pane">
				<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin.</p>
			</div>
	<!-- TAB.PLUGIN_CONTENT -->
			<?php
				foreach($dispatcher->trigger( 'loadTabContainer', array(&$this->item) ) as $tabContainer)
				{
					echo '<div id="' . $tabContainer['tab_key'] . '" class="tab-pane">';
					echo $tabContainer['tabContent'];
					echo '</div>';
				}
			?>
	<!-- TAB.PLUGIN_CONTENT -->
		</div>
	</div>
	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
</div>

<?php if($this->item->id): ?>
	<script>
	// XAP-TODO: Have to set more functions to the edit form, such as a DB-checkout on activate and checkin on save or check in on deactivate !!!!
		jQuery(function() {
			// Prevent submit on enter (keycode 13) event in form fields
			$("#form-irmcustomer").bind('keypress keydown keyup', function(e) {
				if(e.keyCode == 13) { e.preventDefault(); }
			});

			// Enable edit event button
			$("#form-irmcustomer .input-control").attr("readonly", true);
			var inp = $('.input-control').get(0);
			var classInp = $('#input-control-button').get(0);
	
			$('#id-disable-check').on('click', function() {
				if(inp.hasAttribute('readonly')) {
					$("#form-irmcustomer .input-control").attr("readonly", false);
					classInp.removeAttribute('class', 'red');
					classInp.setAttribute('class' , 'control-label green');
					$("#form-buttons").removeClass("hidden");
				} else {
					$("#form-irmcustomer .input-control").attr("readonly", true);
					classInp.removeAttribute('class', 'green');
					classInp.setAttribute('class' , 'control-label red');
					$("#form-buttons").addClass("hidden");
				}
				if ($("#checkout-info").is(":hidden")) {
					setTimeout(function () {
							$("#checkout-info").slideDown("slow");
					}, 2000)
				} else {
					$("#checkout-info").slideUp("slow");
					setTimeout(function () {
						$.gritter.add({
							title: 'Successfully checked-in',
							text: 'You have successfully checked-in this contact, so other user can edit now',
							class_name: 'gritter-success'
						});
					}, 2000)
				}
			});
		});
	</script>
<?php endif; ?>