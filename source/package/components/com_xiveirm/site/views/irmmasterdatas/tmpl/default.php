<?php
/**
 * @version     3.0.2
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

$jinput = JFactory::getApplication()->input;
$search = $jinput->get('filter_search', '', 'filter');
?>
<div class="row-fluid">
	<div class="row-fluid">
		<h3 class="header smaller lighter blue">
			<?php if($search == '') { ?>
				List of all Customers in Database
			<?php } else if($search == '09' || in_array($search, $letters)) { ?>
				List of all Customers that last name starts with: <?php echo $search; ?>
			<?php } else { ?>
				List of all Customers named like: <?php echo $search; ?>
				<?php $addLastName = $search; ?>
			<?php } ?>
			<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
				<span class="pull-right">
					<a class="btn btn-small btn-primary" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.edit&id=0'); ?>" data-rel="tooltip" data-placement="left" title data-original-title="<?php echo JText::_("COM_XIVEIRM_ADD_ITEM"); if(isset($addLastName)) { echo ' ' . $addLastName; } ?>"><i class="icon-plus"></i></a>
				</span>
			<?php endif; ?>
		</h3>
	</div>
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
						<i class="icon-barcode"></i><span class="hidden-phone"> ID</span>
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
					<td class=" "><span data-rel="tooltip" data-placement="right" data-original-title="Last modified: <?php echo $item->modified; ?>"><?php echo $item->customer_id; ?></span> <?php if($item->checked_out): echo '<i class="icon-lock" data-rel="tooltip" data-placement="right" data-original-title="Checked out by: ' . $item->checked_out_by . '\n on' . $item->checked_out_time . '"></i>'; endif; ?></td>
					<td class=" "><a href="<?php echo JRoute::_('index.php?option=com_xiveirm&view=irmmasterdata&id=' . (int)$item->id); ?>"><?php echo $item->last_name . ', ' . $item->first_name; ?></a></td>
					<td class=" "><?php if($item->gender == 'm'): echo '<i class="icon-user blue"></i>'; elseif($item->gender == 'f'): echo '<i class="icon-user red"></i>'; elseif($item->gender == 'c'): echo'<i class="icon-user green"></i>'; else: echo '<i class="icon-user"></i>'; endif; ?> <?php echo $item->dob; ?></td>
					<td class="hidden-480 "><?php echo $item->address_street; ?> <?php echo $item->address_houseno; ?><br><?php echo $item->address_zip; ?> <?php echo $item->address_city; ?>, <?php echo $item->address_country; ?></td>
					<td class="hidden-phone "><?php if($item->mobile != ''): echo '<i class="icon-mobile-phone"></i> ' . $item->mobile; endif; if($item->phone != ''): echo '<i class="icon-phone"></i> ' . $item->phone; endif; ?></td>
					<td class="hidden-480 "><span class="label label-warning">NOSTATUS</span> <?php if($item->remarks): echo '<i class="icon-comment-alt" data-rel="tooltip" data-placement="left" data-original-title="' . $item->remarks . '">'; endif; ?></td>
					<td class=" ">
						<div class="hidden-phone visible-desktop btn-group">
							<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')) : ?>
								<a class="btn btn-mini <?php if($item->state == 1): echo 'btn-success'; endif; ?>" title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>" href="javascript:document.getElementById('form-irmmasterdata-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo '<i class="icon-ok"></i>'; else: echo '<i class="icon-remove"></i>'; endif; ?></a>
							<?php endif; if(JFactory::getUser()->authorise('core.delete','com_xiveirm')) : ?>
								<a class="btn btn-mini btn-danger" title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>" href="javascript:document.getElementById('form-irmmasterdata-delete-<?php echo $item->id; ?>').submit()"><i class="icon-trash"></i></a>
							<?php endif; ?>
							<button class="btn btn-mini btn-info"><i class="icon-edit"></i></button>
							<button class="btn btn-mini btn-warning"><i class="icon-flag"></i></button>
						</div>
						<div class="hidden-desktop visible-phone">
							<div class="inline position-relative">
								<button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-caret-down icon-only"></i></button>
								<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
									<li><a class="<?php if($item->state == 1): echo 'tooltip-success'; endif; ?>" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>" href="javascript:document.getElementById('form-irmmasterdata-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo '<span class="green"><i class="icon-ok"></i></span>'; else: echo '<span class="gray"><i class="icon-remove"></i></span>'; endif; ?></a></li>
									<li><a class="tooltip-error" data-rel="tooltip" title="" data-placement="left" data-original-title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>" href="javascript:document.getElementById('form-irmmasterdata-delete-<?php echo $item->id; ?>').submit()"><span class="red"><i class="icon-trash"></i></span></a></li>
								</ul>
							</div>
						</div>
							<form id="form-irmmasterdata-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
								<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
								<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
								<input type="hidden" name="option" value="com_xiveirm" />
								<input type="hidden" name="task" value="irmmasterdata.save" />
								<?php echo JHtml::_('form.token'); ?>
							</form>
							<form id="form-irmmasterdata-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
								<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
								<input type="hidden" name="option" value="com_xiveirm" />
								<input type="hidden" name="task" value="irmmasterdata.remove" />
								<?php echo JHtml::_('form.token'); ?>
							</form>
					</td>
				</tr>
			<?php endif; ?>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>



								<?php echo $item->checked_out; ?>
								<?php echo $item->checked_out_time; ?>
								<?php echo $item->checked_out_by; ?>
								<?php echo $item->modified; ?>
								<?php echo $item->customer_id; ?>
								<?php echo $item->remarks; ?>


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


									<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?><a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=irmmasterdata.edit&id=0'); ?>"><?php echo JText::_("COM_XIVEIRM_ADD_ITEM"); ?></a>
	<?php endif; ?>