<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xiveirm_alertbox_clientactivities
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
					<li class="purple no-border margin-1">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-bell-alt icon-animated-bell icon-only"></i>
							<span class="badge badge-important">8</span>
						</a>
						<ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-warning-sign"></i> 8 Meldungen
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-comment btn btn-mini btn-pink"></i> Neue Kommentare</span>
										<span class="pull-right badge badge-info">+12</span>
									</div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<i class="icon-user btn btn-mini btn-primary"></i> Thomas hat sich angemeldet ...
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-shopping-cart btn btn-mini btn-success"></i> Neue Fahrauftr&auml;ge</span>
										<span class="pull-right badge badge-success">+8</span>
									</div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-twitter btn btn-mini btn-info"></i> Fahrzeugupdates</span>
										<span class="pull-right badge badge-info">+4</span>
									</div>
								</a>
							</li>
																
							<li>
								<a href="#">
									Alle Mitteilungen anzeigen
									<i class="icon-arrow-right"></i>
								</a>
							</li>
						</ul>
					</li>

