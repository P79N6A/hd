CREATE TABLE IF NOT EXISTS `push_msg` (
  
	`id` int(11) NOT NULL AUTO_INCREMENT,
  
	`push_content` varchar(255) DEFAULT NULL,
  
	`push_mode` int(11) DEFAULT NULL COMMENT '0:�㲥 1��ֱ�� 2��ר�� 3��webҳ 10��ugcֱ�� 11��ugc�㲥',
  
	`push_id` varchar(255) DEFAULT NULL,
  
	`ac_code` varchar(32) DEFAULT NULL COMMENT'ֱ��Ƶ��',
  
	`push_url` varchar(255) DEFAULT NULL COMMENT'url',
  
	`push_image` varchar(255) DEFAULT NULL COMMENT'ͼƬ��ַ',
  
	`push_channelid` int(11) DEFAULT NULL,
  
	`push_ablumid` int(11) DEFAULT NULL,
  
	`push_terminal` int(11) DEFAULT '0' COMMENT '��׿��ƻ��',
  
	`push_single` int(11) DEFAULT '0' COMMENT '�Ƿ����͵����� -������',
  
	`push_single_client` varchar(256) DEFAULT '0' COMMENT '�Ƿ����͵�����id',
  
	`push_type` int(11) DEFAULT '1' COMMENT '0����ʱ���� 1����ʱ����',
  
	`push_timestamp` varchar(255) DEFAULT NULL,
  
	`created_at` varchar(255) DEFAULT NULL,
  
	`status` int(11) DEFAULT '0' COMMENT '0��û���ͣ�1���Ѿ����ͣ�3������ʧ��,4�Ƕ�ʱ������',
  
	`remark` text,
  
	PRIMARY KEY (`id`)

) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;