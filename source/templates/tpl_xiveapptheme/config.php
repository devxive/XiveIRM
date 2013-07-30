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

/**
 * Load the minor core variables
 */
$user = JFactory::getUser();

// Import HTML and Helper Classes
nimport('NHtml.JavaScript');
NHtmlJavaScript::setSiteReadyOverlay();

/**
 * Load framework dependencies
 */
nimport('NHelper.Template', false);
$templateHelper = new NHelperTemplate();

// $templateHelper->addNewJsHead();

$templateHelper->addNewCssHead('file', 'bootstrap.min.css', 'framework');
$templateHelper->addNewCssHead('file', 'bootstrap-responsive.min.css', 'framework');
$templateHelper->addNewCssHead('file', 'font-awesome.css', 'framework');
$templateHelper->addNewCssHead('file', 'nfw-icon-animation.css', 'framework');

/**
 * Load and perform template based settings
 */
// remove unused scripts, styles and tags
// $templateHelper->removeScript('mootools-core.js,caption.js');
// $templateHelper->removeStyle('');
$templateHelper->removeGenerator();
$templateHelper->forceIeChromeFrame();

// Added template specific styles
$templateHelper->addNewCssHead('file', 'ace.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'ace-responsive.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'skin5.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'custom.css', 'xiveapptheme');

// Added template specific scripts
$templateHelper->addNewJsBodyBottom('file', 'uncompressed/ace-elements.js', 'xiveapptheme', '1000');
$templateHelper->addNewJsBodyBottom('file', 'uncompressed/ace.js', 'xiveapptheme', '1001');
$templateHelper->addNewJsBodyBottom('file', 'devxive/template.js', 'xiveapptheme', '5000');

// Getting page class suffix and template path
// $pageclass = $params->get('pageclass_sfx');
// $tpath = $this->baseurl.'/templates/'.$this->template;

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







/**
 * used in /components/com_xiveirm/views/irmmasterdatas/tmpl/default.php
 */
$templateHelper->addNewJsBodyBottom('file', 'chosen.jquery.min.js', 'xiveapptheme', '1004');
$templateHelper->addNewJsBodyBottom('file', '/devxive/jquery.gritter.bootstrap.js', 'xiveapptheme', '1007');
$templateHelper->addNewJsBodyBottom('file', 'jquery.timeago.js', 'xiveapptheme', '1008');




$doc->addScript('/templates/' . $this->template . '/assets/js/uncompressed/ace.js');
$doc->addScript('/templates/' . $this->template . '/assets/js/uncompressed/ace-elements.js');
$doc->addScript('/templates/' . $this->template . '/assets/js/devxive/template.js');






$componentCustomScript = '
	// TimeAgoScript
	jQuery("abbr.timeago").timeago();

';

$templateHelper->addNewJsBodyBottom('custom', $componentCustomScript, 'xiveapptheme', '2000');


// STRIP OUT JUI HEAD DATA

JHtml::_('bootstrap.framework');
$doc = JFactory::getDocument();
$headData = $doc->getHeadData();
$head = (array) $headData['scripts'];
unset($head['/media/jui/js/jquery.min.js']);
unset($head['/media/jui/js/jquery-noconflict.js']);
unset($head['/media/jui/js/bootstrap.min.js']);

// print_r(JFactory::getDocument()->getHeadData());
// print_r($head);

?>