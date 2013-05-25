<?php
/**
 * @version     3.0.0
 * @package     com_mc3prm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of Mc3prm.
 */
class Mc3prmViewPrmmasterdatas extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
    protected $params;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
        $app                = JFactory::getApplication();
        
        $this->state		= $this->get('State');
        $this->items		= $this->get('Items');
        $this->pagination	= $this->get('Pagination');
        $this->params       = $app->getParams('com_mc3prm');
        
        // Check for errors.
        if (count($errors = $this->get('Errors'))) {;
            throw new Exception(implode("\n", $errors));
        }
        
        $this->_prepareDocument();
        parent::display($tpl);
	}


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app	= JFactory::getApplication();
		$menus	= $app->getMenu();
		$title	= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', JText::_('com_mc3prm_DEFAULT_PAGE_TITLE'));
		}
		$title = $this->params->get('page_title', '');
		if (empty($title)) {
			$title = $app->getCfg('sitename');
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 1) {
			$title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
		}
		elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
			$title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
		}
		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}    
    	
}

// Javascript added at template bottom
$session = JFactory::getSession();

$xap_scripts_loadBottomBody = '
	<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.dataTables.bootstrap.js"></script>
';

$xap_scripts_loadBottomBody .= '
	<script type="text/javascript">
		$(function() {
			var oTable1 = $(\'#table_report\').dataTable( {
				"aoColumns": [
					{ "bSortable": false },
					null, null,null, null, null,
					{ "bSortable": false }
				]
			});
	
			$(\'table th input:checkbox\').on(\'click\' , function(){
				var that = this;
				$(this).closest(\'table\').find(\'tr > td:first-child input:checkbox\')
				.each(function(){
				this.checked = that.checked;
				$(this).closest(\'tr\').toggleClass(\'selected\');
			});
			
		});

		$(\'[data-rel=tooltip]\').tooltip();
		})
		</script>
';

$session->set('xap_scripts_loadBottomBody', $xap_scripts_loadBottomBody);
