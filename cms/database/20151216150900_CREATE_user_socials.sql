DROP TABLE user_bind;
ALTER TABLE `users` DROP `head_image`;
CREATE TABLE `user_socials` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主ID',
  `channel_id` int(11) DEFAULT NULL COMMENT '频道ID',
  `user_id` int(11) NOT NULL COMMENT '用户ID',
  `type` enum('weibo','qq','wechat') NOT NULL DEFAULT 'qq' COMMENT '账户类型',
  `open_id` varchar(64) NOT NULL DEFAULT '' COMMENT '社交账户ID',
  `refresh_token` varchar(64) NOT NULL DEFAULT '' COMMENT '刷新令牌',
  `token` varchar(64) NOT NULL DEFAULT '' COMMENT '数据获取令牌',
  `created_at` int(11) NOT NULL COMMENT '创建时间',
  `updated_at` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`type`),
  UNIQUE KEY `channel_id` (`channel_id`,`type`,`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;