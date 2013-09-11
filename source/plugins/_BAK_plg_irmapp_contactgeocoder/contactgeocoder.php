<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.Contactgeocoder
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @since       6.0
 */

defined('JPATH_BASE') or die;

class PlgIrmAppContactgeocoder extends JPlugin
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
		parent::__construct($subject, $config);

		// TODO: Check first if we have an internet connection, else return false or throw message htat there is no connection and that this geocoder doesn't work
		// NFWConnectionHelper::checkOnline(); or something else

		$this->appKey = 'contactgeocoder';
		$this->loadLanguage();
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
		// Init google maps and jquery gmap3 plugin and helper js file
		NFWGeoGmap3::initMap();
		JHtml::_('script', 'plugins/irmapp/autogeocoder/assets/js/geo-helper.js', false, false, false, false);

		NFWHtmlJavascript::detectChanges();

		// Set the script
		$script = "
			function callbackCodeAddress(results) {
				if (results === 'ZERO_RESULTS') {
					alertify.alert('<div class=\"modal-header\"><h3>Address verification failed!</h3></div><div class=\"modal-body\">Sorry, we can\'t verify the address you typed in! Please try the following:<ul><li>You have to type in a house number</li><li>Try to type in similar words for street or city</li><li>Try to type in the state or the country</li><li>Try to check the address on google maps</li></ul></div>');
					return false;
				}

				var nameObserver = '.address-block[data-direction=\"' + results[0].transport.direction + '\"][data-order=\"' + results[0].transport.order + '\"]';

				$(nameObserver + ' input[id*=\"address_street\"]').val(results[0].input_address.address_street);
				$(nameObserver + ' input[id*=\"address_houseno\"]').val(results[0].input_address.address_houseno);
				$(nameObserver + ' input[id*=\"address_zip\"]').val(results[0].input_address.address_zip);
				$(nameObserver + ' input[id*=\"address_city\"]').val(results[0].input_address.address_city);
				$(nameObserver + ' input[id*=\"address_region\"]').val(results[0].input_address.address_region);
				$(nameObserver + ' input[id*=\"address_country\"]').val(results[0].input_address.address_country);

				if( results[0].geometry.coords.lat && results[0].geometry.coords.lng && results[0].geometry.location_type === 'ROOFTOP') {
					alertify.success('<i class=\"icon-globe\"></i> Valid address found!');
					$('#b-address-geo-verified-1').addClass('green').removeClass('red');
					$(nameObserver + ' input[id*=\"address_lat\"]').val(results[0].geometry.coords.lat);
					$(nameObserver + ' input[id*=\"address_lng\"]').val(results[0].geometry.coords.lng);
				} else {
					alertify.warning = alertify.extend('warning');
					alertify.warning('<i class=\"icon-globe red\"></i> There are only approximate geo coordinations for the given address. Pleasy try to type in more informations!');
					$('#b-address-geo-verified-1').addClass('red').removeClass('green');
					$(nameObserver + ' input[id*=\"address_lat\"]').val('');
					$(nameObserver + ' input[id*=\"address_lng\"]').val('');
				}

				window.aaAutoGeocoder = results;
			}


			/*
			 * Function to get the item cid (db id of the core item we are in. Should always ends --regex selector $-- with _cid)
			 */
			function getItemId() {
				var itemId = jQuery('form[data-order=\"1\"] input[id$=_cid]').val();
				if( itemId && itemId > 0 ) {
					return itemId;
				} else {
					return false;
				}
			}


			/*
			 * Function to check the form, if we have a single address or a route.
			 *
			 * Single Forms only have a 'b'ase direction, while route forms have a 'f'rom and 't'o direction. To show those maps, we could only use the first one we get on inital call of the appropriate item.
			 */
			function checkForm() {
				var direction = 'form[data-order=\"1\"] .address-block[data-direction=\"' + ownDirection + '\"]';

				if( driection === 'b' ) {
					return 'single';
				} else {
					return route;
				}
			}


			/*
			 * Global ready function
			 */
			jQuery(document).ready(function() {
				/*
				 * Checking section if we should or should not display any further options
				 */
				// If its an existing contact we have to hide the geocoder fields
				if( getItemId() ) {
					$('#geocode-progress, #address_auto_geocoder').hide();

					// Check for lat lng values, set the icon color and init the map
					var iconLatLng = getAddress('b', 1);
					if( iconLatLng.address_lat && iconLatLng.address_lng ) {
						$('#b-address-geo-verified-1').show().addClass('green').removeClass('red');

						// Initialize map with existing latlng (initLat, initLng, initZoom, initMarker)
						$('#map-body').show();
						initialize(iconLatLng.address_lat, iconLatLng.address_lng, 17, true);
					} else {
						$('#b-address-geo-verified-1').show().addClass('red').removeClass('green');

						// Initialize map on document ready function (initLat, initLng, initZoom, initMarker)
						$('#map-body').show();
						initialize(0, 0, 1, false);
					}
				} else {
					$('#b-address-geo-verified-1').fadeIn(2500);
					$('#b-address-geo-verified-1').show().addClass('red').removeClass('green');

					// Initialize map on document ready function (initLat, initLng, initZoom, initMarker)
					$('#map-body').show();
					initialize(50, 9, 4, false);

					// Show the geocoder field and the counter
					$('.address_auto_geocoder, .easypie-progress').fadeIn('slow', function() {
						$('.easypie-progress.ep-chart').data('easyPieChart').update(100);
					});

				}

				// If user click edit button, we have to show the input field and graphical stuff
				$('#loading-btn-edit').click(function() {
					$(document).ajaxSuccess(function() {
						$('.address_auto_geocoder, .easypie-progress').fadeIn('slow', function() {
							$('.easypie-progress.ep-chart').data('easyPieChart').update(100);
						});
					});
				});

				// If user click update/save button, we have to hide the input field and graphical stuff
				$('#loading-btn-save').click(function() {
					$(document).ajaxSuccess(function() {
						$('.address_auto_geocoder, .easypie-progress').hide();
					});
				});



				// ##################### START OF TRIGGERED EVENTS TO THE GEOCODING PROCESS #########################
				var timeoutId;

				// Triggered on every change in the auto geocoder input field but only if its focused (this.value determines the actual field)
				$('.address_auto_geocoder input').focus(function() {
					// Get the direction and order position
					var ownOrder = $(this).parents('form').data('order');
					var ownDirection = $(this).parents('.address-block').data('direction');

					var nameObserver = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';

					$(nameObserver + ' .geocode-input-helptext').slideToggle();

					$(this).on('inputchange', function() {
						window.clearTimeout(timeoutId);
						var addressValue = $(nameObserver + ' .address_auto_geocoder input').val();

						// Trigger only if at least 5 digits typed in
						if( addressValue.length > 5 ) {
							timeoutId = window.timeoutId = setTimeout(function() {
								codeAddress(addressValue, ownDirection, ownOrder);
							}, 1500);

							// If anything in this field has changed play with the animation
							$('.easypie-progress.ep-chart').data('easyPieChart').update(100);
							$('.easypie-progress.ep-chart').data('easyPieChart').update(0);
						}
					});
				}).focusout(function() {
					// Get the direction and order position
					var ownOrder = $(this).parents('form').data('order');
					var ownDirection = $(this).parents('.address-block').data('direction');
					var nameObserver = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';

					$(nameObserver + ' .geocode-input-helptext').slideToggle();
				});


				// Triggered on every change in the inner-address-block (this.value determines the actual field)
				$('.inner-address-block input').on('inputchange', function() {
					window.clearTimeout(timeoutId);

					// Get the direction and order position
					var ownOrder = $(this).parents('form').data('order');
					var ownDirection = $(this).parents('.address-block').data('direction');
					var nameObserver = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';

					// try workaround to could change the housenumber. we have to remove the latlng if houseno is removed and add cecks below: if houseno is empty, remove latlng, in check below: if all set houseno >= 0 and latlng empty then trigger new
					var checkHouseNo = $(nameObserver + ' input[id*=\"address_houseno\"]').val();
					if( !checkHouseNo ) {
						$(nameObserver + ' input[id*=\"address_lat\"]').val('');
						$(nameObserver + ' input[id*=\"address_lng\"]').val('');
					}

					var latHelp = $(nameObserver + ' input[id*=\"address_lat\"]').val(),
					lngHelp = $(nameObserver + ' input[id*=\"address_lng\"]').val();
					if(  !latHelp && !lngHelp ) {
						var latlngHelper = true;
					}

					// Prevent double trigger if above function is used
					var addressValue = $(nameObserver + ' .address_auto_geocoder input').val();
					if( addressValue.length > 0 ) {
						return;
					} else {
						// Get the address values from appropriate dir and order, returned as string
						var address = getAddress(ownDirection, ownOrder);

						timeoutId = window.timeoutId = setTimeout(function() {
							// Send address values to codeAddress function to get a coded address in the callbackCodeAddress function above that do all the magic
							if(
								(address.address_street !== '' && address.address_street.length >= 3 &&
								address.address_houseno !== '' && address.address_houseno.length >= 1 &&
								address.address_zip !== '' && address.address_zip.length >= 4 &&
								address.address_city === '')
							||
								(address.address_street !== '' && address.address_street.length >= 3 &&
								address.address_houseno !== '' && address.address_houseno.length >= 1 &&
								address.address_zip === '' &&
								address.address_city !== '' && address.address_city.length >= 2)
							||
								(address.address_street !== '' && address.address_street.length >= 3 &&
								address.address_zip !== '' && address.address_zip.length >= 4 &&
								address.address_city !== '' && address.address_city.length >= 2) && (address.address_houseno.length >= 0 && latlngHelper)
							) {
									codeAddress(address.string_name, ownDirection, ownOrder);
							}
						}, 1500);

						$('.easypie-progress.ep-chart').data('easyPieChart').update(100);
						$('.easypie-progress.ep-chart').data('easyPieChart').update(0);
					}
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

		ob_start();
		?>
		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

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