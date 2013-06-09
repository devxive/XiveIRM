<?php
/**
 * @version     3.0.0
 * @package     com_medtranscoord
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * Additionalinformation controller class.
 */
class MedtranscoordControllerAdditionalinformation extends JControllerForm
{

    function __construct() {
        $this->view_list = 'additionalinformations';
        parent::__construct();
    }

}