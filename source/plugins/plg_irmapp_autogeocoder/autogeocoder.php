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
	 * Stores the app ACL
	 * @var	acl
	 * @since	6.0
	 */
	var $acl;


	/**
	 * INITIATE THE CONSTRUCTOR
	 */
	public function __construct(& $subject, $config)
	{
		// TODO: Check first if we have an internet connection, else return false or throw message htat there is no connection and that this geocoder doesn't work
		// NFWConnectionHelper::checkOnline(); or something else

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
	public function onBeforeContent( &$item = null, &$params = null )
	{
		// Init google maps and jquery gmap3 plugin
		NFWGeoGmap3::initMap();
		JHtml::_('script', 'plugins/irmapp/autogeocoder/assets/js/geo-helper.js', false, false, false, false);

		NFWHtmlJavascript::detectChanges();
		NFWHtmlJavascript::loadEasyPie('.ep-chart', false, false);


		// Set the script
		$script = "
			function geocodeInputHelper() {
				jQuery('#geocode-input-helper').fadeToggle('slow');
			}

			/*
			 * Function to get the item cid (db id of the core item we are in. Should always ends --regex selector $-- with _cid)
			 */
			function getItemId() {
				var itemId = jQuery('input[id$=_cid]').val();
				if( itemId && itemId > 0 ) {
					return itemId;
				} else {
					return false;
				}
			}


			/*
			 * Global ready function
			 */
			jQuery(document).ready(function() {
				/*
				 * Initals to build before any user event is triggered
				 */
				// Build and insert the geoIcon right after the #address-hash-verified icon
				var geoIcon = '<span id=\"address-geo-verified\" class=\"small-margin-left\" style=\"vertical-align: middle;\"><i class=\"icon-globe\" style=\"font-size: 18px;\"></i></span>';
				$(geoIcon).insertAfter('#address-hash-verified');

				// Build and insert the auto geoInputHelper field before the #inner-address-block
				var geoInputHelper = '<div id=\"address_auto_geocoder\" class=\"controls controls-row\">';
					geoInputHelper += '<div class=\"alert\" style=\"padding: 8px !important; margin-bottom: 10px;\">';
						geoInputHelper += '<input type=\"text\" class=\"input-control span12 red\" placeholder=\"Type in: Street HouseNo, City, State, Country\" onFocus=\"geocodeInputHelper()\" onBlur=\"geocodeInputHelper()\" style=\"margin: 0 !important; float: none;\"/>';
						geoInputHelper += '<div id=\"geocode-input-helper\" class=\"center\" style=\"margin-top: 10px; display: none;\">';
							geoInputHelper += '<small>';
								geoInputHelper += 'Type in here the address the geocoder should find and validate. This can take up to 5 seconds!<br>';
								geoInputHelper += '<em><strong>Please note that this field will not save its values!</strong></em>';
							geoInputHelper += '</small>';
						geoInputHelper += '</div>';
					geoInputHelper += '</div>';
				geoInputHelper += '</div>';
				$(geoInputHelper).insertBefore('#inner-address-block');

				// Append after #address-specific-options
				var addressOptions = '<span id=\"geocode-progress\" class=\"icon-custom ep-chart xpopover pull-right\" data-original-title=\"Click here if you wish to check the address already filled out below\" data-percent=\"100\" data-size=\"22\" data-line-width=\"3\" data-animate=\"1500\" data-color=\"#EBA450\" style=\"top: 2px;\"></span>';
				$('#address-specific-options').append(addressOptions);

				/*
				 * Checking section if we should or should not display any further options
				 */
				// If its an existing contact we have to hide the geocoder fields
				if( getItemId() ) {
					$('#geocode-progress, #address_auto_geocoder, .gverifier').hide();

					// Check for lat lng values and set the icon color
					var iconLatLng = getAddress(null, 0);
					if( iconLatLng.address_lat && iconLatLng.address_lng ) {
						$('#address-geo-verified').addClass('green').removeClass('red');
					} else {
						$('#address-geo-verified').addClass('red').removeClass('green');
					}
				}

				// If user click edit button, we have to show the input field and graphical stuff
				$('#loading-btn-edit').click(function() {
					$(document).ajaxSuccess(function() {
						$('#geocode-progress, #address_auto_geocoder').fadeIn('slow', function() {
							$('#geocode-progress.ep-chart').data('easyPieChart').update(100);
						});
					});
				});

				// If user click update/save button, we have to hide the input field and graphical stuff
				$('#loading-btn-save').click(function() {
					$(document).ajaxSuccess(function() {
						$('#geocode-progress, #address_auto_geocoder').hide();
					});
				});


				// Adding geoVerifier click funtion
				$('.gverifier').click(function() {
					// Get the direction and order position
					var ownDirection = $(this).parents('.address-block').data('direction');
					var ownOrder = $(this).parents('.address-block').data('order');

					// Get the address values from appropriate dir and order, returned as string
					var address = getAddress(ownDirection, ownOrder, true);

					console.log('Direction: ' + ownDirection + ', Order: ' + ownOrder + ', Address: ' + address);

					// Overgive the function the address values to do all the magic
					geoFormatting(address);
				});

			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

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
			$this->acl = NFWAccessHelper::getActions('com_xiveirm');
		} else {
			// We have a category id and use the category acl
			$this->acl = NFWAccessHelper::getActions('com_xiveirm', 'category', $item->catid);
		}

		// Build the input value address
		$initAddress = '';
		if(!empty($item->address_name)) {
			$initAddress .= $item->address_name;
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

		// Check if we have geo coordinates in db to manipulate the script Declaration or the icon-globe class
		if($item->address_lat && $item->address_lng) {
			$address_geo_verified = true;
		} else {
			$address_geo_verified = false;
		}



		$script = "
			/*
			 * Global ready function
			 */
			jQuery(document).ready(function() {


			// ##################### have to add a timeout function to prevent realtime requests!

				// Triggered on every change in the auto geocoder block (this.value determines the actual field)
				$('#address_auto_geocoder input').on('inputchange', function() {
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
					var address_name = ( $('#address_name').val() )     ? $('#address_name').val() + ' '     : '',
					address_name_add = ( $('#address_name_add').val() ) ? $('#address_name_add').val() + ' ' : '',
					address_street   = ( $('#address_street').val() )   ? $('#address_street').val() + ' '   : '',
					address_houseno  = ( $('#address_houseno').val() )  ? $('#address_houseno').val() + ', ' : '',
					address_zip      = ( $('#address_zip').val() )      ? $('#address_zip').val() + ' '      : '',
					address_city     = ( $('#address_city').val() )     ? $('#address_city').val() + ', '    : '',
					address_region   = ( $('#address_region').val() )   ? $('#address_region').val() + ' '   : '',
					address_country  = ( $('#address_country').val() )  ? $('#address_country').val() + ' '  : '',
					address_lat      = ( $('#address_lat').val() )      ? $('#address_lat').val()            : '',
					address_lng      = ( $('#address_lng').val() )      ? $('#address_lng').val()            : '';

					var address_full = address_name + address_name_add + address_street + address_houseno + address_zip + address_city + address_region + address_country;

					$('#location').val(address_full);
					$('#location').trigger('auto-geocoder.onKeyUp');

					// If anything in this field has changed play with the animation
					$('#geocode-progress.ep-chart').fadeIn('slow');
					$('#geocode-progress.ep-chart').data('easyPieChart').update(100);
					$('#geocode-progress.ep-chart').data('easyPieChart').update(0);
				});


				// Triggered on every change in lat lng vars
				$('.address-block #geo-coords input').on('inputchange', function() {
					// Check for values and set the icon color
					var iconLatLng = getAddress(null, 0);
					if( iconLatLng.address_lat && iconLatLng.address_lng ) {
						$('#address-geo-verified').addClass('green').removeClass('red');
					} else {
						$('#address-geo-verified').addClass('red').removeClass('green');
					}

					// Get and set the address vars to auto-geocoder and trigger onKeyUp
					$('#location').val(this.value);
					$('#location').trigger('auto-geocoder.onKeyUp');
				});
			});
		";
		JFactory::getDocument()->addScriptDeclaration($script);

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

		<pre>
		<?php
			$test='';
			print_r($test);
		?>
		</pre>

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