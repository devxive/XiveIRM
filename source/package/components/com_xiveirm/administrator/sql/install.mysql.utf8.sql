CREATE TABLE IF NOT EXISTS `#__xiveirm_masterdata` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL ,
`trash` TINYINT(1)  NOT NULL ,
`client_id` VARCHAR(50)  NOT NULL ,
`customer_id` VARCHAR(25)  NOT NULL ,
`title` VARCHAR(100)  NOT NULL ,
`last_name` VARCHAR(150)  NOT NULL ,
`first_name` VARCHAR(150)  NOT NULL ,
`gender` VARCHAR(255)  NOT NULL ,
`dob` DATE NOT NULL ,
`address_name` VARCHAR(150)  NOT NULL ,
`address_name_add` VARCHAR(100)  NOT NULL ,
`address_street` VARCHAR(100)  NOT NULL ,
`address_houseno` VARCHAR(10)  NOT NULL ,
`address_zip` VARCHAR(10)  NOT NULL ,
`address_city` VARCHAR(100)  NOT NULL ,
`address_country` VARCHAR(100)  NOT NULL ,
`phone` VARCHAR(25)  NOT NULL ,
`fax` VARCHAR(25)  NOT NULL ,
`mobile` VARCHAR(25)  NOT NULL ,
`email` VARCHAR(100)  NOT NULL ,
`web` VARCHAR(150)  NOT NULL ,
`remarks` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_masterdata_add` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`tab_id` VARCHAR(100)  NOT NULL ,
`tab_field_id` VARCHAR(100)  NOT NULL ,
`tab_value` TEXT NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`ordering` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_selectlists` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`sl_key` VARCHAR(100)  NOT NULL ,
`sl_value` VARCHAR(100)  NOT NULL ,
`sl_string` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_audit` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`created` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`item_id` INT(11)  NOT NULL ,
`reference_key` VARCHAR(100)  NOT NULL ,
`reference_value` TEXT NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

