CREATE TABLE IF NOT EXISTS `push_msg` (
  
	`id` int(11) NOT NULL AUTO_INCREMENT,
  
	`push_content` varchar(255) DEFAULT NULL,
  
	`push_mode` int(11) DEFAULT NULL COMMENT '0:点播 1：直播 2：专题 3：web页 10：ugc直播 11：ugc点播',
  
	`push_id` varchar(255) DEFAULT NULL,
  
	`ac_code` varchar(32) DEFAULT NULL COMMENT'直播频道',
  
	`push_url` varchar(255) DEFAULT NULL COMMENT'url',
  
	`push_image` varchar(255) DEFAULT NULL COMMENT'图片地址',
  
	`push_channelid` int(11) DEFAULT NULL,
  
	`push_ablumid` int(11) DEFAULT NULL,
  
	`push_terminal` int(11) DEFAULT '0' COMMENT '安卓，苹果',
  
	`push_single` int(11) DEFAULT '0' COMMENT '是否推送单个人 -测试用',
  
	`push_single_client` varchar(256) DEFAULT '0' COMMENT '是否推送单个人id',
  
	`push_type` int(11) DEFAULT '1' COMMENT '0：定时推送 1：即时推送',
  
	`push_timestamp` varchar(255) DEFAULT NULL,
  
	`created_at` varchar(255) DEFAULT NULL,
  
	`status` int(11) DEFAULT '0' COMMENT '0是没推送，1是已经推送，3是推送失败,4是定时待推送',
  
	`remark` text,
  
	PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;