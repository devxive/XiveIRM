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

$app = JFactory::getApplication();

IRMComponentHelper::loadLanguage();

NFWHtmlJavascript::setAutoRemove();
NFWHtmlJavascript::setToggle('extended', 'toggleExtend');
NFWHtmlJavascript::setTooltip('.xtooltip');
NFWHtmlJavascript::setPopover('.xpopover');
NFWHtmlJavascript::loadMoment();
NFWHtmlJavascript::loadDateRangePicker();
IRMHtmlSelect2::init('.select2');

// Init the dataTable
$tableParams = '{
	"bProcessing": true,
	"bAutoWidth": false,
	"bLengthChange": true,
	"bStateSave": false,
	"aaSorting": [[4,"asc"]],
	"sPaginationType": "bootstrap",
	"aoColumnDefs": [
		{ "bSortable": false, "aTargets": [0] },
		{ "bSortable": false, "aTargets": [1] },
		{ "bSortable": false, "aTargets": [7] },
		{ "bSortable": false, "aTargets": [8] },
		{ "bSearchable": false, "aTargets": [0] },
		{ "bSearchable": false, "aTargets": [1] },
		{ "bSearchable": false, "aTargets": [7] },
		{ "bSearchable": false, "aTargets": [8] }
	],
	"oLanguage": {
		"sLengthMenu": \'Zeige <select>\'+
			\'<option vlaue="10">10</option>\'+
			\'<option vlaue="25">25</option>\'+
			\'<option vlaue="50">50</option>\'+
			\'<option vlaue="100">100</option>\'+
			\'<option vlaue="1">1 (Testzwecke)</option>\'+
			\'<option vlaue="-1">Alle (Achtung!)</option>\'+
			\'</select> Eintraege\'
	},
	"sDom": "<\'row-fluid\'<\'span6\'T<\'#dt-table_category_filter\'>l><\'span6\'f>r>t<\'row-fluid\'<\'span6\'i><\'span6\'p>>"
}
';
NFWHtmlDatatables::loadDataTable('table_contacts', $tableParams, true);


