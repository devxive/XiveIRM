<?php
/**
 * @version     6.0.0
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

// Create shortcuts
$modules = $this->modules;

// Import HTML and Helper Classes
nimport('NHtml.JavaScript');
nimport('NHtml.DataTables');
nimport('NItem.Helper', false);

NHtmlJavaScript::setAutoRemove();
NHtmlJavaScript::setToggle('extended', 'toggleExtend');

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');
?>
<div class="row-fluid">
	<div class="header smaller lighter blue">
		<h1>
			<div>
				<i class="icon-dashboard"></i>
				<span> Dashboard</span> <small><i class="icon-double-angle-right"></i> overview & stats</small>
			</div>
		</h1>
	</div><!--/page-header-->



        <!-- Begin Dashboard Modules -->
        <?php if(count(JModuleHelper::getModules('dashboard-top'))) : ?>
        <div class="row-fluid">
        	<div class="span12">
        		<?php echo $modules->render('dashboard-top', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>
        <?php if(count(JModuleHelper::getModules('dashboard-left')) || count(JModuleHelper::getModules('dashboard-right'))) : ?>
        <div class="row-fluid">
        	<div class="span6">
        		<?php echo $modules->render('dashboard-left', array('style' => 'xhtml'), null); ?>
        	</div>
        	<div class="span6">
        		<?php echo $modules->render('dashboard-right', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>
        <?php if(count(JModuleHelper::getModules('dashboard-bottom'))) : ?>
        <div class="row-fluid">
        	<div class="span12">
        		<?php echo $modules->render('dashboard-bottom', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>
        <!-- End Dashboard Modules -->



	<div class="row-fluid">
		<jdoc:include type="modules" name="dashtop-a" /><!-- dashtop-a -->
		<jdoc:include type="modules" name="dashtop-b" /><!-- dashtop-b -->
		<jdoc:include type="modules" name="dashtop-c" /><!-- dashtop-c -->
		<!-- CONTENT -->
		<jdoc:include type="modules" name="dashbottom-a" /><!-- dashbottom-a -->
		<jdoc:include type="modules" name="dashbottom-b" /><!-- dashbottom-b -->
		<jdoc:include type="modules" name="dashbottom-c" /><!-- dashbottom-c -->
	</div>


	<div class="row-fluid">

<?php $coreApp = 'contacts'; echo substr($coreApp, 0, -1); ?>
<?php echo hash('sha256', 'Papa'); ?>


















<div class="alert alert-block alert-success">
 <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
 <i class="icon-ok green"></i> Welcome to <strong class="green">Ace <small>(v1)</small></strong>,
 the lightweight, feature-rich, easy to use and well-documented admin template.
</div>



<div class="space-6"></div>
<div class="row-fluid">


 <div class="span7 infobox-container">
   
	<div class="infobox infobox-green">
		<div class="infobox-icon"><i class="icon-comments"></i></div>
		<div class="infobox-data">
			<span class="infobox-data-number">32</span>
			<span class="infobox-content">comments + 2 reviews</span>
		</div>
		<div class="stat stat-success">8%</div>
	</div>


	<div class="infobox infobox-blue">
		<div class="infobox-icon"><i class="icon-twitter"></i></div>
		<div class="infobox-data">
			<span class="infobox-data-number">11</span>
			<span class="infobox-content">new followers</span>
		</div>
		<div class="badge badge-success">+32%</div>
	</div>





	<div class="infobox infobox-pink">
		<div class="infobox-icon"><i class="icon-shopping-cart"></i></div>
		<div class="infobox-data">
			<span class="infobox-data-number">8</span>
			<span class="infobox-content">new orders</span>
		</div>
		<div class="stat stat-important">4%</div>
	</div>



	<div class="infobox infobox-red">
		<div class="infobox-icon"><i class="icon-beaker"></i></div>
		<div class="infobox-data">
			<span class="infobox-data-number">7</span>
			<span class="infobox-content">experiemnts</span>
		</div>
	</div>



	<div class="infobox infobox-orange2">
		<div class="infobox-chart">
			<span class="sparkline" data-values="196,128,202,177,154,94,100,170,224"></span>
		</div>
		<div class="infobox-data">
			<span class="infobox-data-number">6,251</span>
			<span class="infobox-content">pageviews</span>
		</div>
		<div class="badge badge-success">7.2% <i class="icon-arrow-up"></i></div>
	</div>
	
	
	<div class="infobox infobox-blue2">
		<div class="infobox-progress">
			<div class="easy-pie-chart percentage" data-percent="42" data-size="46"><span class="percent">42</span>%
			</div>
		</div>
		
		<div class="infobox-data">
			<span class="infobox-text">traffic used</span>
			<span class="infobox-content"><span class="approx">~</span> 58GB remaining</span>
		</div>
	</div>

	
	<div class="space-6"></div>
	
		
	<div class="infobox infobox-small infobox-dark infobox-green">
		<div class="infobox-progress">
			<div class="easy-pie-chart percentage" data-percent="61" data-size="39">
				<span class="percent">61</span>%
			</div>
		</div>
		<div class="infobox-data">
			<span class="infobox-content"><b>Task</b></span>
			<br />
			<span class="infobox-content">Completion</span>
		</div>
	</div>
	
	<div class="infobox infobox-small infobox-dark infobox-blue">
		<div class="infobox-chart">
			<span class="sparkline" data-values="3,4,2,3,4,4,2,2"></span>
		</div>
		<div class="infobox-data">
			<span class="infobox-content"><b>Earnings</b></span>
			<br />
			<span class="infobox-content">$32,000</span>
		</div>
	</div>
	
	<div class="infobox infobox-small infobox-dark infobox-grey">
		<div class="infobox-icon"><i class="icon-download-alt"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><b>Downloads</b></span>
			<br />
			<span class="infobox-content">1,205</span>
		</div>
	</div>


 </div>


 <div class="vspace"></div>


 <div class="span5">
	<div class="widget-box">
		<div class="widget-header widget-header-flat widget-header-small">
			<h5><i class="icon-signal"></i> Traffic Sources</h5>
			<div class="widget-toolbar no-border">
				<button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown">This Week <i class="icon-angle-down icon-on-right"></i></button>
				<ul class="dropdown-menu dropdown-info pull-right dropdown-caret">
					<li class="active"><a href="#">This Week</a></li>
					<li><a href="#">Last Week</a></li>
					<li><a href="#">This Month</a></li>
					<li><a href="#">Last Month</a></li>
				</ul>
			</div>
		</div>
		
		<div class="widget-body">
		 <div class="widget-main">
			<div id="piechart-placeholder"></div>
			
			<div class="hr hr8 hr-double"></div>
			
			<div class="clearfix">
				<div class="grid3">
					<span class="grey"><i class="icon-facebook-sign icon-2x blue"></i> &nbsp; likes</span>
					<h4 class="bigger pull-right">1,255</h4>
				</div>
				
				<div class="grid3">
					<span class="grey"><i class="icon-twitter-sign icon-2x purple"></i> &nbsp; tweets</span>
					<h4 class="bigger pull-right">941</h4>
				</div>
				
				<div class="grid3">
					<span class="grey"><i class="icon-pinterest-sign icon-2x red"></i> &nbsp; pins</span>
					<h4 class="bigger pull-right">1,050</h4>
				</div>
			</div>
		 </div><!--/widget-main-->
		</div><!--/widget-body-->
	</div><!--/widget-box-->


 </div><!--/span-->
 

</div><!--/row-->


<div class="hr hr32 hr-dotted"></div>


<div class="row-fluid">


	<div class="span5">
		<div class="widget-box transparent">
			<div class="widget-header widget-header-flat">
				<h4 class="lighter"><i class="icon-star orange"></i>Popular Domains</h4>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse"><i class="icon-chevron-up"></i></a>
				</div>
			</div>
			
			<div class="widget-body">
			 <div class="widget-main no-padding">
			  <table id="table_bug_report" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th><i class="icon-caret-right blue"></i>name</th>
						<th><i class="icon-caret-right blue"></i>price</th>
						<th class="hidden-phone"><i class="icon-caret-right blue"></i>status</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td class="">internet.com</td>
						<td>
							<small><s class="red">$29.99</s></small>
							<b class="green">$19.99</b>
						</td>
						<td class="hidden-phone"><span class="label label-info arrowed-right arrowed-in">on sale</span></td>
					</tr>
					
					<tr>
						<td class="">online.com</td>
						<td>
							<b class="blue">$16.45</b>
						</td>
						<td class="hidden-phone"><span class="label label-success arrowed-in arrowed-in-right">approved</span></td>
					</tr>
					
					<tr>
						<td class="">newnet.com</td>
						<td>
							<b class="blue">$15.00</b>
						</td>
						<td class="hidden-phone"><span class="label label-important arrowed">pending</span></td>
					</tr>
					<tr>
						<td class="">web.com</td>
						<td>
							<small><s class="red">$19.95</s></small>
							<b class="green">$14.99</b>
						</td>
						<td class="hidden-phone"><span class="label arrowed"><s>out of stock</s></span></td>
					</tr>
					
					<tr>
						<td class="">domain.com</td>
						<td>
							<b class="blue">$12.00</b>
						</td>
						<td class="hidden-phone"><span class="label label-warning arrowed arrowed-right">SOLD</span></td>
					</tr>

				</tbody>
			  </table>
			 </div><!--/widget-main-->
			</div><!--/widget-body-->
		</div><!--/widget-box-->
	</div>


	
	<div class="span7">
	  <div class="widget-box transparent">
		<div class="widget-header widget-header-flat">
			<h4 class="lighter"><i class="icon-signal"></i> Sale Stats</h4>
			<div class="widget-toolbar">
				<a href="#" data-action="collapse"><i class="icon-chevron-up"></i></a>
			</div>
		</div>
		
		<div class="widget-body">
		 <div class="widget-main padding-5">
			<div id="sales-charts"></div>
		 </div><!--/widget-main-->
		</div><!--/widget-body-->
	  </div><!--/widget-box-->
	</div>

</div>



<div class="hr hr32 hr-dotted"></div>



<div class="row-fluid">

 <div class="span6">
	<div class="widget-box transparent">
		
		<div class="widget-header">
			<h4 class="lighter smaller"><i class="icon-rss orange"></i>RECENT</h4>
			<div class="widget-toolbar no-border">
				<ul class="nav nav-tabs" id="recent-tab">
					<li class="active"><a data-toggle="tab" href="#task-tab">Tasks</a></li>
					<li><a data-toggle="tab" href="#member-tab">Members</a></li>
					<li><a data-toggle="tab" href="#comment-tab">Comments</a></li>
				</ul>
			</div>
		</div>
		
		<div class="widget-body">
		 <div class="widget-main padding-5">
			<div class="tab-content padding-8">
				<div id="task-tab" class="tab-pane active">
					<h4 class="smaller lighter green"><i class="icon-list"></i> Sortable Lists</h4>
					<ul id="tasks" class="item-list">
						<li class="item-orange clearfix">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Answering customer questions</span></label>
							<div class="pull-right easy-pie-chart percentage" data-size="30" data-color="#ECCB71" data-percent="42">
								<span class="percent">42</span>%
							</div>
						</li>
						<li class="item-red clearfix">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Fixing bugs</span></label>
							<div class="pull-right">
								<div class="btn-group">
									<button class="btn btn-mini btn-info"><i class="icon-edit"></i></button>
									<button class="btn btn-mini btn-danger "><i class="icon-trash"></i></button>
									<button class="btn btn-mini btn-yellow"><i class="icon-flag"></i></button>
								</div>
							</div>
						</li>
						<li class="item-default clearfix">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Adding new features</span></label>
							<div class="inline pull-right position-relative">
								<button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-angle-down icon-only"></i></button>
								<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-closer">
									<li><a href="#" class="tooltip-success" data-rel="tooltip" title="Mark&nbsp;as&nbsp;done" data-placement="left"><span class="green"><i class="icon-ok"></i></span></a></li>
									<li><a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left"><span class="red"><i class="icon-trash"></i></span></a></li>
								</ul>
							</div>
						</li>
						<li class="item-blue">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Upgrading scripts used in template</span></label>
						</li>
						<li class="item-grey">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Adding new skins</span></label>
						</li>
						<li class="item-green">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Updating server software up</span></label>
						</li>
						<li class="item-pink">
							<label class="inline"><input type="checkbox" /><span class="lbl"> Cleaning up</span></label>
						</li>
					</ul>
				</div>
				
				
				
				<div id="member-tab" class="tab-pane">
					<div class="clearfix">
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Bob's avatar" src="/templates/xapptheme/assets/avatars/user.jpg" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Bob Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">20 min</span></div>
								<div>
									<span class="label label-warning">pending</span>
									<div class="inline position-relative">
										<button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-angle-down icon-only"></i></button>
										<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
											<li><a href="#" class="tooltip-success" data-rel="tooltip" title="Approve" data-placement="right"><span class="green"><i class="icon-ok"></i></span></a></li>
											<li><a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject" data-placement="right"><span class="orange"><i class="icon-remove"></i></span> </a></li>
											<li><a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="right"><span class="red"><i class="icon-trash"></i></span> </a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>
			
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Joe's Avatar" src="/templates/xapptheme/assets/avatars/avatar2.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Joe Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">1 hour</span></div>
								<div>
									<span class="label label-warning">pending</span>
									<div class="inline position-relative">
										<button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-angle-down icon-only"></i></button>
										<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
											<li><a href="#" class="tooltip-success" data-rel="tooltip" title="Approve" data-placement="left"><span class="green"><i class="icon-ok"></i></span></a></li>
											<li><a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject" data-placement="left"><span class="orange"><i class="icon-remove"></i></span> </a></li>
											<li><a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left"><span class="red"><i class="icon-trash"></i></span> </a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>

			  
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Jim's Avatar" src="/templates/xapptheme/assets/avatars/avatar.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Jim Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">2 hour</span></div>
								<div>
									<span class="label label-warning">pending</span>
									<div class="inline position-relative">
										<button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-angle-down icon-only"></i></button>
										<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
											<li><a href="#" class="tooltip-success" data-rel="tooltip" title="Approve" data-placement="right"><span class="green"><i class="icon-ok"></i></span></a></li>
											<li><a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject" data-placement="right"><span class="orange"><i class="icon-remove"></i></span> </a></li>
											<li><a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="right"><span class="red"><i class="icon-trash"></i></span> </a></li>
										</ul>
									</div>
								</div>
							</div>
						</div>


						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Alex's Avatar" src="/templates/xapptheme/assets/avatars/avatar2.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Alex Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">3 hour</span></div>
								<div class="label label-important">blocked</div>
							</div>
						</div>


						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Bob's Avatar" src="/templates/xapptheme/assets/avatars/avatar2.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Bob Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">6 hour</span> </div>
								<div class="label label-success arrowed-in">approved</div>
							</div>
						</div>

						
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Susan's Avatar" src="/templates/xapptheme/assets/avatars/avatar3.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Susan</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">yesterday</span></div>
								<div class="label label-success arrowed-in">approved</div>
							</div>
						</div>

			  
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Phil's Avatar" src="/templates/xapptheme/assets/avatars/avatar4.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Phil Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">2 days ago</span></div>
								<div class="label label-info arrowed-in  arrowed-in-right">online</div>
							</div>
						</div>

						
						<div class="itemdiv memberdiv">
							<div class="user">
								<img alt="Alexa's Avatar" src="/templates/xapptheme/assets/avatars/avatar1.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Alexa Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">3 days ago</span></div>
								<div class="label label-success arrowed-in">approved</div>
							</div>
						</div>
					</div>

					<div class="center">
						<i class="icon-group icon-2x green"></i> &nbsp; <a href="#">See all members &nbsp; <i class="icon-arrow-right"></i></a>
					</div>
					<div class="hr hr-double hr8"></div>

				</div><!-- member-tab -->
				
				
				
				<div id="comment-tab" class="tab-pane">
					<div class="comments">
						<div class="itemdiv commentdiv">
							<div class="user">
								<img alt="Bob's Avatar" src="/templates/xapptheme/assets/avatars/avatar.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Bob Doe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="green">6 min</span></div>
								<div class="text">
									<i class="icon-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
								</div>
							</div>
							
							<div class="tools">
								<div class="inline position-relative">
									<button class="btn btn-minier bigger btn-yellow dropdown-toggle" data-toggle="dropdown"><i class="icon-angle-down icon-only"></i></button>
									<ul class="dropdown-menu dropdown-icon-only dropdown-yellow pull-right dropdown-caret dropdown-close">
										<li><a href="#" class="tooltip-success" data-rel="tooltip" title="Approve" data-placement="left"><span class="green"><i class="icon-ok"></i></span></a></li>
										<li><a href="#" class="tooltip-warning" data-rel="tooltip" title="Reject" data-placement="left"><span class="orange"><i class="icon-remove"></i></span> </a></li>
										<li><a href="#" class="tooltip-error" data-rel="tooltip" title="Delete" data-placement="left"><span class="red"><i class="icon-trash"></i></span> </a></li>
									</ul>
								</div>
							</div>
						</div>
						
						
						<div class="itemdiv commentdiv">
							<div class="user">
								<img alt="Jennifer's Avatar" src="/templates/xapptheme/assets/avatars/avatar1.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Jennifer</a></div>
								<div class="time"><i class="icon-time"></i> <span class="blue">15 min</span></div>
								<div class="text">
									<i class="icon-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip; 
								</div>
							</div>
							
							<div class="tools">
								<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-pencil"></i></a>
								<a href="#" class="btn btn-minier btn-danger"><i class="icon-only icon-trash"></i></a>
							</div>
						</div>
						
						
						<div class="itemdiv commentdiv">
							<div class="user">
								<img alt="Joe's Avatar" src="/templates/xapptheme/assets/avatars/avatar2.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Joe</a></div>
								<div class="time"><i class="icon-time"></i> <span class="orange">22 min</span></div>
								<div class="text">
									<i class="icon-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
								</div>
							</div>
							
							<div class="tools">
								<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-pencil"></i></a>
								<a href="#" class="btn btn-minier btn-danger"><i class="icon-only icon-trash"></i></a>
							</div>
						</div>
						
						
						<div class="itemdiv commentdiv">
							<div class="user">
								<img alt="Rita's Avatar" src="/templates/xapptheme/assets/avatars/avatar3.png" />
							</div>
							
							<div class="body">
								<div class="name"><a href="#">Rita</a></div>
								<div class="time"><i class="icon-time"></i> <span class="red">50 min</span></div>
								<div class="text">
									<i class="icon-quote-left"></i> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis &hellip;
								</div>
							</div>
							
							<div class="tools">
								<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-pencil"></i></a>
								<a href="#" class="btn btn-minier btn-danger"><i class="icon-only icon-trash"></i></a>
							</div>
						</div>
						
					</div>
					
					<div class="hr hr8"></div>
					<div class="center">
							<i class="icon-comments-alt icon-2x green"></i> &nbsp; <a href="#">See all comments &nbsp; <i class="icon-arrow-right"></i></a>
					</div>
					<div class="hr hr-double hr8"></div>
					
				</div>
			</div>
		 </div><!--/widget-main-->
		</div><!--/widget-body-->
		
		
	</div><!--/widget-box-->
 </div><!--/span-->
 
 <div class="span6">
	<div class="widget-box ">
		
		<div class="widget-header">
			<h4 class="lighter smaller"><i class="icon-comment blue"></i>Conversation</h4>
		</div>
		
		<div class="widget-body">
		 <div class="widget-main no-padding">
			
			<div class="dialogs">
				<div class="itemdiv dialogdiv">
					<div class="user">
						<img alt="Alexa's Avatar" src="/templates/xapptheme/assets/avatars/avatar1.png" />
					</div>
					
					<div class="body">
						<div class="time"><i class="icon-time"></i> <span class="green">4 sec</span></div>
						<div class="name"><a href="#">Alexa</a></div>
						<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis. </div>
						
						<div class="tools">
							<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-share-alt"></i></a>
						</div>
					</div>
				</div>
				
				
				<div class="itemdiv dialogdiv">
					<div class="user">
						<img alt="John's Avatar" src="/templates/xapptheme/assets/avatars/avatar.png" />
					</div>
					
					<div class="body">
						<div class="time"><i class="icon-time"></i> <span class="blue">38 sec</span></div>
						<div class="name"><a href="#">John</a></div>
						<div class="text">Raw denim you probably haven't heard of them jean shorts Austin.</div>
						
						<div class="tools">
							<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-share-alt"></i></a>
						</div>
					</div>
				</div>
				
				
				<div class="itemdiv dialogdiv">
					<div class="user">
						<img alt="Bob's avatar" src="/templates/xapptheme/assets/avatars/user.jpg" />
					</div>
					
					<div class="body">
						<div class="time"><i class="icon-time"></i> <span class="orange">2 min</span></div>
						<div class="name"><a href="#">Bob</a> <span class="label label-info arrowed arrowed-in-right">admin</span></div>
						<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque commodo massa sed ipsum porttitor facilisis. </div>
						
						<div class="tools">
							<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-share-alt"></i></a>
						</div>
					</div>
				</div>
				
				
				<div class="itemdiv dialogdiv">
					<div class="user">
						<img alt="Jim's Avatar" src="/templates/xapptheme/assets/avatars/avatar4.png" />
					</div>
					
					<div class="body">
						<div class="time"><i class="icon-time"></i> <span class="muted">3 min</span></div>
						<div class="name"><a href="#">Jim</a></div>
						<div class="text">Raw denim you probably haven't heard of them jean shorts Austin.</div>
						
						<div class="tools">
							<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-share-alt"></i></a>
						</div>
					</div>
				</div>
				
				
				<div class="itemdiv dialogdiv">
					<div class="user">
						<img alt="Alexa's Avatar" src="/templates/xapptheme/assets/avatars/avatar1.png" />
					</div>
					
					<div class="body">
						<div class="time"><i class="icon-time"></i> <span class="green">4 min</span></div>
						<div class="name"><a href="#">Alexa</a></div>
						<div class="text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
						
						<div class="tools">
							<a href="#" class="btn btn-minier btn-info"><i class="icon-only icon-share-alt"></i></a>
						</div>
					</div>
				</div>
			</div>

			
			<form>
				<div class="form-actions input-append">
					<input placeholder="Type your message here ..." type="text" class="width-75" name="message" />
					<button class="btn btn-small btn-info no-radius" onclick="return false;"><i class="icon-share-alt"></i> <span class="hidden-phone">Send</span></button>
				</div>
			</form>
		
		 </div><!--/widget-main-->
		</div><!--/widget-body-->
		
	</div><!--/widget-box-->
 </div><!--/span-->

</div><!--/row-->













		<!--[if lt IE 9]>
		<script type="text/javascript" src="/templates/xapptheme/assets/js/excanvas.min.js"></script>
		<![endif]-->

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery-ui-1.10.2.custom.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.ui.touch-punch.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.slimscroll.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.easy-pie-chart.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.sparkline.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.flot.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.flot.pie.min.js"></script>

		<script type="text/javascript" src="/templates/xapptheme/assets/js/jquery.flot.resize.min.js"></script>






		<!-- inline scripts related to this page -->
		
		<script type="text/javascript">
		
jQuery(document).ready(function() {

	$('.dialogs,.comments').slimScroll({
        height: '300px'
    });
	
	$('#tasks').sortable();
	$('#tasks').disableSelection();
	$('#tasks input:checkbox').removeAttr('checked').on('click', function(){
		if(this.checked) $(this).closest('li').addClass('selected');
		else $(this).closest('li').removeClass('selected');
	});

	var oldie = $.browser.msie && $.browser.version < 9;
	$('.easy-pie-chart.percentage').each(function(){
		var $box = $(this).closest('.infobox');
		var barColor = $(this).data('color') || (!$box.hasClass('infobox-dark') ? $box.css('color') : 'rgba(255,255,255,0.95)');
		var trackColor = barColor == 'rgba(255,255,255,0.95)' ? 'rgba(255,255,255,0.25)' : '#E2E2E2';
		var size = parseInt($(this).data('size')) || 50;
		$(this).easyPieChart({
			barColor: barColor,
			trackColor: trackColor,
			scaleColor: false,
			lineCap: 'butt',
			lineWidth: parseInt(size/10),
			animate: oldie ? false : 1000,
			size: size
		});
	})

	$('.sparkline').each(function(){
		var $box = $(this).closest('.infobox');
		var barColor = !$box.hasClass('infobox-dark') ? $box.css('color') : '#FFF';
		$(this).sparkline('html', {tagValuesAttribute:'data-values', type: 'bar', barColor: barColor , chartRangeMin:$(this).data('min') || 0} );
	});
	
	



  var data = [
	{ label: "social networks",  data: 38.7, color: "#68BC31"},
	{ label: "search engines",  data: 24.5, color: "#2091CF"},
	{ label: "ad campaings",  data: 8.2, color: "#AF4E96"},
	{ label: "direct traffic",  data: 18.6, color: "#DA5430"},
	{ label: "other",  data: 10, color: "#FEE074"}
  ];
 var placeholder = $('#piechart-placeholder').css({'width':'90%' , 'min-height':'150px'});
 $.plot(placeholder, data, {
	
	series: {
        pie: {
            show: true,
			tilt:0.8,
			highlight: {
				opacity: 0.25
			},
			stroke: {
				color: '#fff',
				width: 2
			},
			startAngle: 2
			
        }
    },
    legend: {
        show: true,
		position: "ne", 
	    labelBoxBorderColor: null,
		margin:[-30,15]
    }
	,
	grid: {
		hoverable: true,
		clickable: true
	},
	tooltip: true, //activate tooltip
	tooltipOpts: {
		content: "%s : %y.1",
		shifts: {
			x: -30,
			y: -50
		}
	}
	
 });

 
  var $tooltip = $("<div class='tooltip top in' style='display:none;'><div class='tooltip-inner'></div></div>").appendTo('body');
  placeholder.data('tooltip', $tooltip);
  var previousPoint = null;

  placeholder.on('plothover', function (event, pos, item) {
	if(item) {
		if (previousPoint != item.seriesIndex) {
			previousPoint = item.seriesIndex;
			var tip = item.series['label'] + " : " + item.series['percent']+'%';
			$(this).data('tooltip').show().children(0).text(tip);
		}
		$(this).data('tooltip').css({top:pos.pageY + 10, left:pos.pageX + 10});
	} else {
		$(this).data('tooltip').hide();
		previousPoint = null;
	}
	
 });






		var d1 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d1.push([i, Math.sin(i)]);
		}

		var d2 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.5) {
			d2.push([i, Math.cos(i)]);
		}

		var d3 = [];
		for (var i = 0; i < Math.PI * 2; i += 0.2) {
			d3.push([i, Math.tan(i)]);
		}
		

		var sales_charts = $('#sales-charts').css({'width':'100%' , 'height':'220px'});
		$.plot("#sales-charts", [
			{ label: "Domains", data: d1 },
			{ label: "Hosting", data: d2 },
			{ label: "Services", data: d3 }
		], {
			hoverable: true,
			shadowSize: 0,
			series: {
				lines: { show: true },
				points: { show: true }
			},
			xaxis: {
				tickLength: 0
			},
			yaxis: {
				ticks: 10,
				min: -2,
				max: 2,
				tickDecimals: 3
			},
			grid: {
				backgroundColor: { colors: [ "#fff", "#fff" ] },
				borderWidth: 1,
				borderColor:'#555'
			}
		});


		$('[data-rel="tooltip"]').tooltip();
});




		</script>














	</div>
</div>