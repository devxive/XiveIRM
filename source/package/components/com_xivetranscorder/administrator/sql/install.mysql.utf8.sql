CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorders` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`client_id` VARCHAR(255) NOT NULL,
`state` TINYINT(1) NOT NULL DEFAULT '1',
`created` DATETIME NOT NULL,
`created_by` INT(11) NOT NULL,
`checked_out` INT(11) NOT NULL DEFAULT '0',
`checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`modified` DATETIME NOT NULL,
`catid` INT(11) NOT NULL COMMENT 'XIRM Transcorder categories',
`contact_id` INT(11) NOT NULL,
`order_id` VARCHAR(100) NOT NULL,
`transport_timestamp` VARCHAR(20) NOT NULL,
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

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_tabappvalues` (
`transcorder_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`tab_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`tab_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorder_id_tab_key` (`transcorder_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorder data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_billings` (
`transcorder_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`tab_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`tab_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorder_id_tab_key` (`transcorder_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorder data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_statistics` (
`transcorder_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`tab_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`tab_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorder_id_tab_key` (`transcorder_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorder data storage table';

CREATE TABLE IF NOT EXISTS `#__xiveirm_transcorder_revisions` (
`transcorder_id` INT(11) NOT NULL COMMENT '#__transcorders id',
`tab_key` VARCHAR(100) NOT NULL COMMENT '#__extensions plugin element',
`tab_value` MEDIUMTEXT NOT NULL,
UNIQUE KEY `idx_transcorder_id_tab_key` (`transcorder_id`,`tab_key`)
) ENGINE=InnoDB DEFAULT COLLATE=utf8_general_ci COMMENT='Enhanced and complex transcorder data storage table';