// TODO: CUSTOM DATEPICKER => Build a function/helper method for the daterangepicker
	JFactory::getDocument()->addScriptDeclaration("
		jQuery(document).ready(function() {
			// Set Variables
			var dateObject            = {};

			    dateObject.cDate          = new Date();
			    dateObject.cDay           = dateObject.cDate.getDate();
			    dateObject.cMonth         = dateObject.cDate.getMonth();
			    dateObject.cYear          = dateObject.cDate.getFullYear();
			    dateObject.cMonthFirstDay = new Date( dateObject.cYear, dateObject.cMonth, 1 ).getDate();
			    dateObject.cMonthLastDay  = new Date( dateObject.cYear, dateObject.cMonth + 1, 0 ).getDate();

			$('#daterangepicker').daterangepicker(
				{
					ranges: {
						'Today': [new Date(), new Date()],
						'Tomorrow': [moment().add('days', 1), moment().add('days', 1)],
						'Last 7 Days': [moment().subtract('days', 6), new Date()],
						'Last 30 Days': [moment().subtract('days', 29), new Date()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
					},
					startDate: moment().subtract('days', 29),
					endDate: new Date()
				},
				function(start, end) {
					var startDate = start.format('YYYY.MM.D H:m:s'),
					    endDate   = end.format('YYYY.MM.D H:m:s'),
					    startX    = moment( startDate ).format('X'),
					    endX      = moment( endDate ).format('X');

					var dateArray = [startX, endX];

					$('input[name=\"search_daterange\"').val( JSON.stringify(dateArray) );
					$('#daterangepicker span').html( start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY') );
				}
			);
		});\n"
	);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Check for filters
$filter = $app->getUserState('com_xivetranscorder.transcorders.filter');
$filter_global = isset($filter['global']) ? $filter['global'] : null;
$filter_catid = isset($filter['catid']) ? $filter['catid'] : null;
$filter_daterange = isset($filter['daterange']) ? $filter['daterange'] : null;
$filter_pdk = isset($filter['pdk']) ? $filter['pdk'] : null;
$filter_contactid = isset($filter['contact']) ? $filter['contact'] : null;

// Get TOCA Category Options
$toca_options = IRMFormList::getCategoryOptions('com_xivetranscorder');

$toca_filter_contacts = IRMFormList::getContactFilterOptions($filter_daterange);
?>
<div class="row-fluid">
	<div class="header smaller lighter green">
		<h1>
			<div>
				<i class="icon-list-alt"></i>
				<?php if(!$filter) { ?>
					<span><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TODAY'); ?></span>
				<?php } else { ?>
					<span><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_FILTER'); ?></span>
				<?php } ?>
			</div>
		</h1>
	</div><!--/page-header-->

	<div class="table-header">
		<div class="row-fluid">
			<div class="span6">
				What'ya wanna search today?
			</div>
			<div class="span6">
				<div class="pull-right">
					<?php if( JFactory::getUser()->authorise('core.create','com_xivetranscorder') && JFactory::getUser()->authorise('core.delete','com_xivetranscorder') ): ?>
						<form action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorderform.edit'); ?>" class="inline">
							<div class="input-xlarge">
								<select name="catid" class="select2" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_NEW_CONTACT'); ?>" onchange="this.form.submit()">
									<option value=""></option>
									<?php
										if($toca_options) {
											foreach ($toca_options as $key => $val) {
												echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
											}
										}
									?>
								</select>
							</div>
							<input type="hidden" name="id" value="0">
						</form>
					<?php endif; ?>
					<div class="btn-group">
						<a id="toggleExtend" class="btn btn-small btn-success inline"><i class="icon-double-angle-down"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_MORE'); ?></span></a>
						<?php if(!empty($filter)) { ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorders.filter'); ?>" class="btn btn-small btn-danger inline"><i class="icon-undo"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_RESET_FILTER'); ?></span></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div> <!-- /.row-fluid -->
		<div class="row-fluid extended">
			<div class="header smaller lighter green">
				<h3>Extended search options</h3>
			</div>
			<form class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorders.filter'); ?>" class="inline">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FILTER_CATEGORY_LBL'); ?></label>
							<div class="controls controls-row">
								<div class="span12">
									<select name="search_catid" class="select2" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>">
										<option value=""></option>
										<?php
											if($toca_options) {
												foreach ($toca_options as $key => $val) {
													if ( $filter_catid == $key ) {
														echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
													} else {
														echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
													}
												}
											}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_FILTER_DATERANGE'); ?></label>
							<div class="controls controls-row">
								<span id="daterangepicker" class="btn span12">
									<i class="icon-calendar icon-large"></i>
									<?php if ( $filter_daterange ): ?>
										<span><?php echo date("F j, Y", $filter_daterange[0]); ?> - <?php echo date("F j, Y", $filter_daterange[1]); ?></span> <b class="caret"></b>
									<? else: ?>
										<span><?php echo date("F j, Y"); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b>
									<?php endif; ?>
								</span>
							</div>
							<input type="hidden" name="search_daterange" value='<?php echo $filter_daterange ? json_encode($filter_daterange) : '' ; ?>' />
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_FILTER_CONTACTS'); ?></label>
							<div class="controls controls-row">
								<div class="span12">
									<select name="search_contact" class="select2">
										<option value=""></option>
										<?php
											foreach ($toca_filter_contacts as $key => $val) {
												if ( $filter_contactid == $key ) {
													echo '<option value="' . $key . '" selected>' . JText::_($val) . '</option>';
												} else {
													echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
												}
											}
										?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_FILTER_PDK'); ?></label>
							<div class="controls controls-row">
								<div class="btn-group row-fluid">
									<span>
										<a class="span6 btn btn-mini btn-yellow" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=transcorders.filter&search_pdk=pdk_no_order_id'); ?>"><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_FILTER_PDK_NO_ORDER_ID'); ?></a>
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="center">
					<button class="btn btn-block btn-success"><i class="icon-search"></i> <?php echo JText::_('COM_XIVEIRM_SEARCH'); ?></button>
				</div>
			</form>
		</div> <!-- /.row-fluid extended -->
	</div> <!-- /.table-header -->

	<table id="table_contacts" class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Category</th>
				<th class="center sorting_disabled hidden-phone">
					<label><input type="checkbox"><span class="lbl"></span></label>
				</th>
				<th class="sorting hidden-480">
					<?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_ID'); ?>
				</th>
				<th class="sorting">
					<i class="icon-user"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_NAME'); ?></span>
				</th>
				<th class="sorting hidden-phone">
					<i class="icon-time"></i><span class="hidden-phone hidden-tablet"> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_TIMESTAMP'); ?></span>
				</th>
				<th class="sorting">
					<i class="icon-chevron-up"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_ADDRESS_FROM'); ?></span>
				</th>
				<th class="sorting hidden-480">
					<i class="icon-chevron-down"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_ADDRESS_TO'); ?></span>
				</th>
				<th class="sorting_disabled hidden-phone hidden-tablet">
					<?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TH_STATUS'); ?>
				</th>
				<th class="sorting_disabled">
				</th>
				<th class="hide">
					TD9
				</th>
				<th class="hide">
					TD10
				</th>
				<th class="hide">
					TD11
				</th>
				<th class="hide">
					TD12
				</th>
				<th class="hide">
					TD13
				</th>
				<th class="hide">
					TD14
				</th>
				<th class="hide">
					TD15
				</th>
			</tr>
		</thead>
		<tbody role="alert" aria-live="polite" aria-relevant="all">
		<?php
			$show = false;
			foreach ($this->items as $item) :
		?>
			<?php if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xiveirm'))) : $show = true; ?>
			<tr>
				<td><?php echo NFWItemHelper::getTitleById('category', $item->catid); ?></td>
				<td class="center hidden-phone">
					<?php if($item->checked_out):
						echo '<div style="font-size: 20px;"><i class="icon-lock red"></i></div>';
					else:
						echo '<label><input type="checkbox"><span class="lbl"></span></label>';
					endif; ?>
				</td>

				<td class="hidden-480">
					<span>
					<?php
						if($item->order_id) {
							echo '<i class="icon-barcode"></i> ' . $item->order_id;
						} else {
							echo '<i class="icon-code-fork"></i> ' . $item->id;
						}
					?>
					</span>
				</td>

				<td>
					<a href="<?php echo JRoute::_('index.php?task=transcorderform.edit&id='.$item->id); ?>">
						<?php echo NFWItemHelper::getNameById($item->contact_id, 'xiveirm_contacts', true, true); ?>
					</a>
				</td>
				<td class="center">
					<?php
						if ( !$filter_daterange ) {
							echo date('H:i', $item->transport_timestamp);
						} else {
							echo date('d.m.Y', $item->transport_timestamp) . '<br>';
							echo date('H:i', $item->transport_timestamp);
						}
					?>
				</td>

				<td>
					<?php echo $item->f_address_name; ?><br>
					<?php echo $item->f_address_name_add; ?><br>
					<?php echo $item->f_address_street; ?> <?php echo $item->f_address_houseno; ?><br>
					<?php echo $item->f_address_zip; ?> <?php echo $item->f_address_city; ?><br>
					<em>(<?php echo $item->f_address_region; ?> / <?php echo $item->f_address_country; ?>)</em>
				</td>
				<td>
					<?php echo $item->t_address_name ? $item->t_address_name . '<br>' : ''; ?>
					<?php echo $item->t_address_name_add ? $item->t_address_name_add . '<br>' : ''; ?>
					<?php echo $item->t_address_street; ?> <?php echo $item->t_address_houseno; ?><br>
					<?php echo $item->t_address_zip; ?> <?php echo $item->t_address_city; ?><br>
					<em>(<?php echo $item->t_address_region; ?> / <?php echo $item->t_address_country; ?>)</em>
				</td>
				<td class="hidden-phone hidden-tablet center">
					<?php if(strtotime($item->modified) >= (time() - 86400)): ?>
						<div class="label label-warning xtooltip" title="<?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TD_MODIFIED'); ?>: <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?>"><i class="icon-time"></i> <abbr class="ntime-fromnow" data-time="<?php echo $item->modified; ?>"></abbr></div>
					<?php endif; ?>
				</td>
				<td class="center">
					<div class="hidden-phone visible-desktop btn-group">
						<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=transcorderform.state&id=' . $item->id); ?>').submit()" class="btn btn-mini <?php if($item->state == 1): echo 'btn-success'; endif; ?>" title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>"><?php if($item->state == 1): echo '<i class="icon-ok icon-only"></i>'; else: echo '<i class="icon-remove icon-only"></i>'; endif; ?></a>
						<?php endif; ?>
						<?php if(JFactory::getUser()->authorise('core.delete','com_xiveirm')) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=transcorderform.remove&id=' . $item->id); ?>').submit()" class="btn btn-mini btn-danger" title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>"><i class="icon-trash icon-only"></i></a>
						<?php endif; ?>
						<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=transcorderform.edit&id=' . $item->id); ?>" class="btn btn-mini btn-info"><i class="icon-edit icon-only"></i></a>
						<a class="rowToggle btn btn-mini btn-light"><i class="icon-eye-close icon-only"></i></a>
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
				<td class="hide">
					<?php $item->remarks = "N/A"; ?>
					<?php echo $item->remarks ? '<i class="icon-comment"></i> ' . $item->remarks : ''; ?>
				</td>
				<td class="hide">
					<?php if ($item->checked_out) { ?>
						<i class="icon-time red"></i> <abbr class="ntime-fromnow" data-calendar="<?php echo $item->checked_out_time; ?>"></abbr> - Ausgecheckt von <a href="#" target="_blank"><?php echo JFactory::getUser($item->checked_out)->name; ?></a>
					<?php } ?>
				</td>
				<td class="hide">
					<?php if ($item->modified != '0000-00-00 00:00:00') { ?>
						<i class="icon-time orange"></i> <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?> <span class="small-margin-left hidden-phone"></span><i class="icon-user orange"></i> <a href="#" target="_blank"><?php echo $item->modified_by; ?></a>
					<?php } ?>
				</td>
				<td class="hide">
					<?php echo $item->f_address_region ?>, <?php echo $item->f_address_country ?> <i class="icon-arrow-right"></i> <?php echo $item->t_address_region ?>, <?php echo $item->t_address_country ?>
				</td>
				<td class="hide">
					<i class="icon-sitemap purple"></i> ParentID/Name<br><i class="icon-random purple"></i> ChildID/Name
				</td>
				<td class="hide">
					<i class="icon-calendar"></i> <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->created)); ?> <span class="small-margin-left hidden-phone"></span><i class="icon-user"></i> <a href="#" target="_blank"><?php echo $item->created_by; ?></a>
				</td>
				<td class="hide">
					TD15
				</td>
			</tr>
		<?php endif; ?>
		<?php endforeach; ?>
		</tbody>
	</table>

	<div class="row-fluid center legend">
		<i class="icon-lock red xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_LOCKED_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_LOCKED_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-barcode xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_ORDER_ID_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_NUMERIC_ID_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-user xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_UNKNOWN_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_UNKNOWN_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-female red xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_FEMALE_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_FEMALE_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-male blue xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_MALE_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_MALE_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-building green xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_COMPANY_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_GENDER_COMPANY_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-comment-alt xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_INTERNAL_REMARK_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_INTERNAL_REMARK_CONTENT'); ?>" data-placement="top"></i>
	</div>
