DROP TABLE `app_version`;

CREATE TABLE IF NOT EXISTS `app_version` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `app_id` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1.android or 2.ios',
  `newfeature` text,
  `version` varchar(32) NOT NULL,
  `downloads` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `author_id` int(11) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `publish` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1.上架 2.下架',
  `publishtime` int(11) NOT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `app_id` (`app_id`,`type`,`version`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;