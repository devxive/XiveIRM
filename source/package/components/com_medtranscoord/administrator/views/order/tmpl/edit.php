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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_medtranscoord/assets/css/medtranscoord.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'order.cancel'){
            Joomla.submitform(task, document.getElementById('order-form'));
        }
        else{
            
            if (task != 'order.cancel' && document.formvalidator.isValid(document.id('order-form'))) {
                
                Joomla.submitform(task, document.getElementById('order-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_medtranscoord&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="order-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('modified'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('modified'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('client_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('client_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('masterdata_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('masterdata_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('order_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('order_id'); ?></div>
			</div>
				<input type="hidden" name="jform[customer_fullname]" value="<?php echo $this->item->customer_fullname; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('transport_timestamp'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('transport_timestamp'); ?></div>
			</div>
				<input type="hidden" name="jform[f_poi_id]" value="<?php echo $this->item->f_poi_id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_name_add'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_name_add'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_street'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_street'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_houseno'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_houseno'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_zip'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_zip'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_city'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_city'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_country'); ?></div>
			</div>
				<input type="hidden" name="jform[f_address_lat]" value="<?php echo $this->item->f_address_lat; ?>" />
				<input type="hidden" name="jform[f_address_long]" value="<?php echo $this->item->f_address_long; ?>" />
				<input type="hidden" name="jform[f_address_hash]" value="<?php echo $this->item->f_address_hash; ?>" />
				<input type="hidden" name="jform[t_poi_id]" value="<?php echo $this->item->t_poi_id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_name_add'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_name_add'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_street'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_street'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_houseno'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_houseno'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_zip'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_zip'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_city'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_city'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_address_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_address_country'); ?></div>
			</div>
				<input type="hidden" name="jform[t_address_lat]" value="<?php echo $this->item->t_address_lat; ?>" />
				<input type="hidden" name="jform[t_address_long]" value="<?php echo $this->item->t_address_long; ?>" />
				<input type="hidden" name="jform[t_address_hash]" value="<?php echo $this->item->t_address_hash; ?>" />
				<input type="hidden" name="jform[distcalc_device]" value="<?php echo $this->item->distcalc_device; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('estimated_distance'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('estimated_distance'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('estimated_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('estimated_time'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>