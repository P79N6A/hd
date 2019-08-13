DROP TABLE IF EXISTS `government_department`;
CREATE TABLE `government_department` (
`id` int(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '地区ID',
`father_id` int(8) unsigned NOT NULL COMMENT '父ID',
`name` varchar(128) NOT NULL DEFAULT '' COMMENT '名称',
`pinyin` varchar(256) NOT NULL DEFAULT '' COMMENT '全拼',
`pinyin_short` varchar(50) NOT NULL DEFAULT '' COMMENT '首字母',
`level` enum('department_one','department_two','department_three','department_four','department_five','department_six') NOT NULL DEFAULT 'department_four' COMMENT '级别: 第一级, 第二级, 第三级, 第四级,第五级,第六级',
PRIMARY KEY (`id`),
KEY `father_id` (`father_id`),
KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='行政机构表';




DROP TABLE IF EXISTS `government_department_data`;
CREATE TABLE `government_department_data` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
`data_id` int(11) unsigned NOT NULL COMMENT '媒资表ID',
`government_department_id` int(11) unsigned NOT NULL COMMENT '部门表ID',

PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='部门媒资表';

ALTER TABLE region_data ADD description varchar(255) default '';

ALTER TABLE channel ADD region_id int(11) default '0';

