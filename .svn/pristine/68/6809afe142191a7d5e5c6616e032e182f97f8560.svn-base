ALTER TABLE news DROP INDEX author_name;
ALTER TABLE `data` ADD INDEX( `channel_id`, `created_at`);

CREATE TABLE `specials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '专题',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '简介',
  `thumb` varchar(255) DEFAULT '' COMMENT '源缩略图',
  `banner` varchar(255) DEFAULT NULL COMMENT '横幅Banner',
  `author_id` int(11) NOT NULL COMMENT '作者ID',
  `author_name` varchar(30) NOT NULL DEFAULT '' COMMENT '作者姓名',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `special_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '栏目ID',
  `special_id` int(11) unsigned NOT NULL COMMENT '专题ID',
  `name` varchar(50) NOT NULL COMMENT '栏目名',
  `code` varchar(50) NOT NULL COMMENT '别名代码',
  `logo` varchar(255) DEFAULT NULL COMMENT '栏目图标',
  PRIMARY KEY (`id`),
  KEY `special_id` (`special_id`,`name`),
  KEY `special_id_2` (`special_id`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题栏目表';

CREATE TABLE `special_category_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '关联ID',
  `special_category_id` int(11) unsigned NOT NULL COMMENT '栏目ID',
  `data_id` int(11) unsigned NOT NULL COMMENT '容器ID',
  `sort` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '基于栏目的排序',
  `weight` smallint(2) unsigned NOT NULL DEFAULT '0' COMMENT '权重，锁贴位置',
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_id` (`special_category_id`,`data_id`),
  KEY `sort` (`sort`),
  KEY `weight` (`weight`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='专题栏目关联表';