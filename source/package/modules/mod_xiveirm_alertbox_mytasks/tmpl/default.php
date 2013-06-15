<?php
/**
 * @package     XAP.Site
 * @subpackage  mod_xiveirm_alertbox_mytasks
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
					<li class="grey dark no-border margin-1">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-tasks icon-animated-wrench icon-only"></i>
							<span class="badge">4</span>
						</a>
						<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-ok"></i> 4 Aufgaben zu erledigen
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Keine Geburtsdaten</span>
										<span class="pull-right">65%</span>
									</div>
									<div class="progress progress-mini"><div class="bar" style="width:65%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Adressen unvollst&auml;ndig</span>
										<span class="pull-right">35%</span>
									</div>
									<div class="progress progress-mini progress-danger"><div class="bar" style="width:35%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Abrechnungsrelevanz</span>
										<span class="pull-right">15%</span>
									</div>
									<div class="progress progress-mini progress-warning"><div class="bar" style="width:15%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Vorplanung</span>
										<span class="pull-right">90%</span>
									</div>
									<div class="progress progress-mini progress-success progress-striped active"><div class="bar" style="width:90%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									Aufgabendetails anzeigen
									<i class="icon-arrow-right"></i>
								</a>
							</li>
						</ul>
					</li>
