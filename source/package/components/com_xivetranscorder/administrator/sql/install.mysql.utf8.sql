CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`client_id` VARCHAR(255)  NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL ,
`created_by` INT(11)  NOT NULL ,
`checked_out` INT(11)  NOT NULL ,
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL ,
`catid` INT(11)  NOT NULL ,
`contact_id` INT(11)  NOT NULL ,
`order_id` VARCHAR(100)  NOT NULL ,
`transport_timestamp` VARCHAR(20)  NOT NULL ,
`f_poi_id` INT(11)  NOT NULL ,
`f_address_name` VARCHAR(150)  NOT NULL ,
`f_address_name_add` VARCHAR(100)  NOT NULL ,
`f_address_street` VARCHAR(100)  NOT NULL ,
`f_address_houseno` VARCHAR(10)  NOT NULL ,
`f_address_zip` VARCHAR(10)  NOT NULL ,
`f_address_city` VARCHAR(100)  NOT NULL ,
`f_address_region` VARCHAR(100)  NOT NULL ,
`f_address_country` VARCHAR(100)  NOT NULL ,
`f_address_lat` VARCHAR(255)  NOT NULL ,
`f_address_long` VARCHAR(255)  NOT NULL ,
`f_address_hash` VARCHAR(255)  NOT NULL ,
`t_poi_id` INT(11)  NOT NULL ,
`t_address_name` VARCHAR(150)  NOT NULL ,
`t_address_name_add` VARCHAR(100)  NOT NULL ,
`t_address_street` VARCHAR(100)  NOT NULL ,
`t_address_houseno` VARCHAR(10)  NOT NULL ,
`t_address_zip` VARCHAR(10)  NOT NULL ,
`t_address_city` VARCHAR(100)  NOT NULL ,
`t_address_country` VARCHAR(100)  NOT NULL ,
`t_address_lat` VARCHAR(255)  NOT NULL ,
`t_address_long` VARCHAR(255)  NOT NULL ,
`t_address_hash` VARCHAR(255)  NOT NULL ,
`distcalc_device` VARCHAR(255)  NOT NULL ,
`estimated_distance` VARCHAR(10)  NOT NULL ,
`estimated_time` VARCHAR(10)  NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_tabappvalues` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`transcorder_id` INT(11)  NOT NULL ,
`tab_key` VARCHAR(100)  NOT NULL ,
`tab_value` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_billings` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`transcorder_id` INT(11)  NOT NULL ,
`tab_key` VARCHAR(100)  NOT NULL ,
`tab_value` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_statistics` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`transcorder_id` INT(11)  NOT NULL ,
`tab_key` VARCHAR(100)  NOT NULL ,
`tab_value` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_revisions` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`transcorder_id` INT(11)  NOT NULL ,
`tab_key` VARCHAR(100)  NOT NULL ,
`tab_value` MEDIUMTEXT NOT NULL ,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xivetranscorder_tabapps` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,

`asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0',

`client_id` INT(11)  NOT NULL ,
`access` INT(11)  NOT NULL ,
`plugin` VARCHAR(100)  NOT NULL ,
`catid` INT(11)  NOT NULL ,
`config` TEXT NOT NULL ,
`state` TINYINT(1)  NOT NULL DEFAULT '1',
`ordering` INT(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;
