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
