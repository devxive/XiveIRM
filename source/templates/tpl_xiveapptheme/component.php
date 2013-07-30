<?php
/**
 * @package     XAP.Site
 * @subpackage  Templates.XiveAppTheme
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app   = JFactory::getApplication();
$doc   = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');

// Add Stylesheets
$doc->addStyleSheet('media/nawala/css/bootstrap.min.css');
$doc->addStyleSheet('media/nawala/css/bootstrap-responsive.min.css');

// Load optional rtl Bootstrap css and Bootstrap bugfixes
JHtmlBootstrap::loadCss($includeMaincss = false, $this->direction);

/**
 * Load framework dependencies
 */
nimport('NHelper.Template', false);
$templateHelper = new NHelperTemplate();

$templateHelper->addNewCssHead('file', 'font-awesome.css', 'framework');
$templateHelper->addNewCssHead('file', 'nfw-icon-animation.css', 'framework');

/**
 * Load and perform template based settings
 */
$templateHelper->removeGenerator();
$templateHelper->forceIeChromeFrame();

// Added template specific styles
$templateHelper->addNewCssHead('file', 'ace.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'ace-responsive.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'skin5.css', 'xiveapptheme');
$templateHelper->addNewCssHead('file', 'custom.css', 'xiveapptheme');




?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />
<!--[if IE 7]>
  <link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/font-awesome-ie7.min.css" />
<![endif]-->
<!--[if lt IE 9]>
	<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-ie.min.css" />
	<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
<![endif]-->
</head>
<body>
<div class="container-fluid large-padding">
	<div class="row-fluid">
		<jdoc:include type="message" />
		<jdoc:include type="component" />
	</div>
</div>
</body>
</html>
