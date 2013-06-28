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

// Import HTML and Helper Classes
nawala_import('html.jshelper', 'once');
NHtmlJSHelper::autoRemove();
NHtmlJSHelper::setToggle('extended', 'toggleExtend');

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$letters_am = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
$letters_nz = array('N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$search = JFactory::getApplication()->input->get('filter_search', '', 'filter');
$global_search = JFactory::getApplication()->input->get('global_search', '', 'filter');
?>
<div class="row-fluid">
	<div class="header smaller lighter blue">
		<h1>
			<div>
				<i class="icon-group"></i>
			<?php if(!$search && !$global_search) { ?>
				<span class="hidden-phone hidden-tablet visible-desktop"><?php echo JText::_('COM_XIVEIRM_DES_CONTACT_LIST_CONTACTS_ALL'); ?></span>
				<span class="hidden-phone visible-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_TAB_CONTACT_LIST_CONTACTS_ALL'); ?></span>
				<span class="visible-phone hidden-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_PHO_CONTACT_LIST_CONTACTS_ALL'); ?></span>
			<?php } else if($search == '09' || in_array($search, $letters)) { ?>
				<span class="hidden-phone hidden-tablet visible-desktop"><?php echo JText::_('COM_XIVEIRM_DES_CONTACT_LIST_CONTACTS_STARTS'); ?></span>
				<span class="hidden-phone visible-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_TAB_CONTACT_LIST_CONTACTS_STARTS'); ?></span>
				<span class="visible-phone hidden-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_PHO_CONTACT_LIST_CONTACTS_STARTS'); ?></span>
				<?php echo $search; ?>
			<?php } else if($global_search) { ?>
				<span class="hidden-phone hidden-tablet visible-desktop"><?php echo JText::_('COM_XIVEIRM_DES_CONTACT_LIST_CONTACTS_LIKE'); ?></span>
				<span class="hidden-phone visible-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_TAB_CONTACT_LIST_CONTACTS_LIKE'); ?></span>
				<span class="visible-phone hidden-tablet hidden-desktop"><?php echo JText::_('COM_XIVEIRM_PHO_CONTACT_LIST_CONTACTS_LIKE'); ?></span>
				<?php echo $global_search; ?>
				<?php $addLastName = $global_search; ?>
			<?php } else { ?>
				<span class="hidden-phone hidden-tablet visible-desktop"></span>
				<span class="hidden-phone visible-tablet hidden-desktop"></span>
				<span class="visible-phone hidden-tablet hidden-desktop"></span>
				Please notify the Support about this issue! Code: 8001
			<?php } ?>
			</div>
		</h1>
	</div><!--/page-header-->

	<div class="row">
		<div class="pull-right">
			<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
				<form action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit'); ?>" class="inline">
					<select name="catid" class="chzn-select input-control" style="width: 362px;" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_NEW_CONTACT'); ?>" onchange="this.form.submit()">
						<option value=""></option>
						<?php
							$options = IRMSystem::getListOptions('categories', false);
							if($options->client) {
								echo '<optgroup label="' . JText::sprintf('COM_XIVEIRM_SELECT_CATEGORY_SPECIFIC', NFactory::getTitleById('usergroup', $xsession->client_id)) . '">';
									foreach ($options->client as $key => $val) {
										echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
									}
								echo '</optgroup>';
							}
							if($options->global) {
								echo '<optgroup label="' . JText::_('COM_XIVEIRM_SELECT_GLOBAL') . '">';
									foreach ($options->global as $key => $val) {
										echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
									}
								echo '</optgroup>';
							}
						?>
					</select>
					<input type="hidden" name="id" value="0">
				</form>
			<?php endif; ?>
			<div class="btn-group">
				<?php if($search || $global_search) { ?>
					<a href="javascript:document.getElementById('form-reset-search').submit()" class="btn btn-mini btn-primary"><i class="icon-undo"></i><span class="hidden-phone hidden-480"> Reset Search</span></a>
				<?php } ?>
				<a id="toggleExtend" class="btn btn-mini btn-primary inline"><i class="icon-double-angle-down"></i><span class="hidden-phone hidden-480"> More</span></a>
			</div>
			<form id="form-reset-search" method="post" class="inline form-validate" enctype="multipart/form-data">
				<input type="hidden" name="filter_search" value>
				<input type="hidden" name="global_search" value>
			</form>
		</div>
	</div>

	<div class="row center contact-categories extended" style="background-color: #eff3aa;">

		<p>
			<a href="#" class="btn btn-app radius-4">
				<i class="icon-cog"></i>
				Lieferanten
				<span class="badge badge-pink">+3</span>
			</a>
		
			<a href="#" class="btn btn-app btn-primary no-radius">
				<i class="icon-edit"></i>
				Edit
				<span class="badge badge-warning badge-right">11245</span>
			</a>
		
			<a href="#" class="btn btn-app btn-success">
				<i class="icon-refresh"></i>
				Reload
			</a>
		
			<button class="btn btn-app btn-warning">
				<i class="icon-undo"></i>
				Undo
			</button>

			<a href="#" class="btn btn-app btn-info btn-small no-radius">
				<i class="icon-envelope"></i>
				Mailbox
				<span class="label label-inverse arrowed-in">6+</span>
			</a>
		
			<button class="btn btn-app btn-danger btn-small">
				<i class="icon-trash"></i>
				Delete
			</button>
		
			<button class="btn btn-app btn-purple btn-small">
				<i class="icon-cloud-upload"></i>
				Upload
			</button>
		
			<button class="btn btn-app btn-pink btn-small">
				<i class="icon-share-alt"></i>
				Share
			</button>
		
			<button class="btn btn-app btn-inverse btn-mini">
				<i class="icon-lock"></i>
				Lock
			</button>
		
			<button class="btn btn-app btn-grey btn-mini radius-4">
				<i class="icon-save"></i>
				Patienten
				<span class="badge badge-transparent"><i class="light-red icon-asterisk"></i></span>
			</button>
		
			<button class="btn btn-app btn-light btn-mini">
				<i class="icon-print"></i>
				Subunternehmer
			</button>

			<a href="#" class="btn btn-app btn-yellow btn-mini">
				<i class="icon-shopping-cart"></i>
				Lieferanten
			</a>
		</p>
	</div>

	<div class="center hidden-phone extended" style="background-color: #eff3f8;">
		<div class="btn-group">
			<?php
				// HIER DB ABFRAGE ALLER NAMEN MIT COUNT UND SO, AM BESTEN EIN NAWALA CLASS REIN, DIE ALLES ZÄHLT UND AUSWERTET ANHAND EINES TABELLENNAMENS UND DES FELDES DAS AUSGEWERTET WERDEN SOLL !!!!! HEISST DANN NAlphaIndex::buildIndex('tabellen_name', 'spalten_name') // Berechnung mit suchparameter, alle nachnamen beginnend mit BUCHSTABE !!!!!!!!!!!!!!!!!!!!!!
			?>
			<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&filter_search='); ?>" class="btn btn-small <?php if($search == '') { echo 'active'; } ?>" data-rel="tooltip" title data-original-title="All results">All</a>
			<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&filter_search=09'); ?>" class="btn btn-small <?php if($search == '09') { echo 'active'; } ?>" data-rel="tooltip" title data-original-title="Names starts with a special chars">#</a>
		</div>
		<div class="btn-group">
			<?php foreach($letters_am as $letter) { ?>
				<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&filter_search=' . $letter); ?>" class="btn btn-small <?php if($search == $letter) { echo 'active'; } ?>" data-rel="tooltip" title data-original-title="Names starts with"><?php echo $letter; ?></a>
			<?php } ?>
		</div>
		<div class="btn-group">
			<?php foreach($letters_nz as $letter) { ?>
				<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&filter_search=' . $letter); ?>" class="btn btn-small <?php if($search == $letter) { echo 'active'; } ?>" data-rel="tooltip" title data-original-title="Names starts with"><?php echo $letter; ?></a>
			<?php } ?>
		</div>
	</div>

	<div id="table_report_wrapper" class="dataTables_wrapper" role="grid">
		<table id="table_report" class="table table-striped table-bordered table-hover dataTable" aria-describedby="table_report_info">
			<thead>
				<tr role="row">
					<th class="center sorting_disabled" role="columnheader" rowspan="1" colspan="1" aria-label="">
						<label><input type="checkbox"><span class="lbl"></span></label>
					</th>
					<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label="Customer ID: activate to sort column ascending">
						ID
					</th>
					<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label="Name: activate to sort column ascending">
						<i class="icon-user"></i><span class="hidden-phone"> Name</span>
					</th>
					<th class="sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label="Date of Birth: activate to sort column ascending">
						<i class="icon-calendar"></i><span class="hidden-phone"> Date of Birth</span>
					</th>
					<th class="hidden-480 sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label="Clicks: activate to sort column ascending">
						<i class="icon-home"></i><span class="hidden-phone"> Address</span>
					</th>
					<th class="hidden-phone sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label=" Update: activate to sort column ascending">
						<i class="icon-phone"></i><span class="hidden-phone"> Phone</span>
					</th>
					<th class="hidden-480 sorting" role="columnheader" tabindex="0" aria-controls="table_report" rowspan="1" colspan="1" aria-label="Status: activate to sort column ascending">
						Status
					</th>
					<th class="sorting_disabled" role="columnheader" rowspan="1" colspan="1" style="width: 100px;" aria-label="">
					</th>
				</tr>
			</thead>
			<tbody role="alert" aria-live="polite" aria-relevant="all">
			<?php $show = false; ?>
			<?php foreach ($this->items as $item) : ?>
				<?php if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xiveirm'))) : $show = true; ?>
				<tr>
					<td class="center">
						<?php if($item->checked_out):
							echo '<div style="font-size: 20px;"><i class="icon-lock red" data-rel="tooltip" data-placement="right" data-original-title="Checked out by: ' . JFactory::getUser($item->checked_out)->name . ' on ' . $item->checked_out_time . '"></i></div>';
						else:
							echo '<label><input type="checkbox"><span class="lbl"></span></label>';
						endif; ?>
					</td>

					<td class=" ">
						<span><?php if((int) $item->customer_id): echo '<i class="icon-barcode"></i> ' . $item->customer_id; elseif(!(int)  $item->customer_id): echo '<i class="icon-qrcode"></i> ' . $item->customer_id; else: echo '<i class="icon-code-fork"></i> ' . $item->id; endif; ?></span>
					</td>

					<td class=" ">
						<a href="<?php echo JRoute::_('index.php?task=contactform.edit&id='.$item->id); ?>">
							<?php 
								if(!empty($item->company_name)) {
									echo $item->company_name . '<br>';
								} else {
								}
								if(!empty($item->last_name) && !empty($item->first_name)) {
									echo $item->last_name . ', ' . $item->first_name;
								} else {
									echo !empty($item->last_name) ? $item->last_name : '';
									echo !empty($item->first_name) ? $item->first_name : '';
								}
							?>
						</a>
					</td>
					<td class=" "><?php if($item->gender == 'm'): echo '<i class="icon-user blue"></i>'; elseif($item->gender == 'f'): echo '<i class="icon-user red"></i>'; elseif($item->gender == 'c'): echo'<i class="icon-user green"></i>'; else: echo '<i class="icon-user"></i>'; endif; ?> <?php if(strtotime($item->dob) != -62135600400): echo date(JText::_('DATE_FORMAT_LC4'), strtotime($item->dob)); endif; ?></td>
					<td class="hidden-480"><?php echo $item->address_street; ?> <?php echo $item->address_houseno; ?><br><?php echo $item->address_zip; ?> <?php echo $item->address_city; ?>, <?php echo $item->address_country; ?></td>
					<td class="hidden-phone "><?php if($item->mobile != ''): echo '<i class="icon-mobile-phone"></i> ' . $item->mobile . '<br>'; endif; if($item->phone != ''): echo '<i class="icon-phone"></i> ' . $item->phone; endif; ?></td>
					<td class="hidden-480">
						<?php if(strtotime($item->modified) >= (time() - 86400)): ?>
							<span class="label label-warning" data-rel="tooltip" data-original-title="<?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?>"><i class="icon-time"></i> Modified <abbr class="timeago" data-time="<?php echo $item->modified; ?>"></abbr></span>
						<?php endif; ?>
						<?php if($item->remarks): echo ' <span class="label label-info" data-rel="tooltip" data-placement="left" data-original-title="' . $item->remarks . '"><i class="icon-comment-alt"></i> Intern</span>'; endif; ?>
					</td>
					<td class="">
						<div class="hidden-phone visible-desktop btn-group">
							<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.state&id=' . $item->id); ?>').submit()" class="btn btn-mini <?php if($item->state == 1): echo 'btn-success'; endif; ?>" title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>"><?php if($item->state == 1): echo '<i class="icon-ok"></i>'; else: echo '<i class="icon-remove"></i>'; endif; ?></a>
							<?php endif; ?>
							<?php if(JFactory::getUser()->authorise('core.delete','com_xiveirm')) : ?>
								<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.remove&id=' . $item->id); ?>').submit()" class="btn btn-mini btn-danger" title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>"><i class="icon-trash"></i></a>
							<?php endif; ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit&id=' . $item->id); ?>" class="btn btn-mini btn-info"><i class="icon-edit"></i></a>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.flag&id=' . $item->id); ?>" class="btn btn-mini <?php if(IRMSystem::flagIt($item->id, 'check')) { echo 'btn-warning'; } ?>"><i class="icon-flag"></i></a>
						</div>
						<div class="hidden-desktop visible-phone">
							<div class="inline position-relative">
								<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-caret-down icon-only"></i></button>
								<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
									<li><a class="<?php if($item->state == 1): echo 'tooltip-success'; endif; ?>" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>" href="javascript:document.getElementById('form-irmcustomer-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo '<span class="green"><i class="icon-ok"></i></span>'; else: echo '<span class="gray"><i class="icon-remove"></i></span>'; endif; ?></a></li>
									<li><a class="tooltip-error" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>" href="javascript:document.getElementById('form-irmcustomer-delete-<?php echo $item->id; ?>').submit()"><span class="red"><i class="icon-trash"></i></span></a></li>
								</ul>
							</div>
						</div>
					</td>
				</tr>
			<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<div class="row-fluid center legend">
		<i class="icon-barcode" data-rel="tooltip" data-original-title="This is a numeric ID"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-qrcode" data-rel="tooltip" data-original-title="This is an alphanumeric ID"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-code-fork" data-rel="tooltip" data-original-title="This is a system ID"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-user" data-rel="tooltip" data-original-title="The gender is unknown"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-user red" data-rel="tooltip" data-original-title="This is a female gender"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-user blue" data-rel="tooltip" data-original-title="This is a male gender"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-user green" data-rel="tooltip" data-original-title="No gender, this is a company. Set the date of birth to 01.01.0001 (Tip: Hit 8 times key 0 in chrome)"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-comment-alt" data-rel="tooltip" data-original-title="Internal remarks the customer won't see, ever!"></i>
	</div>
</div>



<!--
<div class="items">
	<ul class="items_list">
		<?php $show = false; ?>
		<?php foreach ($this->items as $item) : ?>
			<?php if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xiveirm'))): $show = true; ?>
				<li>
					<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&view=contact&id=' . (int)$item->id); ?>"><?php echo $item->customer_id; ?></a>
					<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')): ?>
						<a href="javascript:document.getElementById('form-contact-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?></a>
					<?php endif; ?>
					<?php if(JFactory::getUser()->authorise('core.delete','com_xiveirm')): ?>
						<a href="javascript:document.getElementById('form-contact-delete-<?php echo $item->id; ?>').submit()"><?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?></a>
					<?php endif; ?>
				</li>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php
		if (!$show):
			echo JText::_('COM_XIVEIRM_NO_ITEMS');
		endif;
	?>
	</ul>
</div>
<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
	<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contact.edit&id=0'); ?>"><?php echo JText::_("COM_XIVEIRM_ADD_ITEM"); ?></a>
<?php endif; ?>
-->