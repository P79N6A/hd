CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int(11) unsigned NOT NULL COMMENT '收藏ID',
  `channel_id` int(11) unsigned NOT NULL COMMENT '频道ID',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户类型',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '收藏类型 1:媒资',
  `data_id` int(11) unsigned NOT NULL COMMENT '数据ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户收藏表';
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id_2` (`user_id`,`type`,`data_id`),
  ADD KEY `channel_id` (`channel_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `type` (`type`),
  ADD KEY `data_id` (`data_id`);
  ALTER TABLE `favorites`
  MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '收藏ID';