CREATE TABLE IF NOT EXISTS `client` (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'try一下用户ID',
  `mobile` varchar(11) DEFAULT NULL COMMENT '顺便try下手机',
  `origin_id` varchar(32) NOT NULL COMMENT '原始ID，安卓的clientID,iOS自己生成的',
  `hash_id` varchar(64) NOT NULL COMMENT '系统创建的唯一识别ID',
  `model` varchar(30) NOT NULL COMMENT '型号',
  `client_type` varchar(20) NOT NULL COMMENT '终端类型',
  `app_version` varchar(30) NOT NULL COMMENT 'APP版本',
  `push_client` varchar(32) NOT NULL COMMENT '推送ID',
  `push_token` varchar(32) NOT NULL COMMENT '推送token',
  `sdk_version` varchar(20) NOT NULL COMMENT 'sdk版本',
  `created_at` int(11) unsigned NOT NULL COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL COMMENT '更新时间'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='客户端';

ALTER TABLE `client`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `origin_id` (`origin_id`),
  ADD UNIQUE KEY `hash_id` (`hash_id`),
  ADD KEY `client_type` (`client_type`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `mobile` (`mobile`),
  ADD KEY `updated_at` (`updated_at`),
  ADD KEY `create_at` (`created_at`),
  ADD KEY `channel_id` (`channel_id`);

  ALTER TABLE `client`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',AUTO_INCREMENT=1;