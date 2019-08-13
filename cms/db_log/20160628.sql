DROP TABLE IF EXISTS `payment`;
CREATE TABLE `payment` (
`id`  int NOT NULL AUTO_INCREMENT ,
`uid`  int NULL COMMENT '用户id' ,
`channel_id`  int NULL COMMENT '频道ID' ,
`app_short_name`  varchar(50) NULL COMMENT '应用短标识' ,
`created_at`  int NULL ,
`order`  varchar(255) NULL COMMENT '支付订单编号' ,
`amount`  varchar(255) NULL COMMENT '交易金额' ,
`charge_way`  varchar(50) NULL COMMENT '支付方式' ,
`pay_account`  varchar(255) NULL COMMENT '用户支付账号' ,
`rec_account`  varchar(255) NULL COMMENT '收款账户' ,
`charget_datetime`  int(11) NULL COMMENT '交易时间' ,
`charge_serial_no`  varchar(255) NULL COMMENT '第三方支付订单流水号' ,
`state`  tinyint(1) NULL COMMENT '状态' ,
PRIMARY KEY (`id`)
)
ENGINE=InnoDB
DEFAULT CHARACTER SET=utf8 COLLATE=utf8_general_ci
;

DROP TABLE IF EXISTS `ugc_stream`;
CREATE TABLE `ugc_stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) DEFAULT NULL COMMENT '主播ID',
  `stream` varchar(255) DEFAULT '' COMMENT '流名称',
  `hls_url` varchar(255) DEFAULT NULL COMMENT 'HLS 播放URL',
  `play_url` varchar(255) DEFAULT NULL COMMENT 'PLay URL',
  `rtmp_url` varchar(255) DEFAULT NULL COMMENT '推流地址',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `cdn_url1` varchar(255) DEFAULT NULL COMMENT '播放地址1,540P',
  `cdn_url2` varchar(255) DEFAULT NULL COMMENT '播放地址1,720P',
  `cdn_url3` varchar(255) DEFAULT NULL COMMENT '播放地址1,1080P',
  PRIMARY KEY (`id`),
  KEY `Index_adminid` (`admin_id`) USING BTREE,
  KEY `Index_stream` (`stream`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ugc_video_file`;
CREATE TABLE `ugc_video_file` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream_id` int(11) DEFAULT NULL COMMENT '流id',
  `created_at` int(10) DEFAULT NULL,
  `start_time` int(10) DEFAULT NULL,
  `end_time` int(10) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;




DROP TABLE IF EXISTS `admin_group`;
CREATE TABLE `admin_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indexname` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT '组名',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户组配置信息';


DROP TABLE IF EXISTS `admin_group_kv`;
CREATE TABLE `admin_group_kv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

ALTER TABLE `payment`
ADD COLUMN `return_mess`  varchar(255) NULL AFTER `charge_serial_no`;
ALTER TABLE `payment`
CHANGE COLUMN `order` `order_no`  varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '支付订单编号' AFTER `created_at`;





ALTER TABLE `admin_ext`
ADD COLUMN `nick`  VARCHAR(50) NULL DEFAULT '' COMMENT '昵称' AFTER `ugc_group_id`,
ADD COLUMN `sex`  TINYINT(1) NULL DEFAULT 0 COMMENT '性别' AFTER `nick`,
ADD COLUMN `is_anchor`  TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否是主播' AFTER `sex`,
ADD COLUMN `rtmpurl`  VARCHAR(255) NULL DEFAULT '' COMMENT '主播推流地址' AFTER `is_anchor`,
ADD COLUMN `playurl`	VARCHAR(255) NULL DEFAULT '' COMMENT '主播播放地址' AFTER `rtmpurl`;

ALTER TABLE `ugc_stream`
ADD COLUMN `is_pause`  tinyint(1) NULL DEFAULT 0 COMMENT '是否被禁用' AFTER `cdn_url3`;


ALTER TABLE `ugc_video_file`
ADD COLUMN `req_str`  varchar(255) NULL AFTER `video_url`,
ADD COLUMN `rep_str`  varchar(255) NULL AFTER `req_str`;


