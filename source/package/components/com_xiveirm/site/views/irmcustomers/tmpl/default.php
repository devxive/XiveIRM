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

$letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$letters_am = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M');
$letters_nz = array('N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

$search = JFactory::getApplication()->input->get('filter_search', '', 'filter');
?>
<div class="row-fluid">
	<div class="row-fluid header smaller lighter blue">
		<h1>
			<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
				<span class="span7">
			<?php endif; ?>
				<i class="icon-group"></i>
				<?php if($search == '') { ?>
					List of all Customers in Database
				<?php } else if($search == '09' || in_array($search, $letters)) { ?>
					List of all Customers that last name starts with: <?php echo $search; ?>
				<?php } else { ?>
					List of all Customers named like: <?php echo $search; ?>
					<?php $addLastName = $search; ?>
				<?php } ?>
			<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
				</span>
				<span class="span5">
					<span class="pull-right">
						<a class="btn btn-small btn-primary pull-right inline" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.edit&id=0'); ?>" data-rel="tooltip" data-placement="left" title data-original-title="<?php echo JText::_("COM_XIVEIRM_ADD_ITEM"); if(isset($addLastName)) { echo ' ' . $addLastName; } ?>"><i class="icon-plus"></i></a>
					</span>
				</span>
			<?php endif; ?>
		</h1>
	</div><!--/page-header-->

	<div class="center hidden-phone" style="background-color: #eff3f8;">
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
						<label><input type="checkbox"><span class="lbl"></span></label>
					</td>

					<td class=" ">
						<span><?php if((int) $item->customer_id): echo '<i class="icon-barcode"></i> ' . $item->customer_id; elseif(!(int)  $item->customer_id): echo '<i class="icon-qrcode"></i> ' . $item->customer_id; else: echo '<i class="icon-code-fork"></i> ' . $item->id; endif; ?></span>
						<?php if($item->checked_out):
							echo ' <i class="icon-lock red" data-rel="tooltip" data-placement="right" data-original-title="Checked out by: ' . IRMSystem::getUserName($item->checked_out) . ' on ' . $item->checked_out_time . '"></i>';
						endif; ?>
					</td>

					<td class=" ">
						<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.edit&id='.$item->id); ?>">
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
					<td class="hidden-480 "><?php echo $item->address_street; ?> <?php echo $item->address_houseno; ?><br><?php echo $item->address_zip; ?> <?php echo $item->address_city; ?>, <?php echo $item->address_country; ?></td>
					<td class="hidden-phone "><?php if($item->mobile != ''): echo '<i class="icon-mobile-phone"></i> ' . $item->mobile . '<br>'; endif; if($item->phone != ''): echo '<i class="icon-phone"></i> ' . $item->phone; endif; ?></td>
					<td class="hidden-480 ">
						<?php if(strtotime($item->modified) >= (time() - 86400)): ?>
							<span class="label label-warning" data-rel="tooltip" data-original-title="<?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?>"><i class="icon-time"></i> Modified <abbr class="timeago" data-time="<?php echo $item->modified; ?>"></abbr></span>
						<?php endif; ?>
						<?php if($item->remarks): echo ' <span class="label label-info" data-rel="tooltip" data-placement="left" data-original-title="' . $item->remarks . '"><i class="icon-comment-alt"></i> Intern</span>'; endif; ?>
					</td>
					<td class=" ">
						<div class="hidden-phone visible-desktop btn-group">
							<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')) : ?>
								<a class="btn btn-mini <?php if($item->state == 1): echo 'btn-success'; endif; ?>" title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>" href="javascript:document.getElementById('form-irmcustomer-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo '<i class="icon-ok"></i>'; else: echo '<i class="icon-remove"></i>'; endif; ?></a>
							<?php endif; if(JFactory::getUser()->authorise('core.delete','com_xiveirm')) : ?>
								<a class="btn btn-mini btn-danger" title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>" href="javascript:document.getElementById('form-irmcustomer-delete-<?php echo $item->id; ?>').submit()"><i class="icon-trash"></i></a>
							<?php endif; ?>
							<button class="btn btn-mini btn-info"><i class="icon-edit"></i></button>
							<button class="btn btn-mini btn-warning"><i class="icon-flag"></i></button>
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
							<form id="form-irmcustomer-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
								<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
								<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
								<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
								<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
								<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
								<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
								<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
								<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
								<input type="hidden" name="jform[customer_id]" value="<?php echo $item->customer_id; ?>" />
								<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
								<input type="hidden" name="jform[last_name]" value="<?php echo $item->last_name; ?>" />
								<input type="hidden" name="jform[first_name]" value="<?php echo $item->first_name; ?>" />
								<input type="hidden" name="jform[gender]" value="<?php echo $item->gender; ?>" />
								<input type="hidden" name="jform[dob]" value="<?php echo $item->dob; ?>" />
								<input type="hidden" name="jform[address_name]" value="<?php echo $item->address_name; ?>" />
								<input type="hidden" name="jform[address_name_add]" value="<?php echo $item->address_name_add; ?>" />
								<input type="hidden" name="jform[address_street]" value="<?php echo $item->address_street; ?>" />
								<input type="hidden" name="jform[address_houseno]" value="<?php echo $item->address_houseno; ?>" />
								<input type="hidden" name="jform[address_zip]" value="<?php echo $item->address_zip; ?>" />
								<input type="hidden" name="jform[address_city]" value="<?php echo $item->address_city; ?>" />
								<input type="hidden" name="jform[address_country]" value="<?php echo $item->address_country; ?>" />
								<input type="hidden" name="jform[phone]" value="<?php echo $item->phone; ?>" />
								<input type="hidden" name="jform[fax]" value="<?php echo $item->fax; ?>" />
								<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
								<input type="hidden" name="jform[email]" value="<?php echo $item->email; ?>" />
								<input type="hidden" name="jform[web]" value="<?php echo $item->web; ?>" />
								<input type="hidden" name="jform[remarks]" value="<?php echo $item->remarks; ?>" />
								<input type="hidden" name="option" value="com_xiveirm" />
								<input type="hidden" name="task" value="irmcustomer.save" />
								<?php echo JHtml::_('form.token'); ?>
							</form>
							<form id="form-irmcustomer-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmcustomer.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
								<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
								<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
								<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
								<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
								<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
								<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
								<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
								<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
								<input type="hidden" name="jform[customer_id]" value="<?php echo $item->customer_id; ?>" />
								<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
								<input type="hidden" name="jform[last_name]" value="<?php echo $item->last_name; ?>" />
								<input type="hidden" name="jform[first_name]" value="<?php echo $item->first_name; ?>" />
								<input type="hidden" name="jform[gender]" value="<?php echo $item->gender; ?>" />
								<input type="hidden" name="jform[dob]" value="<?php echo $item->dob; ?>" />
								<input type="hidden" name="jform[address_name]" value="<?php echo $item->address_name; ?>" />
								<input type="hidden" name="jform[address_name_add]" value="<?php echo $item->address_name_add; ?>" />
								<input type="hidden" name="jform[address_street]" value="<?php echo $item->address_street; ?>" />
								<input type="hidden" name="jform[address_houseno]" value="<?php echo $item->address_houseno; ?>" />
								<input type="hidden" name="jform[address_zip]" value="<?php echo $item->address_zip; ?>" />
								<input type="hidden" name="jform[address_city]" value="<?php echo $item->address_city; ?>" />
								<input type="hidden" name="jform[address_country]" value="<?php echo $item->address_country; ?>" />
								<input type="hidden" name="jform[phone]" value="<?php echo $item->phone; ?>" />
								<input type="hidden" name="jform[fax]" value="<?php echo $item->fax; ?>" />
								<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
								<input type="hidden" name="jform[email]" value="<?php echo $item->email; ?>" />
								<input type="hidden" name="jform[web]" value="<?php echo $item->web; ?>" />
								<input type="hidden" name="jform[remarks]" value="<?php echo $item->remarks; ?>" />
								<input type="hidden" name="option" value="com_xiveirm" />
								<input type="hidden" name="task" value="irmcustomer.remove" />
								<?php echo JHtml::_('form.token'); ?>
							</form>
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