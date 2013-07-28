<?php
/**
 * @package     XAP.Plugin
 * @subpackage  IRMWidgetsTranscorder.corewidgettoca
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     XAP.Plugin
 * @subpackage  IRMWidgetsTranscorder.corewidgettoca
 * @since       5.0
 */
class PlgIrmWidgetsTranscorderCorewidgetToca extends JPlugin
{
	/**
	 * Stores the tab app name
	 * @var	tab_key
	 * @since	3.1
	 */
	var $tab_key;

	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->tab_key = 'corewidgettoca';
		$this->loadLanguage();
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tab_key = The tab identification, tabContent = Content of the Container
	 *
	 * @since   5.0
	 */
	public function loadInBasedataContainerFirst(&$item = null, &$params = null)
	{
//		echo '<pre>';
//		print_r($item);
//		echo '</pre>';
		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tab_key; ?> ---------->

		<div class="widget-box light-border small-margin-top">
			<div class="widget-header header-color-dark">
				<h5 class="smaller">Core Widget</h5>
 				<div class="widget-toolbar">
 					<span class="label label-warning">1.2% <i class="icon-arrow-down"></i></span>
 					<span class="badge badge-info" data-rel="tooltip" data-placement="bottom" data-original-title="Leichter R&uuml;ckgang der Fahrauftr&auml;ge im vergleich zum Vorjahreszeitraum">info</span>
	 			</div>
 				<div class="widget-toolbar">
					<div class="progress progress-danger progress-striped active" style="width:100px;" data-percent="70%" data-rel="tooltip" data-placement="bottom" data-original-title="Progress">
						<div class="bar" style="width:70%"></div>
					</div>
	 			</div>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<div class="alert alert-warning center">
						<small>
							<?php
								if($item->modified && $item->modified != '0000-00-00 00:00:00') {
									echo JText::_('PLG_IRMWIDGETSTRANSCORDER_COREWIDGET_LAST_MODIFIED') . ': ' . date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified));
								} else {
									echo JText::_('PLG_IRMWIDGETSTRANSCORDER_COREWIDGET_NOT_MODIFIED');
								}
							?>
						</small>
					</div>
				</div>
			</div>
		</div>

		<!---------- End output buffering: <?php echo $this->tab_key; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tab_key' => $this->tab_key,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			tab_key = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function loadInBasedataContainerLast(&$item = null, &$params = null)
	{
//		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&amp;w=350&amp;h=315&amp;fr=0&amp;frc=000000&amp;bg=FFFFFF&amp;hl2=A5A5A5&amp;fc=000000&amp;lc=000000&amp;ff=Arial&amp;fs=10&amp;lnc=000000&amp;hdc=000000&amp;app=miniapp&amp;loc=http%3A//plzkarte.com/plz-suche/';
		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&fr=0&bg=FFF&hl2=FC0&fc=000&lc=000000&ff=Verdana&fs=10&lnc=000000&hdc=000000';

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->tab_key; ?> ---------->

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

		<!---------- End output buffering: <?php echo $this->tab_key; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'tab_key' => $this->tab_key,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}
}
?>