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
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='报名表扩展模型';


/*修改表`activity_signup`*/
ALTER TABLE `activity_signup`
ADD COLUMN `ext_fields`  text NULL COMMENT '扩展字段json' AFTER `create_at`,
ADD COLUMN `ext_values`  text NULL COMMENT '扩展字段值' AFTER `ext_fields`;


/*修改表`activity_signup`*/
ALTER TABLE `activity_signup`
ADD COLUMN `status`  enum('4','3','2','1','0') NULL DEFAULT '0' COMMENT '状态，0-未操作|1-同意|2-拒绝|3-删除|4-ALL' AFTER `create_at`;

/*添加评论配置信息`*/
INSERT INTO `spec_comment` (`themname`, `create_at`, `adminid`) VALUES ('5频道大歌神', '0', '1')

