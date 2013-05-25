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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');

// Import CSS
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_mc3prm/assets/css/mc3prm.css');
?>
<script type="text/javascript">
    js = jQuery.noConflict();
    js(document).ready(function(){
        
	js('input:hidden.prm_id').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('prm_idhidden')){
			js('#jform_prm_id option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_prm_id").trigger("liszt:updated");
	js('input:hidden.physical_state').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('physical_statehidden')){
			js('#jform_physical_state option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_physical_state").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'event.cancel'){
            Joomla.submitform(task, document.getElementById('event-form'));
        }
        else{
            
            if (task != 'event.cancel' && document.formvalidator.isValid(document.id('event-form'))) {
                Joomla.submitform(task, document.getElementById('event-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_mc3prm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="event-form" class="form-validate">
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
				<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
			</div>
				<input type="hidden" name="jform[customer_id]" value="<?php echo $this->item->customer_id; ?>" />
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('prm_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('prm_id'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->prm_id as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="prm_id" name="jform[prm_idhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('physical_state'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('physical_state'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->physical_state as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="physical_state" name="jform[physical_statehidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('start'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('end'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>