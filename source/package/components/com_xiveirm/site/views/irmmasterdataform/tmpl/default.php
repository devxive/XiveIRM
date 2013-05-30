<?php
/**
 * @version     3.1.0
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

JPluginHelper::importPlugin( 'irmmasterdatatabs' ); // returned 1 if get successfully loaded
JPluginHelper::importPlugin( 'irmmasterdatawidgets' ); // returned 1 if get successfully loaded
$dispatcher = JDispatcher::getInstance();
?>
<style>
	input {margin-bottom:10px !important;}
</style>
<div class="row-fluid">
	<div class="row-fluid header smaller lighter blue">
		<h1>
			<span class="span7">
				<i class="icon-user"></i>
				<?php if (!empty($this->item->id)): ?>
					<?php echo ' ' . $this->item->last_name; ?>, <?php echo $this->item->first_name; ?> <?php if($this->item->customer_id): echo '<small><i class="icon-double-angle-right"></i> (#' . $this->item->customer_id . ')</small>'; endif; ?>
				<?php else: ?>
					<?php echo ' ' . JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADD_NEW_CONTACT'); ?>
				<?php endif; ?>
			</span>
			<span class="span5">
				<a class="btn btn-danger btn-mini pull-right inline" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.cancel'); ?>" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CANCEL_CHECKIN_TIP'); ?>"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CANCEL_CHECKIN'); ?></a>
			</span>
		</h1>
	</div><!--/page-header-->

	<!-- CHECK_OUT MESSAGE -->
	<?php if($this->item->checked_out): ?>
		<?php if(IRMSystem::getUserName($this->item->checked_out) == IRMSystem::getUserName(null)): ?>
			<div class="alert alert-info">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CHECKED_OUT_ALERT_INFO'); ?>
			</div>
		<?php else : ?>
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<?php echo JText::printf('COM_XIVEIRM_IRMMASTERDATA_FORM_CHECKED_OUT_ALERT_ERROR', '#', IRMSystem::getUserName($this->item->checked_out), $this->item->checked_out_time, '5'); // 1. Link zum Benutzer, 2. Name, 3. Checkedout time, 4. wann wieder verfügbar ?>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<!-- CHECK_OUT MESSAGE -->

	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
	<div class="tabbable">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> <?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TAB_BASICDATA'); ?></a></li>
			<li><a data-toggle="tab" href="#kv-data">KV-Daten</a></li>
			<li><a data-toggle="tab" href="#messages">Aufgaben <span class="badge badge-important">4</span></a></li>
	<!-- TAB.PLUGIN_BUTTON -->
			<?php
				foreach($dispatcher->trigger( 'loadTabButton', array() ) as $tabButton)
				{
					echo '<li><a data-toggle="tab" href="#' . $tabButton['tabId'] . '">';
					echo $tabButton['tabName'];
					echo '</a></li>';
				}
			?>
	<!-- TAB.PLUGIN_BUTTON -->
			<li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TAB_MORE'); ?> <b class="caret"></b></a>
				<ul class="dropdown-menu dropdown-info">
					<li><a data-toggle="tab" href="#dropdown1">@Anwendung 4</a></li>
					<li><a data-toggle="tab" href="#dropdown1">@Anwendung 5</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 6</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 7</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 8</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 9</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 10</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 11</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 12</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 13</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 14</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 15</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 16</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 17</a></li>
					<li><a data-toggle="tab" href="#">@Anwendung 18</a></li>
				</ul>
			</li>
		</ul>
		<div class="tab-content">
	<!-- BASE-DATA_TAB_CORE -->
			<div id="base-data" class="tab-pane active">
				<form id="form-irmmasterdata" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
					<div class="row-fluid">
					<div class="span7">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CUSTOMER_ID_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[customer_id]" class="span6" id="prependedInput" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CUSTOMER_ID'); ?>" value="<?php echo $this->item->customer_id; ?>">
								<?php if($this->item->modified): ?>
									<span class="visible-desktop span6 help-inline"><small><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_LAST_MODIFIED'); ?> <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($this->item->modified)); ?></small></span>
								<?php endif; ?>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_NAME_LABEL'); ?> <span class="help-button" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_NAME_HELP_TIP'); ?>">?</span></label>
							<div class="controls controls-row">
								<input type="text" class="span3" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_NAME_SALUTATION'); ?>" value="" disabled>
								<span class="visible-desktop span3 help-inline"></span>
								<input type="text" name="jform[title]" class="span3" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_NAME_TITLE'); ?>" value="<?php echo $this->item->title; ?>">
								<span class="visible-desktop span3 help-inline"><small><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_NAME_TITLE_DESC'); ?></small></span>
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[last_name]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_LAST_NAME'); ?>" value="<?php echo $this->item->last_name; ?>">
								<input type="text" name="jform[first_name]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_FIRST_NAME'); ?>" value="<?php echo $this->item->first_name; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_LABEL'); ?></label>
							<div class="controls controls-row">
								<select name="jform[gender]" class="span3" required>
									<option value=""<?php if(!$this->item->gender): echo ' selected'; endif; ?>><?php echo JText::_('COM_CIVEIRM_SELECT'); ?></option>
									<option value="u"<?php if($this->item->gender == 'u'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_GENDER_UNKNOWN'); ?></option>
									<option value="f"<?php if($this->item->gender == 'f'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_GENDER_FEMALE'); ?></option>
									<option value="m"<?php if($this->item->gender == 'm'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_GENDER_MALE'); ?></option>
									<option value="c"<?php if($this->item->gender == 'c'): echo ' selected'; endif; ?>><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_GENDER_COMPANY'); ?></option>
								</select>
								<span class="visible-desktop span3 help-inline"><small><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_GENDER_DESC'); ?></small></span>
								<input type="date" name="jform[dob]" class="span3" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_DOB'); ?>" value="<?php echo $this->item->dob; ?>" required>
								<span class="visible-desktop span3 help-inline"><small><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_TRAIT_DOB_DESC'); ?></small></span>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_LABEL'); ?></label>
							<div class="controls">
								<input type="text" name="jform[address_name]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_NAME'); ?>" maxlength="150" value="<?php echo $this->item->address_name; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_name_add]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_NAME_ADD'); ?>" maxlength="100" value="<?php echo $this->item->address_name_add; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_street]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_STREET'); ?>" maxlength="100" value="<?php echo $this->item->address_street; ?>">
								<input type="text" name="jform[address_houseno]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_HOUSENO'); ?>" maxlength="10" value="<?php echo $this->item->address_houseno; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_zip]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_ZIP'); ?>" maxlength="10" value="<?php echo $this->item->address_zip; ?>">
								<input type="text" name="jform[address_city]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_CITY'); ?>" maxlength="100" value="<?php echo $this->item->address_city; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_country]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_ADDRESS_COUNTRY'); ?>" value="<?php echo $this->item->address_country; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_PHONE_NUMBERS_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[phone]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_PHONE'); ?>" value="<?php echo $this->item->phone; ?>">
								<input type="text" name="jform[fax]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_FAX'); ?>" value="<?php echo $this->item->fax; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[mobile]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_MOBILE'); ?>" value="<?php echo $this->item->mobile; ?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_WEB_LABEL'); ?></label>
							<div class="controls controls-row">
								<input type="text" name="jform[email]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_EMAIL'); ?>" value="<?php echo $this->item->email; ?>">
								<input type="text" name="jform[web]" class="span6" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_WEB'); ?>" value="<?php echo $this->item->web; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_INTERNAL_REMARKS'); ?></label>
							<div class="controls">
								<textarea name="jform[remarks]" class="span12" rows="5" placeholder="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_INTERNAL_REMARKS_DESC'); ?>"><?php echo $this->item->remarks; ?></textarea>
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
						<input type="hidden" name="jform[trash]" value="<?php echo $this->item->trash; ?>" />
						<input type="hidden" name="option" value="com_xiveirm" />
						<input type="hidden" name="task" value="irmmasterdataform.save" />
						<?php echo JHtml::_('form.token'); ?>
					</div>
					<div class="span5">
						<div class="well">
							
						<!-- TAB.PLUGIN_MAIN-WIDGETS -->
						<?php
							foreach($dispatcher->trigger( 'loadInBasedataContainer', array() ) as $inBaseWidget)
							{
								echo '<div id="#' . $inBaseWidget['tabId'] . '">';
								echo $inBaseWidget['tabContent'];
								echo '</div>';
							}
						?>
						<!-- TAB.PLUGIN_MAIN-WIDGETS -->

						</div>
					</div>
				</div>
					<div class="form-actions">
						<button type="submit" class="validate btn btn-info" type="submit" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_SUBMIT_CHECKIN_TIP'); ?>"><i class="icon-ok"></i> <?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_SUBMIT_CHECKIN'); ?></button>
						&nbsp; &nbsp; &nbsp;
						<button class="btn" type="reset" data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_RESET_TIP'); ?>"><i class="icon-undo"></i> <?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_RESET'); ?></button>
						&nbsp; &nbsp; &nbsp;
						<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.cancel'); ?>"  data-rel="tooltip" data-original-title="<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CANCEL_CHECKIN_TIP'); ?>" class="btn btn-danger"><i class="icon-reply"></i> <?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_CANCEL_CHECKIN'); ?></a>
					</div>
				</form>
				
			</div>
	<!-- BASE-DATA_TAB_CORE -->
	<!-- KV-DATA_TAB_ADD -->
			<div id="kv-data" class="tab-pane">
				<p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.</p>
			</div>
	<!-- KV-DATA_TAB_ADD -->
			<div id="messages" class="tab-pane">
				<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
			</div>
			<div id="dropdown1" class="tab-pane">
				<p>Etsy mixtape wayfarers, ethical wes anderson tofu before they sold out mcsweeney's organic lomo retro fanny pack lo-fi farm-to-table readymade.</p>
			</div>
			<div id="dropdown2" class="tab-pane">
				<p>Trust fund seitan letterpress, keytar raw denim keffiyeh etsy art party before they sold out master cleanse gluten-free squid scenester freegan cosby sweater. Fanny pack portland seitan DIY, art party locavore wolf cliche high life echo park Austin.</p>
			</div>
	<!-- TAB.PLUGIN_CONTENT -->
			<?php
				foreach($dispatcher->trigger( 'loadTabContainer', array() ) as $tabContainer)
				{
					echo '<div id="' . $tabContainer['tabId'] . '" class="tab-pane">';
					echo $tabContainer['tabContent'];
					echo '</div>';
				}
			?>
	<!-- TAB.PLUGIN_CONTENT -->
		</div>
	</div>
	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
</div>




<textarea style="width: 100%; height: 250px;">
	<?php print_r($dispatcher->trigger( 'loadTabButton', array() )); ?>
</textarea>

<textarea style="width: 100%; height: 250px;">
	<?php print_r($dispatcher->trigger( 'loadTabContainer', array() )); ?>
</textarea>




