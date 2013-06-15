<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xiveirm_alertbox_mytasks
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

require JModuleHelper::getLayoutPath('mod_xiveirm_alertbox_mytasks', $params->get('layout', 'default'));
