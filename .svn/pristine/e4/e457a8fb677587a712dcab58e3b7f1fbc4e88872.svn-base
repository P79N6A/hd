DROP TABLE `app_list`;

CREATE TABLE IF NOT EXISTS `app_list` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `bundleid` varchar(255) NOT NULL,
  `channel_id` int(11) unsigned DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `intro` text,
  `sku` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `version_android` varchar(32) NOT NULL COMMENT '安卓版本',
  `version_ios` varchar(32) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `bundleid` (`bundleid`),
  UNIQUE KEY `sku` (`sku`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;