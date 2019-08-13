CREATE TABLE IF NOT EXISTS `message_task` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `send_id` int(11) unsigned NOT NULL COMMENT '发送者id',
  `rec_id` int(11) unsigned NOT NULL COMMENT '接收者id',
  `task_id` int(11) unsigned NOT NULL COMMENT '任务id',
  `message` varchar(255) NOT NULL COMMENT '消息',
  `status` tinyint(1) unsigned NOT NULL COMMENT '状态0未读；1已读',
  `timestamp` int(11) NOT NULL COMMENT '时间戳',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

