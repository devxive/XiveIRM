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
?>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_mc3prm'))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_mc3prm&view=prmmasterdata&id=' . (int)$item->id); ?>"><?php echo $item->customer_id; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_mc3prm')):
									?>
										<a href="javascript:document.getElementById('form-prmmasterdata-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_MC3PRM_UNPUBLISH_ITEM"); else: echo JText::_("COM_MC3PRM_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-prmmasterdata-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_mc3prm&task=prmmasterdata.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[trash]" value="<?php echo $item->trash; ?>" />
											<input type="hidden" name="jform[customer_id]" value="<?php echo $item->customer_id; ?>" />
											<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
											<input type="hidden" name="jform[last_name]" value="<?php echo $item->last_name; ?>" />
											<input type="hidden" name="jform[first_name]" value="<?php echo $item->first_name; ?>" />
											<input type="hidden" name="jform[gender]" value="<?php echo $item->gender; ?>" />
											<input type="hidden" name="jform[dob]" value="<?php echo $item->dob; ?>" />
											<input type="hidden" name="jform[address_name]" value="<?php echo $item->address_name; ?>" />
											<input type="hidden" name="jform[address_name_add]" value="<?php echo $item->address_name_add; ?>" />
											<input type="hidden" name="jform[address_street]" value="<?php echo $item->address_street; ?>" />
											<input type="hidden" name="jform[address_house_no]" value="<?php echo $item->address_house_no; ?>" />
											<input type="hidden" name="jform[zip]" value="<?php echo $item->zip; ?>" />
											<input type="hidden" name="jform[country]" value="<?php echo $item->country; ?>" />
											<input type="hidden" name="jform[phone]" value="<?php echo $item->phone; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[supervisor_name]" value="<?php echo $item->supervisor_name; ?>" />
											<input type="hidden" name="jform[supervisor_phone]" value="<?php echo $item->supervisor_phone; ?>" />
											<input type="hidden" name="jform[supervisor_desc]" value="<?php echo $item->supervisor_desc; ?>" />
											<input type="hidden" name="jform[email]" value="<?php echo $item->email; ?>" />
											<input type="hidden" name="jform[web]" value="<?php echo $item->web; ?>" />
											<input type="hidden" name="option" value="com_mc3prm" />
											<input type="hidden" name="task" value="prmmasterdata.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_mc3prm')):
									?>
										<a href="javascript:document.getElementById('form-prmmasterdata-delete-<?php echo $item->id; ?>').submit()"><?php echo JText::_("COM_MC3PRM_DELETE_ITEM"); ?></a>
										<form id="form-prmmasterdata-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_mc3prm&task=prmmasterdata.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[trash]" value="<?php echo $item->trash; ?>" />
											<input type="hidden" name="jform[customer_id]" value="<?php echo $item->customer_id; ?>" />
											<input type="hidden" name="jform[title]" value="<?php echo $item->title; ?>" />
											<input type="hidden" name="jform[last_name]" value="<?php echo $item->last_name; ?>" />
											<input type="hidden" name="jform[first_name]" value="<?php echo $item->first_name; ?>" />
											<input type="hidden" name="jform[gender]" value="<?php echo $item->gender; ?>" />
											<input type="hidden" name="jform[dob]" value="<?php echo $item->dob; ?>" />
											<input type="hidden" name="jform[address_name]" value="<?php echo $item->address_name; ?>" />
											<input type="hidden" name="jform[address_name_add]" value="<?php echo $item->address_name_add; ?>" />
											<input type="hidden" name="jform[address_street]" value="<?php echo $item->address_street; ?>" />
											<input type="hidden" name="jform[address_house_no]" value="<?php echo $item->address_house_no; ?>" />
											<input type="hidden" name="jform[zip]" value="<?php echo $item->zip; ?>" />
											<input type="hidden" name="jform[country]" value="<?php echo $item->country; ?>" />
											<input type="hidden" name="jform[phone]" value="<?php echo $item->phone; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[supervisor_name]" value="<?php echo $item->supervisor_name; ?>" />
											<input type="hidden" name="jform[supervisor_phone]" value="<?php echo $item->supervisor_phone; ?>" />
											<input type="hidden" name="jform[supervisor_desc]" value="<?php echo $item->supervisor_desc; ?>" />
											<input type="hidden" name="jform[email]" value="<?php echo $item->email; ?>" />
											<input type="hidden" name="jform[web]" value="<?php echo $item->web; ?>" />
											<input type="hidden" name="option" value="com_mc3prm" />
											<input type="hidden" name="task" value="prmmasterdata.remove" />
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
            echo JText::_('COM_MC3PRM_NO_ITEMS');
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


									<?php if(JFactory::getUser()->authorise('core.create','com_mc3prm')): ?><a href="<?php echo JRoute::_('index.php?option=com_mc3prm&task=prmmasterdata.edit&id=0'); ?>"><?php echo JText::_("COM_MC3PRM_ADD_ITEM"); ?></a>
	<?php endif; ?>