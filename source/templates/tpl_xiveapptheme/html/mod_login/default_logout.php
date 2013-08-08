<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// JHtml::_('behavior.keepalive');

?>
<li class="user-profile no-border margin-1">
	<a class="user-menu dropdown-toggle" href="#" data-toggle="dropdown">
		<img alt="Gravatar Photo" src="http://gravatar.com/avatar/<?php echo md5(strtolower(trim($user->get('email')))); ?>?size=50" class="nav-user-photo" />
		<span id="user_info">
			<small><?php echo JText::_('MOD_LOGIN_GREETING'); ?>, </small>
			<?php if ($params->get('name') == 0) : {
				echo $user->get('name');
			} else : {
				echo $user->get('username');
			} endif; ?>
		</span>
		<i class="icon-caret-down"></i>
	</a>
	<ul id="user_menu" class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
		<li><a href="<?php echo JRoute::_('#', true, $params->get('usesecure')); ?>"><i class="icon-cog"></i> <?php echo JText::_('TPL_XIVEAPPTHEME_SETTINGS'); ?></a></li>
		<li><a href="<?php echo JRoute::_('index.php?option=com_users&view=profile', true, $params->get('usesecure')); ?>"><i class="icon-user"></i> <?php echo JText::_('TPL_XIVEAPPTHEME_EDIT_PROFILE'); ?></a></li>
		<li class="divider"></li>
		<li>
			<a href="javascript:document.getElementById('form-logout').submit()"><i class="icon-off"></i> <?php echo JText::_('JLOGOUT'); ?></a>
			<form class="hidden" id="form-logout" action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post">
				<input type="hidden" name="option" value="com_users" />
				<input type="hidden" name="task" value="user.logout" />
				<input type="hidden" name="return" value="<?php echo $return; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</li>
	</ul>
</li>