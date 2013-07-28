<?php
/**
 * @version     4.2.3
 * @package     com_xiveirm
 * @copyright   Copyright (C) 1997 - 2013 by devXive - research and development. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      devXive <support@devxive.com> - http://devxive.com
 */
// no direct access
defined('_JEXEC') or die;

// Load the XiveIRMSystem Session Data (Performed by the XiveIRM System Plugin)
$xsession = JFactory::getSession()->get('XiveIRMSystem');

?>

<style>
#breadcrumbs { display: none; }
.footer { display: none; }
#btn-scroll-up { display: none; }
#page-content { padding: 0 !important; }
</style>

<script>
	function jqUpdateSize() {
		// Get the dimensions of the viewport
		var width = jQuery(window).width() - 190;
		var height = jQuery(window).height() - 45;

		jQuery("#map-frame").css('width', width);
		jQuery("#map-frame").css('height', height);
	};
	jQuery(document).ready(jqUpdateSize);
	jQuery(window).resize(jqUpdateSize);
</script>

<iframe id="map-frame" frameborder="0" scrolling="no" src="/components/com_xiveirm/views/maps/tmpl/maps.php" style="height: 600px; wisth: 100%;"></iframe>
<?php
// require_once('maps.php');
?>