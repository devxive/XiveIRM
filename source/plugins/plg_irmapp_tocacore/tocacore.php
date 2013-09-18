<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.tocacore
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

class PlgIrmAppTocacore extends JPlugin
{
	/**
	 * Stores the app name
	 * @var	appKey
	 * @since	6.0
	 */
	var $appKey;


	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);

		$this->appKey = 'tocacore';
		$this->loadLanguage();

	}


	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this contact
	 *
	 * @return  array			appKey = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function htmlBuildWidgetTop( &$item = null, &$params = null )
	{
		// Get Permissions based on category
		if ( !$item->catid ) {
			// We have no category id and use the components acl
			$acl = NFWAccessHelper::getActions('com_xiveirm');
		} else {
			// We have a category id and use the category acl
			$acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $item->catid);
		}

		NFWPluginsSha256::loadSHA256();
		$script = "
			jQuery(document).ready(function() {
				// Triggered on every change in the inner-address-block (this.value determines the actual field)
				$('.inner-address-block input').on('focus', function() {
					var orderDir = $(this).parents('.address-block').attr('data-direction');
					var usher = getUsher( 1, orderDir );

					$('.geo-coords input').val('');
				});
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

		<div class="widget-box widget-box-tabapps light-border small-margin-top">
			<div class="widget-header blue">
				<h5 class="smaller">Toolbar</h5>
 				<div class="widget-toolbar">
					<label>
						<span class="btn-group">
							<a href="javascript:alert('Refresh: In sandbox not available at present')" class="link-control btn btn-mini btn-light"><i class="icon-refresh icon-only"></i></a>
							<a href="javascript:alert('PrintPDF: In sandbox not available at present');" class="link-control btn btn-mini btn-light"><i class="icon-print icon-only"></i></a>
							<a href="javascript:alert('DocUpload: In sandbox not available at present');" class="link-control btn btn-mini btn-light"><i class="icon-cloud-upload icon-only"></i></a>
							<a href="javascript:alert('ShareIt: In sandbox not available at present');" class="link-control btn btn-mini btn-light"><i class="icon-share-alt icon-only"></i></a>
						</span>
					</label>
	 			</div>
			</div>
			<div class="widget-body">
				<div id="core-informations" class="widget-main padding-5 extended">
					<div id="short_info_block" class="alert alert-warning center">
						<small>
							<?php
								if($item->created && $item->created != '0000-00-00 00:00:00') {
									echo JText::sprintf( 'PLG_IRMAPP_TOCACORE_CREATED_ON', date(JText::_('DATE_FORMAT_LC2'), strtotime($item->created)) ) . '<br>';
								}
								if($item->modified && $item->modified != '0000-00-00 00:00:00') {
									echo JText::sprintf('PLG_IRMAPP_TOCACORE_LAST_MODIFIED_ON_BY', date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)), NFWUser::getName($item->modified_by));
								} else {
									echo JText::_('PLG_IRMAPP_TOCACORE_NOT_MODIFIED');
								}
							?>
						</small>
					</div>
				</div>

				<div id="map-body">
					<div id="map-canvas"></div>
				</div>

				<div id="order-body" class="widget-main padding-5">
					<div id="order-canvas"><center class="alert alert-info">No similar orders detected!</center></div>
				</div>

				<div id="icon-descriptions" class="widget-main padding-5 extended small-margin-bottom center grey">
					<span class="xpopover margin-right" data-original-title="Geocoder Verification" data-content="Color based service availability:<br><i class='icon-globe green'></i> Full geo coordinates<br><i class='icon-globe orange'></i> Partially geo coordinates<br><i class='icon-globe red'></i> No geo coordinates<br><small>Geo service works only in full mode.</small>" data-placement="top">
						<i class="icon-globe"></i> 
					</span>
					<span class="xpopover margin-right" data-original-title="Hash Verification" data-content="Color based service availability:<br><i class='icon-anchor green'></i> Full address hashes<br><i class='icon-globe orange'></i> Partially address hashes<br><i class='icon-anchor red'></i> No address hashes<br><small>Hash service works only in full mode.</small>" data-placement="top">
						<i class="icon-anchor"></i> 
					</span>
					<span class="xpopover margin-right" data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_TIME'); ?>" data-content="If available, the estimated duration of the route is displayed." data-placement="top">
						<i class="icon-time"></i>
					</span>
					<span class="xpopover margin-right" data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ESTIMATED_DISTANCE'); ?>" data-content="If available, the estimated distance of the route is displayed." data-placement="top">
						<i class="icon-road"></i>
					</span>
					<span class="xpopover" data-original-title="<?php echo JText::_('COM_XIVETRANSCORDER_FORM_LBL_TRANSCORDER_ORDER_ID'); ?>" data-content="If set, an order id based on the date of execution is displayed. Note: This is not a system id." data-placement="top">
						<span>#</span>
					</span>
				</div>
			</div>
		</div>

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->
		<?php

		$html = ob_get_clean();

		$inMasterContainer = array(
			'appKey' => $this->appKey,
			'html' => $html
		);

		return $inMasterContainer;
	}
}
?>