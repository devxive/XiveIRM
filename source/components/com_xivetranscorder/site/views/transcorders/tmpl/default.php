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

// Import HTML and Helper Classes
nimport('NHtml.JavaScript');
nimport('NHtml.DataTables');
nimport('NItem.Helper', false);

NHtmlJavaScript::setAutoRemove();
NHtmlJavaScript::setToggle('extended', 'toggleExtend');
NHtmlJavaScript::setTooltip('.xtooltip');
NHtmlJavaScript::setPopover('.xpopover');
NHtmlJavaScript::loadMoment();

// Init the dataTable
$tableParams = '
	{"bProcessing": true,
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

//	"aoColumnDefs": [
//		{ "bSortable": false, "aTargets": [ 0 ] }
//	],
//	"aaSorting": [[1, 'asc']]


NHtmlDataTables::loadDataTable('table_contacts', $tableParams, true);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Check for filters
$filter = $app->getUserState('com_xiveirm.contacts.filter');
$filter_global = isset($filter['global']) ? $filter['global'] : null;
$filter_catid = isset($filter['catid']) ? $filter['catid'] : null;
$filter_pdk = isset($filter['pdk']) ? $filter['pdk'] : null;


	$menuId = JFactory::getApplication()->input->getCmd('Itemid', '');

	echo '<pre>Current $menuId = ';
	print_r($menuId);
	echo '</pre>';
?>
<script>
// Set the classes that TableTools uses to something suitable for Bootstrap
jQuery(function() {
});
</script>

<div class="row-fluid">
	<div class="header smaller lighter green">
		<h1>
			<div>
				<i class="icon-list-alt"></i>
			<?php if(!$filter) { ?>
				<span><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_TODAY'); ?></span>
			<?php } else if($filter_catid) { ?>
				<span><?php echo JText::_('COM_XIVETRANSCORDER_ORDER_LIST_CAT'); ?></span>
				<?php echo NItemHelper::getTitleById('category', $filter_catid); ?>
			<?php } else if($filter_pdk) { ?>
				<span><span class="hidden-phone"><?php echo JText::_('COM_XIVEIRM_CONTACT_LIST_CONTACTS_FILTER_PDK'); ?></span> <?php echo JText::_('COM_XIVEIRM_CONTACT_LIST_CONTACTS_FILTER_' . strtoupper($filter_pdk)); ?></span>
			<?php } else if($filter_global) { ?>
				<span><?php echo JText::sprintf('COM_XIVEIRM_CONTACT_LIST_CONTACTS_CUSTOM', $filter_global); ?></span>
				<?php $addLastName = $filter_global; ?>
			<?php } else { ?>
				<span><?php echo JText::_('COM_XIVEIRM_CONTACT_LIST_CONTACTS_FILTER_UNKNOWN'); ?></span>
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
							<?php NHtmlJavaScript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
							<div class="input-xlarge">
								<select name="catid" class="chzn-select-category" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_NEW_CONTACT'); ?>" onchange="this.form.submit()">
									<option value=""></option>
									<?php
										$options = IRMSystem::getListOptions('categories', false, 'com_xivetranscorder.transcorders');
										if($options->client) {
											echo '<optgroup label="' . JText::sprintf('COM_XIVEIRM_SELECT_CATEGORY_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
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
							</div>
							<input type="hidden" name="id" value="0">
						</form>
					<?php endif; ?>
					<div class="btn-group">
						<a id="toggleExtend" class="btn btn-small btn-success inline"><i class="icon-double-angle-down"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_MORE'); ?></span></a>
						<?php if(!empty($filter)) { ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter'); ?>" class="btn btn-small btn-danger inline"><i class="icon-undo"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_RESET_FILTER'); ?></span></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div> <!-- /.row-fluid -->
		<div class="row-fluid extended">
			<div class="header smaller lighter green">
				<h3>Extended search options</h3>
			</div>
			<form class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=orders.filter'); ?>" class="inline">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FILTER_CATEGORY_LBL'); ?></label>
							<div class="controls controls-row">
								<?php NHtmlJavaScript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
								<div class="span12">
									<select name="search_catid" class="chzn-select-category" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" onchange="this.form.submit()">
										<option value=""></option>
										<?php
											$options = IRMSystem::getListOptions('categories', false);
											if($options->client) {
												echo '<optgroup label="' . JText::sprintf('COM_XIVEIRM_SELECT_CATEGORY_SPECIFIC', NItemHelper::getTitleById('usergroup', $xsession->client_id)) . '">';
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
								</div>
							</div>
						</div>
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FILTER_SEARCH_LBL'); ?></label>
							<div class="controls controls-row">
								<span class="input-icon">
									<input class="span12" type="text" name="search_global" placeholder="<?php echo JText::_('COM_XIVEIRM_FILTER_SEARCH_PHOLD'); ?>"/>
									<i class="icon-search"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="span6">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FILTER_PDK_LBL'); ?></label>
							<div class="controls controls-row">
								<div class="btn-group row-fluid">
									<span><a class="span6 btn btn-mini btn-yellow" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter&search_pdk=pdk_no_customer_id'); ?>"><?php echo JText::_('COM_XIVEIRM_FILTER_PDK_BTN_NO_CUSTOMER_ID'); ?></a></span>
									<span><a class="span6 btn btn-mini btn-purple" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter&search_pdk=pdk_special'); ?>"><?php echo JText::_('COM_XIVEIRM_FILTER_PDK_BTN_SPECIAL_CHARS'); ?></a></span>
									<span><a class="span6 btn btn-mini btn-primary" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter&search_pdk=pdk_in_country'); ?>"><?php echo JText::_('COM_XIVEIRM_FILTER_PDK_BTN_IN_COUNTRY'); ?></a></span>
									<span><a class="span6 btn btn-mini btn-info" href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter&search_pdk=pdk_not_in_country'); ?>"><?php echo JText::_('COM_XIVEIRM_FILTER_PDK_BTN_NOT_IN_COUNTRY'); ?></a></span>






									<span><a class="span6 btn btn-mini btn-danger" href="#">DateRangePicker to select a range</a></span>
									<span><a class="span6 btn btn-mini btn-danger" href="#">Liste der Unternehmer oder Fahrzeuge!!</a></span>














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
				<td><?php echo NItemHelper::getTitleById('category', $item->catid); ?></td>
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
						<?php echo NItemHelper::getNameById($item->contact_id, 'xiveirm_contacts', true, true); ?>
					</a>
				</td>
				<td>
					<abbr class="xtooltip" title="<?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->transport_timestamp)); ?>">
						<?php echo date('H:i', $item->transport_timestamp); ?>
					</abbr>
				</td>

				<td>
					<?php echo $item->f_address_name; ?><br>
					<?php echo $item->f_address_name_add; ?><br>
					<?php echo $item->f_address_street; ?> <?php echo $item->f_address_houseno; ?><br>
					<?php echo $item->f_address_zip; ?> <?php echo $item->f_address_city; ?><br>
					<em>(<?php echo $item->f_address_country; ?> / <?php echo $item->f_address_country; ?>)</em>
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
						<a id="rowToggle" class="btn btn-mini btn-light"><i class="icon-eye-close icon-only"></i></a>
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
						<i class="icon-time orange"></i> <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?> <span class="small-margin-left hidden-phone"></span><i class="icon-user orange"></i> <a href="#" target="_blank">Rosalinda Garcia</a>
						<br><em>TODO: New data have to come from user activity app!</em>
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



<h3 class="header lighter pink">Tests</h3>

<a onClick="alert('Dieser Eintrag wurde Archiviert --> Achtung DEMO!!!')" class="btn btn-small btn-app radius-4 <?php echo $item->state == 0 ? 'btn-light' : ''; ?>">
	<i class="icon-archive"></i>
	<?php echo $item->state == 0 ? JText::_("COM_XIVEIRM_PUBLISH_ITEM") : JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); ?>
</a>
<br>
<br>
<hr>
<?php $testDate = '07/15/2013 10:38'; ?>
<abbr class="xtooltip ntime-fromnow" data-calendar="<?php echo $testDate; ?>" title="<?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($testDate)); ?>" data-content-prefix="Termin:" data-icon-class="icon-calendar">WIRD UEBERSCHRIEBEN</abbr>
<br>
<br>
<pre class="prettify">
<?php
echo '<strong><em>devXive - Nawala Framework 4.2.8alpha -> abstract class NHtmlJavaScript::loadMoment() (( nimport(\'NHtml.JavaScript\'); ))</em></strong>';
echo '<br>';
echo '<br>';
	echo htmlentities(" <?php \$testDate = '07/15/2013 10:38'; ?> ");
echo '<br>';
echo '<br>';

echo '<strong><em>abbr tag => timeago (javascript supported)</em></strong>';
	echo htmlentities('
	<abbr
		class="xtooltip ntime-fromnow"
		data-time="2013-07-15 10:38"
		title="Monday, 15 July 2013 10:38"
		data-content-prefix="Termin:"
		data-icon-class="icon-calendar">
			WIRD UEBERSCHRIEBEN
	</abbr>
	');
echo '<br>';
echo '<br>';

echo '<strong><em>abbr tag => calendar (javascript supported)</em></strong>';
	echo htmlentities('
	<abbr
		class="xtooltip ntime-fromnow"
		data-calendar="2013-07-15 10:38"
		title="Monday, 15 July 2013 10:38"
		data-content-prefix="Termin:"
		data-icon-class="icon-calendar">
			WIRD UEBERSCHRIEBEN
	</abbr>
	');
echo '<br>';
echo '<br>';

echo '<strong><em>span tag => none (bootstrap supported)</em></strong>';
	echo htmlentities('
	<span class="xtooltip" data-original-title="Monday, 15 July 2013 10:38">
		<i class="icon-clock"></i> 2013-07-15 10:38
	</span>
	');
?>
</pre>
























?>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xivetranscorder'))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&view=transcorder&id=' . (int)$item->id); ?>"><?php echo $item->client_id; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_xivetranscorder')):
									?>
										<a href="javascript:document.getElementById('form-transcorder-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_XIVETRANSCORDER_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVETRANSCORDER_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-transcorder-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[catid]" value="<?php echo $item->catid; ?>" />
											<input type="hidden" name="jform[contact_id]" value="<?php echo $item->contact_id; ?>" />
											<input type="hidden" name="jform[order_id]" value="<?php echo $item->order_id; ?>" />
											<input type="hidden" name="jform[transport_timestamp]" value="<?php echo $item->transport_timestamp; ?>" />
											<input type="hidden" name="jform[f_poi_id]" value="<?php echo $item->f_poi_id; ?>" />
											<input type="hidden" name="jform[f_address_name]" value="<?php echo $item->f_address_name; ?>" />
											<input type="hidden" name="jform[f_address_name_add]" value="<?php echo $item->f_address_name_add; ?>" />
											<input type="hidden" name="jform[f_address_street]" value="<?php echo $item->f_address_street; ?>" />
											<input type="hidden" name="jform[f_address_houseno]" value="<?php echo $item->f_address_houseno; ?>" />
											<input type="hidden" name="jform[f_address_zip]" value="<?php echo $item->f_address_zip; ?>" />
											<input type="hidden" name="jform[f_address_city]" value="<?php echo $item->f_address_city; ?>" />
											<input type="hidden" name="jform[f_address_region]" value="<?php echo $item->f_address_region; ?>" />
											<input type="hidden" name="jform[f_address_country]" value="<?php echo $item->f_address_country; ?>" />
											<input type="hidden" name="jform[f_address_lat]" value="<?php echo $item->f_address_lat; ?>" />
											<input type="hidden" name="jform[f_address_long]" value="<?php echo $item->f_address_long; ?>" />
											<input type="hidden" name="jform[f_address_hash]" value="<?php echo $item->f_address_hash; ?>" />
											<input type="hidden" name="jform[t_poi_id]" value="<?php echo $item->t_poi_id; ?>" />
											<input type="hidden" name="jform[t_address_name]" value="<?php echo $item->t_address_name; ?>" />
											<input type="hidden" name="jform[t_address_name_add]" value="<?php echo $item->t_address_name_add; ?>" />
											<input type="hidden" name="jform[t_address_street]" value="<?php echo $item->t_address_street; ?>" />
											<input type="hidden" name="jform[t_address_houseno]" value="<?php echo $item->t_address_houseno; ?>" />
											<input type="hidden" name="jform[t_address_zip]" value="<?php echo $item->t_address_zip; ?>" />
											<input type="hidden" name="jform[t_address_city]" value="<?php echo $item->t_address_city; ?>" />
											<input type="hidden" name="jform[t_address_country]" value="<?php echo $item->t_address_country; ?>" />
											<input type="hidden" name="jform[t_address_lat]" value="<?php echo $item->t_address_lat; ?>" />
											<input type="hidden" name="jform[t_address_long]" value="<?php echo $item->t_address_long; ?>" />
											<input type="hidden" name="jform[t_address_hash]" value="<?php echo $item->t_address_hash; ?>" />
											<input type="hidden" name="jform[distcalc_device]" value="<?php echo $item->distcalc_device; ?>" />
											<input type="hidden" name="jform[estimated_distance]" value="<?php echo $item->estimated_distance; ?>" />
											<input type="hidden" name="jform[estimated_time]" value="<?php echo $item->estimated_time; ?>" />
											<input type="hidden" name="option" value="com_xivetranscorder" />
											<input type="hidden" name="task" value="transcorder.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_xivetranscorder')):
									?>
										<a href="javascript:document.getElementById('form-transcorder-delete-<?php echo $item->id; ?>').submit()"><?php echo JText::_("COM_XIVETRANSCORDER_DELETE_ITEM"); ?></a>
										<form id="form-transcorder-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[catid]" value="<?php echo $item->catid; ?>" />
											<input type="hidden" name="jform[contact_id]" value="<?php echo $item->contact_id; ?>" />
											<input type="hidden" name="jform[order_id]" value="<?php echo $item->order_id; ?>" />
											<input type="hidden" name="jform[transport_timestamp]" value="<?php echo $item->transport_timestamp; ?>" />
											<input type="hidden" name="jform[f_poi_id]" value="<?php echo $item->f_poi_id; ?>" />
											<input type="hidden" name="jform[f_address_name]" value="<?php echo $item->f_address_name; ?>" />
											<input type="hidden" name="jform[f_address_name_add]" value="<?php echo $item->f_address_name_add; ?>" />
											<input type="hidden" name="jform[f_address_street]" value="<?php echo $item->f_address_street; ?>" />
											<input type="hidden" name="jform[f_address_houseno]" value="<?php echo $item->f_address_houseno; ?>" />
											<input type="hidden" name="jform[f_address_zip]" value="<?php echo $item->f_address_zip; ?>" />
											<input type="hidden" name="jform[f_address_city]" value="<?php echo $item->f_address_city; ?>" />
											<input type="hidden" name="jform[f_address_region]" value="<?php echo $item->f_address_region; ?>" />
											<input type="hidden" name="jform[f_address_country]" value="<?php echo $item->f_address_country; ?>" />
											<input type="hidden" name="jform[f_address_lat]" value="<?php echo $item->f_address_lat; ?>" />
											<input type="hidden" name="jform[f_address_long]" value="<?php echo $item->f_address_long; ?>" />
											<input type="hidden" name="jform[f_address_hash]" value="<?php echo $item->f_address_hash; ?>" />
											<input type="hidden" name="jform[t_poi_id]" value="<?php echo $item->t_poi_id; ?>" />
											<input type="hidden" name="jform[t_address_name]" value="<?php echo $item->t_address_name; ?>" />
											<input type="hidden" name="jform[t_address_name_add]" value="<?php echo $item->t_address_name_add; ?>" />
											<input type="hidden" name="jform[t_address_street]" value="<?php echo $item->t_address_street; ?>" />
											<input type="hidden" name="jform[t_address_houseno]" value="<?php echo $item->t_address_houseno; ?>" />
											<input type="hidden" name="jform[t_address_zip]" value="<?php echo $item->t_address_zip; ?>" />
											<input type="hidden" name="jform[t_address_city]" value="<?php echo $item->t_address_city; ?>" />
											<input type="hidden" name="jform[t_address_country]" value="<?php echo $item->t_address_country; ?>" />
											<input type="hidden" name="jform[t_address_lat]" value="<?php echo $item->t_address_lat; ?>" />
											<input type="hidden" name="jform[t_address_long]" value="<?php echo $item->t_address_long; ?>" />
											<input type="hidden" name="jform[t_address_hash]" value="<?php echo $item->t_address_hash; ?>" />
											<input type="hidden" name="jform[distcalc_device]" value="<?php echo $item->distcalc_device; ?>" />
											<input type="hidden" name="jform[estimated_distance]" value="<?php echo $item->estimated_distance; ?>" />
											<input type="hidden" name="jform[estimated_time]" value="<?php echo $item->estimated_time; ?>" />
											<input type="hidden" name="option" value="com_xivetranscorder" />
											<input type="hidden" name="task" value="transcorder.remove" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
								?>
							</li>
						<?php endif; ?>

<?php endforeach; ?>
        <?php
        if (!$show):
            echo JText::_('COM_XIVETRANSCORDER_NO_ITEMS');
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


	<?php if(JFactory::getUser()->authorise('core.create','com_xivetranscorder')): ?><a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.edit&id=0'); ?>"><?php echo JText::_("COM_XIVETRANSCORDER_ADD_ITEM"); ?></a>
	<?php endif; ?>