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

//Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_xivetranscorder', JPATH_ADMINISTRATOR);

?>
<?php if ($this->item) : ?>

    <div class="item_fields">

        <ul class="fields_list">

            

        </ul>

    </div>
    
<?php
else:
    echo JText::_('COM_XIVETRANSCORDER_ITEM_NOT_LOADED');
endif;
?>
