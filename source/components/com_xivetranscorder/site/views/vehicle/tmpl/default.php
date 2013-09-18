<?php
/**
 * @version     6.1.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (C) 2013. All rights reserved.
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

            			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_ORDERING'); ?>:
			<?php echo $this->item->ordering; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_CONTACT_ID'); ?>:
			<?php echo $this->item->contact_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_LISTNAME'); ?>:
			<?php echo $this->item->listname; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_CALLNAME'); ?>:
			<?php echo $this->item->callname; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_MOBILE'); ?>:
			<?php echo $this->item->mobile; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_NAVI_ID'); ?>:
			<?php echo $this->item->navi_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_FLEET_CAR'); ?>:
			<?php echo $this->item->fleet_car; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_FITTINGS'); ?>:
			<?php echo $this->item->fittings; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_LICENSE_PLATE'); ?>:
			<?php echo $this->item->license_plate; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_INSURANCE_ID'); ?>:
			<?php echo $this->item->insurance_id; ?></li>
			<li><?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_VEHICLE_INSURANCE_NO'); ?>:
			<?php echo $this->item->insurance_no; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=vehicle.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_XIVETRANSCORDER_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_xivetranscorder')):
								?>
									<a href="javascript:document.getElementById('form-vehicle-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_XIVETRANSCORDER_DELETE_ITEM"); ?></a>
									<form id="form-vehicle-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=vehicle.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[contact_id]" value="<?php echo $this->item->contact_id; ?>" />
										<input type="hidden" name="jform[listname]" value="<?php echo $this->item->listname; ?>" />
										<input type="hidden" name="jform[callname]" value="<?php echo $this->item->callname; ?>" />
										<input type="hidden" name="jform[mobile]" value="<?php echo $this->item->mobile; ?>" />
										<input type="hidden" name="jform[navi_id]" value="<?php echo $this->item->navi_id; ?>" />
										<input type="hidden" name="jform[fleet_car]" value="<?php echo $this->item->fleet_car; ?>" />
										<input type="hidden" name="jform[fittings]" value="<?php echo $this->item->fittings; ?>" />
										<input type="hidden" name="jform[license_plate]" value="<?php echo $this->item->license_plate; ?>" />
										<input type="hidden" name="jform[insurance_id]" value="<?php echo $this->item->insurance_id; ?>" />
										<input type="hidden" name="jform[insurance_no]" value="<?php echo $this->item->insurance_no; ?>" />
										<input type="hidden" name="option" value="com_xivetranscorder" />
										<input type="hidden" name="task" value="vehicle.remove" />
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
