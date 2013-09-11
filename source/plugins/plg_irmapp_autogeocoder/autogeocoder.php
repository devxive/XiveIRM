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
		parent::__construct($subject, $config);

		// TODO: Check first if we have an internet connection, else return false or throw message htat there is no connection and that this geocoder doesn't work
		// NFWConnectionHelper::checkOnline(); or something else

		$this->appKey = 'autogeocoder';
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
				// Do something on ajax success function
				$(document).ajaxSuccess(function() {
					// Do something on ajax success function
				});

				// Available for existing items
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
		$googleMapIcon = '/plugins/irmapp/autogeocoder/assets/img/googlemaps-icon.png';

		ob_start();
		?>
		<script>
			/*
			 * Render map on the large tab view
			 */
			var mapClickCounter = 0;
			jQuery('.autogeocoder_tabbutton').click(function() {
				if( mapClickCounter == 0 ) {
					renderRouteDirection('tabmap-canvas', 1, 'direction-canvas');
				}
				mapClickCounter++;
			});
		</script>

		<!---------- Begin output buffering: <?php echo $this->appKey; ?> ---------->

		<div class="row-fluid">
			<div class="span7">
				<div id="tabmap-canvas" style="height: 500px; width: 100%;"></div>
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