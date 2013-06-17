<?php
/**
 * @version     4.2.3
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
        
	js('input:hidden.category').each(function(){
		var name = js(this).attr('name');
		if(name.indexOf('categoryhidden')){
			js('#jform_category option[value="'+js(this).val()+'"]').attr('selected',true);
		}
	});
	js("#jform_category").trigger("liszt:updated");
    });
    
    Joomla.submitbutton = function(task)
    {
        if(task == 'option.cancel'){
            Joomla.submitform(task, document.getElementById('option-form'));
        }
        else{
            
            if (task != 'option.cancel' && document.formvalidator.isValid(document.id('option-form'))) {
                
                Joomla.submitform(task, document.getElementById('option-form'));
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_xiveirm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" enctype="multipart/form-data" name="adminForm" id="option-form" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset class="adminform">

                			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('client_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('client_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('category'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('category'); ?></div>
			</div>

			<?php
				foreach((array)$this->item->category as $value): 
					if(!is_array($value)):
						echo '<input type="hidden" class="category" name="jform[categoryhidden]['.$value.']" value="'.$value.'" />';
					endif;
				endforeach;
			?>			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('opt_key'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('opt_key'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('opt_value'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('opt_value'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('opt_name'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('opt_name'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
			</div>


            </fieldset>
        </div>

        

        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>

    </div>
</form>