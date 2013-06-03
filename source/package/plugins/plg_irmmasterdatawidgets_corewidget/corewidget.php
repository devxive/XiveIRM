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
	 * Stores the tab app name
	 * @var	tabAppId
	 * @since	3.1
	 */
	var $tabAppId;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->tabAppId = 'corewidget';
		$this->loadLanguage();
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabAppId = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainerFirst(&$item = null, &$params = null)
	{
		$counter = 0;
		if(!empty($item->customer_id)) { $counter = $counter + 1; }
		if(!empty($item->last_name)) { $counter = $counter + 1; }
		if(!empty($item->first_name)) { $counter = $counter + 1; }
		if(!empty($item->gender)) { $counter = $counter + 1; }
		if(!empty($item->dob)) { $counter = $counter + 1; }
		if(!empty($item->address_name)) { $counter = $counter + 1; }
		if(!empty($item->address_name_add)) { $counter = $counter + 1; }
		if(!empty($item->address_street)) { $counter = $counter + 1; }
		if(!empty($item->address_houseno)) { $counter = $counter + 1; }
		if(!empty($item->address_zip)) { $counter = $counter + 1; }
		if(!empty($item->address_city)) { $counter = $counter + 1; }
		if(!empty($item->address_country)) { $counter = $counter + 1; }
		if(!empty($item->phone)) { $counter = $counter + 1; }
		if(!empty($item->fax)) { $counter = $counter + 1; }
		if(!empty($item->mobile)) { $counter = $counter + 1; }
		if(!empty($item->email)) { $counter = $counter + 1; }
		if(!empty($item->web)) { $counter = $counter + 1; }
		if(!empty($item->remarks)) { $counter = $counter + 1; }

		if($counter <= 0) { $percent = 0; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 1) { $percent = 2; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 2) { $percent = 5; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 3) { $percent = 10; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 4) { $percent = 20; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 5) { $percent = 25; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 6) { $percent = 30; $barClass = 'progress-danger progress-striped active'; }
		if($counter == 7) { $percent = 35; $barClass = 'progress-warning progress-striped'; }
		if($counter == 8) { $percent = 40; $barClass = 'progress-warning progress-striped'; }
		if($counter == 9) { $percent = 45; $barClass = 'progress-warning progress-striped'; }
		if($counter == 10) { $percent = 50; $barClass = 'progress-warning progress-striped'; }
		if($counter == 11) { $percent = 55; $barClass = 'progress-info progress-striped'; }
		if($counter == 12) { $percent = 60; $barClass = 'progress-info'; }
		if($counter == 13) { $percent = 70; $barClass = 'progress-info'; }
		if($counter == 14) { $percent = 80; $barClass = 'progress-info'; }
		if($counter == 15) { $percent = 90; $barClass = 'progress-success progress-striped'; }
		if($counter == 16) { $percent = 95; $barClass = 'progress-success progress-striped'; }
		if($counter >= 17) { $percent = 100; $barClass = 'progress-success'; }

//		echo '<pre>';
//		print_r($item);
//		echo '</pre>';
		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabAppId; ?> ---------->

		<div class="widget-box light-border">
			<div class="widget-header header-color-dark">
				<h5 class="smaller">Core Widget</h5>
 				<div class="widget-toolbar">
 					<span class="label label-warning">1.2% <i class="icon-arrow-down"></i></span>
 					<span class="badge badge-info" data-rel="tooltip" data-placement="bottom" data-original-title="Leichter R&uuml;ckgang der Fahrauftr&auml;ge im vergleich zum Vorjahreszeitraum">info</span>
	 			</div>
 				<div class="widget-toolbar">
					<div class="progress <?php echo $barClass; ?>" style="width:100px;" data-percent="<?php echo $percent; ?>%" data-rel="tooltip" data-placement="bottom" data-original-title="Vollst&auml;ndigkeit des Kundenprofil">
						<div class="bar" style="width:<?php echo $percent; ?>%"></div>
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
				</div>
			</div>
		</div>

		<!---------- End output buffering: <?php echo $this->tabAppId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tabAppId' => $this->tabAppId,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tabAppId = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainerLast(&$item = null, &$params = null)
	{
//		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&amp;w=350&amp;h=315&amp;fr=0&amp;frc=000000&amp;bg=FFFFFF&amp;hl2=A5A5A5&amp;fc=000000&amp;lc=000000&amp;ff=Arial&amp;fs=10&amp;lnc=000000&amp;hdc=000000&amp;app=miniapp&amp;loc=http%3A//plzkarte.com/plz-suche/';
		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&fr=0&bg=FFF&hl2=FC0&fc=000&lc=000000&ff=Verdana&fs=10&lnc=000000&hdc=000000';

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tabAppId; ?> ---------->

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

		<!---------- End output buffering: <?php echo $this->tabAppId; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tabAppId' => $this->tabAppId,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}
}
?>