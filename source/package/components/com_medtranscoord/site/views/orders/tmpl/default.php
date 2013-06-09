<?php
/**
 * @version     3.0.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;
?>

<div class="items">
    <ul class="items_list">
<?php $show = false; ?>
        <?php foreach ($this->items as $item) : ?>

            
				<?php
					if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_medtranscoord'))):
						$show = true;
						?>
							<li>
								<a href="<?php echo JRoute::_('index.php?option=com_medtranscoord&view=order&id=' . (int)$item->id); ?>"><?php echo $item->access_id; ?></a>
								<?php
									if(JFactory::getUser()->authorise('core.edit.state','com_medtranscoord')):
									?>
										<a href="javascript:document.getElementById('form-order-state-<?php echo $item->id; ?>').submit()"><?php if($item->state == 1): echo JText::_("COM_MEDTRANSCOORD_UNPUBLISH_ITEM"); else: echo JText::_("COM_MEDTRANSCOORD_PUBLISH_ITEM"); endif; ?></a>
										<form id="form-order-state-<?php echo $item->id ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_medtranscoord&task=order.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo (int)!((int)$item->state); ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[access_id]" value="<?php echo $item->access_id; ?>" />
											<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
											<input type="hidden" name="jform[customer_cid]" value="<?php echo $item->customer_cid; ?>" />
											<input type="hidden" name="jform[order_id]" value="<?php echo $item->order_id; ?>" />
											<input type="hidden" name="jform[customer_fullname]" value="<?php echo $item->customer_fullname; ?>" />
											<input type="hidden" name="jform[transport_timestamp]" value="<?php echo $item->transport_timestamp; ?>" />
											<input type="hidden" name="jform[f_poi_id]" value="<?php echo $item->f_poi_id; ?>" />
											<input type="hidden" name="jform[f_address_name]" value="<?php echo $item->f_address_name; ?>" />
											<input type="hidden" name="jform[f_address_name_add]" value="<?php echo $item->f_address_name_add; ?>" />
											<input type="hidden" name="jform[f_address_street]" value="<?php echo $item->f_address_street; ?>" />
											<input type="hidden" name="jform[f_address_houseno]" value="<?php echo $item->f_address_houseno; ?>" />
											<input type="hidden" name="jform[f_address_zip]" value="<?php echo $item->f_address_zip; ?>" />
											<input type="hidden" name="jform[f_address_city]" value="<?php echo $item->f_address_city; ?>" />
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
											<input type="hidden" name="option" value="com_medtranscoord" />
											<input type="hidden" name="task" value="order.save" />
											<?php echo JHtml::_('form.token'); ?>
										</form>
									<?php
									endif;
									if(JFactory::getUser()->authorise('core.delete','com_medtranscoord')):
									?>
										<a href="javascript:document.getElementById('form-order-delete-<?php echo $item->id; ?>').submit()"><?php echo JText::_("COM_MEDTRANSCOORD_DELETE_ITEM"); ?></a>
										<form id="form-order-delete-<?php echo $item->id; ?>" style="display:inline" action="<?php echo JRoute::_('index.php?option=com_medtranscoord&task=order.remove'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
											<input type="hidden" name="jform[id]" value="<?php echo $item->id; ?>" />
											<input type="hidden" name="jform[state]" value="<?php echo $item->state; ?>" />
											<input type="hidden" name="jform[created]" value="<?php echo $item->created; ?>" />
											<input type="hidden" name="jform[created_by]" value="<?php echo $item->created_by; ?>" />
											<input type="hidden" name="jform[checked_out]" value="<?php echo $item->checked_out; ?>" />
											<input type="hidden" name="jform[checked_out_time]" value="<?php echo $item->checked_out_time; ?>" />
											<input type="hidden" name="jform[modified]" value="<?php echo $item->modified; ?>" />
											<input type="hidden" name="jform[access_id]" value="<?php echo $item->access_id; ?>" />
											<input type="hidden" name="jform[client_id]" value="<?php echo $item->client_id; ?>" />
											<input type="hidden" name="jform[customer_cid]" value="<?php echo $item->customer_cid; ?>" />
											<input type="hidden" name="jform[order_id]" value="<?php echo $item->order_id; ?>" />
											<input type="hidden" name="jform[customer_fullname]" value="<?php echo $item->customer_fullname; ?>" />
											<input type="hidden" name="jform[transport_timestamp]" value="<?php echo $item->transport_timestamp; ?>" />
											<input type="hidden" name="jform[f_poi_id]" value="<?php echo $item->f_poi_id; ?>" />
											<input type="hidden" name="jform[f_address_name]" value="<?php echo $item->f_address_name; ?>" />
											<input type="hidden" name="jform[f_address_name_add]" value="<?php echo $item->f_address_name_add; ?>" />
											<input type="hidden" name="jform[f_address_street]" value="<?php echo $item->f_address_street; ?>" />
											<input type="hidden" name="jform[f_address_houseno]" value="<?php echo $item->f_address_houseno; ?>" />
											<input type="hidden" name="jform[f_address_zip]" value="<?php echo $item->f_address_zip; ?>" />
											<input type="hidden" name="jform[f_address_city]" value="<?php echo $item->f_address_city; ?>" />
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
											<input type="hidden" name="option" value="com_medtranscoord" />
											<input type="hidden" name="task" value="order.remove" />
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
            echo JText::_('COM_MEDTRANSCOORD_NO_ITEMS');
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


									<?php if(JFactory::getUser()->authorise('core.create','com_medtranscoord')): ?><a href="<?php echo JRoute::_('index.php?option=com_medtranscoord&task=order.edit&id=0'); ?>"><?php echo JText::_("COM_MEDTRANSCOORD_ADD_ITEM"); ?></a>
	<?php endif; ?>