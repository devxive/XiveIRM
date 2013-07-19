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

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);
?>

<!-- Styling for making front end forms look OK -->
<!-- This should probably be moved to the template CSS file -->
<style>
    .front-end-edit ul {
        padding: 0 !important;
    }
    .front-end-edit li {
        list-style: none;
        margin-bottom: 6px !important;
    }
    .front-end-edit label {
        margin-right: 10px;
        display: block;
        float: left;
        width: 200px !important;
    }
    .front-end-edit .radio label {
        float: none;
    }
    .front-end-edit .readonly {
        border: none !important;
        color: #666;
    }    
    .front-end-edit #editor-xtd-buttons {
        height: 50px;
        width: 600px;
        float: left;
    }
    .front-end-edit .toggle-editor {
        height: 50px;
        width: 120px;
        float: right;
    }

    #jform_rules-lbl{
        display:none;
    }

    #access-rules a:hover{
        background:#f5f5f5 url('../images/slider_minus.png') right  top no-repeat;
        color: #444;
    }

    fieldset.radio label{
        width: 50px !important;
    }
</style>
<script type="text/javascript">
    function getScript(url,success) {
        var script = document.createElement('script');
        script.src = url;
        var head = document.getElementsByTagName('head')[0],
        done = false;
        // Attach handlers for all browsers
        script.onload = script.onreadystatechange = function() {
            if (!done && (!this.readyState
                || this.readyState == 'loaded'
                || this.readyState == 'complete')) {
                done = true;
                success();
                script.onload = script.onreadystatechange = null;
                head.removeChild(script);
            }
        };
        head.appendChild(script);
    }
    getScript('//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js',function() {
        js = jQuery.noConflict();
        js(document).ready(function(){
            js('#form-transcorder').submit(function(event){
                 
            }); 
        
            
        });
    });
    
</script>

<div class="transcorder-edit front-end-edit">
    <?php if (!empty($this->item->id)): ?>
        <h1>Edit <?php echo $this->item->id; ?></h1>
    <?php else: ?>
        <h1>Add</h1>
    <?php endif; ?>

    <form id="form-transcorder" action="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.save'); ?>" method="post" class="form-validate" enctype="multipart/form-data">
        <ul>
            			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('client_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('client_id'); ?></div>
			</div>

			<div class="control-group">
				<?php $canState = false; ?>
					<?php $canState = $canState = JFactory::getUser()->authorise('core.edit.state','com_xivetranscorder'); ?>				<?php if(!$canState): ?>
				<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<?php
						$state_string = 'Unpublish';
						$state_value = 0;
						if($this->item->state == 1):
							$state_string = 'Publish';
							$state_value = 1;
						endif;
					?>
					<div class="controls"><?php echo $state_string; ?></div>
					<input type="hidden" name="jform[state]" value="<?php echo $state_value; ?>" />
				<?php else: ?>
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>					<?php endif; ?>
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
				<div class="control-label"><?php echo $this->form->getLabel('catid'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('catid'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('contact_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('contact_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('order_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('order_id'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('transport_timestamp'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('transport_timestamp'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_poi_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_poi_id'); ?></div>
			</div>
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
				<div class="control-label"><?php echo $this->form->getLabel('f_address_region'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_region'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('f_address_country'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('f_address_country'); ?></div>
			</div>
			<input type="hidden" name="jform[f_address_lat]" value="<?php echo $this->item->f_address_lat; ?>" />
			<input type="hidden" name="jform[f_address_long]" value="<?php echo $this->item->f_address_long; ?>" />
			<input type="hidden" name="jform[f_address_hash]" value="<?php echo $this->item->f_address_hash; ?>" />

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('t_poi_id'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('t_poi_id'); ?></div>
			</div>
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

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('distcalc_device'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('distcalc_device'); ?></div>
			</div>

			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('estimated_distance'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('estimated_distance'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('estimated_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('estimated_time'); ?></div>
			</div>

        </ul>

        <div>
            <button type="submit" class="validate"><span><?php echo JText::_('JSUBMIT'); ?></span></button>
            <?php echo JText::_('or'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_xivetranscorder&task=transcorder.cancel'); ?>" title="<?php echo JText::_('JCANCEL'); ?>"><?php echo JText::_('JCANCEL'); ?></a>

            <input type="hidden" name="option" value="com_xivetranscorder" />
            <input type="hidden" name="task" value="transcorderform.save" />
            <?php echo JHtml::_('form.token'); ?>
        </div>
    </form>
</div>
