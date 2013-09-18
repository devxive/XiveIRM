<?php
/**
 * @version     5.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access.
defined('_JEXEC') or die;

require_once JPATH_COMPONENT.'/controller.php';

/**
 * Transcorders list controller class.
 */
class XivetranscorderControllerTranscorders extends XivetranscorderController
{
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function &getModel($name = 'Transcorders', $prefix = 'XivetranscorderModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}


	/**
	 * Function to set a filter.
	 * What it does?
	 * 		Get the filter from a form GET or POST or an url and set the vale in the userState object
	 *		After that, redirects to the contacts list, where before the list is rendered, the model is called
	 *		In the model we get the userState object and set the where clause for the filter
	 *
	 * @since	4.0
	 */
	public function filter($search_global = null, $search_catid = null, $search_task = null)
	{
		$app = JFactory::getApplication();

		// Get and set the search filter for the filter query (in model)
		$search = array();
		if ( !$search['global'] = $app->input->get('search_global', null, 'string') ) { unset($search['global']); };
		if ( !$search['catid'] = $app->input->get('search_catid', null, 'string') ) { unset($search['catid']); };
		if ( !$search['pdk'] = $app->input->get('search_pdk', null, 'string') ) { unset($search['pdk']); };
		if ( !$search['daterange'] = json_decode($app->input->get('search_daterange', null, 'string')) ) { unset($search['daterange']); };
		if ( !$search['contact'] = $app->input->get('search_contact', null, 'INT') ) { unset($search['contact']); };

		$app->setUserState('com_xivetranscorder.transcorders.filter', $search);

		// Redirect to the list.
		$this->setRedirect(JRoute::_('index.php?option=com_xivetranscorder', false));

$userState = $app->getUserState('com_xivetranscorder.transcorders');
echo '<pre>';
print_r($userState);
echo '</pre>';
	}
}