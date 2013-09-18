<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.contactcore
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

/**
 * An example custom profile plugin.
 *
 * @package     IRM.Plugin
 * @subpackage  IRMApp.contactcore
 * @since       6.0
 */
class PlgIrmAppContactcore extends JPlugin
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

		$this->appKey = 'contactcore';
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
		$itemHelper = IRMItemHelper::getContactObject($item->id);

		// Get Permissions based on category
		if ( !$item->catid ) {
			// We have no category id and use the components acl
			$acl = NFWAccessHelper::getActions('com_xiveirm');
		} else {
			// We have a category id and use the category acl
			$acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $item->catid);
		}

		// Check if we have hash values in db to manipulate the script Declaration or the icon-globe class
		if( $item->address_hash && $item->address_hash != hash('sha256', '') ) {
			$address_hash_verified = true;
		} else {
			$address_hash_verified = false;
		}

		// Check if we have hash values in db to manipulate the script Declaration or the icon-globe class
		if( $item->address_hash && $item->address_hash != hash('sha256', '') ) {
			$address_geo_verified = true;
		} else {
			$address_geo_verified = false;
		}

		NFWPluginsSha256::loadSHA256();
		NFWHtmlJavascript::detectChanges();
		$script = "
			jQuery(document).ready(function() {
				// Triggered on every change in the inner-address-block (this.value determines the actual field)
				$('.inner-address-block input').on('keyup', function() {
					$('#address_hash').val('');
					$('#address_lat').val('');
					$('#address_lng').val('');
				});
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

		<div class="widget-box light-border small-margin-top">
			<div class="widget-header header-color-dark">
				<h5 class="smaller">Core Widget</h5>
 				<div class="widget-toolbar">
					<?php if(!$itemHelper->contact->system_checked) { ?>
						<span id="address-hash-verified" class="<?php echo $address_hash_verified ? 'green' : 'red'; ?>" style="vertical-align: middle;">
							<i class="icon-anchor" style="font-size: 17px;"></i> 
						</span>
						<span id="address-geo-verified" class="small-margin-left <?php echo $address_geo_verified ? 'green' : 'red'; ?>" style="vertical-align: middle; display: none;">
							<i class="icon-globe" style="font-size: 18px;"></i>
						</span>
					<?php } else { ?>
						<span id="verified-address" style="vertical-align: middle;">
							<i class="icon-ok-sign" style="font-size: 18px;"></i>
						</span>
					<?php } ?>
	 			</div>
			</div>
			<div class="widget-body">
				<div id="core-informations" class="widget-main padding-5">
					<div id="short_info_block" class="alert alert-warning center extended">
						<small>
							<?php
								if($item->created && $item->created != '0000-00-00 00:00:00') {
									echo JText::sprintf( 'PLG_IRMAPP_CONTACTCORE_CREATED_ON', date(JText::_('DATE_FORMAT_LC2'), strtotime($item->created)) ) . '<br>';
								}
								if($item->modified && $item->modified != '0000-00-00 00:00:00') {
									echo JText::sprintf('PLG_IRMAPP_CONTACTCORE_LAST_MODIFIED_ON_BY', date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified)), NFWUser::getName($item->modified_by));
								} else {
									echo JText::_('PLG_IRMAPP_CONTACTCORE_NOT_MODIFIED');
								}
							?>
						</small>
					</div>
				</div>
			</div>
			<div class="widget-body">
				<div id="map-body" class="widget-main padding-5" style="display:none;">
					<div id="map-canvas" style="height: 250px; width: 100%;"></div>
				</div>
			</div>
		</div>
		<div id="icon-descriptions" class="extended small-margin-top center">
			<span class="xpopover margin-right" data-original-title="Geocoder Verification" data-content="<i class='icon-globe red'></i> No verified Geo-Coordinates<br><i class='icon-globe green'></i> Verified Geo-Coordinates" data-placement="top">
				<i class="icon-globe"></i> 
			</span>
			<span class="xpopover margin-right" data-original-title="Hash Verification" data-content="<i class='icon-anchor red'></i> Not Hashed Geolocation<br><i class='icon-anchor green'></i> Hashed Geolocation" data-placement="top">
				<i class="icon-anchor"></i> 
			</span>
			<span class="xpopover" data-original-title="Verified Address" data-content="This address is verified by the System. You can not edit this item because all of its values are proofed! If you wish do fit values to your own, you have to copy it!" data-placement="top">
				<i class="icon-ok-sign"></i>
			</span>
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