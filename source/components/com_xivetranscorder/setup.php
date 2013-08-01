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

if ($package['installer']->getInstallType() == 'install') {
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

	// Fill up the database with core values
	$db = JFactory:: getDBO();
	$query = $db->getQuery(true);

		/*
		 * Set transport categories
		 *
		 */
		NFWTableCategory::store(array('extension' => 'com_xivetranscorder.transcorders','title' => 'COM_XIVETRANSCORDER_CATEGORY_PATIENT_TRANSPORT'));
		NFWTableCategory::store(array('extension' => 'com_xivetranscorder.transcorders','title' => 'COM_XIVETRANSCORDER_CATEGORY_QUALIFIED_PATIENT_AMBULANCE_TRANSPORT'));
		NFWTableCategory::store(array('extension' => 'com_xivetranscorder.transcorders','title' => 'COM_XIVETRANSCORDER_CATEGORY_STUFF_TRANSPORT'));
		NFWTableCategory::store(array('extension' => 'com_xivetranscorder.transcorders','title' => 'COM_XIVETRANSCORDER_CATEGORY_DESPATCHING_ORDER'));
		NFWTableCategory::store(array('extension' => 'com_xivetranscorder.transcorders','title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_TEMPLATE'));

		/*
		 * Set options categories
		 *
		 */
		$transportDeviceId = NFWTableCategory::store(array('extension' => 'com_xiveirm.options', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_DEVICE', 'alias' => 'transport-device'));
		$transportTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm.options', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_TYPE', 'alias' => 'transport-type'));
		$orderTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm.options', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_ORDER_TYPE', 'alias' => 'order-type'));

		/*
		 * Set the option values
		 *
		 */
		// Transport Devices
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'TTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_TAXI_TRANSPORT_VEHICLE', 'access' => 2) );
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RENTAL_TRANSPORT_VEHICLE', 'access' => 2) );
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'HTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_HANDICAPPED_TRANSPORT_VEHICLE', 'access' => 2) );
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'PTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_PATIENT_TRANSPORT_AMBULANCE', 'access' => 2) );
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RESCUE_TRANSPORT_AMBULANCE', 'access' => 2) );
		NFWTableData::store( 'Option', 'XiveirmTable', array('client' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'ITA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_INTENSIVE_TRANSPORT_AMBULANCE', 'access' => 2) );

		// Transport Types
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ABLE_TO_WALK', 'ATW', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_WHEELCHAIR', 'WC', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_SPECIAL_WHEELCHAIR', 'SWC', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_CARRY_CHAIR', 'CC', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_LYING', 'L', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ELECTRIC_WHEELCHAIR', 'EWC', '2', ''));

		// Order Types
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_AMBULANT', 'AMBU', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_ADMISSION', 'ADM', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DIALYSIS', 'DIA', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DISMISSAL', 'DISM', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_HOSPITAL_TRANSFER', 'HTRANS', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_INTER_HOSPITAL_TRANSFER', 'IHTRANS', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_STUFF_TRANSPORT', 'STUFF', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_ONKOLOGY_CHEMO', 'CHEMO', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_RADIATION_THERAPY', 'RAY', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_REPATRIATION', 'REP', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_REPATRIATION_FLIGHT_ATTENDANT', 'REPFA', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_PATIENT_TRANSFER', 'PTRANS', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_EMERGENCY_ROOM', 'ER', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_PHYSIOTHERAPY', 'PHYSIO', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_DAY_HOSPITAL', 'DAYHOS', '2', ''));
		$query->values(implode(',', array('2', 'catid', '', 'COM_XIVETRANSCORDER_OPTION_ORDER_TYPE_OTHER', 'OTHER', '2', ''));

		// Set the query string
		$query->setQuery($query);

		try {
			$db->execute();
			// $insertedId = (int)$db->insertid();
			$message[] = '<i class="icon-ok"></i> Added core values to the database';
		} catch (Exception $e) {
			$message[] = '<i class="icon-cancel"></i> Error ' . (int)$e->getCode() . ' ends up wit message: ' . $e->getMessage();
		}
} else {
	// Make the admin menu item a child of the $parent_menu (element, parent)
	if (NFWInstallerHelper::setComponentChildMenuItem($element, $parent_menu)) {
		$message[] = '<i class="icon-ok"></i> Added submenu items to ' . $parent_menu . ' main menu';
	} else {
		$message[] = '<i class="icon-cancel red"></i> Adding submenu item to ' . $parent_menu . ' main menu failed';
	}
}
	



// Set the option values
// $columns = array('id', 'client_id', 'catid', 'opt_key', 'opt_value', 'opt_name', 'access', 'ordering');
// $values_1 = array('1', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_UNKNOWN', 'unknown', '2', '');
// $values_2 = array('2', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_FEMALE', 'female', '2', '');
// $values_3 = array('3', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_MALE', 'male', '2', '');
// $values_4 = array('4', '2', 'catid', '', 'COM_XIVEIRM_CONTACT_FORM_TRAIT_GENDER_COMPANY', 'company', '2', '');
// 
// $query
// 	->insert($db->quoteName('#__xiveirm_options'))
// 	->columns($db->quoteName($columns))
// 	->values(implode(',', $values));
// 
// $query->setQuery($query);




$message[] = '<i class="icon-ok"></i> Removed temporary files';

if (!empty($message)) {
	return $message;
} else {
	return true;
}