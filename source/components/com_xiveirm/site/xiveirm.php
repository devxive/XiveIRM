<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/helpers/xiveirm.php';
require_once JPATH_COMPONENT.'/helpers/irmsystem.php';

// $irm = new IRMSystem();

// Include dependancies
jimport('joomla.application.component.controller');

// Execute the task.
$controller	= JControllerLegacy::getInstance('Xiveirm');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
