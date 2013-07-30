<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xiveirm_alertbox_mymessages
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$icon1 = array('icon' => $params->get('icon1_icon'), 'color' => $params->get('icon1_color'), 'title' => $params->get('icon1_title'), 'link' => $params->get('icon1_link'));
$icon2 = array('icon' => $params->get('icon2_icon'), 'color' => $params->get('icon2_color'), 'title' => $params->get('icon2_title'), 'link' => $params->get('icon2_link'));
$icon3 = array('icon' => $params->get('icon3_icon'), 'color' => $params->get('icon3_color'), 'title' => $params->get('icon3_title'), 'link' => $params->get('icon3_link'));
$icon4 = array('icon' => $params->get('icon4_icon'), 'color' => $params->get('icon4_color'), 'title' => $params->get('icon4_title'), 'link' => $params->get('icon4_link'));
?>
					<li class="green no-border margin-1">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-envelope-alt icon-animated-vertical icon-only"></i>
							<span class="badge badge-success">5</span>
						</a>
						<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-envelope"></i> 5 neue Nachrichten
							</li>
							
							<li>
								<a href="#">
									<img alt="Alex's Avatar" class="msg-photo" src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/avatars/avatar.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">94/11:</span>
											Hi, Ich bin frei in Naumburg ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>vor einer Minute</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									<img alt="Susan's Avatar" class="msg-photo" src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/avatars/avatar3.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">DRK HU:</span>
											Morgen, k&ouml;nntet Ihr uns einen Patien ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>vor 20 Minuten</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									<img alt="Bob's Avatar" class="msg-photo" src="<?php echo $this->baseurl . '/templates/' . $this->template; ?>/assets/avatars/avatar4.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">Uwe Walz:</span>
											Bitte an das Teammeeting nachher denken. Steht im Kal ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>15:12 Uhr</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									Alle Nachrichten anzeigen
									<i class="icon-arrow-right"></i>
								</a>
							</li>									
	
						</ul>
					</li>
