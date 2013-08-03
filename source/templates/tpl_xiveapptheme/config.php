<?php
/**
 * @package     XAP.Site
 * @subpackage  Templates.XiveAppTheme
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Load the major core variables
 */
$app = JFactory::getApplication();
	// Getting template param
	$params = $app->getTemplate(true)->params;
	// Getting menu/site params
	$siteparams = $app->getParams();

	// Detecting active variables
	$option   = $app->input->getCmd('option', '');
	$view     = $app->input->getCmd('view', '');
	$layout   = $app->input->getCmd('layout', '');
	$task     = $app->input->getCmd('task', '');
	$itemid   = $app->input->getCmd('Itemid', '');
	$sitename = $app->getCfg('sitename');

	// Getting menu info
	$menu = $app->getMenu();
	if(isset($menu->getActive()->id)) {
		$menu_id = $menu->getActive()->id;
		$page_title = $menu->getActive()->title;
		$menu_params = $menu->getParams($menu_id);
		$show_page_heading = $menu_params->get('show_page_heading');
		$show_menu_text = $menu_params->get('menu_text');
		$page_heading = $menu_params->get('page_heading');
		$menu_anchor_icon = $menu_params->get('menu-anchor_css');
	} else if($app->input->getCmd('Itemid', '')) {
		$menu_id = $app->input->getCmd('Itemid', '');
	} else {
	}

$doc = JFactory::getDocument(); 
	// Getting language and direction
	$this->language = $doc->language;
	$this->direction = $doc->direction;

// Load the minor core variables
$user = JFactory::getUser();

// Load framework dependencies
NFWHtml::loadJsFramework();
NFWHtml::loadCssFramework();

// Load optional rtl Bootstrap css and Bootstrap bugfixes
NFWHtml::loadCssFramework($includeMaincss = false, $this->direction);

// Added template specific styles
JHtml::_('stylesheet', 'templates/xiveapptheme/assets/css/ace.css', false, false);
JHtml::_('stylesheet', 'templates/xiveapptheme/assets/css/ace-responsive.css', false, false);
JHtml::_('stylesheet', 'templates/xiveapptheme/assets/css/skin5.css', false, false);
JHtml::_('stylesheet', 'nawala/font-awesome.css', false, true);
JHtml::_('stylesheet', 'nawala/nfw-icon-animation.css', false, true);
JHtml::_('stylesheet', 'templates/xiveapptheme/assets/css/custom.css', false, false);

// Added template specific scripts
JHtml::_('script', 'templates/xiveapptheme/assets/js/uncompressed/ace-elements.js', false, false);
JHtml::_('script', 'templates/xiveapptheme/assets/js/uncompressed/ace.js', false, false);
JHtml::_('script', 'templates/xiveapptheme/assets/js/devxive/template.js', false, false);

// Set Siteready overlay function
NFWHtmlJavascript::setSiteReadyOverlay();

// Logo file or site title param
if ($params->get('logoFile'))
{
	$logo = '<img src="'. JURI::root() . $params->get('logoFile') .'" alt="'. $sitename .'" />';
}
elseif ($params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($params->get('sitetitle')) .'</span>';
}
else
{
	$logo = '<span class="site-title" title="XiveAppTheme">XiveAppTheme by devXive</span>';
}