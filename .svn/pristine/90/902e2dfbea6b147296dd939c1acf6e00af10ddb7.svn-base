ALTER TABLE `admin`
ADD COLUMN `job_name`  varchar(254) NULL DEFAULT '' COMMENT '员工岗位名' AFTER `name`;

ALTER TABLE `admin_ext`
MODIFY COLUMN `department`  varchar(254) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '部门' AFTER `pinyin`;


DROP TABLE IF EXISTS `admin_contact`;
CREATE TABLE `admin_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(10) unsigned NOT NULL DEFAULT '0',
  `contact_id` int(10) unsigned NOT NULL DEFAULT '0',
  `contact_static` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