</div>

<?php if ($show): ?>
	<div class="pagination">
	<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?>



<div class="items">
		<?php $show = false; ?>
		<?php foreach ($this->items as $item) : ?>
			<?php if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xiveirm'))): $show = true; ?>
			<?php endif; ?>
		<?php endforeach; ?>
	<?php
		if (!$show):
			echo JText::_('COM_XIVEIRM_NO_ITEMS');
		endif;
	?>
</div>

<script>
function fnFormatDetails ( oTable, nTr )
{
	var aData = oTable.fnGetData( nTr );
	var sOut = '<div class="innerDetails">';
	sOut += '<table class="table">';
	sOut += '<tr><td>Interne Bemerkungen</td><td>'+aData[9]+'</td><td rowspan="3" class="table-divider"></td><td>Directions </td><td>' + aData[12] + '</td></tr>';
	sOut += '<tr><td>Lfd Prozess</td><td>'+aData[10]+'</td><td>Verbindungen</td><td>' + aData[13] + '</td></tr>';
	sOut += '<tr><td>Zuletzt bearbeitet</td><td>' + aData[11] + '</td><td>Datensatz erzeugt</td><td>' + aData[14] + '</td></tr>';
	sOut += '</table>';
	sOut += '</div>';

	return sOut;
}
</script>