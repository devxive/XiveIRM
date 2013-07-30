<?php
/**
 * @package     XAP.Site
 * @subpackage  Template.XiveAppTheme
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

require_once(__DIR__ . '/config.php');

// Offlinelogo file or site title param
if ($app->getCfg('offline_image'))
{
	$offlinelogo = '<img src="' . $app->getCfg('offline_image') . '" alt="' . htmlspecialchars($app->getCfg('sitename')) . '" style="margin-top: 25px;" />';
}
elseif ($this->params->get('logoFile'))
{
 	$offlinelogo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
}
else
{
	$offlinelogo = '<h1><i class="icon-cloud green"></i> <span class="red">XiveAppTheme</span> <small class="white">by devXive</small></h1>';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<jdoc:include type="head" />
	<?php
	// Use of Google Font
	if ($this->params->get('googleFont'))
	{
	?>
		<link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName'); ?>' rel='stylesheet' type='text/css' />
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
	  <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/media/nawala/css/font-awesome-ie7.min.css" />
	<![endif]-->
	<!--[if lt IE 9]>
		<link rel="stylesheet" href="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/css/ace-ie.min.css" />
		<script src="<?php echo $this->baseurl; ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>
<body class="login-layout" style="background-color: #ccc;">
	
		<div class="container-fluid" id="main-container">
			<div id="main-content">
				<div class="row-fluid">
					<div class="span12">
						
<div class="login-container">

<div class="row-fluid">
	<div class="center">
		<?php echo $offlinelogo; ?>
		<h4 class="blue"><?php echo htmlspecialchars($app->getCfg('sitename')); ?></h4>
	</div>
</div>

<div class="space-6"></div>

<div class="row-fluid">

<div class="position-relative">


	<div id="login-box" class="visible widget-box no-border">

		<div class="widget-body">
		 <div class="widget-main">
			<h4 class="header lighter bigger"><i class="icon-coffee green"></i> Please Enter Your Information</h4>

			<div class="space-6"></div>
			
			<?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != '') : ?>
				<p class="center">
					<?php echo $app->getCfg('offline_message'); ?>
				</p>
				<div class="space-6"></div>
			<?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != '') : ?>
				<p class="center">
					<?php echo JText::_('JOFFLINE_MESSAGE'); ?>
				</p>
				<div class="space-6"></div>
			<?php  endif; ?>

			<p><jdoc:include type="message" /></p>

			<form name="offline-login" action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
				<fieldset>
					<label>
						<span class="block input-icon input-icon-right">
							<input name="username" type="text" class="span12" alt="<?php echo JText::_('TPL_XIVEAPPTHEME_USERNAME'); ?>" placeholder="<?php echo JText::_('TPL_XIVEAPPTHEME_USERNAME'); ?>" required />
							<i class="icon-user"></i>
						</span>
					</label>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="password" name="password" class="span12" alt="<?php echo JText::_('TPL_XIVEAPPTHEME_PASSWORD'); ?>" placeholder="<?php echo JText::_('TPL_XIVEAPPTHEME_PASSWORD'); ?>" required />
							<i class="icon-lock"></i>
						</span>
					</label>
					<div class="space"></div>
					<div class="row-fluid">
						<label class="span8">
							<input type="checkbox" name="remember" value="yes" alt="<?php echo JText::_('TPL_XIVEAPPTHEME_REMEMBER_ME'); ?>"><span class="lbl"> <?php echo JText::_('TPL_XIVEAPPTHEME_REMEMBER_ME'); ?></span>
						</label>
						<button type="submit" onclick="document.offline-login.submit();" class="span4 btn btn-small btn-primary"><i class="icon-key"></i> <?php echo JText::_('TPL_XIVEAPPTHEME_LOGIN'); ?></button>
					</div>
					
				</fieldset>
				<input type="submit" name="Submit" class="hidden" />
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.login" />
				<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</form>

		 </div><!--/widget-main-->
		
		
		 <div class="toolbar clearfix">
			<div>
				<a href="#" onclick="show_box('forgot-box'); return false;" class="forgot-password-link"><i class="icon-arrow-left"></i> I forgot my password</a>
			</div>
			<div>
				<a href="#" onclick="show_box('signup-box'); return false;" class="user-signup-link">I want to register <i class="icon-arrow-right"></i></a>
			</div>
		 </div>
		</div><!--/widget-body-->

	</div><!--/login-box-->
	
	
	
	
	
	
	<div id="forgot-box" class="widget-box no-border">

		<div class="widget-body">
		 <div class="widget-main">
			<h4 class="header red lighter bigger"><i class="icon-key"></i> Retrieve Password</h4>
			
			<div class="space-6"></div>
			
			<p>
				Enter your email and to receive instructions
			</p>
			<form>
				 <fieldset>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="email" class="span12" placeholder="Email" />
							<i class="icon-envelope"></i>
						</span>
					</label>
	
					<div class="row-fluid">
						<button onclick="return false;" class="span5 offset7 btn btn-small btn-danger"><i class="icon-lightbulb"></i> Send Me!</button>
					</div>
					
				  </fieldset>
			</form>
		 </div><!--/widget-main-->
		

		 <div class="toolbar center">
			<a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">Back to login <i class="icon-arrow-right"></i></a>
		 </div>
		</div><!--/widget-body-->

	</div><!--/forgot-box-->
	
	

	
	<div id="signup-box" class="widget-box no-border">

		<div class="widget-body">
		 <div class="widget-main">
			<h4 class="header green lighter bigger"><i class="icon-group blue"></i> New User Registration</h4>

			<div class="alert alert-error center">
				This has been disabled!<br>
				Feel free to contact us to gain access!
			</div>

			<div class="space-6"></div>

			<p>
				Enter your details to begin:
			</p>
			
			<form>
				 <fieldset>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="email" class="span12" placeholder="Email" disabled />
							<i class="icon-envelope"></i>
						</span>
					</label>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="text" class="span12" placeholder="Username" disabled />
							<i class="icon-user"></i>
						</span>
					</label>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="password" class="span12" placeholder="Password" disabled />
							<i class="icon-lock"></i>
						</span>
					</label>
					<label>
						<span class="block input-icon input-icon-right">
							<input type="password" class="span12" placeholder="Repeat password" disabled />
							<i class="icon-retweet"></i>
						</span>
					</label>
					
					<label>
						<input type="checkbox" disabled><span class="lbl"> I accept the <a href="#" disabled>Agreement</a></span>
					</label>
					
					<div class="space-24"></div>
					
					<div class="row-fluid">
						<button type="reset" class="span6 btn btn-small"><i class="icon-refresh" disabled></i> Reset</button>
						<button onclick="return false;" class="span6 btn btn-small btn-success" disabled>Register <i class="icon-arrow-right icon-on-right"></i></button>
					</div>
					
				  </fieldset>
			</form>
		</div>
		

		<div class="toolbar center">
			<a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link"><i class="icon-arrow-left"></i> Back to login</a>
		</div>

	 </div><!--/widget-body-->

	</div><!--/signup-box-->
	
	
</div><!--/position-relative-->
	
</div>


</div>


					</div><!--/span-->
				</div><!--/row-->
			</div>
		</div><!--/.fluid-container-->

		<script type="text/javascript">
				function show_box(id) {
					jQuery('.widget-box.visible').removeClass('visible');
					jQuery('#'+id).addClass('visible');
				}
		</script>
</body>
</html>
