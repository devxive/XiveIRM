<?php
/**
 * @version     3.0.0
 * @package     com_mc3prm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_mc3prm', JPATH_ADMINISTRATOR);
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_mc3prm');
if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_mc3prm')) {
	$canEdit = JFactory::getUser()->id == $this->item->created_by;
}
?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ID'); ?>:
			<?php echo $this->item->id; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_STATE'); ?>:
			<?php echo $this->item->state; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_CREATED'); ?>:
			<?php echo $this->item->created; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_CREATED_BY'); ?>:
			<?php echo $this->item->created_by; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_CHECKED_OUT'); ?>:
			<?php echo $this->item->checked_out; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_CHECKED_OUT_TIME'); ?>:
			<?php echo $this->item->checked_out_time; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_MODIFIED'); ?>:
			<?php echo $this->item->modified; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_TRASH'); ?>:
			<?php echo $this->item->trash; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_CUSTOMER_ID'); ?>:
			<?php echo $this->item->customer_id; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_TITLE'); ?>:
			<?php echo $this->item->title; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_LAST_NAME'); ?>:
			<?php echo $this->item->last_name; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_FIRST_NAME'); ?>:
			<?php echo $this->item->first_name; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_GENDER'); ?>:
			<?php echo $this->item->gender; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_DOB'); ?>:
			<?php echo $this->item->dob; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ADDRESS_NAME'); ?>:
			<?php echo $this->item->address_name; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ADDRESS_NAME_ADD'); ?>:
			<?php echo $this->item->address_name_add; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ADDRESS_STREET'); ?>:
			<?php echo $this->item->address_street; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ADDRESS_HOUSE_NO'); ?>:
			<?php echo $this->item->address_house_no; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_ZIP'); ?>:
			<?php echo $this->item->zip; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_COUNTRY'); ?>:
			<?php echo $this->item->country; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_PHONE'); ?>:
			<?php echo $this->item->phone; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_MOBILE'); ?>:
			<?php echo $this->item->mobile; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_SUPERVISOR_NAME'); ?>:
			<?php echo $this->item->supervisor_name; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_SUPERVISOR_PHONE'); ?>:
			<?php echo $this->item->supervisor_phone; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_SUPERVISOR_DESC'); ?>:
			<?php echo $this->item->supervisor_desc; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_EMAIL'); ?>:
			<?php echo $this->item->email; ?></li>
			<li><?php echo JText::_('COM_MC3PRM_FORM_LBL_PRMMASTERDATA_WEB'); ?>:
			<?php echo $this->item->web; ?></li>


        </ul>

    </div>
    <?php if($canEdit): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_mc3prm&task=prmmasterdata.edit&id='.$this->item->id); ?>"><?php echo JText::_("COM_MC3PRM_EDIT_ITEM"); ?></a>
	<?php endif; ?>
								<?php if(JFactory::getUser()->authorise('core.delete','com_mc3prm')):
								?>
									<a href="javascript:document.getElementById('form-prmmasterdata-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_MC3PRM_DELETE_ITEM"); ?></a>
									<form id="form-prmmasterdata-delete-<?php echo $this->item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_mc3prm&task=prmmasterdata.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
										<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
										<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
										<input type="hidden" name="jform[created]" value="<?php echo $this->item->created; ?>" />
										<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />
										<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
										<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
										<input type="hidden" name="jform[modified]" value="<?php echo $this->item->modified; ?>" />
										<input type="hidden" name="jform[trash]" value="<?php echo $this->item->trash; ?>" />
										<input type="hidden" name="jform[customer_id]" value="<?php echo $this->item->customer_id; ?>" />
										<input type="hidden" name="jform[title]" value="<?php echo $this->item->title; ?>" />
										<input type="hidden" name="jform[last_name]" value="<?php echo $this->item->last_name; ?>" />
										<input type="hidden" name="jform[first_name]" value="<?php echo $this->item->first_name; ?>" />
										<input type="hidden" name="jform[gender]" value="<?php echo $this->item->gender; ?>" />
										<input type="hidden" name="jform[dob]" value="<?php echo $this->item->dob; ?>" />
										<input type="hidden" name="jform[address_name]" value="<?php echo $this->item->address_name; ?>" />
										<input type="hidden" name="jform[address_name_add]" value="<?php echo $this->item->address_name_add; ?>" />
										<input type="hidden" name="jform[address_street]" value="<?php echo $this->item->address_street; ?>" />
										<input type="hidden" name="jform[address_house_no]" value="<?php echo $this->item->address_house_no; ?>" />
										<input type="hidden" name="jform[zip]" value="<?php echo $this->item->zip; ?>" />
										<input type="hidden" name="jform[country]" value="<?php echo $this->item->country; ?>" />
										<input type="hidden" name="jform[phone]" value="<?php echo $this->item->phone; ?>" />
										<input type="hidden" name="jform[mobile]" value="<?php echo $this->item->mobile; ?>" />
										<input type="hidden" name="jform[supervisor_name]" value="<?php echo $this->item->supervisor_name; ?>" />
										<input type="hidden" name="jform[supervisor_phone]" value="<?php echo $this->item->supervisor_phone; ?>" />
										<input type="hidden" name="jform[supervisor_desc]" value="<?php echo $this->item->supervisor_desc; ?>" />
										<input type="hidden" name="jform[email]" value="<?php echo $this->item->email; ?>" />
										<input type="hidden" name="jform[web]" value="<?php echo $this->item->web; ?>" />
										<input type="hidden" name="option" value="com_mc3prm" />
										<input type="hidden" name="task" value="prmmasterdata.remove" />
										<?php echo JHtml::_('form.token'); ?>
									</form>
								<?php
								endif;
							?>
<?php
else:
    echo JText::_('COM_MC3PRM_ITEM_NOT_LOADED');
endif;
?>
