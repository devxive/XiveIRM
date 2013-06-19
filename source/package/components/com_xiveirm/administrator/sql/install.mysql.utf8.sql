CREATE TABLE IF NOT EXISTS `#__xiveirm_contacts` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`client_id` INT(11) NOT NULL COMMENT 'usergroup id',
`parent_id` INT(11) NOT NULL COMMENT 'parent contact id',
`state` TINYINT(1) NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL,
`created_by` INT(11) NOT NULL,
`checked_out` INT(11) NOT NULL,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL,
`catid` INT(11) NOT NULL COMMENT 'XAP categories',
`customer_id` VARCHAR(50) NOT NULL,
`company` VARCHAR(150) NOT NULL,
`title` VARCHAR(100) NOT NULL,
`last_name` VARCHAR(150) NOT NULL,
`first_name` VARCHAR(150) NOT NULL,
`gender` INT NOT NULL,
`dob` DATE NOT NULL,
`address_name` VARCHAR(150) NOT NULL,
`address_name_add` VARCHAR(100) NOT NULL,
`address_street` VARCHAR(100) NOT NULL,
`address_houseno` VARCHAR(10) NOT NULL,
`address_zip` VARCHAR(10) NOT NULL,
`address_city` VARCHAR(100) NOT NULL,
`address_region` VARCHAR(100) NOT NULL,
`address_country` VARCHAR(100) NOT NULL,
`phone` VARCHAR(25) NOT NULL,
`fax` VARCHAR(25) NOT NULL,
`mobile` VARCHAR(25) NOT NULL,
`email` VARCHAR(150) NOT NULL,
`web` VARCHAR(250) NOT NULL,
`remarks` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_contact_tabappvalues` (
`contact_id` INT(11)  NOT NULL,
`tab_key` VARCHAR(100) NOT NULL COMMENT 'plugin element',
`tab_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_contact_id_tab_key` (`contact_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex contact data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_options` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`client_id` INT(11) NOT NULL COMMENT 'usergroup id',
`catid` INT NOT NULL,
`opt_key` VARCHAR(100) NOT NULL,
`opt_value` VARCHAR(150) NOT NULL,
`opt_name` VARCHAR(255) NOT NULL,
`access` INT(11) NOT NULL,
`ordering` INT(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Simple or client related options list storage table with accesslevels';

CREATE TABLE IF NOT EXISTS `#__xiveirm_tabapps` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',
`client_id` INT(11) NOT NULL COMMENT 'usergroup id',
`access` INT(10) NOT NULL COMMENT 'access and view level',
`plugin` VARCHAR(100) NOT NULL COMMENT 'tab_key',
`catid` INT(11) NOT NULL COMMENT 'XAP categories',
`config` TEXT NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
`ordering` INT(11) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Storage Table for the TabApp and Widget configurations';