# Dump of table xap_dbtest
# ------------------------------------------------------------

DROP TABLE IF EXISTS `xap_dbtest`;

CREATE TABLE `jos_dbtest` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;