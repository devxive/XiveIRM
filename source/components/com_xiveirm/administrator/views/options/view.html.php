<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Xiveirm.
 */
class XiveirmViewOptions extends JViewLegacy
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
        
		XiveirmHelper::addSubmenu('options');
        
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
		require_once JPATH_COMPONENT.'/helpers/xiveirm.php';

		$state	= $this->get('State');
		$canDo	= XiveirmHelper::getActions($state->get('filter.category_id'));

		JToolBarHelper::title(JText::_('COM_XIVEIRM_TITLE_OPTIONS'), 'options.png');

        //Check if the form exists before showing the add/edit buttons
        $formPath = JPATH_COMPONENT_ADMINISTRATOR.'/views/option';
        if (file_exists($formPath)) {

            if ($canDo->get('core.create')) {
			    JToolBarHelper::addNew('option.add','JTOOLBAR_NEW');
		    }

		    if ($canDo->get('core.edit') && isset($this->items[0])) {
			    JToolBarHelper::editList('option.edit','JTOOLBAR_EDIT');
		    }

        }

		if ($canDo->get('core.edit.state')) {

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::custom('options.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			    JToolBarHelper::custom('options.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
            } else if (isset($this->items[0])) {
                //If this component does not use state then show a direct delete button as we can not trash
                JToolBarHelper::deleteList('', 'options.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->state)) {
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('options.archive','JTOOLBAR_ARCHIVE');
            }
            if (isset($this->items[0]->checked_out)) {
            	JToolBarHelper::custom('options.checkin', 'checkin.png', 'checkin_f2.png', 'JTOOLBAR_CHECKIN', true);
            }
		}
        
        //Show trash and delete for components that uses the state field
        if (isset($this->items[0]->state)) {
		    if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			    JToolBarHelper::deleteList('', 'options.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    } else if ($canDo->get('core.edit.state')) {
			    JToolBarHelper::trash('options.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_xiveirm');
		}
        
        //Set sidebar action - New in 3.0
		JHtmlSidebar::setAction('index.php?option=com_xiveirm&view=options');
        
        $this->extra_sidebar = '';
        
		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_CATEGORY"),
			'filter_catid',
			JHtml::_('select.options', JHtml::_('category.options', 'com_xiveirm.options.catid'), "value", "text", $this->state->get('filter.catid'))

		);

		JHtmlSidebar::addFilter(
			JText::_("JOPTION_SELECT_ACCESS"),
			'filter_access',
			JHtml::_('select.options', JHtml::_("access.assetgroups", true, true), "value", "text", $this->state->get('filter.access'), true)

		);

        
	}
    
	protected function getSortFields()
	{
		return array(
//		'a.id' => JText::_('JGRID_HEADING_ID'),
		'a.client_id' => JText::_('COM_XIVEIRM_OPTIONS_USERGROUP'),
		'a.catid' => JText::_('COM_XIVEIRM_OPTIONS_CATEGORY'),
		'a.opt_key' => JText::_('COM_XIVEIRM_OPTIONS_OPT_KEY'),
		'a.opt_value' => JText::_('COM_XIVEIRM_OPTIONS_OPT_VALUE'),
		'a.opt_name' => JText::_('COM_XIVEIRM_OPTIONS_OPT_NAME'),
		'a.access' => JText::_('COM_XIVEIRM_OPTIONS_ACCESS'),
		'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
		);
	}

    
}
