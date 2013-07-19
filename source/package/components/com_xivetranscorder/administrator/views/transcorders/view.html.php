<?php
/**
 * @version     5.0.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (c) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Xivetranscorder.
 */
class XivetranscorderViewTranscorders extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->state		= $this->get('State');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors));
		}
        
		XivetranscorderHelper::addSubmenu('transcorders');
        
		$this->addToolbar();
        
        $this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		require_once JPATH_COMPONENT.'/helpers/xivetranscorder.php';

		$state	= $this->get('State');
		$canDo	= XivetranscorderHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_XIVETRANSCORDER_TITLE_TRANSCORDERS'), 'transcorders.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/transcorder';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('transcorder.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('transcorder.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('transcorders.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('transcorders.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'transcorders.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('transcorders.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('transcorders.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'transcorders.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('transcorders.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_xivetranscorder');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_xivetranscorder&view=transcorders');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_CATEGORY"),
			'filter_catid',
			JHtml::_('select.options', JHtml::_('category.options', 'com_xivetranscorder.transcorders.catid'), "value", "text", $this->state->get('filter.catid'))

		);

		//Filter for the field distcalc_device
		$select_label = JText::sprintf('COM_XIVETRANSCORDER_FILTER_SELECT_LABEL', 'Distance Calc - Device');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "walk";
		$options[0]->text = "Walk";
		$options[1] = new stdClass();
		$options[1]->value = "car";
		$options[1]->text = "Car";
		$options[2] = new stdClass();
		$options[2]->value = "truck";
		$options[2]->text = "Truck";
		$options[3] = new stdClass();
		$options[3]->value = "train";
		$options[3]->text = "Train";
		$options[4] = new stdClass();
		$options[4]->value = "airplane";
		$options[4]->text = "Airplane";
		$options[5] = new stdClass();
		$options[5]->value = "custom";
		$options[5]->text = "Custom";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_distcalc_device',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.distcalc_device'), true)
		);

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.client_id' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_CLIENT_ID'),
		'a.state' => JText::_('JSTATUS'),
		'a.catid' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_CATID'),
		'a.contact_id' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_CONTACT_ID'),
		'a.order_id' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_ORDER_ID'),
		'a.transport_timestamp' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_TRANSPORT_TIMESTAMP'),
		'a.distcalc_device' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_DISTCALC_DEVICE'),
		'a.estimated_distance' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_ESTIMATED_DISTANCE'),
		'a.estimated_time' => JText::_('COM_XIVETRANSCORDER_TRANSCORDERS_ESTIMATED_TIME'),
		);
	}

    
}
