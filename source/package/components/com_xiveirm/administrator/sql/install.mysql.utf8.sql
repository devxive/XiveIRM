CREATE TABLE IF NOT EXISTS `#__xiveirm_customer` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`access_id` VARCHAR(50) UNSIGNED NOT NULL DEFAULT '0',
`state` TINYINT(1) NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL,
`created_by` INT(11) NOT NULL,
`checked_out` INT(11) NOT NULL,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL,
`client_id` VARCHAR(50) NOT NULL,
`customer_id` VARCHAR(25) NOT NULL,
`company_name` VARCHAR(150) NOT NULL,
`title` VARCHAR(100) NOT NULL,
`last_name` VARCHAR(150) NOT NULL,
`first_name` VARCHAR(150) NOT NULL,
`gender` VARCHAR(255) NOT NULL,
`dob` DATE NOT NULL,
`address_name` VARCHAR(150) NOT NULL,
`address_name_add` VARCHAR(100) NOT NULL,
`address_street` VARCHAR(100) NOT NULL,
`address_houseno` VARCHAR(10) NOT NULL,
`address_zip` VARCHAR(10) NOT NULL,
`address_city` VARCHAR(100) NOT NULL,
`address_country` VARCHAR(100) NOT NULL,
`phone` VARCHAR(25) NOT NULL,
`fax` VARCHAR(25) NOT NULL,
`mobile` VARCHAR(25) NOT NULL,
`email` VARCHAR(100) NOT NULL,
`web` VARCHAR(150) NOT NULL,
`remarks` TEXT NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_customer_add` (
`customer_db_id` INT(11) NOT NULL,
`tab_key` VARCHAR(100) NOT NULL,
`tab_value` MEDIUMTEXT NOT NULL,
`ordering` INT(11) NOT NULL DEFAULT '0',
UNIQUE KEY `idx_masterdata_id_tab_key` (`customer_db_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Extensive customer data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_selectlists` (
`client_id` INT(11) NOT NULL,
`sl_key` VARCHAR(100) NOT NULL,
`sl_value` VARCHAR(100) NOT NULL,
`sl_string` VARCHAR(255) NOT NULL,
`ordering` INT(11)  NOT NULL,
UNIQUE KEY `idx_client_id_sl_key` (`client_id`,`sl_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Simple or client related select list storage table';