CREATE TABLE `backend_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL COMMENT '操作用户',
  `ip` varchar(50) NOT NULL COMMENT 'ip地址',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道id',
  `controller` varchar(50) NOT NULL COMMENT '控制器',
  `type` tinyint(2) unsigned NOT NULL COMMENT '操作类型',
  `remark` varchar(1000) NOT NULL COMMENT '备注',
  `created_at` int(11) NOT NULL COMMENT '操作时间',
  PRIMARY KEY (`id`),
  KEY `channel_id` (`channel_id`,`created_at`),
  KEY `user_id` (`user_id`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;