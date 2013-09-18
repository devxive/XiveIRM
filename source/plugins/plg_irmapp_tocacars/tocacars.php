<?php
/**
 * @package     IRM.Plugin
 * @subpackage  IRMApp.tocacars
 *
 * @copyright   Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @since       6.0
 */

defined('JPATH_BASE') or die;

class PlgIrmAppTocacars extends JPlugin
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

		$this->appKey = 'tocacars';
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
		JHtml::_('script', 'plugins/irmapp/tocacars/assets/js/geo-helper.js', false, false, false, false);

		NFWHtmlJavascript::detectChanges();

		// Set the script
		$script = "
			/*
			 * Function to get the item cid (db id of the core item we are in. Should always ends --regex selector $-- with _cid)
			 */
			function getItemId() {
				var itemId = jQuery('form[data-order=\"1\"] input[id*=\"_cid-\"]').val();
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
				// If user click edit button, we have to show the input field and graphical stuff
				$('#loading-btn-edit').click(function() {
					$(document).ajaxSuccess(function() {
//						$('.address_auto_geocoder').fadeIn('slow');
					});
				});

				// If user click update/save button, we have to hide the input field and graphical stuff
				$('#loading-btn-save').click(function() {
					$(document).ajaxSuccess(function() {
//						$('.address_auto_geocoder, .easypie-progress').hide();
					});
				});



				// ##################### START OF TRIGGERED EVENTS TO THE GEOCODING PROCESS #########################
				// Triggered on every change in the auto geocoder input field but only if its focused (this.value determines the actual field)
//				$('.address_auto_geocoder input').focus(function() {
//					// Get the direction and order position
//					var ownOrder = $(this).parents('form').data('order');
//					var ownDirection = $(this).parents('.address-block').data('direction');
//					var nameObserver = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';
//
//					$(nameObserver + ' .geocode-input-helptext').slideToggle();
//
//					$(this).on('inputchange', function() {
//						window.clearTimeout(timeoutId);
//						var addressValue = $(nameObserver + ' .address_auto_geocoder input').val();
//
//						// Trigger only if at least 5 digits typed in
//						if( addressValue.length > 5 ) {
//							timeoutId = window.timeoutId = setTimeout(function() {
//								codeAddress(addressValue, ownDirection, ownOrder);
//							}, 1500);
//
//							// If anything in this field has changed play with the animation
//							$('.easypie-progress.ep-chart').data('easyPieChart').update(100);
//							$('.easypie-progress.ep-chart').data('easyPieChart').update(0);
//						}
//					});
//				}).focusout(function() {
//					// Get the direction and order position
//					var ownOrder = $(this).parents('form').data('order');
//					var ownDirection = $(this).parents('.address-block').data('direction');
//					var nameObserver = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';
//
//					$(nameObserver + ' .geocode-input-helptext').slideToggle();
//				});


				// Triggered on every change in the inner-address-block (this.value determines the actual field) NOTE: METHODS ONLY FOR SINGLE EDITS!
				if( getItemId() ) {
					// Render the Map Route if estimated values are set else, show BIG verify button
					var singleRoute = getRoute(1);
					$('#map-body').addClass('widget-main').addClass('padding-5');

					if( singleRoute.estimated_time && singleRoute.estimated_distance ) {
						$('#map-canvas').height('250px').width('100%');

						renderRoute( singleRoute );
					} else {
						var htmlButton = '<a onClick=\"verifyRoute( getRoute(1) )\" id=\"verifyroutebutton\" class=\"btn btn-large btn-block\" style=\"font-size: 200%;\">';
							htmlButton += '<i class=\"icon-compass icon-spin\"></i> Verify Route';
						htmlButton += '</a>';
						$('#map-canvas').html(htmlButton);
					}

					// Directions Observer
					$('.inner-address-block input').on('inputchange', function() {
						// Get the direction and order position
						var ownOrder = $(this).parents('form').data('order');
						var ownDirection = $(this).parents('.address-block').data('direction');

						var masterUsher = 'form[data-order=\"' + ownOrder + '\"] .address-block[data-direction=\"' + ownDirection + '\"]';
						var orderUsher = 'form[data-order=\"' + ownOrder + '\"]';

						// User changed something, kick off hash, latlng and estimates
						$(masterUsher + ' input[id*=\"address_lat\"]').val('');
						$(masterUsher + ' input[id*=\"address_lng\"]').val('');

						$(orderUsher + ' input[id*=\"estimated_time\"]').val('');
						$(orderUsher + ' input[id*=\"estimated_distance\"]').val('');
					});
				} else {
				}
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


	/**
	 * Method to inject a tab with form fields to extend the core form. The position of this tab is the same as the buttons, based on the plugin position.
	 *
	 * @param     object    &$item      The item referenced object which includes the system id of this contact
	 * @param     object    &$params    The params referenced object
	 *
	 * @return    array                 Array with the $appKey, the $html data for rendering and the $tabButton to show at the top.
	 *                                  tabButton - Use either a name and/or a translatable string with or without an icon
	 *
	 * @since     6.0
	 */
	public function htmlBuildTab( &$item = null, &$params = null )
	{
		$googleMapIcon = '/plugins/irmapp/tocacars/assets/img/googlemaps-icon.png';

		ob_start();
		?>
		<script>
			/*
			 * Render map on the large tab view
			 */
			jQuery('.tocacars_tabbutton').click(function() {
				renderRouteDirection('tabmap-canvas', 1, 'direction-canvas');
			});
		</script>

		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->


		<div class="row-fluid">
			<div class="span7">
				<div id="tabmap-canvas" style="height: 450px; width: 100%;"></div>
			</div>
			<div class="span5">
				<div id="direction-canvas" class="googlemap" style="width: 100%;"></div>
			</div>

			<div class="hr"></div>

			<center>
				<span class="help-button xpopover" data-trigger="hover" data-placement="top" data-content="Informations given here are used in other applications, such as the despatching app => order form. Use this as help to minimize inputs during remaining phone orders." data-original-title="Info about cross referencing!"><i class="icon-random"></i></span>
			</center>
		</div>

		<!---------- End output buffering: <?php echo $this->appKey; ?> ---------->

		<?php
		$html = ob_get_clean();

		// Create the tabbed button
		$tabButton = '<img src="' . $googleMapIcon . '" style="height: 15px; margin-top: -2px;"> ' . JText::_('Google Maps');

		$eventArray = array(
			'appKey'    => $this->appKey,
			'tabButton' => $tabButton,
			'tabBody'   => $html
		);

		if ( $item->id ) {
			return $eventArray;
		}
	}
}
?>