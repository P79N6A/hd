ALTER TABLE `ugc_live`
CHANGE COLUMN `uid` `admin_id`  int(11) NULL DEFAULT NULL COMMENT '用户ID' AFTER `data_id`;

ALTER TABLE `ugc_live_room`
CHANGE COLUMN `uid` `admin_id`  int(11) NULL DEFAULT NULL AFTER `tags`,
CHANGE COLUMN `createat` `create_at`  int(255) NULL DEFAULT NULL AFTER `admin_id`;

DROP TABLE IF EXISTS `admin_conf`;
CREATE TABLE `admin_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) NOT NULL,
  `gid` int(11) NOT NULL,
  `spec_conf` text NOT NULL COMMENT '配置信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indexname` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT '组名',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户组配置信息';

DROP TABLE IF EXISTS `admin_group_kv`;
CREATE TABLE `admin_group_kv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;