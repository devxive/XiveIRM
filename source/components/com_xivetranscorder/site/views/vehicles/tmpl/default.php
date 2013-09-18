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
?>
<script type="text/javascript">
    function deleteItem(item_id){
        if(confirm("<?php echo JText::_('COM_XIVETRANSCORDER_DELETE_MESSAGE'); ?>")){
            document.getElementById('form-vehicle-delete-' + item_id).submit();
        }
    }
</script>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_xivetranscorder'))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&view=vehicle&id=' . (int)$item->id); ?>"><?php echo $item->listname; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_xivetranscorder')):
									?>
										<a href="javascript:document.getElementById('form-vehicle-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_XIVETRANSCORDER_UNPUBLISH_ITEM"); else: echo JText::_("COM_XIVETRANSCORDER_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-vehicle-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=vehicle.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[contact_id]" value="<?php echo $item->contact_id; ?>" />
											<input type="hidden" name="jform[listname]" value="<?php echo $item->listname; ?>" />
											<input type="hidden" name="jform[callname]" value="<?php echo $item->callname; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[navi_id]" value="<?php echo $item->navi_id; ?>" />
											<input type="hidden" name="jform[fleet_car]" value="<?php echo $item->fleet_car; ?>" />
											<input type="hidden" name="jform[fittings]" value="<?php echo $item->fittings; ?>" />
											<input type="hidden" name="jform[license_plate]" value="<?php echo $item->license_plate; ?>" />
											<input type="hidden" name="jform[insurance_id]" value="<?php echo $item->insurance_id; ?>" />
											<input type="hidden" name="jform[insurance_no]" value="<?php echo $item->insurance_no; ?>" />
											<input type="hidden" name="option" value="com_xivetranscorder" />
											<input type="hidden" name="task" value="vehicle.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_xivetranscorder')):
									?>
										<a href="javascript:deleteItem(<?php echo $item->id; ?>);"><?php echo JText::_("COM_XIVETRANSCORDER_DELETE_ITEM"); ?></a>
										<form id="form-vehicle-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=vehicle.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[ordering]" value="<?php echo $item->ordering; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[contact_id]" value="<?php echo $item->contact_id; ?>" />
											<input type="hidden" name="jform[listname]" value="<?php echo $item->listname; ?>" />
											<input type="hidden" name="jform[callname]" value="<?php echo $item->callname; ?>" />
											<input type="hidden" name="jform[mobile]" value="<?php echo $item->mobile; ?>" />
											<input type="hidden" name="jform[navi_id]" value="<?php echo $item->navi_id; ?>" />
											<input type="hidden" name="jform[fleet_car]" value="<?php echo $item->fleet_car; ?>" />
											<input type="hidden" name="jform[fittings]" value="<?php echo $item->fittings; ?>" />
											<input type="hidden" name="jform[license_plate]" value="<?php echo $item->license_plate; ?>" />
											<input type="hidden" name="jform[insurance_id]" value="<?php echo $item->insurance_id; ?>" />
											<input type="hidden" name="jform[insurance_no]" value="<?php echo $item->insurance_no; ?>" />
											<input type="hidden" name="option" value="com_xivetranscorder" />
											<input type="hidden" name="task" value="vehicle.remove" />
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


									<?php if(JFactory::getUser()->authorise('core.create','com_xivetranscorder')): ?><a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=vehicle.edit&id=0'); ?>"><?php echo JText::_("COM_XIVETRANSCORDER_ADD_ITEM"); ?></a>
	<?php endif; ?>