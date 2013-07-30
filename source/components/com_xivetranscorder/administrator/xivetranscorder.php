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

require_once JPATH_SITE.'/components/com_xiveirm/helpers/xiveirm.php';
require_once JPATH_SITE.'/components/com_xiveirm/helpers/irmsystem.php';

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_xivetranscorder')) 
{
	throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

// Include dependancies
jimport('joomla.application.component.controller');
nimport('NItem.Helper', false);

$controller	= JControllerLegacy::getInstance('Xivetranscorder');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();