<?php
/**
 * @package     XAP.Site
 * @subpackage  Templates.XiveAppTheme
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once(__DIR__ . '/config.php');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo $this->title; ?> <?php echo $this->error->getMessage();?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="language" content="<?php echo $this->language; ?>" />
	<?php
		$debug = JFactory::getConfig()->get('debug_lang');
		if ((defined('JDEBUG') && JDEBUG) || $debug)
		{
	?>
		<link rel="stylesheet" href="<?php echo $this->baseurl ?>/media/cms/css/debug.css" type="text/css" />
	<?php
		}
	?>
	<?php
	// Use of Google Font
	if ($params->get('googleFont'))
	{
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $params->get('googleFontName'));?>', sans-serif;
			}
		</style>
	<?php
	}
	?>
	<link rel="stylesheet" href="/media/nawala/css/bootstrap.min.css" />
	<link rel="stylesheet" href="/media/nawala/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="/media/nawala/css/font-awesome.css" />
	<link rel="stylesheet" href="/media/nawala/css/nfw-icon-animation.css" />

	<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace.css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-responsive.css" />
	<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-skins.css" />

	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<!--[if IE 7]>
	  <link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/font-awesome-ie7.min.css" />
	<![endif]-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-ie.min.css" />
		<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fixedLayout') ? ' navbar-fixed' : '');
?>">
	<div class="navbar navbar-inverse <?php echo ($params->get('fixedLayout') ? ' navbar-fixed-top' : ''); ?>">
		<div class="navbar-inner">
			<div class="container-fluid">
				<a class="brand" href="<?php echo $this->baseurl; ?>"><small><i class="icon-cloud"></i> <?php echo $logo; ?></small></a>
			</div><!--/.container-fluid-->
		</div><!--/.navbar-inner-->
	</div><!--/.navbar-->

	<div class="container-fluid" id="main-container">
		<div id="sidebar" class="<?php echo ($params->get('fixedLayout') ? ' fixed' : ''); ?>">
		</div><!--/#sidebar-->

		<div id="main-content" class="clearfix">
			<div id="page-content" class="clearfix">
				<div id="content" class="row-fluid">
					<!-- APP CONTENT BEGINS HERE -->
					<div class="error-container">
						<div class="well">
							<h1 class="grey lighter smaller">
								<span class="blue bigger-125"><i class="icon-sitemap"></i> <?php echo $this->error->getCode(); ?></span> <?php echo $this->error->getMessage();?>
							</h1>
							<hr />
							<h2 class="lighter smaller"><i class="icon-wrench icon-animated-wrench"></i> We looked everywhere but we couldn't find it!</h2>
							<div>
								<h4 class="smaller"><?php echo JText::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?></h4>
								<ul class="unstyled spaced inline bigger-110">
									<li><i class="icon-hand-up orange"></i> <?php echo JText::_('JERROR_LAYOUT_AN_OUT_OF_DATE_BOOKMARK_FAVOURITE'); ?></li>
									<li><i class="icon-hand-up orange"></i> <?php echo JText::_('JERROR_LAYOUT_SEARCH_ENGINE_OUT_OF_DATE_LISTING'); ?></li>
									<li><i class="icon-hand-up red"></i> <?php echo JText::_('JERROR_LAYOUT_MIS_TYPED_ADDRESS'); ?></li>
									<li><i class="icon-hand-up red"></i> <?php echo JText::_('JERROR_LAYOUT_YOU_HAVE_NO_ACCESS_TO_THIS_PAGE'); ?></li>
								</ul>
							</div>
							<div class="space"></div>
							<div>
								<?php if (JModuleHelper::getModule('search')) : ?>
									<p><strong><?php echo JText::_('JERROR_LAYOUT_SEARCH'); ?></strong></p>
									<p><?php echo JText::_('JERROR_LAYOUT_SEARCH_PAGE'); ?></p>
									<?php
										$module = JModuleHelper::getModule('search');
										echo JModuleHelper::renderModule($module);
									?>
								<?php endif; ?>
								<div class="space"></div>
								<h4 class="smaller">Try one of the following:</h4>
								<ul class="unstyled spaced inline bigger-110">
									<li><i class="icon-hand-right blue"></i> Re-check the url for typos</li>
									<li><i class="icon-hand-right blue"></i> Read the faq</li>
									<li><i class="icon-hand-right blue"></i> Tell us about it</li>
								</ul>
							</div>
							<hr />
							<div class="space"></div>
							<div class="row-fluid">
								<div class="center">
									<a href="javascript:top.history.back();" class="btn btn-grey"><i class="icon-arrow-left"></i> Try to go back</a>
									<a href="<?php echo $this->baseurl; ?>/" class="btn btn-primary"><i class="icon-home"></i> <?php echo JText::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
									<a href="http://devxive.com" class="btn btn-warning" target="_blank"><i class="icon-ticket"></i> Report an Issue</a>
								</div>
							</div>
						</div>
					</div><!--/.error-container-->
					<!-- APP CONTENT ENDS HERE -->
				</div><!--/.row-fluid#content-->
			</div><!--/#page-content-->
		</div><!--/#main-content-->
	</div><!--/.fluid-container#main-container-->

	<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
		<i class="icon-double-angle-up icon-only"></i>
	</a>

	<!-- Footer -->
	<div class="footer">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
		</div>
		<div class="container-fluid" style="text-align: right;">&copy; <?php echo $sitename; ?> <?php echo date('Y');?></div>
		<jdoc:include type="modules" name="debug" style="none" />
	</div>
</body>
</html>