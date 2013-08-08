<?php
/**
 * @project		XAP Project - Xive-Application-Platform
 * @subProject	Nawala Framework - A PHP and Javascript framework
 *
 * @package		NFW.Installer
 * @subPackage	Framework
 * @version		6.0
 *
 * @author		devXive - research and development <support@devxive.com> (http://www.devxive.com)
 * @copyright		Copyright (C) 1997 - 2013 devXive - research and development. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @assetsLicense	devXive Proprietary Use License (http://www.devxive.com/license)
 *
 * @since		5.0
 */

// Set variables
$message = array();
$element = 'com_xivetranscorder';
$parent_menu = 'com_xiveirm';

// Set the include path for the table class
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xivetranscorder/tables');

if ($package['installer']->getInstallType() == 'install') {
	// Runs on install

	// Restore assets from backup
	if (NFWInstallerHelper::restoreAssets($element)) {
		$message[] = '<i class="icon-ok"></i> Assets restored successfully';
	}

	// Make the admin menu item a child of the $parent_menu (element, parent)
	if (NFWInstallerHelper::setComponentChildMenuItem($element, $parent_menu)) {
		$message[] = '<i class="icon-ok"></i> Added submenu items to ' . $parent_menu . ' main menu';
	} else {
		$message[] = '<i class="icon-cancel red"></i> Adding submenu item to ' . $parent_menu . ' main menu failed';
	}


	/*
	 * Setup the parent category for all categories in the TOCA app
	 */
	$parentCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_PARENT_XIVETRANSCORDER', 'alias' => 'com-xivetranscorder', 'access' => 2, 'language' => '*'));
	if ($parentCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set global XiveTransCorder category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set global XiveTransCorder category ... FAILED';
	}


	/*
	 * Set parent transportations category
	 */
	$parentTransportationsCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_PARENT_TRANSPORTATIONS', 'alias' => 'xivetranscorder-transportations', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if ($parentTransportationsCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set parent transportations category ... OK';

		//Set transport related categories
		$tc1 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORTATIONS_PATIENT_TRANSPORT', 'alias' => 'transportations-patient-transport', 'access' => 2, 'language' => '*', 'parent_id' => $parentTransportationsCategoryId));
		$tc2 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORTATIONS_QUALIFIED_PATIENT_AMBULANCE_TRANSPORT', 'alias' => 'transportations-qualified-patient-transport', 'access' => 2, 'language' => '*', 'parent_id' => $parentTransportationsCategoryId));
		$tc3 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORTATIONS_STUFF_TRANSPORT', 'alias' => 'transportations-stuff-transport', 'access' => 2, 'language' => '*', 'parent_id' => $parentTransportationsCategoryId));
		$tc4 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORTATIONS_DESPATCHING_ORDER', 'alias' => 'transportations-despatcher-transport', 'access' => 2, 'language' => '*', 'parent_id' => $parentTransportationsCategoryId));
		$tc5 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORTATIONS_TRANSPORT_TEMPLATE', 'alias' => 'transportations-transport-template', 'access' => 2, 'language' => '*', 'parent_id' => $parentTransportationsCategoryId));
		if ($tc1 && $tc2 && $tc3 && $tc4 && $tc5 ) {
			$message[] = '<i class="icon-ok"></i> Set transportations related categories ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport related categories ... FAILED!';
		}
	} else {
		$message[] = '<i class="icon-cancel"></i> Set parent transportations category ... FAILED';
	}


	/*
	 * Set parent transport form category
	 */
	$parentFormCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_PARENT_TRANSPORT_FORM', 'alias' => 'xivetranscorder-transport-form', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if ($parentFormCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set parent transport form category ... OK';

		//Set transport device category
		$transportDeviceId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORT_FORM_TRANSPORT_DEVICE', 'alias' => 'transport-form-transport-devices', 'access' => 2, 'language' => '*', 'parent_id' => $parentFormCategoryId));
		if ( $transportDeviceId ) {
			$message[] = '<i class="icon-ok"></i> Set transport device category ... OK';

			// Get the helper var
			$rows = $this->tableQueryHelper;

			//Set transport device values
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'TTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_TAXI_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RENTAL_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'HTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_HANDICAPPED_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'PTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_PATIENT_TRANSPORT_AMBULANCE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RESCUE_TRANSPORT_AMBULANCE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'ITA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_INTENSIVE_TRANSPORT_AMBULANCE', 'access' => 2);

			// Set the helper var
			$this->tableQueryHelper = $rows;
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport device category ... FAILED';
		}	

		//Set transport type category
		$transportTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORT_FORM_TRANSPORT_TYPE', 'alias' => 'transport-form-transport-types', 'access' => 2, 'language' => '*', 'parent_id' => $parentFormCategoryId));
		if ( $transportTypeId ) {
			$message[] = '<i class="icon-ok"></i> Set transport type category ... OK';

			// Get the helper var
			$rows = $this->tableQueryHelper;

			//Set transport type values
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'ATW', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ABLE_TO_WALK', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'WC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_WHEELCHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'SWC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_SPECIAL_WHEELCHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'CC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_CARRY_CHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'L', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_LYING', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'EWC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ELECTRIC_WHEELCHAIR', 'access' => 2);

			// Set the helper var
			$this->tableQueryHelper = $rows;
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport device category ... FAILED';
		}	

		//Set order type category
		$orderTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVETRANSCORDER_TRANSPORT_FORM_ORDER_TYPE', 'alias' => 'transport-form-order-types', 'access' => 2, 'language' => '*', 'parent_id' => $parentFormCategoryId));
		if ( $orderTypeId ) {
			$message[] = '<i class="icon-ok"></i> Set order type category ... OK';

			// Get the helper var
			$rows = $this->tableQueryHelper;

			//Set order type values
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'AMBU', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_AMBULANT', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'ADM', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_ADMISSION', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'DIA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DIALYSIS', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'DISM', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DISMISSAL', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'HTRANS', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_HOSPITAL_TRANSFER', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'IHTRANS', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_INTER_HOSPITAL_TRANSFER', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'STUFF', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_STUFF_TRANSPORT', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'CHEMO', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_ONKOLOGY_CHEMO', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'RAY', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_RADIATION_THERAPY', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'REP', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_REPATRIATION', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'REPFA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_REPATRIATION_FLIGHT_ATTENDANT', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'PTRANS', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_PATIENT_TRANSFER', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'ER', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_EMERGENCY_ROOM', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'PHYSIO', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_PHYSIOTHERAPY', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'DAYHOS', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DAY_HOSPITAL', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $orderTypeId, 'opt_value' => 'OTHER', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_OTHER', 'access' => 2);

			// Set the helper var
			$this->tableQueryHelper = $rows;
		} else {
			$message[] = '<i class="icon-cancel"></i> Set order type category ... FAILED';
		}	
	} else {
		$message[] = '<i class="icon-cancel"></i> Set parent transport form category ... FAILED';
	}


	/*
	 * Build the menu items
	 */
	// Get the component id
	$component = 'com_xivetranscorder';
	$com = JComponentHelper::getComponent($component);
	$eid = (is_object($com) && isset($com->id)) ? $com->id : 0;

	$menu = array('title' => 'ControlCenter', 'alias' => 'controlcenter', 'link' => '', 'component_id' => 0, 'type' => 'heading', 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-desktop"}');
	$menuId = NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add ControlCenter menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add ControlCenter menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Order List', 'alias' => 'order-list', 'link' => 'index.php?option=' . $component . '&view=transcorders', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Order List menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Order List menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Despatcher', 'alias' => 'despatcher', 'link' => 'index.php?option=' . $component . '&view=despatcher', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Despatcher menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Despatcher menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Templates', 'alias' => 'templates', 'link' => 'index.php?option=' . $component . '&view=templates', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Templates menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Templates menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Scheduling', 'alias' => 'scheduling', 'link' => 'index.php?option=' . $component . '&view=scheduling', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Scheduling menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Scheduling menu ... FAILED';
	}


//	/*
//	 * Set the component settings in database
//	 */
//	$componentData = array(
//		'extension_id' => $eid,
//		'params' => '{"parent_app_category":"' . $parentTransportationsCategoryId . '"}'
//	);
//	$setComponentParams = NFWDatabase::save('extensions', $componentData);
//	DOES NOT WORK AT PRESENT BECAUSE THE SAVE FUNCTION ONLY DETECTS id TO UPDATE AN ITEM !!!!!
} else {
	// Runs on update
	$message[] = '<i class="icon-ok"></i> Check integrity ... OK';
}
	
if (!empty($message)) {
	return $message;
} else {
	return true;
}