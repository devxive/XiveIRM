<?php
/**
 * @version     3.0.4
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
					Neuen Kontakt erfassen
				<?php endif; ?>
			</span>
			<span class="span5">
				<a class="btn btn-danger btn-mini pull-right inline" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.cancel'); ?>" data-rel="tooltip" data-original-title="<?php echo JText::_('JCANCEL'); ?>"><i class="icon-reply"></i> <?php echo JText::_('JCANCEL'); ?></a>
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
				<h1><i class="icon-signout"></i> Sie haben diesen Kontakt ausgecheckt!</h1>
				<p>Andere Benutzer sehen, dass Sie diesen Kontakt gerade bearbeiten und erhalten einen entsprechenden Hinweis.</p>
				<p>Bitte beachten Sie auch die folgenden Hinweise:</p>
				<ul>
					<li>Das "CICA-System" <i>(Check-In, Check-Out)</i> weist Sie auf eine laufende bearbeitung hin.</li>
					<li>Eine gleichzeitige Bearbeitung ist m&ouml;glich, kann jedoch zu inkonsistenten Datens&auml;tzen f&uuml;hren</li>
					<li>Verlassen Sie diese Kontakt bitte "IMMER" &uuml;ber die entsprechenden Schaltfl&auml;chen! (Speichern, Abbrechen, etc...)</li>
					<li>Eine automatische Freigabe des Datensatzes erfolgt automatisch, wenn innerhalb von 10 Minuten keine Eingabe erfolgt!</li>
				</ul>
			</div>
		<?php else : ?>
			<div class="alert alert-error">
				<button type="button" class="close" data-dismiss="alert">
					<i class="icon-remove"></i>
				</button>
				<h1><i class="icon-signout"></i> Achtung, dieser Kontakt wurde von einem anderen Benutzer ausgecheckt!</h1>
				<p>Was ist passiert?
					<ul>
						<li>Ein anderer Benutzer bearbeitet gerade diesen Kontakt!</li>
					</ul>
				</p>
				<p>Was kann ich tun?
					<ul>
						<li>Klicken Sie auf Abbrechen um m&ouml;gliche Dateninkonsistenzen zu vermeiden!</li>
						<li>Kontaktieren Sie anschlie&szlig;end die entsprechende Person/Benutzer (siehe unten) oder versuchen es sp&auml;ter erneut.</li>
					</ul>
					TIPP: Wenn ein Kontakt ausgescheckt ist, erkennen Sie das am roten Schloss-Symbol <i class="icon-lock red"></i> in der Tabellen&uuml;bersicht!
				</p>
				<p>
					SYSLOG: Der Benutzer <a href="#"><?php echo IRMSystem::getUserName($this->item->checked_out); ?></a>
					hat diesen Kontakt am <?php echo $this->item->checked_out_time; ?> ausgecheckt. Eine voraussichtliche Bearbeitung ist in ca. 10 Minuten m&ouml;glich.
				</p>
			</div>
		<?php endif; ?>
	<?php endif; ?>
	<!-- CHECK_OUT MESSAGE -->

	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
	<div class="tabbable">
		<ul class="nav nav-tabs" id="myTab">
			<li class="active"><a data-toggle="tab" href="#base-data"><i class="green icon-home bigger-110"></i> Stammdaten</a></li>
			<li><a data-toggle="tab" href="#kv-data">KV-Daten</a></li>
			<li><a data-toggle="tab" href="#messages">Aufgaben <span class="badge badge-important">4</span></a></li>
			<li class="dropdown">
				<a data-toggle="dropdown" class="dropdown-toggle" href="#">Dropdown <b class="caret"></b></a>
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
				<div class="span7">










					<form id="form-irmmasterdata" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
						<div class="control-group">
							<label class="control-label">Kundennummer</label>
							<div class="controls controls-row">
								<input type="text" name="jform[customer_id]" class="span6" id="prependedInput" placeholder="Customer ID" value="<?php echo $this->item->customer_id; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Name</label>
							<div class="controls controls-row">
								<input type="text" class="span3" placeholder="Salutation" title="Depends on selected gender" value="" disabled>
								<span class="visible-desktop span3 help-inline"><small>Depends on selected gender</small></span>
								<input type="text" name="jform[title]" class="span3" placeholder="Title" value="<?php echo $this->item->title; ?>">
								<span class="visible-desktop span3 help-inline"><small>Academic title</small></span>
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[last_name]" class="span6" placeholder="Last Name" value="<?php echo $this->item->last_name; ?>">
								<input type="text" name="jform[first_name]" class="span6" placeholder="First Name" value="<?php echo $this->item->first_name; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Merkmale</label>
							<div class="controls controls-row">
								<select name="jform[gender]" class="span3" required>
									<option value=""<?php if(!$this->item->gender): echo ' selected'; endif; ?>>- Select the Gender -</option>
									<option value="u"<?php if($this->item->gender == 'u'): echo ' selected'; endif; ?>>Unknown</option>
									<option value="f"<?php if($this->item->gender == 'f'): echo ' selected'; endif; ?>>Female</option>
									<option value="m"<?php if($this->item->gender == 'm'): echo ' selected'; endif; ?>>Male</option>
									<option value="c"<?php if($this->item->gender == 'c'): echo ' selected'; endif; ?>>Company</option>
								</select>
								<span class="visible-desktop span3 help-inline"><small>The gender will taken affect on varius things</small></span>
								<input type="date" name="jform[dob]" class="span3" placeholder="Date of Birth" value="<?php echo $this->item->dob; ?>" required>
								<span class="visible-desktop span3 help-inline"><small>Date of Birth</small></span>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Adresse</label>
							<div class="controls">
								<input type="text" name="jform[address_name]" class="span6" placeholder="Name of an intitution, hospital, etc... that describes the address" maxlength="150" value="<?php echo $this->item->address_name; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_name_add]" class="span6" placeholder="Additional address info, such as: Entry thru the backdoor" maxlength="100" value="<?php echo $this->item->address_name_add; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_street]" class="span6" placeholder="Name of the street" maxlength="100" value="<?php echo $this->item->address_street; ?>">
								<input type="text" name="jform[address_houseno]" class="span6" placeholder="House No" maxlength="10" value="<?php echo $this->item->address_houseno; ?>">
							</div>
							<div class="controls controls-row">
								<input type="text" name="jform[address_zip]" class="span6" placeholder="ZIP-Code" maxlength="10" value="<?php echo $this->item->address_zip; ?>">
								<input type="text" name="jform[address_city]" class="span6" placeholder="Name of the city" maxlength="100" value="<?php echo $this->item->address_city; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[address_country]" class="span6" placeholder="Name of the country" value="<?php echo $this->item->address_country; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Telefon</label>
							<div class="controls controls-row">
								<input type="text" name="jform[phone]" class="span6" placeholder="Phone" value="<?php echo $this->item->phone; ?>">
								<input type="text" name="jform[fax]" class="span6" placeholder="Fax" value="<?php echo $this->item->fax; ?>">
							</div>
							<div class="controls">
								<input type="text" name="jform[mobile]" class="span6" placeholder="Mobile" value="<?php echo $this->item->mobile; ?>">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label">Internet</label>
							<div class="controls controls-row">
								<input type="text" name="jform[email]" class="span6" placeholder="eMail-Address" value="<?php echo $this->item->email; ?>">
								<input type="text" name="jform[web]" class="span6" placeholder="Link to a social profile or a website" value="<?php echo $this->item->web; ?>">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">Bemerkungen</label>
							<div class="controls">
								<textarea name="jform[remarks]" class="span12" rows="5" placeholder="Type in your text here"><?php echo $this->item->remarks; ?></textarea>
							</div>
						</div>
						
						<div class="form-actions">
							<button type="submit" class="validate btn btn-info" type="submit"><i class="icon-ok"></i> <?php echo JText::_('JSUBMIT'); ?></button>
							&nbsp; &nbsp; &nbsp;
							<button class="btn" type="reset"><i class="icon-undo"></i> Reset</button>
							&nbsp; &nbsp; &nbsp;
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>" class="btn btn-danger"><i class="icon-reply"></i> <?php echo JText::_('JCANCEL'); ?></a>
						</div>
						
						<div class="hr"></div>

<?php echo $this->form->getInput('id'); ?>
<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
<?php echo $this->form->getInput('created'); ?>
<?php if(empty($this->item->created_by)){ ?>
	<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />
<?php } else { ?>
	<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
<?php } ?>
<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
<?php echo $this->form->getInput('modified'); ?>
<input type="hidden" name="jform[trash]" value="<?php echo $this->item->trash; ?>" />
<?php echo $this->form->getInput('client_id'); ?>
<input type="hidden" name="option" value="com_xiveirm" />
<input type="hidden" name="task" value="irmmasterdataform.save" />
<?php echo JHtml::_('form.token'); ?>







					</form>







				</div>
				<div class="span5"><div class="well">RECHTS</div></div>
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
		</div>
	</div>
	<!-- MASTER_TAP_PANE_PLUGINSTYLED -->
</div>











