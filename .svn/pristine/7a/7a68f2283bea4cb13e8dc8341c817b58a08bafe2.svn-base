CREATE TABLE `region_data` (
  `data_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` int(8) unsigned NOT NULL COMMENT '国家ID',
  `province_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '省ID',
  `city_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '市ID',
  `county_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '县区ID',
  `village_id` int(8) unsigned NOT NULL DEFAULT '0' COMMENT '村ID',
  PRIMARY KEY (`data_id`),
  KEY `country_id` (`country_id`,`province_id`,`city_id`,`county_id`,`village_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `data` DROP `country_id`, DROP `province_id`, DROP `city_id`, DROP `county_id`, DROP `village_id`;