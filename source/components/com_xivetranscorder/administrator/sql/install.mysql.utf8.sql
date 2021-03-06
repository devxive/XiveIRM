CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`client_id` VARCHAR(255) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL,
`created_by` INT(11) NOT NULL,
`checked_out` INT(11) NOT NULL DEFAULT '0',
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL,
`modified_by` INT(11) NOT NULL,
`catid` INT(11) NOT NULL COMMENT 'XIRM Transcorder categories',
`contact_id` INT(11) NOT NULL,
`order_id` VARCHAR(100) NOT NULL,
`transport_timestamp` VARCHAR(20) NOT NULL COMMENT 'build by 2 form values or in model',
`transport_device` INT(11) NOT NULL COMMENT 'xiveirm_options id',
`transport_type` INT(11) NOT NULL COMMENT 'xiveirm_options id',
`order_type` INT(11) NOT NULL COMMENT 'xiveirm_options id',
`f_poi_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Additional XiveIRM Contact Id',
`f_address_name` VARCHAR(150) NOT NULL,
`f_address_name_add` VARCHAR(100) NOT NULL,
`f_address_street` VARCHAR(100) NOT NULL,
`f_address_houseno` VARCHAR(10) NOT NULL,
`f_address_zip` VARCHAR(10) NOT NULL,
`f_address_city` VARCHAR(100) NOT NULL,
`f_address_region` VARCHAR(100) NOT NULL,
`f_address_country` VARCHAR(100) NOT NULL,
`f_address_lat` VARCHAR(20) NOT NULL,
`f_address_lng` VARCHAR(20) NOT NULL,
`f_address_hash` VARCHAR(100) NOT NULL,
`t_poi_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Additional XiveIRM Contact Id',
`t_address_name` VARCHAR(150) NOT NULL ,
`t_address_name_add` VARCHAR(100) NOT NULL,
`t_address_street` VARCHAR(100) NOT NULL,
`t_address_houseno` VARCHAR(10) NOT NULL,
`t_address_zip` VARCHAR(10) NOT NULL,
`t_address_city` VARCHAR(100) NOT NULL,
`t_address_region` VARCHAR(100) NOT NULL,
`t_address_country` VARCHAR(100) NOT NULL,
`t_address_lat` VARCHAR(20) NOT NULL,
`t_address_lng` VARCHAR(20) NOT NULL,
`t_address_hash` VARCHAR(100) NOT NULL,
`distcalc_device` VARCHAR(10) NOT NULL,
`estimated_distance` VARCHAR(10) NOT NULL,
`estimated_time` VARCHAR(10) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders_appvalues` (
`transcorders_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`app_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`app_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorders_id_app_key` (`transcorders_id`,`app_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorders data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders_billings` (
`transcorders_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`app_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`app_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorders_id_app_key` (`transcorders_id`,`app_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorders data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders_statistics` (
`transcorders_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`app_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`app_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorders_id_app_key` (`transcorders_id`,`app_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorders data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders_revisions` (
`transcorders_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`app_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`app_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorders_id_app_key` (`transcorders_id`,`app_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorders data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders_vehicles` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`ordering` INT(11) NOT NULL,
`created_by` INT(11) NOT NULL,
`state` TINYINT(1) NOT NULL,
`contact_id` INT(11) NOT NULL COMMENT '#__contacts id',
`listname` VARCHAR(255) NOT NULL,
`callname` VARCHAR(100) NOT NULL,
`mobile` VARCHAR(50) NOT NULL,
`navi_id` VARCHAR(255) NOT NULL,
`fleet_car` TINYINT(1) NOT NULL DEFAULT '0',
`fittings` VARCHAR(255) NOT NULL,
`license_plate` VARCHAR(15) NOT NULL,
`insurance_id` INT(11) NOT NULL COMMENT '#__contacts id',
`insurance_no` VARCHAR(255) NOT NULL,
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Table to store core vehicle datas';