<?php
/**
 * @version     6.1.0
 * @package     com_xivetranscorder
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive - research and development <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Xivetranscorder.
 */
class XivetranscorderViewVehicles extends JViewLegacy
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
        
		XivetranscorderHelper::addSubmenu('vehicles');
        
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

		JToolBarHelper::title(JText::_('COM_XIVETRANSCORDER_TITLE_VEHICLES'), 'vehicles.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/vehicle';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('vehicle.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('vehicle.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('vehicles.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('vehicles.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'vehicles.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('vehicles.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('vehicles.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'vehicles.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('vehicles.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_xivetranscorder');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_xivetranscorder&view=vehicles');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(

			JText::_('JOPTION_SELECT_PUBLISHED'),

			'filter_published',

			JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), "value", "text", $this->state->get('filter.state'), true)

		);

		//Filter for the field fleet_car
		$select_label = JText::sprintf('COM_XIVETRANSCORDER_FILTER_SELECT_LABEL', 'Fleet Car');
		$options = array();
		$options[0] = new stdClass();
		$options[0]->value = "0";
		$options[0]->text = "No";
		$options[1] = new stdClass();
		$options[1]->value = "1";
		$options[1]->text = "Yes";
		JHtmlSidebar::addFilter(
			$select_label,
			'filter_fleet_car',
			JHtml::_('select.options', $options , "value", "text", $this->state->get('filter.fleet_car'), true)
		);

        
	}
    
	protected function getSortFields()
	{
		return array(
		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		'a.state' => JText::_('JSTATUS'),
		'a.contact_id' => JText::_('COM_XIVETRANSCORDER_VEHICLES_CONTACT_ID'),
		'a.listname' => JText::_('COM_XIVETRANSCORDER_VEHICLES_LISTNAME'),
		'a.callname' => JText::_('COM_XIVETRANSCORDER_VEHICLES_CALLNAME'),
		'a.fleet_car' => JText::_('COM_XIVETRANSCORDER_VEHICLES_FLEET_CAR'),
		'a.fittings' => JText::_('COM_XIVETRANSCORDER_VEHICLES_FITTINGS'),
		'a.license_plate' => JText::_('COM_XIVETRANSCORDER_VEHICLES_LICENSE_PLATE'),
		);
	}

    
}
