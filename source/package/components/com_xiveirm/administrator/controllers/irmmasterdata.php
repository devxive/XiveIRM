<?php
/**
 * @version     3.0.2
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Irmmasterdata controller class.
 */
class XiveirmControllerIrmmasterdata extends JControllerForm
{

    function __construct() {
        $this->view_list = 'irmmasterdatas';
        parent::__construct();
    }

}