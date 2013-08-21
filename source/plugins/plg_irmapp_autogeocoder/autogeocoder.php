<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.autogeocoder
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @since       6.0
 */

defined('JPATH_BASE') or die;

class PlgIrmAppAutogeocoder extends JPlugin
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
		$this->appKey = 'autogeocoder';
		$this->loadLanguage();

		parent::__construct($subject, $config);
	}


	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this transport
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

		// Check if we have geo coordinates in db to manipulate the script Declaration or the icon-globe class
		if($item->address_lat && $item->address_lng) {
			$address_geo_verified = true;
		} else {
			$address_geo_verified = false;
		}

		if($item->address_hash) {
			$address_hash_verified = true;
		} else {
			$address_hash_verified = false;
		}

		// Build the input value address
		$initAddress = '';
		if(!empty($item->address_name)) {
			$initAddress .= ' ' . $item->address_name;
		}
		if(!empty($item->address_name_add)) {
			$initAddress .= ' ' . $item->address_name_add;
		}
		if(!empty($item->address_street)) {
			$initAddress .= ' ' . $item->address_street;
		}
		if(!empty($item->address_houseno)) {
			$initAddress .= ' ' . $item->address_houseno;
		}
		if(!empty($item->address_zip)) {
			$initAddress .= ' ' . $item->address_zip;
		}
		if(!empty($item->address_city)) {
			$initAddress .= ' ' . $item->address_city;
		}
		if(!empty($item->address_region)) {
			$initAddress .= ' ' . $item->address_region;
		}
		if(!empty($item->address_country)) {
			$initAddress .= ' ' . $item->address_country;
		}

		NFWHtmlJavascript::detectChanges();
		NFWHtmlJavaScript::loadAutoGeocoder('#location', false, 'components/com_xiveirm/assets/js/');
		$script = "
			jQuery(document).ready(function() {
				// Initial
				$('#geocode-progress.ep-chart').hide();

				// Triggered on every change in the auto geocoder block (this.value determines the actual field)
				$('#address_auto_geocoder').on('inputchange', function() {
					// Get and set the address vars to auto-geocoder and trigger onKeyUp
					$('#location').val(this.value);
					$('#location').trigger('auto-geocoder.onKeyUp');
					
					// If anything in this field has changed play with the animation
					$('#geocode-progress.ep-chart').fadeIn('slow');
					$('#geocode-progress.ep-chart').data('easyPieChart').update(100);
					$('#geocode-progress.ep-chart').data('easyPieChart').update(0);
				});


				// Triggered on every change in the inner-address-block (this.value determines the actual field)
				$('#inner-address-block input').on('inputchange', function() {
					// Get the live var on every change
					var address_name     = ( $('#address_name').val() )     ? $('#address_name').val() + ' '     : '';
					var address_name_add = ( $('#address_name_add').val() ) ? $('#address_name_add').val() + ' ' : '';
					var address_street   = ( $('#address_street').val() )   ? $('#address_street').val() + ' '   : '';
					var address_houseno  = ( $('#address_houseno').val() )  ? $('#address_houseno').val() + ', ' : '';
					var address_zip      = ( $('#address_zip').val() )      ? $('#address_zip').val() + ' '      : '';
					var address_city     = ( $('#address_city').val() )     ? $('#address_city').val() + ', '    : '';
					var address_region   = ( $('#address_region').val() )   ? $('#address_region').val() + ' '   : '';
					var address_country  = ( $('#address_country').val() )  ? $('#address_country').val() + ' '  : '';
					var address_lat      = ( $('#address_lat').val() )      ? $('#address_lat').val()            : '';
					var address_lng      = ( $('#address_lng').val() )      ? $('#address_lng').val()            : '';

					var address_full = address_name + address_name_add + address_street + address_houseno + address_zip + address_city + address_region + address_country;

					$('#location').val(address_full);
					$('#location').trigger('auto-geocoder.onKeyUp');

					// Hashing the values
					var address_hashEmpty = sha256_digest('');
					var address_hash = sha256_digest(address_full);
					$('#address_hash').val(address_hash);

					// Check and set the hash ancor icon
					if( address_hash != '' && address_hash != address_hashEmpty ) {
						$('#address-hash-verified').removeClass('red').addClass('green');
					} else {
						$('#address-hash-verified').removeClass('green').addClass('red');
					}

					// If anything in this field has changed play with the animation
					$('#geocode-progress.ep-chart').fadeIn('slow');
					$('#geocode-progress.ep-chart').data('easyPieChart').update(100);
					$('#geocode-progress.ep-chart').data('easyPieChart').update(0);
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
					<?php if(!$item->address_system_checked) { ?>
						<span id="address-geo-verified" class="small-margin-right <?php echo $address_geo_verified? 'green' : 'red'; ?>" style="vertical-align: middle;">
							<i class="icon-globe" style="font-size: 18px;"></i> 
						</span>
						<span id="address-hash-verified" class="<?php echo $address_hash_verified? 'green' : 'red'; ?>" style="vertical-align: middle;">
							<i class="icon-anchor" style="font-size: 17px;"></i> 
						</span>
					<?php } else { ?>
						<span class="" style="vertical-align: middle;">
							<i class="icon-ok-sign" style="font-size: 18px;"></i>
						</span>
					<?php } ?>
	 			</div>
			</div>
			<div class="widget-body">
				<div class="widget-main padding-5">
					<div class="alert alert-warning center extended">
						<small>
							<?php
								if($item->created && $item->created != '0000-00-00 00:00:00') {
									echo JText::_('PLG_IRMAPP_AUTOGEOCODER_CREATED') . ': ' . date(JText::_('DATE_FORMAT_LC2'), strtotime($item->created)) . '<br>';
								}
								if($item->modified && $item->modified != '0000-00-00 00:00:00') {
									echo JText::_('PLG_IRMAPP_AUTOGEOCODER_LAST_MODIFIED') . ': ' . date(JText::_('DATE_FORMAT_LC2'), strtotime($item->modified));
								} else {
									echo JText::_('PLG_IRMAPP_AUTOGEOCODER_NOT_MODIFIED');
								}
							?>
						</small>
					</div>
					<div class="">
						<input type="hidden" id="location" value="<?php echo $initAddress; ?>" />
					</div>
				</div>
			</div>
		</div>
		<div class="extended small-margin-top">
			<center>
				<span class="xpopover margin-right" data-original-title="Geocoder Verification" data-content="<i class='icon-globe red'></i> No verified Geo-Coordinates<br><i class='icon-globe green'></i> Verified Geo-Coordinates" data-placement="top">
					<i class="icon-globe"></i> 
				</span>
				<span class="xpopover margin-right" data-original-title="Hash Verification" data-content="<i class='icon-anchor red'></i> Not Hashed Geolocation<br><i class='icon-anchor green'></i> Hashed Geolocation" data-placement="top">
					<i class="icon-anchor"></i> 
				</span>
				<span class="xpopover" data-original-title="Verified Address" data-content="This address is verified by the System. You can not edit this item because all of its values are proofed! If you wish do fit values to your own, you have to copy it!" data-placement="top">
					<i class="icon-ok-sign"></i>
				</span>
			</center>
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

	/**
	 * @param   object	&$item		The item referenced object which includes the system id of this transport
	 *
	 * @return  array			appKey = The tab identification, tabContent = Content of the Container
	 *
	 * @since   3.0
	 */
	public function inBaseWidgetBottom_OUTDATED( &$item = null, &$params = null )
	{
//		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&amp;w=350&amp;h=315&amp;fr=0&amp;frc=000000&amp;bg=FFFFFF&amp;hl2=A5A5A5&amp;fc=000000&amp;lc=000000&amp;ff=Arial&amp;fs=10&amp;lnc=000000&amp;hdc=000000&amp;app=miniapp&amp;loc=http%3A//plzkarte.com/plz-suche/';
		$plzUrl = 'http://www.postdirekt.de/plzserver/PlzSearchServlet?app=miniapp&fr=0&bg=FFF&hl2=FC0&fc=000&lc=000000&ff=Verdana&fs=10&lnc=000000&hdc=000000';

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

		<div class="widget-box small-margin-top extended">
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

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->
		<?php

		$tabContent = ob_get_clean();

		$inMasterContainer = array(
			'appKey' => $this->appKey,
			'tabContent' => $tabContent
		);

		return $inMasterContainer;
	}
}
?>