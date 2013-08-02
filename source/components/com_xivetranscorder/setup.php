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
	// Runs on update

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
	$parentCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_PARENT_CATEGORY', 'alias' => 'com-xivetranscorder', 'access' => 2));
	if ($parentCategoryId) {
		$message[] = '<i class="icon-ok"></i> XiveTransCorder category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> XiveTransCorder category ... FAILED';
	}


	/*
	 * Set transport main category
	 */
	$parentTransportCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_PATIENT_TRANSPORT', 'alias' => 'toca-patient-transport', 'access' => 2, 'parent_id' => $parentCategoryId));
	if ($parentTransportCategoryId) {
		//Set transport related categories
		$tc1 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_PATIENT_TRANSPORT', 'alias' => 'toca-patient-transport', 'access' => 2, 'parent_id' => $parentTransportCategoryId));
		$tc2 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_QUALIFIED_PATIENT_AMBULANCE_TRANSPORT', 'alias' => 'toca-qualified-patient-transport', 'access' => 2, 'parent_id' => $parentTransportCategoryId));
		$tc3 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_STUFF_TRANSPORT', 'alias' => 'toca-stuff-transport', 'access' => 2, 'parent_id' => $parentTransportCategoryId));
		$tc4 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_DESPATCHING_ORDER', 'alias' => 'toca-despatcher-transport', 'access' => 2, 'parent_id' => $parentTransportCategoryId));
		$tc5 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_TEMPLATE', 'alias' => 'toca-transport-template', 'access' => 2, 'parent_id' => $parentTransportCategoryId));
		if ($tc1 && $tc2 && $tc3 && $tc4 && $tc5 ) {
			$message[] = '<i class="icon-ok"></i> Set transport related categories ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport related categories ... FAILED!';
		}

		$message[] = '<i class="icon-ok"></i> Set transport main category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set transport main category ... FAILED';
	}


	/*
	 * Set main transport form category
	 */
	$parentFormCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVETRANSCORDER_CATEGORY_PATIENT_TRANSPORT', 'alias' => 'toca-patient-transport', 'access' => 2, 'parent_id' => $parentCategoryId));
	if ($parentFormCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set main transport form category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set main transport form category ... FAILED';
	}

		//Set transport device category
		$transportDeviceId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_DEVICE', 'alias' => 'transport-devices', 'access' => 2, 'parent_id' => $parentFormCategoryId));
		if($transportDeviceId) {
			//Set transport device values
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'TTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_TAXI_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RENTAL_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'HTV', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_HANDICAPPED_TRANSPORT_VEHICLE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'PTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_PATIENT_TRANSPORT_AMBULANCE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'RTA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_RESCUE_TRANSPORT_AMBULANCE', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportDeviceId, 'opt_value' => 'ITA', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_DEVICE_INTENSIVE_TRANSPORT_AMBULANCE', 'access' => 2);

			$data = (object) $rows;
			$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);

			if($return) {
				$message[] = '<i class="icon-ok"></i> Form - Set transport device values ... OK';
			} else {
				$message[] = '<i class="icon-cancel"></i> Form - Set transport device values ... FAILED';
			}

			$message[] = '<i class="icon-ok"></i> Set transport device category ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport device category ... FAILED';
		}	

		//Set transport type category
		$transportTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_TRANSPORT_TYPE', 'alias' => 'transport-types', 'access' => 2, 'parent_id' => $parentFormCategoryId));
		if($transportTypeId) {
			//Set transport type values
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'ATW', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ABLE_TO_WALK', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'WC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_WHEELCHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'SWC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_SPECIAL_WHEELCHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'CC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_CARRY_CHAIR', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'L', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_LYING', 'access' => 2);
			$rows[] = array('client_id' => 2, 'catid' => $transportTypeId, 'opt_value' => 'EWC', 'opt_name' => 'COM_XIVETRANSCORDER_OPTION_TRANSPORT_TYPE_ELECTRIC_WHEELCHAIR', 'access' => 2);

			$data = (object) $rows;
			$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);

			if($return) {
				$message[] = '<i class="icon-ok"></i> Form - Set transport type values ... OK';
			} else {
				$message[] = '<i class="icon-cancel"></i> Form - Set transport type values ... FAILED';
			}

			$message[] = '<i class="icon-ok"></i> Set transport type category ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set transport device category ... FAILED';
		}	

		//Set order type category
		$orderTypeId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVETRANSCORDER_CATEGORY_ORDER_TYPE', 'alias' => 'order-types', 'access' => 2, 'parent_id' => $parentFormCategoryId));
		if($transportDeviceId) {
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

			$data = (object) $rows;
			$return = NFWTableData::store( 'Option', 'XiveirmTable', $data);

			if($return) {
				$message[] = '<i class="icon-ok"></i> Form - Set order type values ... OK';
			} else {
				$message[] = '<i class="icon-ok"></i> Form - Set order type values ... FAILED';
			}

			$message[] = '<i class="icon-ok"></i> Set order type category ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set order type category ... FAILED';
		}	
} else {
	// Runs on update
}
	
$message[] = '<i class="icon-ok"></i> Removed temporary files';

if (!empty($message)) {
	return $message;
} else {
	return true;
}