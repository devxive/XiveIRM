<?php
/**
 * @package     XAP.Site
 * @subpackage  Templates.XiveAppTheme
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Defining variables before config load, to prevent errors
$show_menu_text = '';

require_once(__DIR__ . '/config.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<?php
	// Use of Google Font
	if ($this->params->get('googleFont'))
	{
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
		<style type="text/css">
			h1,h2,h3,h4,h5,h6,.site-title{
				font-family: '<?php echo str_replace('+', ' ', $this->params->get('googleFontName'));?>', sans-serif;
			}
		</style>
	<?php
	}
	?>
	<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template; ?>/favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/font-awesome-ie7.min.css" />
	<![endif]-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-ie.min.css" />
		<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="skin-5 site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fixedLayout') ? ' navbar-fixed' : ''); ?>
">

	<div id="siteready-overlay" class="loader-overlay">
		<div class="loader-wrapper">
			<div class="loader"></div>
		</div>
	</div>
		<div class="navbar navbar-inverse <?php echo ($params->get('fixedLayout') ? ' navbar-fixed-top' : ''); ?>">
		  <div class="navbar-inner">
		   <div class="container-fluid">

			  <a class="brand" href="<?php echo $this->baseurl; ?>"><small><i class="icon-cloud"></i> <?php echo $logo; ?></small> </a>
			  <ul class="nav ace-nav pull-right">
				<jdoc:include type="modules" name="mytasks" /><!--.mytasks-->
				<jdoc:include type="modules" name="clientactivities" /><!--.clientactivities-->
				<jdoc:include type="modules" name="mymessages" /><!--.mymessages-->
				<jdoc:include type="modules" name="login" /><!--.login-->
			  </ul><!--/.ace-nav-->

		   </div><!--/.container-fluid-->
		  </div><!--/.navbar-inner-->
		</div><!--/.navbar-->

		<div class="container-fluid" id="main-container">

			<a href="#" id="menu-toggler"><span></span></a><!-- menu toggler -->

			<div id="sidebar" class="<?php echo ($params->get('fixedLayout') ? ' fixed' : ''); ?>">
				
				<jdoc:include type="modules" name="sidebar-shortcuts" />
				<!-- #sidebar-shortcuts -->

<!-- #XAP START MODULE -->
				<jdoc:include type="modules" name="sidebar" />
<!-- #XAP END MODULE -->

				<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>

			</div><!--/#sidebar-->

			<div id="main-content" class="clearfix">
					
					<div id="breadcrumbs">
						<jdoc:include type="modules" name="breadcrumbs" /><!--.breadcrumb-->

						<div id="nav-search">
							<jdoc:include type="modules" name="search" /><!--.search-->
						</div><!--#nav-search-->
					</div><!--#breadcrumbs-->

					<div id="page-content" class="clearfix">


						<?php if($show_menu_text == 1) { ?>
							<div class="page-header position-relative">
								<h1><?php if($menu_anchor_icon): echo '<i class="' . $menu_anchor_icon . '"></i> '; endif; ?><?php echo $page_title; ?> <small><i class="icon-double-angle-right"></i> <?php echo $page_heading; ?></small></h1>
							</div><!--/page-header-->
						<?php } ?>



						<div id="content" class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

	<jdoc:include type="modules" name="main-top" style="xhtml" />
	<jdoc:include type="message" />
	<jdoc:include type="component" />
	<jdoc:include type="modules" name="main-bottom" style="none" />


<!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
	
					</div><!--/#page-content-->
					  

<!--
					<div id="ace-settings-container">
						<div class="btn btn-app btn-mini btn-warning" id="ace-settings-btn">
							<i class="icon-cog"></i>
						</div>
						<div id="ace-settings-box">
							<div>
								<div class="pull-left">
									<select id="skin-colorpicker" class="hidden">
										<option data-class="default" value="#438EB9">#438EB9</option>
										<option data-class="skin-1" value="#222A2D">#222A2D</option>
										<option data-class="skin-2" value="#C6487E">#C6487E</option>
										<option data-class="skin-3" value="#D0D0D0">#D0D0D0</option>
									</select>
								</div>
								<span>&nbsp; Choose Skin</span>
							</div>
							<div><input type="checkbox" class="ace-checkbox-2" id="ace-settings-header" /><label class="lbl" for="ace-settings-header"> Fixed Header</label></div>
							<div><input type="checkbox" class="ace-checkbox-2" id="ace-settings-sidebar" /><label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label></div>
						</div>
					</div><!--/#ace-settings-container-->

			</div><!-- #main-content -->

		</div><!--/.fluid-container#main-container-->

		<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only"></i>
		</a>

<!-- Navigation -->
	<jdoc:include type="modules" name="position-1" style="none" />

<!-- Banner -->
	<jdoc:include type="modules" name="banner" style="xhtml" />

<!-- Nav Sidebar left-->
	<jdoc:include type="modules" name="position-7" style="well" />

	<!-- Footer -->
	<div class="footer">
		<div class="container<?php echo ($params->get('fluidContainer') ? '-fluid' : '');?>">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
		</div>
		<div class="container-fluid" style="text-align: right;">&copy; <?php echo $sitename; ?> <?php echo date('Y');?></div>
		<jdoc:include type="modules" name="debug" style="none" />
	</div>

	<?php // $templateHelper->loadJsBodyBottom(); ?>
</body>
</html>
