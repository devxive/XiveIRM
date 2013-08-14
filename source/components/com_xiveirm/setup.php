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

// Set the include path for the table class
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_xiveirm/tables');

if ($package['installer']->getInstallType() == 'install') {
	// Runs on install

	// Check if a XiveIRM sidebar menu exist for frontend usage, if not create it. Also create a menu module
	$checkMenu = NFWDatabase::select( 'menu_types', '*', array('menutype' => 'xiveirm5') );
	if ( !isset($checkMenu['id']) ) {
		$returnedId = NFWInstallerHelper::addMenuType( array('menutype' => 'xiveirm5', 'title' => 'XiveIRM', 'description' => 'XiveIRM Menu'), true );

		if( $returnedId ) {
			$message[] = '<i class="icon-ok"></i> Set XiveIRM Menu Sidebar Module ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set XiveIRM Menu Sidebar Module ... FAILED';
		}
	}

	/*
	 * Set the parent category for all categories for the XiveContacts App
	 */
	$parentCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_PARENT_XIVECONTACTS', 'access' => 2, 'language' => '*', 'alias' => 'com-xivecontacts'));
	if($parentCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set XiveContacts Core Category ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Set XiveContacts Core Category ... FAILED';
	}

	/*
	 * Set gender category as a subcategory of the XiveContacts Core Category
	 */
	$genderCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_GENDER', 'alias' => 'xivecontacts-gender', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if($genderCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set gender category ... OK';

		// Get the helper var
		$rows = $this->tableQueryHelper;

		// Set the gender option values
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'UNKNOWN', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_UNKNOWN', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'FEMALE', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_FEMALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'MALE', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_MALE', 'access' => 2);
		$rows[] = array('client_id' => 2, 'catid' => $genderCategoryId, 'opt_value' => 'COMPANY', 'opt_name' => 'COM_XIVEIRM_XIVECONTACTS_GENDER_TRAIT_COMPANY', 'access' => 2);

		// Set the helper var
		$this->tableQueryHelper = $rows;
	} else {
		$message[] = '<i class="icon-cancel"></i> Set gender category ... FAILED';
	}

	/*
	 * Set parent contacts category
	 */
	$contactsCategoryId = NFWTableCategory::store(array('extension' => 'com_xiveirm', 'title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS', 'alias' => 'xivecontacts-contacts', 'access' => 2, 'language' => '*', 'parent_id' => $parentCategoryId));
	if($contactsCategoryId) {
		$message[] = '<i class="icon-ok"></i> Set parent contacts category ... OK';

		// Set the contacts related categories
		// Kunde
		$cc1 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_CUSTOMER', 'alias' => 'contacts-customer', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Lieferant

		$cc2 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_SUPPLIER', 'alias' => 'contacts-supplier', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Subunternehmer
		$cc3 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_SUBCONTRACTOR', 'alias' => 'contacts-subcontractor', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Interessent
		$cc4 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_LEAD', 'alias' => 'contacts-lead', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		// Behörde
		$cc5 = NFWTableCategory::store(array('extension' => 'com_xiveirm','title' => 'COM_XIVEIRM_CATEGORY_XIVECONTACTS_CONTACTS_AGENCY', 'alias' => 'contacts-agency', 'access' => 2, 'language' => '*', 'parent_id' => $contactsCategoryId));
		if ($cc1 && $cc2 && $cc3 && $cc4 && $cc5 ) {
			$message[] = '<i class="icon-ok"></i> Set contacts related categories ... OK';
		} else {
			$message[] = '<i class="icon-cancel"></i> Set contacts related categories ... FAILED!';
		}
	} else {
		$message[] = '<i class="icon-cancel"></i> Set parent contacts category ... FAILED';
	}

	/*
	 * Build the menu items
	 */
	// Get the component id
	$component = 'com_xiveirm';
	$com = JComponentHelper::getComponent($component);
	$eid = (is_object($com) && isset($com->id)) ? $com->id : 0;

	$menu = array('title' => 'Dashboard', 'alias' => 'dashboard', 'link' => 'index.php?option=' . $component . '&view=dashboard', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-dashboard","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Dashboard menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Dashboard menu ... FAILED';
	}

	$menu = array('title' => 'Contacts', 'alias' => 'contacts', 'link' => 'index.php?option=' . $component . '&view=contacts', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-group","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Contacts menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Contacts menu ... FAILED';
	}

	$menu = array('title' => 'Maps', 'alias' => 'maps', 'link' => 'index.php?option=' . $component . '&view=maps', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-globe","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Maps menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Maps menu ... FAILED';
	}

	$menu = array('title' => 'Toolbox', 'alias' => 'toolbox', 'link' => '', 'component_id' => 0, 'type' => 'heading', 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-dashboard"}');
	$menuId = NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Toolbox menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Toolbox menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'inSearch', 'alias' => 'insearch', 'link' => 'index.php?option=' . $component . '&view=search', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add inSearch menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add inSearch menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Userlist', 'alias' => 'userlist', 'link' => 'index.php?option=' . $component . '&view=userlist', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Userlist menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Userlist menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Transportation Safety Board (TSB)', 'alias' => 'transportation-safety-board-tsb', 'link' => 'index.php?option=' . $component . '&view=tsb', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Transportation Safety Board (TSB) menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Transportation Safety Board (TSB) menu ... FAILED';
	}

	$menu = array('parent_id' => $menuId, 'title' => 'Interface (API)', 'alias' => 'interface-api', 'link' => 'index.php?option=' . $component . '&view=interface', 'component_id' => $eid, 'params' => '{"menu-anchor_title":"","menu-anchor_css":"icon-double-angle-right","menu_image":"","menu_text":0,"page_title":"","show_page_heading":0,"page_heading":"","pageclass_sfx":"","menu-meta_description":"","menu-meta_keywords":"","robots":"","secure":0}');
	NFWInstallerHelper::addMenuItem($menu, 'xiveirm');
	if ( $menu ) {
		$message[] = '<i class="icon-ok"></i> Add Interface (API) menu ... OK';
	} else {
		$message[] = '<i class="icon-cancel"></i> Add Interface (API) menu ... FAILED';
	}


//	/*
//	 * Set the component settings in database
//	 */
//	$componentData = array(
//		'extension_id' => $eid,
//		'params' => '{"parent_app_category":"' . $contactsCategoryId . '"}'
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