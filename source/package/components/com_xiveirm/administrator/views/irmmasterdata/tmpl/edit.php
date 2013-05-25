<?php
/**
 * @version     3.0.2
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
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
$document->addStyleSheet('components/com_xiveirm/assets/css/xiveirm.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'irmmasterdata.cancel'){
            Joomla.submitform(task, document.getElementById('irmmasterdata-form'));
        }
        else{
            
            if (task != 'irmmasterdata.cancel' && document.formvalidator.isValid(document.id('irmmasterdata-form'))) {
                Joomla.submitform(task, document.getElementById('irmmasterdata-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_xiveirm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="irmmasterdata-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
				<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />

				<?php echo $this->form->getInput('created'); ?>
				<?php if(empty($this->item->created_by)){ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo JFactory::getUser()->id; ?>" />

				<?php } 
				else{ ?>
					<input type="hidden" name="jform[created_by]" value="<?php echo $this->item->created_by; ?>" />

				<?php } ?>				<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
				<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />
				<input type="hidden" name="jform[checked_out_by]" value="<?php echo $this->item->checked_out_by; ?>" />

				<?php echo $this->form->getInput('modified'); ?>				<input type="hidden" name="jform[trash]" value="<?php echo $this->item->trash; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('client_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('client_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('customer_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('customer_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('last_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('last_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('first_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('first_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('gender'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('gender'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('dob'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('dob'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_name_add'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_name_add'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_street'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_street'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_houseno'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_houseno'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_zip'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_zip'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_city'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_city'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('address_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('address_country'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('phone'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('phone'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('fax'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('fax'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('mobile'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('web'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('web'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('remarks'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('remarks'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>