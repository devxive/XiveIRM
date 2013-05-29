<?php
/**
 * @version     3.1.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;
$jinput = JFactory::getApplication()->input;
// $search = $jinput->get('filter_search', '', 'filter');


// print_r($jinput);



?>





<form id="form-apis" action="<?php echo JRoute::_('index.php?option=com_xiveirm&task=apis.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">
	<input type="text" name="jform[supervisor_name]" placeholder="Supervisor Name" />
	<input type="text" name="jform[supervisor_phone]" placeholder="Supervisor Phone" />
	<input type="text" name="jform[supervisor_desc]" placeholder="Supervisor Description" />
	<input type="hidden" name="option" value="com_xiveirm" />
	<input type="hidden" name="task" value="apis.save" />
	<?php echo JHtml::_('form.token'); ?>
	<button type="submit" class="validate btn btn-info" type="submit">Submit</button>
	&nbsp; &nbsp; &nbsp;
	<button class="btn" type="reset">Reset</button>
</form>
