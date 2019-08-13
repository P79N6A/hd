CREATE TABLE `queues` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `task_type` varchar(255) NOT NULL DEFAULT '' COMMENT '任务类型',
  `task_data` text NOT NULL COMMENT '任务数据',
  `request_data` longtext NOT NULL COMMENT '请求数据',
  `response_data` longtext NOT NULL COMMENT '请求结果',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  PRIMARY KEY (`id`),
  KEY `status` (`status`,`task_type`,`created_at`),
  KEY `channel_id` (`channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;