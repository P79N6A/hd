
DROP TABLE IF EXISTS `ugc_live`;
CREATE TABLE `ugc_live` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) DEFAULT NULL COMMENT '容器ID',
  `uid` int(11) DEFAULT NULL COMMENT '用户ID',
  `stream_event` enum('end','start') DEFAULT 'start' COMMENT '流状态,start/end',
  `stream_type` enum('pull','push') DEFAULT 'push' COMMENT 'push/pull',
  `source_ip` varchar(15) DEFAULT NULL COMMENT '上层IP',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `domain` varchar(255) DEFAULT NULL COMMENT '推流域名host push.a.com',
  `stream_name` varchar(255) DEFAULT NULL COMMENT '流id,id冗余字段',
  `path` varchar(255) DEFAULT NULL COMMENT 'host后面路径,live',
  `rtmp_url` varchar(255) DEFAULT NULL COMMENT '"rtmp://push.a.com/live/123", //rtmp地址',
  `push_tool` varchar(255) DEFAULT 'obs_0202' COMMENT '推流工具 ,取不到默认空',
  `width` double DEFAULT '1280' COMMENT '视频宽',
  `height` double DEFAULT '720' COMMENT '视频高',
  `vidio_framerate` int(255) DEFAULT '123' COMMENT '视频帧率',
  `videorate` int(255) DEFAULT '234' COMMENT '视频码率,单位kbps',
  `videocoding_algorithm` varchar(255) DEFAULT 'ACC' COMMENT '视频压缩算法',
  `audiorate` varchar(255) DEFAULT '64' COMMENT '音频码率,单位kbps',
  `audio_framerate` varchar(255) DEFAULT '12' COMMENT '音频帧率',
  `audio_samplingrate` varchar(255) DEFAULT '44,100' COMMENT '音频采样率,单位Hz',
  `audio_channel` smallint(255) DEFAULT '2' COMMENT '音频声道',
  `push_args` varchar(255) DEFAULT NULL COMMENT '用户推流参数',
  `cdn_url1` varchar(255) DEFAULT NULL,
  `cdn_url2` varchar(255) DEFAULT NULL,
  `cdn_url3` varchar(255) DEFAULT NULL,
  `is_rec` tinyint(1) DEFAULT '1' COMMENT '是否录制文件',
  `terminal` tinyint(1) DEFAULT '1' COMMENT '主播终端类型，1:IOS,2:安卓',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='UGC直播媒资媒资文件';

DROP TABLE IF EXISTS `ugc_live_room`;
CREATE TABLE `ugc_live_room` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel_id` int(11) DEFAULT NULL,
  `roomname` varchar(50) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `intro` text,
  `tags` text,
  `uid` int(11) DEFAULT NULL,
  `createat` int(255) DEFAULT NULL,
  `online_num` tinyint(4) DEFAULT NULL COMMENT '线路数量',
  `showstatus` tinyint(4) DEFAULT NULL,
  `runstatus` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `ugc_live_video`;
CREATE TABLE `ugc_live_video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rate` int(255) DEFAULT NULL COMMENT '码率',
  `data_id` int(11) DEFAULT NULL COMMENT '容器ID',
  `stream_id` int(11) NOT NULL COMMENT '流ID',
  `file_url` varchar(255) DEFAULT NULL COMMENT '播放文件URL',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_conf`;
CREATE TABLE `user_conf` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `gid` int(11) NOT NULL,
  `spec_conf` text NOT NULL COMMENT '配置信息',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

DROP TABLE IF EXISTS `user_group`;
CREATE TABLE `user_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `indexname` varchar(50) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT '组名',
  `desc` varchar(255) NOT NULL COMMENT '描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='用户组配置信息';

DROP TABLE IF EXISTS `user_group_kv`;
CREATE TABLE `user_group_kv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

ALTER TABLE `admin_ext`
ADD COLUMN `ugc_group_id`  int(4) NULL DEFAULT 0 COMMENT 'UGC模板分组' AFTER `sort`;

