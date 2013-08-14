<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

$app = JFactory::getApplication();

// Import HTML and Helper Classes
// nimport('NHtml.JavaScript');
// nimport('NHtml.DataTables');
// nimport('NItem.Helper', false);

NFWHtmlJavascript::setAutoRemove();
NFWHtmlJavascript::setToggle('extended', 'toggleExtend');
NFWHtmlJavascript::setTooltip('.xtooltip');
NFWHtmlJavascript::setPopover('.xpopover');
NFWHtmlJavascript::loadMoment();

// Init the dataTable
$tableParams = '
	{"bProcessing": true,
	"bAutoWidth": false,
	"bLengthChange": true,
	"bStateSave": false,
	"aaSorting": [[3,"asc"]],
	"sPaginationType": "bootstrap",
	"aoColumnDefs": [
		{ "bSortable": false, "aTargets": [0] },
		{ "bSortable": false, "aTargets": [1] },
		{ "bSortable": false, "aTargets": [6] },
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


NFWHtmlDatatables::loadDataTable('table_contacts', $tableParams, true);

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

// Check for filters
$filter = $app->getUserState('com_xiveirm.contacts.filter');
$filter_global = isset($filter['global']) ? $filter['global'] : null;
$filter_catid = isset($filter['catid']) ? $filter['catid'] : null;
$filter_pdk = isset($filter['pdk']) ? $filter['pdk'] : null;

//	echo '<pre>';
//	print_r($filter);
//	echo '</pre>';
?>
<script>
// Set the classes that TableTools uses to something suitable for Bootstrap
jQuery(function() {
});
</script>

<div class="row-fluid">
	<div class="header smaller lighter blue">
		<h1>
			<div>
				<i class="icon-group"></i>
			<?php if(!$filter) { ?>
				<span><?php echo JText::_('COM_XIVEIRM_CONTACT_LIST_CONTACTS_ALL'); ?></span>
			<?php } else if($filter_catid) { ?>
				<span><?php echo JText::_('COM_XIVEIRM_CONTACT_LIST_CONTACTS_CAT'); ?></span>
				<?php echo NFWItemHelper::getTitleById('category', $filter_catid); ?>
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
					<?php if(JFactory::getUser()->authorise('core.create','com_xiveirm')): ?>
						<form action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit'); ?>" class="inline">
							<?php NFWHtmlJavascript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
							<div class="input-xlarge">
								<select name="catid" class="chzn-select-category" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_NEW_CONTACT'); ?>" onchange="this.form.submit()">
									<option value=""></option>
									<?php
										$options = IRMFormList::getCategoryOptions('com_xiveirm');
										if($options) {
											foreach ($options as $key => $val) {
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
						<a id="toggleExtend" class="btn btn-small btn-primary inline"><i class="icon-double-angle-down"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_MORE'); ?></span></a>
						<?php if(!empty($filter)) { ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter'); ?>" class="btn btn-small btn-danger inline"><i class="icon-undo"></i><span class="hidden-phone hidden-480"> <?php echo JText::_('COM_XIVEIRM_RESET_FILTER'); ?></span></a>
						<?php } ?>
					</div>
				</div>
			</div>
		</div> <!-- /.row-fluid -->
		<div class="row-fluid extended">
			<div class="header smaller lighter blue">
				<h3>Extended search options</h3>
			</div>
			<form class="form-horizontal" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contacts.filter'); ?>" class="inline">
				<div class="row-fluid">
					<div class="span6">
						<div class="control-group">
							<label class="control-label"><?php echo JText::_('COM_XIVEIRM_FILTER_CATEGORY_LBL'); ?></label>
							<div class="controls controls-row">
								<?php NFWHtmlJavascript::setChosen('.chzn-select-category', false, array('disable_search_threshold' => '15', 'no_results_text' => 'Oops, nothing found!', 'width' => '100%')); ?>
								<div class="span12">
									<select name="search_catid" class="chzn-select-category" data-placeholder="<?php echo JText::_('COM_XIVEIRM_SELECT_CATEGORY'); ?>" onchange="this.form.submit()">
										<option value=""></option>
										<?php
											$options = IRMFormList::getCategoryOptions('com_xiveirm');
											if($options) {
												foreach ($options as $key => $val) {
													echo '<option value="' . $key . '">' . JText::_($val) . '</option>';
												}
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
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
				<div class="center">
					<button class="btn btn-block btn-primary"><i class="icon-search"></i> <?php echo JText::_('COM_XIVEIRM_SEARCH'); ?></button>
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
					<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_ID'); ?>
				</th>
				<th class="sorting">
					<i class="icon-user"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_NAME'); ?></span>
				</th>
				<th class="sorting hidden-phone">
					<i class="icon-calendar"></i><span class="hidden-phone hidden-tablet"> <?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_DOB'); ?></span>
				</th>
				<th class="sorting">
					<i class="icon-home"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_ADDRESS'); ?></span>
				</th>
				<th class="sorting hidden-480">
					<i class="icon-phone"></i><span class="hidden-phone"> <?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_PHONE'); ?></span>
				</th>
				<th class="sorting_disabled hidden-phone hidden-tablet">
					<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TH_STATUS'); ?>
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
						if((int) $item->customer_id && !empty($item->customer_id)) {
							echo '<i class="icon-barcode"></i> ' . $item->customer_id;
						} else if(!(int) $item->customer_id && !empty($item->customer_id)) {
							echo '<i class="icon-qrcode"></i> ' . $item->customer_id;
						} else {
							echo '<i class="icon-code-fork"></i> ' . $item->id;
						}
					?>
					</span>
				</td>
				<td>
					<a href="<?php echo JRoute::_('index.php?task=contactform.edit&id='.$item->id); ?>">
						<?php 
							if(!empty($item->company)) {
								echo $item->company . '<br>';
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
				<td class="hidden-phone">
					<?php
						if($item->gender == 'MALE') {
							echo '<i class="icon-male blue"></i>';
						} else if($item->gender == 'FEMALE') {
							echo '<i class="icon-female red"></i>';
						} else if($item->gender == 'COMPANY') {
							echo'<i class="icon-building green"></i>';
						} else {
							echo '<i class="icon-user"></i>';
						}

						if($item->dob != '0000-00-00') {
							echo ' ' . date(JText::_('DATE_FORMAT_LC4'), strtotime($item->dob));
						}
					?>
				</td>

				<td><?php echo $item->address_street; ?> <?php echo $item->address_houseno; ?><br><?php echo $item->address_zip; ?> <?php echo $item->address_city; ?>, <?php echo $item->address_country; ?></td>
				<td class="hidden-480"><?php if($item->mobile != ''): echo '<i class="icon-mobile-phone"></i> ' . $item->mobile . '<br>'; endif; if($item->phone != ''): echo '<i class="icon-phone icon-only"></i> ' . $item->phone; endif; ?></td>
				<td class="hidden-phone hidden-tablet center">
					<?php if(strtotime($item->modified) >= (time() - 86400)): ?>
						<div class="label label-warning xtooltip" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TD_MODIFIED'); ?>: <?php echo date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?>"><i class="icon-time"></i> <abbr class="ntime-fromnow" data-time="<?php echo $item->modified; ?>"></abbr></div>
					<?php endif; ?>
				</td>
				<td class="center">
					<div class="hidden-phone visible-desktop btn-group">
						<?php if(JFactory::getUser()->authorise('core.edit.state','com_xiveirm')) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.state&id=' . $item->id); ?>').submit()" class="btn btn-mini <?php if($item->state == 1): echo 'btn-success'; endif; ?>" title="<?php if($item->state == 1): echo JText::_("COM_XIVEIRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVEIRM_PUBLISH_ITEM"); endif; ?>"><?php if($item->state == 1): echo '<i class="icon-ok icon-only"></i>'; else: echo '<i class="icon-remove icon-only"></i>'; endif; ?></a>
						<?php endif; ?>
						<?php if(JFactory::getUser()->authorise('core.delete','com_xiveirm')) : ?>
							<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.remove&id=' . $item->id); ?>').submit()" class="btn btn-mini btn-danger" title="<?php echo JText::_("COM_XIVEIRM_DELETE_ITEM"); ?>"><i class="icon-trash icon-only"></i></a>
						<?php endif; ?>
						<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.edit&id=' . $item->id); ?>" class="btn btn-mini btn-info"><i class="icon-edit icon-only"></i></a>
						<a href="<?php echo JRoute::_('index.php?option=com_xiveirm&task=contactform.flag&id=' . $item->id); ?>" class="btn btn-mini <?php if(IRMItemHelper::flagIt($item->id, 'check')) { echo 'btn-warning'; } ?>"><i class="icon-flag icon-only"></i></a>
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
					<?php
						$kontostand = rand(-1000.00, 1000.00);
						if($kontostand < 0) {
							echo '<span class="red"><i class="icon-eur"></i> ' . str_replace('.', ',', $kontostand) . ' EUR</span>';
						} else {
							echo '<span class="green"><i class="icon-eur"></i> ' . str_replace('.', ',', $kontostand) . ' EUR</span>';
						}
					?>
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
		<i class="icon-barcode xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_NUMERIC_ID_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_NUMERIC_ID_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-qrcode xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_ALPHA_NUMERIC_ID_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_ALPHA_NUMERIC_ID_CONTENT'); ?>" data-placement="top"></i>
		&nbsp;&nbsp;&nbsp;
		<i class="icon-code-fork xpopover" title="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_SYSTEM_ID_TITLE'); ?>" data-content="<?php echo JText::_('COM_XIVEIRM_CONTACTS_LIST_TF_SYSTEM_ID_CONTENT'); ?>" data-placement="top"></i>
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
	sOut += '<tr><td>Interne Bemerkungen</td><td>'+aData[9]+'</td><td rowspan="3" class="table-divider"></td><td>Kontostand </td><td>' + aData[12] + '</td></tr>';
	sOut += '<tr><td>Lfd Prozess</td><td>'+aData[10]+'</td><td>Verbindungen</td><td>' + aData[13] + '</td></tr>';
	sOut += '<tr><td>Zuletzt bearbeitet</td><td>' + aData[11] + '</td><td>Datensatz erzeugt</td><td>' + aData[14] + '</td></tr>';
	sOut += '</table>';
	sOut += '</div>';

	return sOut;
}
</script>