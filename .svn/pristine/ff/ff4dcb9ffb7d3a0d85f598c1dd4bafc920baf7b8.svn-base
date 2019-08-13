ALTER TABLE `activity`
ADD COLUMN `status`  tinyint(1) DEFAULT '0' COMMENT '状态，0-未操作|1-同意|2-拒绝|3-删除|4-ALL',
ADD COLUMN `ext_fields` text COMMENT '扩展字段json',
ADD COLUMN `ext_values` text COMMENT '扩展字段值';


DROP TABLE IF EXISTS `activity_ext_model`;
CREATE TABLE `activity_ext_model` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) NOT NULL,
  `activity_id` int(11) NOT NULL,
  `field_name` varchar(90) DEFAULT NULL COMMENT '字段名称',
  `field_text` varchar(50) DEFAULT NULL COMMENT '字段描述文字',
  `field_type` enum('N','D','B','T') DEFAULT NULL COMMENT '字段类型,N，数值型，D，日期型，T,文本格式,B:布尔型',
  `filed_width` int(11) DEFAULT NULL COMMENT '文本宽度',
  `field_def` varchar(255) DEFAULT NULL COMMENT '扩展字段默认值',
  `field_isshowback` tinyint(1) DEFAULT '1' COMMENT '是否后台显示',
  `field_required` enum('0','1') DEFAULT '1' COMMENT '字段是否必填',
  `terminal` set('android','ios','wap','web') DEFAULT 'android,ios,wap,web' COMMENT '1:web 2.wap 3:ios 4:android',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='报名表扩展模型';


INSERT INTO `activity_ext_model` (`id`, `channel_id`, `activity_id`, `field_name`, `field_text`, `field_type`, `filed_width`, `field_def`, `field_isshowback`, `field_required`, `terminal`) VALUES
(null, 8, 3, 'ex_nationname', '姓名', 'T', 50, NULL, 1, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_birthday', '出生年月', 'D', 50, NULL, 1, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_mobile', '手机号码', 'T', 11, NULL, 1, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_address', '常住地址', 'T', 255, NULL, 1, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_profession', '职业', 'T', 255, NULL, 1, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_isREC', '曾经参加的节目', 'T', 255, '', 0, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_personalintro', '个人介绍', 'T', 255, NULL, 0, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_vediourl', '视频地址', 'T', 255, NULL, 0, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_vedioid', '视频ID', 'T', 50, NULL, 0, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_dataid', '容器ID', 'N', 11, NULL, 0, '1', 'android,ios,wap,web'),
(null, 8, 3, 'ex_token', 'Token', 'T', 64, NULL, 1, '1', 'android,ios,wap,web');

		 
		  