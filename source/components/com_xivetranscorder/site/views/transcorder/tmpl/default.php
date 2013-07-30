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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_xivetranscorder');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_xivetranscorder')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CLIENT_ID'); ?>:
			<?php echo $this->item->client_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CREATED'); ?>:
			<?php echo $this->item->created; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_MODIFIED'); ?>:
			<?php echo $this->item->modified; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CATID'); ?>:
			<?php echo $this->item->catid_title; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_CONTACT_ID'); ?>:
			<?php echo $this->item->contact_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ORDER_ID'); ?>:
			<?php echo $this->item->order_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_TRANSPORT_TIMESTAMP'); ?>:
			<?php echo $this->item->transport_timestamp; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_POI_ID'); ?>:
			<?php echo $this->item->f_poi_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_NAME'); ?>:
			<?php echo $this->item->f_address_name; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_NAME_ADD'); ?>:
			<?php echo $this->item->f_address_name_add; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_STREET'); ?>:
			<?php echo $this->item->f_address_street; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_HOUSENO'); ?>:
			<?php echo $this->item->f_address_houseno; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_ZIP'); ?>:
			<?php echo $this->item->f_address_zip; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_CITY'); ?>:
			<?php echo $this->item->f_address_city; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_REGION'); ?>:
			<?php echo $this->item->f_address_region; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_COUNTRY'); ?>:
			<?php echo $this->item->f_address_country; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_LAT'); ?>:
			<?php echo $this->item->f_address_lat; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_LONG'); ?>:
			<?php echo $this->item->f_address_long; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_F_ADDRESS_HASH'); ?>:
			<?php echo $this->item->f_address_hash; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_POI_ID'); ?>:
			<?php echo $this->item->t_poi_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_NAME'); ?>:
			<?php echo $this->item->t_address_name; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_NAME_ADD'); ?>:
			<?php echo $this->item->t_address_name_add; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_STREET'); ?>:
			<?php echo $this->item->t_address_street; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_HOUSENO'); ?>:
			<?php echo $this->item->t_address_houseno; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_ZIP'); ?>:
			<?php echo $this->item->t_address_zip; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_CITY'); ?>:
			<?php echo $this->item->t_address_city; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_COUNTRY'); ?>:
			<?php echo $this->item->t_address_country; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_LAT'); ?>:
			<?php echo $this->item->t_address_lat; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_LONG'); ?>:
			<?php echo $this->item->t_address_long; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_T_ADDRESS_HASH'); ?>:
			<?php echo $this->item->t_address_hash; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_DISTCALC_DEVICE'); ?>:
			<?php echo $this->item->distcalc_device; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_DISTANCE'); ?>:
			<?php echo $this->item->estimated_distance; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_TIME'); ?>:
			<?php echo $this->item->estimated_time; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_XIVETRANSCORDER_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_xivetranscorder')):
								?>
									<a href="javascript:document.getElementById('form-transcorder-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_XIVETRANSCORDER_DELETE_ITEM"); ?></a>
									<form id="form-transcorder-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[client_id]" value="<?php echo $this->item->client_id; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[created]" value="<?php echo $this->item->created; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[modified]" value="<?php echo $this->item->modified; ?>" />
										<input type="hidden" name="jform[catid]" value="<?php echo $this->item->catid; ?>" />
										<input type="hidden" name="jform[contact_id]" value="<?php echo $this->item->contact_id; ?>" />
										<input type="hidden" name="jform[order_id]" value="<?php echo $this->item->order_id; ?>" />
										<input type="hidden" name="jform[transport_timestamp]" value="<?php echo $this->item->transport_timestamp; ?>" />
										<input type="hidden" name="jform[f_poi_id]" value="<?php echo $this->item->f_poi_id; ?>" />
										<input type="hidden" name="jform[f_address_name]" value="<?php echo $this->item->f_address_name; ?>" />
										<input type="hidden" name="jform[f_address_name_add]" value="<?php echo $this->item->f_address_name_add; ?>" />
										<input type="hidden" name="jform[f_address_street]" value="<?php echo $this->item->f_address_street; ?>" />
										<input type="hidden" name="jform[f_address_houseno]" value="<?php echo $this->item->f_address_houseno; ?>" />
										<input type="hidden" name="jform[f_address_zip]" value="<?php echo $this->item->f_address_zip; ?>" />
										<input type="hidden" name="jform[f_address_city]" value="<?php echo $this->item->f_address_city; ?>" />
										<input type="hidden" name="jform[f_address_region]" value="<?php echo $this->item->f_address_region; ?>" />
										<input type="hidden" name="jform[f_address_country]" value="<?php echo $this->item->f_address_country; ?>" />
										<input type="hidden" name="jform[f_address_lat]" value="<?php echo $this->item->f_address_lat; ?>" />
										<input type="hidden" name="jform[f_address_long]" value="<?php echo $this->item->f_address_long; ?>" />
										<input type="hidden" name="jform[f_address_hash]" value="<?php echo $this->item->f_address_hash; ?>" />
										<input type="hidden" name="jform[t_poi_id]" value="<?php echo $this->item->t_poi_id; ?>" />
										<input type="hidden" name="jform[t_address_name]" value="<?php echo $this->item->t_address_name; ?>" />
										<input type="hidden" name="jform[t_address_name_add]" value="<?php echo $this->item->t_address_name_add; ?>" />
										<input type="hidden" name="jform[t_address_street]" value="<?php echo $this->item->t_address_street; ?>" />
										<input type="hidden" name="jform[t_address_houseno]" value="<?php echo $this->item->t_address_houseno; ?>" />
										<input type="hidden" name="jform[t_address_zip]" value="<?php echo $this->item->t_address_zip; ?>" />
										<input type="hidden" name="jform[t_address_city]" value="<?php echo $this->item->t_address_city; ?>" />
										<input type="hidden" name="jform[t_address_country]" value="<?php echo $this->item->t_address_country; ?>" />
										<input type="hidden" name="jform[t_address_lat]" value="<?php echo $this->item->t_address_lat; ?>" />
										<input type="hidden" name="jform[t_address_long]" value="<?php echo $this->item->t_address_long; ?>" />
										<input type="hidden" name="jform[t_address_hash]" value="<?php echo $this->item->t_address_hash; ?>" />
										<input type="hidden" name="jform[distcalc_device]" value="<?php echo $this->item->distcalc_device; ?>" />
										<input type="hidden" name="jform[estimated_distance]" value="<?php echo $this->item->estimated_distance; ?>" />
										<input type="hidden" name="jform[estimated_time]" value="<?php echo $this->item->estimated_time; ?>" />
										<input type="hidden" name="option" value="com_xivetranscorder" />
										<input type="hidden" name="task" value="transcorder.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php
else:
    echo JText::_('COM_XIVETRANSCORDER_ITEM_NOT_LOADED');
endif;
?>
