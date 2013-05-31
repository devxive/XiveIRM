<?php
/**
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataWidgets.corewidget
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     XAP.Plugin
 * @subpackage  IRMMasterDataWidgets.corewidget
 * @since       3.0
 */
class PlgIrmmasterdatawidgetsCorewidget extends JPlugin
{
	/**
	 * Stores the tab name
	 * @var	tabId
	 * @since	3.1
	 */
	var $tabId;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->tabId = 'corewidget';
		$this->loadLanguage();
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabId = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainer(&$item = null, &$params = null)
	{
//		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&amp;w=350&amp;h=315&amp;fr=0&amp;frc=000000&amp;bg=FFFFFF&amp;hl2=A5A5A5&amp;fc=000000&amp;lc=000000&amp;ff=Arial&amp;fs=10&amp;lnc=000000&amp;hdc=000000&amp;app=miniapp&amp;loc=http%3A//plzkarte.com/plz-suche/';
		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&fr=0&bg=FFF&hl2=FC0&fc=000&lc=000000&ff=Verdana&fs=10&lnc=000000&hdc=000000';

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabId; ?> ---------->
		<div class="alert alert-error">
			<h4>Please note:</h4>
			This is just a demo widget without any functions!
		</div>
		<input id="pinput" name="test" class="span6" />
		<div class="widget-box light-border">
			<div class="widget-header header-color-dark">
				<h5 class="smaller">Core Widget</h5>
				<div class="widget-toolbar">
					<span class="badge badge-important" data-rel="tooltip" data-placement="bottom" data-original-title="Patient hat akute Infektionen!">Infektionsgefahr</span>
				</div>
 				<div class="widget-toolbar">
 					<span class="label label-warning">1.2% <i class="icon-arrow-down"></i></span>
 					<span class="badge badge-info" data-rel="tooltip" data-placement="bottom" data-original-title="Leichter R&uuml;ckgang der Fahrauftr&auml;ge im vergleich zum Vorjahreszeitraum">info</span>
	 			</div>
 				<div class="widget-toolbar">
					<div class="progress progress-mini progress-danger progress-striped active" style="width:100px;" data-percent="61%" data-rel="tooltip" data-placement="bottom" data-original-title="Vollst&auml;ndigkeit des Kundenprofil">
						<div class="bar" style="width:61%"></div>
					</div>
	 			</div>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<?php if($item->modified): ?>
						<div class="alert alert-warning center">
							<small>
								<?php echo JText::_('COM_XIVEIRM_IRMMASTERDATA_FORM_LAST_MODIFIED') . ' ' . date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)); ?>
							</small>
						</div>
					<?php endif; ?>
					<p>
						Basierend auf den uns vorliegenden Informationen zu den angegebenen Infektionskrankheiten haben wir folgende Artikel rescherchiert:<br>
						<ul>
							<li><div style="background: url(http://upload.wikimedia.org/wikipedia/commons/9/9e/Wikipedia-logo-v2-de.svg) 50% 20% no-repeat; height: 100px; width: 105px; zoom: 15%; display: inline-table; vertical-align: middle;"></div> <a href="http://de.wikipedia.org/w/index.php?search=hiv#.C3.9Cbertragung" target="_blank">Hinweise zur &Uuml;bertragung von HIV bei Wikipedia</a></li>
							<li><i class="icon-google-plus-sign"></i>  <a href="#" target="_blank">Artikel zu HIV im Google DocsWiki</a></li>
						</ul>
						Wobei man folgendes nicht ausschliessen kann:<br>
						Maecenas id erat
						vel sem convallis blandit. Nunc aliquam enim ut arcu aliquet adipiscing. Fusce dignissim volutpat justo non
						consectetur. Nulla fringilla eleifend consectetur. Etiam justo nisl, gravida id egestas eu, eleifend vel metus.
						Pellentesque tellus ipsum, euismod in facilisis quis, aliquet quis sem.
					</p>
				</div>
				<div class="widget-toolbox padding-5 clearfix">
					<div class="center">
						<small>Dieses und weitere n&uuml;tzliche Widgets finden Sie auf unserer Website <a href="#">devXive - research and development</a></small>
					</div>
				</div>
			</div>
		</div>

		<div class="widget-box small-margin-top">
			<div class="widget-header" style="background: url(/images/system/widgets/logo_deutschepost.png) 95% 40% no-repeat #FC0; height: 31px;">
				<h5 onClick="hanna()">Postleitzahlsuche</h5>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="">
					<div class="widget-main">
						<iframe id="plzsifr" name="plzsifr" src="<?php echo $plzUrl; ?>" style="width:100%; height:315px;" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" vspace="0"></iframe>
					</div>
				</div>
			</div>
		</div>

		<!---------- End output buffering: <?php echo $this->tabId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tabId' => $this->tabId,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}
}
?>